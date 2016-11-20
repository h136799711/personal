<?php
namespace app\src\user\facade;


use app\src\alibaichuan\service\OpenIMUserService;
use app\src\base\facade\IAccount;
use app\src\base\helper\ConfigHelper;
use app\src\base\helper\SessionHelper;
use app\src\base\helper\ValidateHelper;
use app\src\system\logic\SecurityCodeLogic;
use app\src\system\model\SecurityCode;
use app\src\user\enum\RegFromEnum;
use app\src\user\logic\MemberConfigLogic;
use app\src\user\logic\MemberLogic;
use app\src\user\logic\UcenterMemberLogic;
use app\src\user\model\UcenterMember;
use think\Db;

class DefaultUserFacade implements IAccount
{

    /**
     * 登录成功
     * @param $userinfo array 用户信息
     */
    private function loginSuccess($userinfo){

        //1. 更新用户登录时间
        $logic = new UcenterMemberLogic();
        $logic->save(['id'=>$userinfo['id']],['last_login_time'=>time()]);

        //2. 记录session
        SessionHelper::logSession($userinfo['id']);

        //3. 判断是否已经注册过百川
        if(empty($userinfo['alibaichuan_id'])){
            $result = $this->syncBaichuanUser($userinfo['id'],$userinfo['password'],$userinfo['nickname']);
            if($result['status']){
                $alibaichuan_id = $result['info'];
                $logic = new MemberConfigLogic();
                $logic->save(['uid'=>$userinfo['id']],['alibaichuan_id'=>$alibaichuan_id]);
            }
        }
        
    }

    private function loginFail(){

    }

    /**
    * 自动登录
    * @param $uid
    * @param $auth_code
    * @return mixed
    */
    public function autoLogin($uid,$auth_code) {

        $password = think_ucenter_decrypt($auth_code,$uid);

        $result = (new DefaultUserFacade())->getInfo($uid);
        $flag = false;
        $info = "";

        if(ValidateHelper::legalArrayResult($result)){
            $userinfo = $result['info'];
            $last_login_time = $userinfo['last_login_time'];
            
            if($last_login_time > time() + 3600*24*15){
                //距离上次登录超过15天时间了
                $flag = false;
                $info = lang("err_re_login");
            }elseif($password != $userinfo['password']){
                //密码不正确
                $flag = false;
                $info = lang('err_incorrect_password');
            }else{
                $flag = true;
                $info = $userinfo;
            }
        }else{
            $info = $result['info'];
        }

        if($flag){
            $this->loginSuccess($info);
            return ['status'=>true,'info'=>$info];
        }else{
            $this->loginFail();
            return ['status'=>false,'info'=>$info];
        }
    }

    /**
     * @param $entity
     * @return mixed
     */
    function delete($entity)
    {
        $mobile = $entity['mobile'];
        $error = '';
        $flag = false;
        $ucenterUserLogic  = new UcenterMemberLogic();
        $memberLogic       = new MemberLogic();
        $memberConfigLogic = new MemberConfigLogic();
        $map = ['mobile'=>$mobile];
        $result = $ucenterUserLogic->getInfo($map);

        $userinfo = [];
        if(!$result['status'] || empty($result['info']) || !is_array($result['info'])){
            return ['status'=>false,'info'=>lang('tip_mobile_unregistered')];
        }

        $userinfo = $result['info'];
        $uid = $userinfo['id'];

        Db::startTrans();
        $result = $ucenterUserLogic->delete($map);

        if($result['status'] && intval($result['info']) == 1){
            $result = $memberLogic->delete(['uid'=>$uid]);
            if($result['status']){
                $result = $memberConfigLogic->delete(['uid'=>$uid]);
            }
        }

        if(!$result['status']){
            $flag = true;
            $error = $result['info'];
        }



        if ($flag) {

            Db::rollback();

            return ['status' => false, 'info' => $error];
        } else {
            Db::commit();
            return ['status' => true, 'info' => lang('tip_success')];
        }

    }

    /**
     * @param $mobile
     * @param $code
     * @param $country
     * @return mixed
     */
    function loginByCode($mobile, $code, $country)
    {
        $api = new SecurityCodeLogic();
        $result = $api->isLegalCode($code,$country . $mobile,SecurityCode::TYPE_FOR_LOGIN);

        if(!$result['status']){
            $this->loginFail();
            return ['status'=>false,'info'=> lang('err_invalid_code')];
        }

        $api = new UcenterMemberLogic();
        $result = $api->getInfo(['mobile'=>$mobile,'country_no'=>$country]);

        $userinfo = ['id'=>0];
        if($result['status'] && !empty($result['info']) && is_array($result['info'])){
            $userinfo = $result['info'];
        }else{
            $this->loginFail();
            return ['status'=>false,'info'=> lang('err_account_unregistered')];
        }

        $userinfo['auth_code'] = think_ucenter_encrypt($userinfo['password'],$userinfo['id'],900);

        $result = $this->getUserInfo($userinfo);

        if($result['status']){
            $this->loginSuccess($result['info']);
            return $result;
        }else{
            $this->loginFail();
            return $result;
        }
    }


    /**
     * 通过手机号 + 验证码
     * @param $username
     * @param $password
     * @param $type
     * @param $country
     * @return mixed
     */
    function login($username, $password, $type,$country)
    {
        $salt = ConfigHelper::getPasswordSalt();
        $encrypt_pwd = think_ucenter_md5($password,$salt);
        $map = [
            'mobile'=>$username,
            "country_no"=>$country,
        ];
        $logic = new UcenterMemberLogic();
        $result = $logic->getInfo($map);

        if($result['status'] && empty($result['info'])){
            $map = [
                'username'=>$username
            ];
            $result = $logic->getInfo($map);
        }
        $login_suc = false;
        $info = "";
        $userinfo = [
            'id'=>0
        ];

        if($result['status'] && !empty($result['info']) && is_array($result['info'])){
            $userinfo = $result['info'];

            if(isset($userinfo['password']) && $userinfo['password'] ==  $encrypt_pwd){
                $login_suc = true;
            }else{
                $info = lang('err_login_fail');
            }

        }else{
            $login_suc = false;
            $info = lang('err_account_unregistered');
        }

        if(!$login_suc){
            return ['status'=>false,'info'=> $info];
        }

        //获取用户信息
        $result = $this->getUserInfo($userinfo);
        if($result['status']){
            $this->loginSuccess($result['info']);
            return $result;
        }else{
            $this->loginFail();
            return $result;
        }
    }

    private function getUserInfo($userinfo){

        $map = ['uid' => $userinfo['id'] ];
        $memberLogic       = new MemberLogic();
        $memberConfigLogic = new MemberConfigLogic();

        $result = $memberLogic->getInfo($map);

        if(!$result['status'] || empty($result['info'])){
            $err_msg = empty($result['info'])?"未知(-1)":$result['info'];

            return ['status'=>false,'info'=>$err_msg];
        }

        $userinfo = array_merge($userinfo,$result['info']);

        $result = $memberConfigLogic->getInfo($map);


        if(!$result['status']){
            $err_msg = empty($result['info'])?"未知(-2)":$result['info'];
            return ['status'=>false,'info'=>$err_msg];
        }
        $userinfo = array_merge($userinfo,$result['info']);

        $userinfo['auto_login_code'] = think_ucenter_encrypt($userinfo['password'],$userinfo['id'],15*24*3600);

        return  ['status'=>true,'info'=>$userinfo] ;
    }

    /**
     * @param $entity
     * @return mixed
     */
    function register($entity)
    {

        if (!isset($entity['username']) || !isset($entity['password'])) {
            return ['status' => false, 'info' => lang("lack_parameter",['param'=>"username or password"])];
        }
        $username = $entity['username'];

        if(strlen($username) < 6 || strlen($username) > 64){
            return ['status'=>false,'info'=> lang('tip_username_length')];
        }

        if(!preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_]{5,64}$/",$username)){
            return ['status'=>false,'info'=>lang('tip_username')];
        }

        $password = $entity['password'];

        $result = ValidateHelper::legalPwd($password);

        if(!$result['status']){
            return $result;
        }
        $country     = $entity['country'];
        $mobile      = $entity['mobile'];
        $reg_type    = $entity['reg_type'];
        $email       = !empty($entity['email']) ? $entity['email']:'';
        $reg_from        = !empty($entity['reg_from']) ? $entity['reg_from']:'';
        $realname    = !empty($entity['realname']) ? $entity['realname']:'';
        $nickname    = !empty($entity['nickname']) ? $entity['nickname']:'';
        $birthday    = !empty($entity['birthday']) ? $entity['birthday']:'0';
        $idcode      = !empty($entity['idcode']) ? $entity['idcode']:'';
        $invite_id      = !empty($entity['invite_id']) ? $entity['invite_id']:'0';
        $head        = !empty($entity['head']) ? $entity['head']:'';
        $sex         = !empty($entity['sex']) ? $entity['sex']: "0";

        //微信的openid
        $wxopenid  = !empty($entity['wxopenid'])?$entity['wxopenid']:'';


        $ucenterUserLogic  = new UcenterMemberLogic();
        $memberLogic       = new MemberLogic();
        $memberConfigLogic = new MemberConfigLogic();


        //1. 检测是否存在用户名
        $result = $ucenterUserLogic->getInfo(array('username'=>$username));
        if($result['status'] && is_array($result['info']) && !empty($result['info'])){
            return array('status'=>false,'info'=>lang('tip_username_exist'));
        }

        $result = $ucenterUserLogic->getInfo(array('mobile'=>$mobile));

        if($result['status'] && is_array($result['info']) && !empty($result['info'])){
            return array('status'=>false,'info'=>lang('tip_mobile_exist'));
        }

        $password = think_ucenter_md5($password,ConfigHelper::getPasswordSalt());

        Db::startTrans();

        
        $error = "";
        $flag = false;
        //写入第一张表 UcenterMember
        $result = $ucenterUserLogic->register($username,$password,$email,$mobile,$country,RegFromEnum::getInstance($reg_from));
        $uid = 0;

        if ($result['status']) {
            $uid = $result['info'];
            $member = array(
                'uid'         => $uid,
                'realname'    => $realname,
                'nickname'    => $nickname,
                'idnumber'    => '',
                'sex'         =>  $sex,
                'birthday'    => $birthday,
                'qq'          => '',
                'head'        => $head,
                'update_time' => NOW_TIME, //
                'status'      => 1,        //
                'score'       => 0,
                'login'       => 0,
            );

            //写入第二张表 common_member
            $result = $memberLogic->add($member,"uid");

            if (!$result['status']) {
                $flag = true;
                $error = '[用户信息]'.$result['info'];
            }else{

            }
        } else {
            $flag = true;
            $error = '[用户账户]'.$result['info'];
        }

        //同步百川
        $result = $this->syncBaichuanUser($uid,$password,$nickname);
        $alibaichuan_id = 0 ;

        if(!$result['status']){
            $flag = true;
            $error = "BAICHUAN_".$result['info'];
        }else{
            $alibaichuan_id    = $result['info'];
        }

        if(!$flag){

            //插入到第三张表
            $map = array(
                'uid'               =>$uid,
                'phone_validate'    =>0,
                'email_validate'    =>0,
                'identity_validate' =>0,
                'idcode'            =>$idcode,
                'default_address'   =>0,
                'exp'               =>0,
                'invite_id'         => $invite_id,
                'wxopenid'          => $wxopenid,
                'alibaichuan_id'    => $alibaichuan_id
            );

            if($reg_type == UcenterMember::ACCOUNT_TYPE_MOBILE) {
                $map['phone_validate'] = 1;
            }

            $result = $memberConfigLogic->add($map,"uid");
            if(!$result['status'] ){
                $flag  = true;
                $error = '[用户配置]'.$result['info'];
            }

        }



        if ($flag) {

            Db::rollback();

            return ['status' => false, 'info' => $error];
        } else {
            Db::commit();
            /**
             *
             * 增加idcode 的处理，idcode ＝ 用户uid+100000的36进制表示
             * @author hebidu <hebiduhebi@126.com>
             * @date  15/11/29 17:11
             * @copyright by itboye.com
             */
            $idcode = get_36HEX(intval($uid)+100000);
            $result = $memberConfigLogic->save(['uid'=>$uid],['idcode'=>$idcode]);



            return ['status' => true, 'info' => $uid];
        }

    }

    /**
     * 同步百川用户信息
     * @param $uid
     * @param $password
     * @param $nickname
     * @return mixed
     */
    private function syncBaichuanUser($uid,$password,$nickname){
        $service  = new OpenIMUserService();
        $icon_url = ConfigHelper::avatar_url().'?uid='.$uid;
        $info = [
            'uid'=>$uid,
            'pwd'=>$password,
            'nickname'=>$nickname,
            'icon_url'=>$icon_url
        ];
        return $service->add($info);
    }

    /**
     * 获取用户信息
     * @param $id
     * @return mixed
     */
    function getInfo($id)
    {
        $logic = new UcenterMemberLogic();
        $result = $logic->getInfo(['id'=>$id]);

        if(!$result['status'] || empty($result['info'])){
            return ['status'=>false,'info'=> lang('err_not_find')];
        }

        $userinfo = $result['info'];

        //获取用户信息
        return $this->getUserInfo($userinfo);
    }

    /**
     * @param $uid
     * @param $entity
     * @return mixed
     */
    function update($uid, $entity)
    {
        
    }

    /**
     *
     * @param $map
     * @param $new_pwd
     * @return array
     */
    function updatePwd($map,$new_pwd){
        $result = ValidateHelper::legalPwd($new_pwd);
        if(!$result['status']){
            return $result;
        }
        $logic  = new UcenterMemberLogic();

        $result = $logic->getInfo($map);
        if(!ValidateHelper::legalArrayResult($result)){
            return ['status'=>false,'info'=>lang('err_modified')];
        }
        $userinfo = $result['info'];
        $uid = $userinfo['id'];

        $memberConfigLogic = new MemberConfigLogic();

        $result = $memberConfigLogic->getInfo(['uid'=>$uid]);

        if(!ValidateHelper::legalArrayResult($result)){
            return ['status'=>false,'info'=>lang('err_modified')];
        }

        $alibaichuan_id = $result['info']['alibaichuan_id'];
        Db::startTrans();
        $flag = true;
//        $error = "";

        $salt   = ConfigHelper::getPasswordSalt();
        $new_pwd= think_ucenter_md5($new_pwd,$salt);
        $result = $logic->save($map,['password'=>$new_pwd ]);

        if(!$result['status']){
            $flag = false;
//            $error = $result['info'];
        }

        //百川更新密码
        $service = new OpenIMUserService();
        $result = $service->update($alibaichuan_id,false,$new_pwd);

        if(!$result['status']){
            $flag = false;
//            $error = $result['info'];
        }

        if($flag){

            Db::commit();

            return ['status'=>true,'info'=>lang('suc_modified')];
        }else{

            Db::rollback();
            return ['status'=>false,'info'=>lang('err_modified')];
        }
    }

}
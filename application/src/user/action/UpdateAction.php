<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 14:39
 */
namespace app\src\user\action;

use app\src\alibaichuan\service\OpenIMUserService;
use app\src\base\action\BaseAction;
use app\src\base\helper\ConfigHelper;
use app\src\base\helper\ValidateHelper;
use app\src\user\logic\MemberConfigLogic;
use app\src\user\logic\MemberLogic;
use app\src\user\logic\UcenterMemberLogic;
use think\Db;

class UpdateAction extends BaseAction
{
    public function update($entity){
        $uid = $entity['uid'];

        $ucenter_entity = $entity['ucenter_entity'];
        $user_entity    = $entity['user_entity'];
        $member_config    = $entity['member_config'];
        if(empty($ucenter_entity) && empty($user_entity) && empty($member_config)){
            return $this->success(lang('suc_modified'));
        }

        $ucenterMemberLogic  = new UcenterMemberLogic();
        $userLogic  = new MemberLogic();
        $memberConfigLogic   = new MemberConfigLogic();
        $effect=0;
        if(!empty($ucenter_entity)){
            $result = $ucenterMemberLogic->save(['id'=>$uid],$ucenter_entity);
            $effect = $result['info'];
        }

        if(!empty($user_entity)) {
            $result = $userLogic->save(['uid' => $uid], $user_entity);
            if ($effect == 0) {
                $effect = $result['info'];
            }
        }

        if(!empty($member_config)){
            $result =  $memberConfigLogic->save(['uid'=>$uid],$member_config);
            if ($effect == 0) {
                $effect = $result['info'];
            }
        }
        if($effect == 1){
            return $this->success(lang('suc_modified'));
        }

        return $this->error(lang('err_modified'));
    }


    /**
     * @param $map 
     * @param $new_pwd string 明文密码
     * @return \app\src\base\logic\status|array|bool
     */
    public function updatePwd($map,$new_pwd){

        $result = ValidateHelper::legalPwd($new_pwd);
        if(!$result['status']){
            return $result;
        }
        $logic  = new UcenterMemberLogic();

        $result = $logic->getInfo($map);
        if(!ValidateHelper::legalArrayResult($result)){
            return $this->error(lang('err_modified'));
        }
        $userinfo = $result['info'];
        $uid = $userinfo['id'];

        $memberConfigLogic = new MemberConfigLogic();

        $result = $memberConfigLogic->getInfo(['uid'=>$uid]);

        if(!ValidateHelper::legalArrayResult($result)){
            return $this->error(lang('err_modified'));
        }

        $alibaichuan_id = $result['info']['alibaichuan_id'];
        Db::startTrans();
        $flag = true;
        $error = "";

        $salt   = ConfigHelper::getPasswordSalt();
        $new_pwd= think_ucenter_md5($new_pwd,$salt);
        $result = $logic->save($map,['password'=>$new_pwd ]);

        if(!$result['status']){
            $flag = false;
            $error = $result['info'];
        }

        //百川更新密码
        $baichuan = new OpenIMUserService();
        $result = $baichuan->update($alibaichuan_id,false,$new_pwd);

        if(!$result['status']){
            $flag = false;
            $error = $result['info'];
        }

        if($flag){

            Db::commit();

            return $this->success(lang('suc_modified'));
        }else{

            Db::rollback();
            return $this->error(lang('err_modified'));
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-09-14
 * Time: 16:13
 */

namespace app\src\alibaichuan\service;

/**
 * 用户同步
 * Class OpenIMUserService
 * @package app\alibaichuan\service
 */
class OpenIMUserService extends BaseAlibaichuanService
{

    public function get($id){
        //调用taobao.openim.users.get

        $topClient = $this->getTopClient();
        $req = new \OpenimUsersGetRequest();

        $req -> setUserids($id);
        $resp = $topClient->execute($req);
        $parseResp = $this->parseResp($resp);
        if($parseResp['code'] === "0"){
            $uid_suc = $resp['userinfos'];
            if(empty($uid_suc)) return ['status'=>false,'info'=>'账号错误:未取到账号百川数据'];
            $uid_suc = $uid_suc['userinfos'];

            if(is_array($uid_suc) && count($uid_suc) == 1){
                return ['status'=>true,'info'=>$uid_suc[0],'extra'=>[]];
            }

            return ['status'=>false,'info'=>'未知错误','extra'=>$parseResp];
        }else{
            //错误
            return ['status'=>false,'info'=>$parseResp['info'],'extra'=>$parseResp];
        }
    }

    /**
     *
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @return string
     */
    private function getOpenIMUid($uid){
        return "TE".$uid.time();
    }

    /**
     * 与百川同步一个用户信息
     * @param $info  array uid ,pwd,nickname,icon_url
     * @return mixed
     */
    public function add($info){

        $topClient = $this->getTopClient();
        $req       = new \OpenimUsersAddRequest();
        $userinfo  = new \Userinfos();

        // 填写用户信息
        $uid = $this->getOpenIMUid($info['uid']);
        $userinfo->userid   = $uid;
        $userinfo->password = $info['pwd'];
        $userinfo->nick     = $info['nickname'];
        $userinfo->icon_url = $info['icon_url'];

        $userinfo->mobile   = "";
        $userinfo->taobaoid = "";
        $userinfo->email    = "";
        $userinfo->remark   = "";
        $userinfo->extra    = "{}";
        $userinfo->career   = "";
        $userinfo->vip      = "{}";
        $userinfo->address  = "";
        $userinfo->name     = "";
        $userinfo->age      = "18";
        $userinfo->gender   = "";
        $userinfo->wechat   = "";
        $userinfo->qq       = "";
        $userinfo->weibo    = "";
        
        $req->setUserinfos(json_encode($userinfo));
        $resp = $topClient->execute($req);
        $parseResp = $this->parseResp($resp);
        if($parseResp['code'] === "0"){
            //正确
            $uid_succ = $resp['uid_succ'];//['string'];
            $uid_fail = $resp['uid_fail'];//['string'];
            $fail_msg = $resp['fail_msg'];//['string'];
            if(is_array($uid_succ) && count($uid_succ) == 1){
                return ['status'=>true,'info'=>$uid,'extra'=>[]];
            }
            return ['status'=>false,'info'=>$fail_msg,'extra'=>$parseResp];
        }else{
            //错误
            return ['status'=>false,'info'=>$parseResp['info'],'extra'=>$parseResp];
        }
    }


    /**
     * 更新用户信息,nickname,password
     * @param $nick
     * @return array
     * @internal param $open_uid
     * @internal param $info
     */
    public function update($open_uid,$nick=false,$password=false){

        $topClient =  $this->getTopClient();
        $req = new \OpenimUsersUpdateRequest();
        $userinfo = new \Userinfos();

        $userinfo->userid   = $open_uid;

        if($nick !== false){
            $userinfo->nick     = $nick;
        }

        if($password !== false) {
            $userinfo->password = $password;
        }

        $req->setUserinfos(json_encode($userinfo));
        $resp = $topClient->execute($req);

        $parseResp = $this->parseResp($resp);
        if($parseResp['code'] == "0"){

            $uid_succ = $resp['uid_succ'];
            $uid_fail = $resp['uid_fail'];
            $fail_msg = $resp['fail_msg'];
            if(isset($fail_msg['string'])){
                $fail_msg = $fail_msg['string'][0];
            }

            if(is_array($uid_succ) && count($uid_succ) == 1){
                return ['status'=>true,'info'=>lang('success'),'extra'=>[]];
            }
            return ['status'=>false,'info'=>$fail_msg,'extra'=>$parseResp];
        }else{

            //错误
            return ['status'=>false,'info'=>$parseResp['info'],'extra'=>$parseResp];
        }
    }

    /**
     * 删除
     * @author hebidu <email:346551990@qq.com>
     * @param $ids
     */
    public function delete($ids){

        $topClient =  $this->getTopClient();
        $req = new \OpenimUsersDeleteRequest();
        $req->setUserids($ids);
        $resp = $topClient->execute($req);
        $parseResp = $this->parseResp($resp);
        if($parseResp['code'] == "0"){

            dump($resp);
        }else{

            //错误
            return ['status'=>false,'info'=>$parseResp['info'],'extra'=>$parseResp];
        }
    }

}
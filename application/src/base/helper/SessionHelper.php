<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-10
 * Time: 17:20
 */

namespace app\src\base\helper;


use app\src\session\logic\LoginSessionLogic;
use think\Session;

class SessionHelper
{

    public static function clearLoginSession($login_uid){
        $logic = new LoginSessionLogic();
        $logic->delete(['uid'=>$login_uid]);
    }

    public static function singleLoginCheck($login_uid){
        $logic = new LoginSessionLogic();
        $result= $logic->getInfo(['uid'=>$login_uid]);

        if(ValidateHelper::legalArrayResult($result)){
            return $result['info'];
        }

        return false;
    }

    /**
     * 记录 Session 信息
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @return string
     */
    public static function logSession($uid){
        $key = config('session.var_session_id');
        if(!isset($_GET[$key])){
            $session_id = session_id();
        }else{
            $session_id = $_GET[$key];
        }
        $session_id = config('session.session_prefix').$session_id;
        self::log(($uid),$session_id);

        return $session_id;

    }

    public static function log($login_uid,$session_id){
        $logic = new LoginSessionLogic();
        $result= $logic->getInfo(['uid'=>$login_uid,'session_id'=>$session_id]);

        if($result['status'] && empty($result['info'])){

            //清除该uid对应的其它记录
            self::clearLoginSession($login_uid);

            $login_info = get_client_ip();

            //插入
            $result = $logic->add([
                'session_id'=>$session_id,
                'uid'=>$login_uid,
                'login_info'=>$login_info,
                //TODO: 获取当前登录用户的设备信息（设备型号、ios、android等）
            ]);

            if(!$result['status']){

            }

        }
    }

    public static function logout(){
        Session::clear();
    }

    public static function setUserInfo($userinfo){
         session(SessionKeys::USER,$userinfo);
    }

}
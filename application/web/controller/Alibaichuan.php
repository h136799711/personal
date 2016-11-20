<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-31
 * Time: 15:40
 */

namespace app\web\controller;


use app\src\base\helper\ConfigHelper;
use app\src\base\helper\ValidateHelper;
use app\src\user\facade\DefaultUserFacade;
use think\Controller;

class Alibaichuan extends Controller
{
    public function im(){


        $uid = $this->request->get('uid',0);
        $info = $this->getInfo($uid);
        $touid = $this->request->get('touid',0);
        
        $toUserInfo = $this->getInfo($touid);

        $this->assign("touser",$toUserInfo);
        $this->assign("user",$info);

        return $this->fetch();
    }

    private function getInfo($uid){
        $logic = new DefaultUserFacade();
        $result = $logic->getInfo($uid);
        if(!ValidateHelper::legalArrayResult($result)){
            $this->error('非法uid');
        }

        $uid = $result['info']['id'];
        $openim_uid = $result['info']['alibaichuan_id'];
        $password = $result['info']['password'];

        $user = [
            'avatar'=>ConfigHelper::getAvatarUrl($uid,120),
            'uid'=>$openim_uid,
            'password'=>$password,
            'title'=>'正在对话中..',
        ];
        return $user;
    }

}
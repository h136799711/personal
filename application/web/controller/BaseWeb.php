<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-11
 * Time: 10:20
 */

namespace app\web\controller;


use app\src\base\helper\ConfigHelper;
use app\src\user\logic\UcenterMemberLogic;
use think\Controller;
use think\Request;

class BaseWeb extends Controller
{
    protected $lang;

    protected function _initialize()
    {
        parent::_initialize();

        $this->setLang();
        $this->auth();
    }

    private function setLang(){
        $this->lang = $this->request->get('lang','zh-cn');

        //检查语言是否支持
        $lang_support = ConfigHelper::getLangSupport();
        $is_support = false;

        foreach ($lang_support as $lang){
            if($lang['value'] == $this->lang){
                $is_support = true;
            }
        }

        if(!$is_support){
            //对于不支持的语言都使用英语
            $this->lang = "en";
        }
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _post($key,$default='',$emptyErrMsg=''){

        $value = request()->post($key,$default);

        if($default == $value && !empty($emptyErrMsg)){
            $this->error($emptyErrMsg);
        }
        return $value;
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _get($key,$default='',$emptyErrMsg=''){
        $value = request()->get($key,$default);

        if($default == $value && !empty($emptyErrMsg)){
            $this->error($emptyErrMsg);
        }
        return $value;
    }

    private function auth(){

        $uid = $this->_get('uid','',lang('uid_need'));

        $psw = $this->_get('psw','',lang('auth_psw_need'));
        $result = (new UcenterMemberLogic())->auth($uid,$psw,1);

        if(!$result){
            $this->error(lang("err_auth_fail"));
        }
    }
}
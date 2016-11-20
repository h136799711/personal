<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 11:36
 */

namespace app\domain;


use app\src\base\helper\ConfigHelper;
use app\src\extend\sms\SmsFacade;
use app\src\system\logic\SecurityCodeLogic;
use app\src\system\model\SecurityCode;
use app\src\user\logic\UcenterMemberLogic;

class SecurityCodeDomain extends BaseDomain
{
    /**
     *
     */
    public function send(){

        $this->checkVersion("101");

        $country = $this->_post("country","",lang('country_tel_number_need'));
        $mobile = $this->_post("mobile","",lang('mobile_need'));
        $type = $this->_post("code_type","",lang('code_type_need'));
        $notes = SecurityCode::getTypeDesc($type);
        if($notes == "未知"){
            $this->apiReturnErr(lang("invalid_parameter",['param'=>'code_type']));
        }

        addLog($this->domain_class, $_GET, $_POST,$this->client_id ."用于".$notes.",发送了验证码到".$mobile);
        
        $logic = new SecurityCodeLogic();
        
        $map = array(
            'accepter' => $country.$mobile
        );

        $result = $logic->getInfo($map,"starttime desc");
        //开发环境不检测
        if(!ConfigHelper::isDebug()){
            if($result['status'] && is_array($result['info'])){
                $info = $result['info'];
                if($info['starttime'] <  NOW_TIME && $info['starttime'] + 60*1 > NOW_TIME  ){
                    //1分钟内只能向一个手机发一次信息
                    $delay = 60*1  + $info['starttime'] - NOW_TIME;

                    $this->apiReturnErr(lang('delay_do_msg_send',['param'=>$delay]) );
                }
            }
        }

        $this->mobileCheck($country,$mobile,$type);

        //生成短信验证码6位
        $code = mt_rand(100000, 999999);

        //2. 纪录到数据库
        $entity = array(
            'code' => $code,
            'accepter' => $country.$mobile,
            'starttime' => time(),
            'endtime' => time() + 1800,
            'ip' => ip2long(get_client_ip()),
            'client_id' => $this->client_id,
            'type'=>$type,
            'status'=>0,// 未验证
        );

        $securityCodeLogic = new SecurityCodeLogic();
        
        //1. 重置该手机号对应的验证码
        $securityCodeLogic->resetAll($mobile);
        $result = $securityCodeLogic->add($entity,"id");


        if (!$result['status']) {
            $this->apiReturnErr($result['info']);
        }

        //调用短信接口进行发送
        $smsFacade = new SmsFacade();
        $smsFacade->send();

//        $code = "短信已发送，请注意查看!";
        $code = "你的验证码是（".$code."）!";
        $this->apiReturnSuc($code);

    }

    /**
     * 检测手机号和用途的合法性
     * @param $country
     * @param $mobile
     * @param $type
     */
    private function mobileCheck($country,$mobile,$type){
        $map = array(
            'mobile' => $mobile,
            'country_no'=>$country
        );

        $logic = new UcenterMemberLogic();

        $result = $logic->getInfo($map);

        if($type == SecurityCode::TYPE_FOR_REGISTER){
            if ($result['info'] != null) {
                $this->apiReturnErr(lang('tip_mobile_registered'));
            }
        }elseif($type == SecurityCode::TYPE_FOR_UPDATE_PSW ){
            if ($result['info'] == null) {
                $this->apiReturnErr(lang('tip_mobile_unregistered'));
            }
        }
    }

}
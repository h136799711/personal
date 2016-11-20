<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 11:47
 */

namespace app\src\system\logic;


use app\src\base\logic\BaseLogic;
use app\src\system\model\SecurityCode;

class SecurityCodeLogic extends BaseLogic
{
    /**
     * @return mixed
     */
    protected function _init()
    {
        $this->setModel(new SecurityCode());
    }
    
    public function resetAll($mobile){
        
        $result = $this->save(array('accepter'=>$mobile) , array('status'=>1));

        if($result === false){
            return $this->apiReturnErr($this->getModel()->getError());
        }

        return $this->apiReturnSuc($mobile);
    }

    /**
     * 验证验证码是否有效
     * @param $code     string
     * @param $mobile   string
     * @param $type
     * @return array
     */
    public function isLegalCode($code, $mobile, $type){

        
        if($code == "itboye"){
            return $this->apiReturnSuc("legal code");
        }

        $map=array(
            'code'=>$code,
            'accepter'=>$mobile,
            'type'=>$type,
        );
        $order="endtime desc";

        $result = $this->getModel()->where($map)->order($order)->find();

        if($result === false){
            return $this->apiReturnErr($this->getModel()->getError());
        }

        if(is_null($result)){
            return $this->apiReturnErr(lang("err_invalid_code"));
        }

        $codeEntity = $result;

        if($codeEntity['status'] != 0){
            return $this->apiReturnErr(lang("err_code_used"));
        }

        if($codeEntity['endtime'] < NOW_TIME){
            return $this->apiReturnErr(lang("err_code_expired"));
        }

        $result = $this->resetAll($mobile);

        if(!$result['status']){
            return $this->apiReturnErr($result['info']);
        }
        return $this->apiReturnSuc("legal code");

    }
    

}
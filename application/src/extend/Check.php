<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/11/2
 * Time: 21:38
 */

namespace app\src\extend;

class Check {

    //身份证验证
    public function is_ID_Card($id_card){

        if(strlen($id_card) == 15){
            //身份证正则表达式(15位)
            $isIDCard1="/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/";
            if(preg_match($isIDCard1,$id_card)){
                return true;
            }
        }elseif(strlen($id_card) == 18){
            //身份证正则表达式(18位)
            $isIDCard2="/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/";
            if(preg_match($isIDCard2,$id_card)){
                return true;
            }
        }else{
            return false;
        }
    }

}
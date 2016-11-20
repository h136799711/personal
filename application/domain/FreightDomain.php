<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 21:59
 */

namespace app\domain;


use app\src\freight\action\FreightCalcAction;

/**
 * 运费计算
 * Class FreightDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class FreightDomain extends BaseDomain
{

    /**
     * 立即购买计算运费
     * @author hebidu <email:346551990@qq.com>
     */
    public function  calcNow(){

        $uid = $this->_post("uid",'',lang('uid_need'));
        $count = $this->_post('count',"",lang("lack_parameter",["param"=>"count"]));
        $sku_pkid = $this->_post('sku_pkid',"",lang("lack_parameter",["param"=>"sku_pkid"]));
        $address_id = $this->_post('address_id','',lang('address_id_need'));

        $count_arr = explode(",",$count);
        $sku_pkid_arr = explode(",",$sku_pkid);

        if(empty($count_arr) || empty($sku_pkid_arr) || count($sku_pkid_arr) != count($count_arr)){
            $this->apiReturnErr(lang("err_param"));
        }
        
        $result = (new FreightCalcAction())->calcBuyNow($uid,$count_arr,$sku_pkid_arr,$address_id);

        $this->exitWhenError($result,true);
    }

    /**
     * 计算运费
     * @author hebidu <email:346551990@qq.com>
     */
    public function calc(){
        $ids = $this->_post("ids",'',lang('id_need'));
        $uid = $this->_post("uid",'',lang('uid_need'));
        $address_id = $this->_post('address_id','',lang('address_id_need'));

        $result = (new FreightCalcAction())->calc($uid,$ids,$address_id);
        
        $this->exitWhenError($result,true);
    }
    
    
}
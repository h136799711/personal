<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 17:54
 */

namespace app\domain;


use app\src\address\action\AddressAddAction;
use app\src\address\action\AddressDeleteAction;
use app\src\address\logic\AddressLogic;
use app\src\base\helper\ValidateHelper;
use app\src\user\logic\MemberConfigLogic;

/**
 * Class AddressDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class AddressDomain extends BaseDomain
{

    /**
     * 设置默认地址
     * @author hebidu <email:346551990@qq.com>
     */
    public function setDefault(){
        $this->checkVersion("100");
        $id      = $this->_post("id",0,lang("id_need"));
        $uid     = $this->_post("uid",0,lang("uid_need"));

        $addressLogic = new AddressLogic();

        $result = $addressLogic->getInfo(['id'=>$id]);
        $this->exitWhenError($result);

        $addressInfo = $result['info'];

        if(!empty($addressInfo) && isset($addressInfo['uid'])){
            if($addressInfo['uid'] == $uid){
                $result = $this->setDefaultAddress($id,$uid);
                if($result['status']){
                    $this->apiReturnSuc(lang('success'));
                }
            }else{
                $this->apiReturnErr(lang('err_not_users_data'));
            }
        }else{
            $this->apiReturnErr(lang('err_address_not_exits'));
        }

        $this->apiReturnErr(lang('fail'));

    }

    /**
     * 获取用户的默认地址
     * @author hebidu <email:346551990@qq.com>
     */
    public function getDefault(){
        $this->checkVersion("100");
        $uid     = $this->_post("uid",0,lang("uid_need"));

        $userLogic = new MemberConfigLogic();
        $result = $userLogic->getInfo(['uid'=>$uid]);
        $this->exitWhenError($result);
        $member_config = $result['info'];
        if(!empty($member_config) && isset($member_config['default_address'])){
            $address_id = $member_config['default_address'];
            $addressLogic = new AddressLogic();
            $result = $addressLogic->getInfo(['id'=>$address_id]);
            if($result['status']){
                $this->apiReturnSuc($result['info']);
            }else{
                $this->apiReturnErr($result['info']);
            }
        }else{
            $this->apiReturnErr(lang('invalid_parameter',['param'=>'uid']));
        }
    }

    /**
     * 收货地址更新接口
     * @author hebidu <email:346551990@qq.com>
     */
    public function update(){
        $this->checkVersion("100");

        $id      = $this->_post("id",0,lang("id_need"));
        $uid     = $this->_post("uid",0,lang("uid_need"));
        $default = $this->_post("default",-1);
        $entity  = $this->getAddressModel();
        $logic   = new AddressLogic();

        $result = $logic->getInfo(['id'=>$id,'uid'=>$uid]);

        if(!ValidateHelper::legalArrayResult($result)){
            $this->apiReturnErr(lang('invalid_id'));
        }

        $result  = $logic->save(['id'=>$id,'uid'=>$uid],$entity);

        if(intval($default) == 1){
            $this->setDefaultAddress($id,$uid);
        }
        
        $this->exitWhenError($result,true);
    }

    /**
     * 收货地址查询接口
     * @author hebidu <email:346551990@qq.com>
     */
    public function query(){
        $this->checkVersion("100");
        $uid     = $this->_post("uid",0,lang("uid_need"));

        $logic   = new AddressLogic();
        $result  = $logic->queryNoPaging(['uid'=>$uid],'id desc');

        $this->exitWhenError($result);
        $address_list = $result['info'];
        $userLogic = new MemberConfigLogic();
        $result = $userLogic->getInfo(['uid'=>$uid]);
        if(!$result['status'] || empty($result['info'])){
            $this->apiReturnErr(lang('invalid_parameter',['param'=>'uid']));
        }
        $member_config = $result['info'];

        if(isset($member_config['default_address'])){
            $default_address = $member_config['default_address'];
            foreach ($address_list as &$address){
                $address['is_default'] = 0;
                if($address['id'] == $default_address){
                    $address['is_default'] = 1;
                }
            }

            $this->apiReturnSuc($address_list);

        }else{
            $this->apiReturnErr(lang('invalid_parameter',['param'=>'uid']));
        }

    }

    /**
     * 删除地址
     * @author hebidu <email:346551990@qq.com>
     */
    public function delete(){

        $this->checkVersion("100");
        $uid     = $this->_post("uid",0,lang("uid_need"));
        $id     = $this->_post("id",0,lang("address_id_need"));
        
        $action = new AddressDeleteAction();
        
        $result = $action->delete(['id'=>$id,'uid'=>$uid]);

        $this->exitWhenError($result,true);
    }

    /**
     * 地址添加
     * @author hebidu <email:346551990@qq.com>
     */
    public function add(){
        $this->checkVersion("100");

        $uid     = $this->_post("uid",0,lang("uid_need"));
        $default = $this->_post("default",0);


        $result = (new MemberConfigLogic())->getInfo(['uid'=>$uid]);

        $this->exitWhenError($result);

        $member_config = $result['info'];


        $map = $this->getAddressModel();
        $map['uid'] = $uid;
        $action = new AddressAddAction();
        $result = $action->add($map);
        $default_address_id = 0;

        if($result['status']) {
            $default_address_id = $result['info'];
        }else {
            $this->apiReturnErr($result['info']);
        }


        $result = (new AddressLogic())->count(['uid'=>$uid]);

        $this->exitWhenError($result);

        //如果是第一个
        if(intval($result['info']) == 1 || intval($default) == 1) {
            $this->setDefaultAddress($default_address_id,$uid);
        }

        $this->apiReturnSuc(lang("success"));
    }

    private function setDefaultAddress($address_id,$uid){

        $userLogic = new MemberConfigLogic();

        return $userLogic->save(['uid' => $uid], ['default_address' => $address_id]);
    }

    /**
     * 获取收货地址模型
     * @return array
     */
    private function getAddressModel(){

        $province    = $this->_post('province','');
        $provinceid  = $this->_post('provinceid','');
        $city        = $this->_post('city','');
        $area        = $this->_post('area','');
        $cityid      = $this->_post('cityid','');
        $areaid      = $this->_post('areaid','');

        $detailinfo  = $this->_post('detailinfo','');
        $contactname = $this->_post('contactname','',lang('err_contact_name_need'));
        $mobile      = $this->_post('mobile','',lang('err_contact_mobile_need'));
        $postal_code = $this->_post('postal_code','',lang("err_contact_email_need"));
        $country_id  = $this->_post("country_id",0,lang("err_country_id_need"));
        $country     = $this->_post("country","");
        $wxno        = $this->_post('wxno','');
        $id_card     = $this->_post("id_card",'');
//
//            $check = new Check();
//            if(!empty($id_card) && !$check->is_ID_Card($id_card)){
//                $this->apiReturnErr(lang('err_invalid_idcard'));
//            }
        $country_id = intval($country_id);
        if($country_id <= 0){
            $this->apiReturnErr(lang("err_country_id_error"));
        }
        $map = [
            'country_id'  =>$country_id,
            'country'     =>$country,
            'city'        =>$city,
            'province'    =>$province,
            'area'        =>$area,
            'detailinfo'  =>$detailinfo,
            'contactname' =>$contactname,
            'mobile'      =>$mobile,
            'postal_code' =>$postal_code,
            'wxno'        =>$wxno,
            'cityid'      =>$cityid,
            'provinceid'  =>$provinceid,
            'areaid'      =>$areaid,
            'id_card'     =>$id_card,
            'update_time' =>time()
        ];

        return $map;
    }
}
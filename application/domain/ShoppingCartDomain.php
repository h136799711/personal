<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 15:26
 */

namespace app\domain;


use app\src\shoppingCart\action\ShoppingCartAddAction;
use app\src\shoppingCart\action\ShoppingCartBulkAddAction;
use app\src\shoppingCart\action\ShoppingCartQueryAction;
use app\src\shoppingCart\logic\ShoppingCartLogic;
use app\src\shoppingCart\validate\CartValidate;

class ShoppingCartDomain extends BaseDomain
{
    /**
     * 查询购物车项
     * 102: 对商品进行了分类，按发布人
     * @author hebidu <email:346551990@qq.com>
     */
    public function query(){
        $this->checkVersion(["102"]);

        $uid = $this->_post('uid','',lang("uid_need"));
        $action = new ShoppingCartQueryAction();
        $result = $action->query($uid);
        $this->exitWhenError($result,true);
    }


    /**
     * 购物车项更新
     * @author hebidu <email:346551990@qq.com>
     */
    public function update(){
        $id = $this->_post('id','',lang('id_need'));
        $cnt = $this->_post('count','',lang('count_need'));
        $uid  = $this->_post('uid','',lang('uid_need'));

        $logic = new ShoppingCartLogic();

        $result = $logic->save(['id'=>$id,'uid'=>$uid],['count'=>$cnt]);

        $this->exitWhenError($result,true);
    }

    /**
     * 购物车批量添加
     * @author hebidu <email:346551990@qq.com>
     */
    public function bulkAdd(){
        $uid = $this->_post('uid');
        $id  = $this->_post("id");
        $count = $this->_post('count');
        $sku_pkid = $this->_post('sku_pkid');

        $count_arr = explode(",",$count);
        $sku_pkid_arr = explode(",",$sku_pkid);

        if(empty($count_arr) || empty($sku_pkid_arr) || count($sku_pkid_arr) != count($count_arr)){
            $this->apiReturnErr(lang("err_param"));
        }

        
        $action = new ShoppingCartBulkAddAction();

        $result = $action->bulkAdd($uid,$id,$count_arr,$sku_pkid_arr);

        $this->exitWhenError($result,true);
    }

    /**
     * 添加
     * @author hebidu <email:346551990@qq.com>
     */
    public function add(){
        
        $entity = $this->getParams(['uid','count','id','sku_pkid']);

        $validate =  new CartValidate();

        if(!$validate->check($entity)){
            $this->apiReturnErr($validate->getError());
        }

        $action = new ShoppingCartAddAction();
        
        $result = $action->add($entity);
        
        $this->exitWhenError($result,true);
    }

    /**
     * 删除操作
     * @author hebidu <email:346551990@qq.com>
     */
    public function delete(){
        $id  = $this->_post('id','',lang('id_need'));
        $uid  = $this->_post('uid','',lang('uid_need'));

        $logic = new ShoppingCartLogic();

        $id = trim($id,",");


//        $result = $logic->delete(['id'=>$id,'uid'=>$uid]);

        $result = $logic->delete(['id'=>['in',$id],'uid'=>$uid]);

        $this->exitWhenError($result,true);
    }

}
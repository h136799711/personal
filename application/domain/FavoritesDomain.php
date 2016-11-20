<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-22
 * Time: 10:11
 */

namespace app\domain;


use app\src\favorites\action\FavoritesAddAction;
use app\src\favorites\action\FavoritesQueryAction;
use app\src\favorites\logic\FavoritesLogic;
use app\src\favorites\model\Favorites;
use app\src\goods\logic\ProductAttrLogic;
use app\src\goods\model\Product;

/**
 *
 * 收藏相关接口
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class FavoritesDomain extends BaseDomain
{
    /**
     * 收藏物品查询
     * @author hebidu <email:346551990@qq.com>
     */
    public function query(){


        $uid = $this->_post("uid","",lang("uid_need"));
        $uid = 39;
        $cate_id = $this->_post("cate_id","");
        $keyword = $this->_post("keyword","");
        $page_index = $this->_post("page_index",1);
        $page_size = $this->_post("page_size",10);
        
        $action  = new FavoritesQueryAction();

//        $result = $action->query(['stick'=>Favorites::FAV_UNSTICK,'uid'=>$uid,'page_index'=>$page_index,'page_size'=>$page_size,'cate_id'=>$cate_id,'keyword'=>$keyword]);

        $result = $action->query(['uid'=>$uid,'page_index'=>$page_index,'page_size'=>$page_size,'cate_id'=>$cate_id,'keyword'=>$keyword]);

        if($result['status'] && is_array($result['info'])){
            if(isset($result['info']['list']) && count($result['info']['list']) > 0){
                $list = $result['info']['list'];

                foreach ($list as &$vo){
                    $vo['favorite_content'] = json_decode($vo['favorite_content'],JSON_OBJECT_AS_ARRAY);
                }

                $result['info']['list'] = $list;
            }
        }
        $this->exitWhenError($result,true);
    }

    /**
     * 收藏物品置顶
     * @author hebidu <email:346551990@qq.com>
     */
    public function stick(){
        $uid = $this->_post('uid','',lang('uid_need'));
        $ids   = $this->_post('ids','',lang('id_need'));
        $logic = new FavoritesLogic();
        $ids   = trim(trim($ids),",");
        $where = [
            'uid'=>$uid,
            'id'=>['in',$ids],
        ];

        $result = $logic->save($where,['stick'=>Favorites::FAV_STICK]);

        $this->exitWhenError($result,true);
    }

    /**
     * 收藏的商品类目
     * @author hebidu <email:346551990@qq.com>
     */
    public function cate(){

        $uid = $this->_post("uid","",lang("uid_need"));

        $logic = new FavoritesLogic();

        $result = $logic->queryCates($uid);

        $this->exitWhenError($result,true);
    }
    
    /**
     * 批量取消收藏
     * @author hebidu <email:346551990@qq.com>
     *
     */
    public function bulkCancel(){

        $uid = $this->_post('uid','',lang('uid_need'));
        $ids   = $this->_post('ids','',lang('id_need'));
        $logic = new FavoritesLogic();
        $ids   = trim(trim($ids),",");
        $where = [
            'uid'=>$uid,
            'id'=>['in',$ids],
        ];

        $result = $logic->delete($where);




        $this->exitWhenError($result,true);
    }

    /**
     * 收藏添加
     * 101: 支持类目收藏
     * @author hebidu <email:346551990@qq.com>
     */
    public function add(){

        $this->checkVersion(["101"]);

        $uid = $this->_post('uid','',lang('uid_need'));
        $id  = $this->_post('id','',lang('id_need'));
        $type= $this->_post('f_type','',lang('type_need'));

        $entity = [
            'favorite_id'=>$id,
            'uid'=>$uid,
            'type'=>$type,
        ];

        if(!Favorites::isLegalType($type)){
            $this->apiReturnErr(lang('err_type'));
        }

        $action = new FavoritesAddAction();

        $result = $action->add($entity);

        if($result['status']){
            $this->afterFavoriteSuccess($type,$id);
        }

        $this->exitWhenError($result,true);
    }



    /**
     * 收藏成功后
     * @param $type
     * @param $id
     */
    private function afterFavoriteSuccess($type,$id){

        if($type == Favorites::FAV_TYPE_PRODUCT){
            (new ProductAttrLogic())->setInc(['pid'=>$id],'favorite_cnt');
        }

    }
}
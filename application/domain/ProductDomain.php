<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 19:27
 */

namespace app\domain;


use app\src\base\helper\ValidateHelper;
use app\src\favorites\logic\FavoritesLogic;
use app\src\favorites\model\Favorites;
use app\src\goods\action\ProductDetailAction;
use app\src\goods\action\ProductSearchAction;
use app\src\goods\logic\ProductAttrLogic;
use app\src\goods\logic\ProductLogic;

/**
 * 商品相关接口
 * Class ProductDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class ProductDomain extends BaseDomain
{
    /**
     * 商品详情接口
     * 102: 增加了商品是否收藏的字段 is_fav
     * @author hebidu <email:346551990@qq.com>
     */
    public function detail(){

        $this->checkVersion("102");
        
        $id = $this->_post("id",'',lang('id_need'));
        $action = new ProductDetailAction();
        $result = $action->detail($id);

        $this->incViewCnt($id);
        
        $this->exitWhenError($result,true);
    }

    /**
     * 增加查看次数
     * @param $id
     */
    private function incViewCnt($id){
        (new ProductAttrLogic())->setInc(['pid'=>$id],'view_cnt');
    }
    
    /**
     * 商品搜索接口
     * @author hebidu <email:346551990@qq.com>
     */
    public function search(){

        $entity = $this->getParams(['cate_id','prop_id','keyword','page_index','page_size']);

        $action  = new ProductSearchAction();

        $entity['lang'] = $this->lang;
        
        $result  = $action->search($entity);
        
        $this->exitWhenError($result,true);
    }

    /**
     * 商品搜索关键词接口
     * @author hebidu <email:346551990@qq.com>
     */
    public function searchKeywords(){
        $keyword = $this->_post("keyword","");

        $logic = new ProductLogic();
        $map = [
            'name'=>['like','%'.$keyword.'%']
        ];

        $result = $logic->queryWithCount($map,['curpage'=>1,'size'=>10], false, false, "id,name,secondary_headlines");

        $list = $result['info'];
        if(is_array($list) && isset($list['count'])){
            $list = $list['list'];
            $result['info'] = $list;

            if(count($list) == 0){
                $map = [
                    'secondary_headlines'=>['like','%'.$keyword.'%']
                ];

                $result = $logic->queryWithCount($map,['curpage'=>1,'size'=>10], false, false, "id,name,secondary_headlines");

                $list = $result['info'];
                if(is_array($list) && isset($list['count'])){
                    $list = $list['list'];
                    $result['info'] = $list;
                }
            }

        }

        $this->exitWhenError($result,true);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-21
 * Time: 17:19
 */

namespace app\src\goods\action;


use app\src\base\action\BaseAction;
use app\src\goods\logic\ProductLogic;

class ProductSearchAction extends BaseAction
{
    
    /**
     * 商品搜索
     * @author hebidu <email:346551990@qq.com>
     * @param $entity
     * @return array
     */
    public function search($entity){
        $lang = $entity['lang'];
        $cate_id = $entity['cate_id'];
        $prop_id = $entity['prop_id'];
        $keyword = $entity['keyword'];
        $page_index = $entity['page_index'];
        $page_size =  max(intval($entity['page_size']),1);
        $page_size =  min($page_size,1000);

        $logic = new ProductLogic();

        $result = $logic->search($lang,$cate_id,$prop_id,$keyword,['page_size'=>$page_size,'page_index'=>$page_index]);

        //获取图片
        if($result['status'] && is_array($result['info'])){
            $count = $result['info']['count'];
            $list = $result['info']['list'];
            $list = (new ProductLogic())->mergeImages($list);
            return $this->success(['count'=>$count,'list'=>$list]);
        }else{
            return $this->error($result['info']);
        }
    }

}
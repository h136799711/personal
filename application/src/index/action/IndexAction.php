<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-04
 * Time: 14:52
 */

namespace app\src\index\action;

use app\src\base\action\BaseAction;
use app\src\base\helper\PageHelper;
use app\src\goods\logic\ProductLogic;
use app\src\goods\model\Product;
use app\src\goods\model\ProductImage;

class IndexAction extends BaseAction
{
    /**
     * 首页
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @param $lang
     * @param PageHelper $pageHelper
     * @return array
     */
    public function index($uid,$lang,PageHelper $pageHelper){
        
        $logic = new ProductLogic();
        $result = $logic->random($uid,$lang,'','','',$pageHelper->toPageParam());
        
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
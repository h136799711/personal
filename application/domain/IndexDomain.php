<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 17:24
 */

namespace app\domain;
use app\src\banners\logic\BannersLogic;
use app\src\banners\model\Banners;
use app\src\base\helper\PageHelper;
use app\src\base\helper\ValidateHelper;
use app\src\index\action\IndexAction;

/**
 * app首页
 * Class IndexDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class IndexDomain extends BaseDomain
{
    /**
     * 首页商品接口
     * 101: 增加了收藏字段is_fav 有的时候为收藏id，没有为0 或 空字符串
     * 102: 商品数据也为数组
     * @author hebidu <email:346551990@qq.com>
     */
    public function index(){

        $this->checkVersion(["102"],"商品数据也为数组");
        
        //返回数据
        $uid = $this->_post('uid','');
        
        $action = new IndexAction();

        //1. 过滤不喜欢的类目
        $result = $action->index($uid,$this->lang,new PageHelper($this->getPageParams()));

        $ads = $this->queryAd();

        if(ValidateHelper::legalArrayResult($result)){
            $data = $this->combine($result['info'],$ads);
            $result['info'] = $data;
        }

        $this->exitWhenError($result,true);
    }

    private function combine($info,$ads){
        $count = $info['count'];
        $list  = $info['list'];
        $total = count($ads) + count($list);
        $tmp   = [];
        $i     = 0;//ads index
        $j     = 0;//list index

        while($i + $j < $total){
            if(rand(0,10) < 5){
                if($j < count($list)){
                    array_push($tmp, ['type' => 'p', 'info' => [$list[$j]]]);
                    $j++;
                }elseif($i < count($ads)) {
                    array_push($tmp, ['type' => 'ad', 'info' => $ads[$i]]);
                    $i++;
                }
            }else{
                if($i < count($ads)) {
                    array_push($tmp, ['type' => 'ad', 'info' =>  $ads[$i]]);
                    $i++;
                }elseif($j < count($list)){
                    array_push($tmp, ['type' => 'p', 'info' => [$list[$j]]]);
                    $j++;
                }
            }
        }

        return ['count'=>$count,'list'=>$tmp];
    }

    /**
     * 向返回数据插入 广告条目
     * @author hebidu <email:346551990@qq.com>
     */
    private function queryAd(){
        //1. 随机获取首页广告 进行插入
        $logic = new BannersLogic();

        $rand = rand(0,2);

        $result = $logic->query(['position'=>Banners::APP_AD],['curpage'=>1,'size'=>$rand]);
        if(!empty($result['info']) && isset($result['info']['list'])){

            //TODO: 支持多图片返回
            $list = $result['info']['list'];
            $tmp = [];
            foreach ($list as $item){
                array_push($tmp,[$item]);
            }

            return $tmp;
        }

        return [];
    }
}
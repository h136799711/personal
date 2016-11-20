<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-22
 * Time: 10:08
 */

namespace app\src\favorites\logic;


use app\src\base\helper\PageHelper;
use app\src\base\logic\BaseLogic;
use app\src\favorites\model\Favorites;
use think\Db;

class FavoritesLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new Favorites());
    }

    /**
     * 搜索
     * @param $map
     * @return array
     */
    public function search($map){
        
        $pageHelper = PageHelper::renew($map);

        $keyword  = $map['keyword'];
        $cate_id  = $map['cate_id'];
        $uid  = $map['uid'];
//        $stick  = $map['stick'];

        $query = Db::table("itboye_favorites")->alias("f")
            ->field("f.*,p.cate_id,p.name")
            ->join("itboye_product p"," f.favorite_id= p.id and f.type=".Favorites::FAV_TYPE_PRODUCT,"LEFT")
            ;
        
        if(!empty($keyword)){
            $query->where('p.name','like','%'.$keyword.'%');
        }

        if(!empty($cate_id)){
            $query->where('p.cate_id',$cate_id);
        }

        $list = $query->where('f.uid',$uid)->order("stick desc,f.id desc")->limit($pageHelper->getOffset(),$pageHelper->getPageSize())->select();

        $query = Db::table("itboye_favorites")->alias("f")
            ->field("f.*,p.cate_id,p.name")
            ->join("itboye_product p"," f.favorite_id= p.id and f.type=".Favorites::FAV_TYPE_PRODUCT,"LEFT")
        ;
        if(!empty($keyword)){
            $query->where('p.name','like','%'.$keyword.'%');
        }

        if(!empty($cate_id)){
            $query->where('p.cate_id',$cate_id);
        }
        
        $count = $query->where('f.uid',$uid)->count();


        return $this->apiReturnSuc(['count'=>$count,'list'=>$list]);
    }
    

    /**
     * @param $uid
     * @return array
     */
    public function queryCates($uid){

        $result = Db::table("itboye_favorites")->alias("f")
            ->field("distinct(p.cate_id),cate.name")
            ->join("itboye_product p"," f.favorite_id= p.id and f.type=".Favorites::FAV_TYPE_PRODUCT,"LEFT")
            ->join("itboye_category cate","cate.id = p.cate_id","left")
            ->where("f.uid",$uid)
            ->select();

        return $this->apiReturnSuc($result);
    }
    
}
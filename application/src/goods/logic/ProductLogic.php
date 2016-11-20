<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 9:28
 */

namespace app\src\goods\logic;


use app\src\base\logic\BaseLogic;
use app\src\favorites\model\Favorites;
use app\src\goods\model\Product;
use app\src\goods\model\ProductImage;
use think\Db;
use think\Exception;
use think\exception\DbException;

class ProductLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new Product());
    }

    public function mergeImages($list){
        $pid = "";
        foreach ($list as $vo){
            $pid .= $vo['id'].',';
        }
        
        $pid = rtrim($pid,',');
        
        $result =  $this->queryImages($pid,ProductImage::Carousel_Images.','.ProductImage::Main_Images);

        if($result['status']) {
            $tmp = [];
            foreach ($result['info'] as $item) {

                if (!isset($tmp[$item['pid']])) {
                    $tmp[$item['pid']] = [];
                }

                array_push($tmp[$item['pid']], $item['img_id']);
            }

            foreach ($list as &$item) {
                $pid = $item['id'];
                if(isset($tmp[$pid])){
                    $item['_imgs'] = $tmp[$pid];
                }
            }
        }else{
            foreach ($list as &$item) {
                $item['_imgs'] = [];
            }
        }

        return $list;
    }

    /**
     * 搜索商品的图片
     * @param $pid
     * @param $type
     * @return array
     */
    public function queryImages($pid,$type){

        try{
            $result = Db::table('itboye_product_image')->alias("img")
            ->field("img.pid,img.img_id")
                ->where('img.pid','in',$pid)
                ->where('img.type','in',$type)
            ->select();
            return $this->apiReturnSuc($result);
        }catch (DbException $ex){
            return $this->apiReturnErr($ex->getMessage());
        }

    }

    /**
     * @param $uid
     * @param $lang
     * @param $cate_id
     * @param $prop_id
     * @param $keyword
     * @param array $page
     * @return array
     */
    public function random($uid,$lang,$cate_id,$prop_id,$keyword,$page=['page_index'=>1,'page_size'=>10]){
        $query = Db::table('itboye_product')->alias("p")
            ->field("count( DISTINCT  p.id) as tp_count")
            ->join("itboye_product_attr attr","attr.pid = p.id","LEFT")
            ->join("itboye_product_prop prop","prop.pid = p.id","LEFT")
             ->where('p.status',1)
            ->where('p.onshelf',Product::SHELF_ON)
            ->where('attr.expire_time','gt',time());
        $query->where('p.lang',$lang);

        if(!empty($cate_id)){
            $query->where('p.cate_id',$cate_id);
        }
        if(!empty($prop_id)){
            $query->where('prop.prop_id',$prop_id);
        }

        if(!empty($keyword)){
            $query->where('p.name','like','%'.$keyword.'%');
        }
        $result = $query->find();
        $count = 0;
        if(is_array($result) && isset($result['tp_count'])){
            $count = $result['tp_count'];
        }

        $query = Db::table('itboye_product')->alias("p")
            ->field(" p.* ,0 as is_fav")
            ->join("itboye_product_attr attr","attr.pid = p.id","LEFT")
            ->join("itboye_product_prop prop","prop.pid = p.id","LEFT")
            ->where('p.status',1)
            ->where('p.onshelf',Product::SHELF_ON)
            ->where('attr.expire_time','gt',time());

        if(!empty($uid)){
            $query->field(" p.* , IFNULL(fav.id,0)  as is_fav ")
                ->join("itboye_favorites fav","fav.uid = ". $uid."  and fav.favorite_id = p.id and fav.type = ".Favorites::FAV_TYPE_PRODUCT,"LEFT");
        }

        $query->where('p.lang',$lang);
        if(!empty($cate_id)){
            $query->where('p.cate_id',$cate_id);
        }
        if(!empty($prop_id)){
            $query->where('prop.prop_id',$prop_id);
        }
        if(!empty($keyword)){
            $query->where('p.name','like','%'.$keyword.'%');
        }

        $min = $count - $page['page_size'] < 0 ? 0 : $count - $page['page_size'];

        $randStart = rand(0,$min);

        $list = $query->limit($randStart,$page['page_size'])->group("p.id")->select();

        if (false === $list) {
            return $this -> apiReturnErr(lang('err_data_query'));
        }


        return $this -> apiReturnSuc(["count" => $count, "list" => $list]);
    }



    public function getBusinessStatusDesc($business_status){

        if(($business_status & Product::BUSINESS_STATUS_EXPIRED) == Product::BUSINESS_STATUS_EXPIRED){
            return lang('err_product_status_expired');
        }

        if(($business_status & Product::BUSINESS_STATUS_SHELF_OFF) == Product::BUSINESS_STATUS_SHELF_OFF){
            return lang('err_product_status_shelf_off');
        }

        if(($business_status & Product::BUSINESS_STATUS_DELETE) == Product::BUSINESS_STATUS_DELETE){
            return lang('err_product_status_delete');
        }

        if(($business_status & Product::BUSINESS_STATUS_OUTSOLD) == Product::BUSINESS_STATUS_OUTSOLD){
            return lang('err_product_status_outsold');
        }
        
        return "normal";
    }

    public function getBusinessStatus($product){
        $business_status = 0;
        //1. 是否下架测试
        if(isset($product['onshelf']) && $product['onshelf'] == Product::SHELF_OFF){

            $business_status |= Product::BUSINESS_STATUS_SHELF_OFF;

        }

        //2. 商品是否过期
        if(isset($product['expire_time']) && $product['expire_time']  > 0 && $product['expire_time'] < time()){

            $business_status |= Product::BUSINESS_STATUS_EXPIRED;

        }


        //3. 商品是否被删除
        if(isset($product['status']) && intval($product['status']) == -1){

            $business_status |= Product::BUSINESS_STATUS_DELETE;

        }
        
        return $business_status;

    }

    /**
     * 设置一个商品的业务数据
     * @author hebidu <email:346551990@qq.com>
     * @param $product
     */
    public function setBusinessStatus($product){

        $product['business_status'] = 0;


        //1. 是否下架测试
        if(isset($product['onshelf']) && $product['onshelf'] == Product::SHELF_OFF){
            
            $product['business_status'] |= Product::BUSINESS_STATUS_SHELF_OFF;
            
        }

        //2. 商品是否过期
        if(isset($product['expire_time']) && $product['expire_time']  > 0 && $product['expire_time'] < time()){

            $product['business_status'] |= Product::BUSINESS_STATUS_EXPIRED;

        }

        return $product;

    }


    /**
     * 根据商品id来查询
     * @param $pIds
     * @return array
     */
    public function queryWithIds($pIds){
        
        $query = Db::table('itboye_product')->alias("p");
        $result = $query->field("p.place_origin,p.uid,p.product_code ,pattr.expire_time,pattr.min_buy_cnt,pattr.has_sample,pattr.consignment_time,pattr.contact_name,pattr.contact_way,pattr.total_sales,pattr.buy_limit,pattr.view_cnt,p.dt_goods_unit,p.dt_origin_country,p.id,p.name,p.onshelf,p.store_id,p.weight,p.synopsis,p.secondary_headlines as secondary,p.template_id,p.loc_country,p.loc_province,p.loc_city,p.loc_address,p.cate_id,p.createtime as create_time,p.updatetime as update_time")
            //->join("itboye_product_sku sku","sku.product_id = p.id","LEFT")
            ->join("itboye_product_attr pattr","pattr.pid = p.id","LEFT")
            ->where("p.status",'1')
            ->where("p.id","in",$pIds)
            ->select();

        return $this->apiReturnSuc($result);
    }

    /**
     * 根据商品sku id字符串来查询商品信息
     * @param $skuIds
     * @return array
     */
    public function queryWithSkuIds($skuIds){
        $query = Db::table('itboye_product_sku')->alias("sku");
        $result = $query->field("p.status,ft.type as freight_type,ft.company,fa.replenishmoney,fa.replenishpiece,fa.firstmoney,fa.addressids,fa.firstpiece,p.lang,p.place_origin,p.uid,p.product_code,sku.cnt1,sku.cnt2,sku.cnt3,sku.price2,sku.price3 ,sku.id as sku_pkid,sku.sku_id,sku.sku_desc,sku.ori_price,sku.price,sku.quantity,sku.product_code as sku_product_code,sku.icon_url,pattr.expire_time,pattr.min_buy_cnt,pattr.has_sample,pattr.consignment_time,pattr.contact_name,pattr.contact_way,pattr.total_sales,pattr.buy_limit,pattr.view_cnt,p.dt_goods_unit,p.dt_origin_country,p.id,p.name,p.onshelf,p.store_id,p.weight,p.synopsis,p.secondary_headlines as secondary,p.template_id,p.loc_country,p.loc_province,p.loc_city,p.loc_address,p.cate_id,p.createtime as create_time,p.updatetime as update_time")
            ->join("itboye_product p","sku.product_id = p.id","LEFT")
            ->join("itboye_product_attr pattr","pattr.pid = sku.product_id","LEFT")
            ->join("itboye_freight_address fa","fa.template_id = p.template_id","LEFT")
            ->join("itboye_freight_template ft","ft.id = p.template_id","LEFT")
            ->where("p.status",'1')
            ->where("sku.id","in",$skuIds)
            ->select();

        return $this->apiReturnSuc($result);
    }

    public function search($lang,$cate_id,$prop_id,$keyword,$page=['page_index'=>1,'page_size'=>10]){
        $query = Db::table('itboye_product')->alias("p")
            ->field("p.*")
            ->join("itboye_product_prop prop","prop.pid = p.id","LEFT");

        $query->where('p.lang',$lang);
        if(!empty($cate_id)){
            $query->where('p.cate_id',$cate_id);
        }
        if(!empty($prop_id)){
            $query->where('prop.prop_id',$prop_id);
        }
        if(!empty($keyword)){
            $query->where(['p.name'=>['like','%'.$keyword.'%']]);
        }

        $start = max(intval($page['page_index'])-1,0) * intval($page['page_size']);


        $list = $query->limit($start,$page['page_size'])->group("p.id")->select();


        if (false === $list) {
            return $this -> apiReturnErr(lang('err_data_query'));
        }
        $query = Db::table('itboye_product')->alias("p")
            ->field("count( DISTINCT  p.id) as tp_count")
            ->join("itboye_product_prop prop","prop.pid = p.id","LEFT");

        $query->where('p.lang',$lang);
        
        if(!empty($cate_id)){
            $query->where('p.cate_id',$cate_id);
        }
        if(!empty($prop_id)){
            $query->where('prop.prop_id',$prop_id);
        }

        if(!empty($keyword)){
            $query->where(['p.name'=>['like','%'.$keyword.'%']]);
        }
        $result = $query->find();
        $count = 0;
        if(is_array($result) && isset($result['tp_count'])){
            $count = $result['tp_count'];
        }
        
        return $this -> apiReturnSuc(["count" => $count, "list" => $list]);
    }

    /**
     * 商品详情数据
     * @param $id
     * @return array
     */
    public function detail($id){
        
        $result = Db::table('itboye_product')->alias("p")
            ->field("sku.price2,sku.price3,sku.cnt1,sku.cnt2,sku.cnt3,img.img_id as main_img,pattr.*,p.uid,p.place_origin,p.product_code ,datatree1.name as goods_unit_name,sku.id as sku_pkid,sku.sku_id,sku.sku_desc,sku.ori_price,sku.price,sku.quantity,sku.product_code as sku_product_code,sku.icon_url,p.dt_goods_unit,p.dt_origin_country,p.id,p.name,p.onshelf,p.store_id,p.weight,p.synopsis,p.secondary_headlines as secondary,p.template_id,p.loc_country,p.loc_province,p.loc_city,p.loc_address,p.cate_id,p.createtime as create_time,p.updatetime as update_time")
            ->join("itboye_product_attr pattr","pattr.pid = p.id","LEFT")
            ->join("itboye_product_sku sku","sku.product_id = p.id","LEFT")
            ->join("itboye_product_image img","img.pid = p.id and img.type = 6015","LEFT")
            ->join(["datatree as datatree1","common_"],"datatree1.parentid =  37 and datatree1.code = p.dt_goods_unit","LEFT")
            ->where("p.id",$id)
            ->select();

        if(is_array($result) && count($result) > 0) {

            $product = $result[0];

            foreach ($result as $p) {

                if (!isset($product['sku_list'])) {
                    $product['sku_list'] = [];
                }

                if (isset($p['sku_pkid'])) {
                    $sku_info = [
                        'sku_pkid' => $p['sku_pkid'],
                        'sku_id' => $p['sku_id'],
                        'sku_desc' => $p['sku_desc'],
                        'ori_price' => $p['ori_price'],
                        'price' => $p['price'],
                        'quantity' => $p['quantity'],
                        'product_code' => $p['sku_product_code'],
                        'icon_url' => $p['icon_url'],
                        'price3' => $p['price3'],
                        'price2' => $p['price2'],
                        'cnt1' => $p['cnt1'],
                        'cnt2' => $p['cnt2'],
                        'cnt3' => $p['cnt3']
                    ];

                    array_push($product['sku_list'], $sku_info);
                }

            }

            unset($product['price2']);
            unset($product['price3']);
            unset($product['cnt1']);
            unset($product['cnt2']);
            unset($product['cnt3']);
            unset($product['sku_id']);
            unset($product['sku_desc']);
            unset($product['ori_price']);
            unset($product['price']);
            unset($product['quantity']);
            unset($product['icon_url']);
            $result = $product;
            $result['properties'] = $this->getProperties($id);

            $result = $this->setBusinessStatus($result);
            unset($result['create_time']);
            unset($result['pid']);
            unset($result['is_second']);
            unset($result['sku_product_code']);
            unset($result['buy_limit']);
            unset($result['min_buy_cnt']);
            unset($result['dt_goods_unit']);
            unset($result['dt_origin_country']);

        }


        return $this->apiReturnSuc($result);
    }

    /**
     * 获取商品属性
     * @author hebidu <email:346551990@qq.com>
     */
    private function getProperties($pid){
        $logic = new ProductPropLogic();
        
        return  $logic->queryPropList($pid);
    }
}
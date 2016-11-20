<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-19
 * Time: 14:35
 */

namespace app\domain;

use app\src\category\logic\CategoryLogic;


/**
 * Class CategoryDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class CategoryDomain extends BaseDomain
{
    /**
     * @author hebidu <email:346551990@qq.com>
     */
    public function queryMainCategory(){
        $this->checkVersion("100");

        $logic = new CategoryLogic();

        $result = $logic->queryMainCategory($this->lang);

        $cate_list = $result['info'];

        if(is_array($cate_list)){
            $rebuild_cate_list = [];
            foreach ($cate_list as &$cate){
                $key = 'key_'.$cate['id'];
                if(!isset($rebuild_cate_list[$key])) {
                    $rebuild_cate_list[$key] = $cate;
                    $rebuild_cate_list[$key]['sub_prop'] = [];
                }

                $sub_prop_list = $rebuild_cate_list[$key]['sub_prop'];
                if(!empty($cate['prop_id'])){
                    $sub_prop = [
                        'prop_name'=>lang('tip_filter_by').$cate['prop_name'],
                        'prop_id'=>$cate['prop_id'],
                    ];
                    array_push($sub_prop_list,$sub_prop);
                }

                $rebuild_cate_list[$key]['sub_prop'] = $sub_prop_list;
            }

            $cate_list = [];
            foreach ($rebuild_cate_list as $value){
                array_push($cate_list,$value);
            }

            $result['info'] = $cate_list;
        }

        $this->exitWhenError($result,true);
    }

    /**
     * @author hebidu <email:346551990@qq.com>
     */
    public function querySubCategory(){

        $this->checkVersion("100");

        $cate_id = $this->_post('cate_id','',lang('err_category_cate_id_need'));

        $cate_id = intval($cate_id);
        if($cate_id <= 0){
            $this->apiReturnErr(lang('invalid_parameter',['param'=>'cate_id']));
        }

        $logic = new CategoryLogic();

        $result = $logic->querySubCategory($cate_id,$this->lang);

        $this->exitWhenError($result,true);
    }

}
<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace app\src\base\logic;

use think\Db;
use think\Model;

/**
 * Logic 基类
 */
abstract class BaseLogic {

    /**
     * API调用模型实例
     * @access  protected
     * @var object
     */
    private $model;

    /**
     * 构造方法，检测相关配置
     */
    public function __construct() {
        $this -> _init();
    }

    /**
     * 抽象方法，用于设置模型实例
     */
    abstract protected function _init();

    /**
     * 返回错误结构
     * @param $info
     * @return array
     */
    protected function apiReturnErr($info) {
        return array('status' => false, 'info' => $info);
    }

    /**
     * 返回成功结构
     * @param $info
     * @return array
     */
    protected function apiReturnSuc($info) {
        return array('status' => true, 'info' => $info);
    }

    /**
     * 返回结构
     * @param $status
     * @param $info
     * @return array
     */
    protected function apiReturn($status, $info) {
        return array('status' => $status, 'info' => $info);
    }



    /**
     * get model
     * @return Model
     */
    public function getModel() {
        return $this -> model;
    }

    /**
     * set Model
     * @param Model $model
     */
    public function setModel(Model $model){
        $this->model = $model;
    }

    
    /**
     * 求和统计
     * @param $map
     * @param $field
     * @return array
     */
    public function sum($map,$field){

        $result = $this -> model -> where($map) -> sum($field);
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 数量统计
     * @param $map
     * @param bool $field
     * @return array
     */
    public function count($map, $field = false) {

        if ($field === false) {
            $result = $this -> model -> where($map) -> count();
        } else {
            $result = $this -> model -> where($map) -> count($field);
        }
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }


    /**
     * 禁用
     * 必须有status字段 ，0 为禁用状态
     * @param $map
     * @return status|bool
     */
    public function disable($map) {
        return $this -> save($map, array('status' => 0));
    }

    /**
     * 启用
     * 必须有status字段，1 为启用状态
     * @param $map
     * @return status|bool
     */
    public function enable($map) {
        return $this -> save($map, array('status' => 1));
    }

    /**
     * 假删除
     * 必须有status字段，且 －1 为删除状态
     * @param $map
     * @return status|bool
     */
    public function pretendDelete($map) {
        return $this -> save($map, array('status' => -1));
    }

    /**
     * 根据id保存数据
     * @param $id
     * @param $entity
     * @return array | bool
     */
    public function saveByID($id, $entity) {
        unset($entity['id']);

        return $this -> save($entity,array('id' => $id));
    }

    /**
     * 数字类型字段有效
     * @param $map array 条件
     * @param $field string 更改字段
     * @param float|int $cnt float 增加的值
     * @return 返回影响记录数 或 错误信息
     */
    public function setInc($map, $field, $cnt = 1) {
        $result = $this -> model -> where($map) -> setInc($field, $cnt);
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 数字类型字段有效
     * @param $map 条件
     * @param $field 更改字段
     * @param 减少的值|int $cnt 减少的值
     * @return 返回影响记录数 或 错误信息
     */
    public function setDec($map, $field, $cnt = 1) {
        $result = $this -> model -> where($map) -> setDec($field, $cnt);
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }
    /**
     * 数字类型字段有效,不允许小于0,维护字段最小为0,金额等敏感类型不适用
     * @param $map 条件
     * @param $field 更改字段
     * @param 减少的值|int $cnt 减少的值
     * @return 返回影响记录数 或 错误信息
     */
    public function setDec2($map, $field, $cnt = 1) {
        $result = $this -> model ->where($map) ->find() ->toArray();
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            if(!empty($result)){
                $c = $result[$field];
                if($c-$cnt<0) $cnt=$c;
            }
        }
        $result = $this -> model -> where($map) -> setDec($field, $cnt);
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 批量更新，仅根据主键来
     * @param $entity
     * @return array
     */
    public function saveAll($entity){
        $result = $this->model->saveAll($entity);
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 保存
     * @param $map
     * @param $entity
     * @return status|bool , info 错误信息或更新条数
     */
    public function save($map, $entity) {
        $result = $this -> model -> save($entity,$map);
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 获取数据find
     * @param $map
     * @param bool $order
     * @return array
     */
    public function getInfo($map,$order=false,$field=false,$noNull=false) {
        if(false === $order){
            if(false === $field)
                $result = $this -> model -> where($map) -> find() ;
            else
                $result = $this -> model -> where($map) -> field($field) -> find();
        }else{
            if(false === $field){
                $result = $this->model->where($map)->order($order)->find();
            }
            else{
                $result = $this->model->where($map)->order($order)-> field($field) -> find();
            }
        }

        if (false === $result) {
            return $this -> apiReturnErr($this -> model -> getError());
        } else {
            
            if(is_object($result)){
                return $this -> apiReturnSuc($result->toArray());
            }

            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 删除
     * @map 条件
     * @result array('status'=>'false|true',$info=>'错误信息|删除数据数')
     * @param $map
     * @return array
     */
    public function delete($map){
        $result = $this -> model -> where($map) -> delete();
        if ($result === false) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }


    /**
     * 批量删除
     * @param $data
     * @return array
     */
    public function bulkDelete($data){
        $result = $this->getModel()->destroy($data);
        return $this->apiReturnSuc($result);
    }


    public function getInsertId($pk='id'){
        return $this->model->$pk;
    }

    /**
     * add 添加
     * @param $entity
     * @return bool
     */
    public function add($entity,$pk='id') {

        $result = $this -> model -> data($entity) ->isUpdate(false) -> save();

        if ($result === false) {
            return $this -> apiReturnErr($this -> model -> getError());
        }
        
        $result = $this->getInsertId($pk);
        return $this -> apiReturnSuc($result);
    }

    /**
     * 批量插入
     * @param $list 数组
     * @return array
     */
    public function addAll($list){

        $result = $this->model->saveAll($list);
        if($result === FALSE){
            return $this->apiReturnErr($this -> model -> getError());
        }else{
            return $this->apiReturnSuc($result);
        }
    }

    /**
     * query 不分页
     * @param 查询条件|array $map
     * @param 排序条件|bool $order
     * @param 只获取指定字段|bool $fields
     * @return array
     */
    public function queryNoPaging($map = null, $order = false, $fields = false) {
        $query = $this->model;
        if(!empty($map)) $query = $query->where($map);
        if(false !== $order) $query = $query->order($order);
        if(false !== $fields) $query = $query->field($fields);
        $list = $query -> select();

        if (false === $list) {
            return $this -> apiReturnErr($this -> model -> getError());
        }

        return $this -> apiReturnSuc($list);

    }


    /**
     * query
     * @param 查询条件|null $map
     * @param array|分页参数 $page
     * @param bool|排序参数 $order
     * @param bool|点击分页时带参数 $params
     * @param bool $fields
     * @return array
     * @internal param 查询条件 $map
     * @internal param 分页参数 $page
     * @internal param 排序参数 $order
     * @internal param 点击分页时带参数 $params
     */
    public function query($map = null, $page = ['curpage'=>1,'size'=>10], $order = false, $params = false, $fields = false) {
        $query = $this->model;
        if(!is_null($map)) $query = $query->where($map);
        if(false !== $order) $query = $query->order($order);
        if(false !== $fields) $query = $query->field($fields);
        $start = max(intval($page['curpage'])-1,0)*intval($page['size']);
        $list = $query -> limit($start,$page['size']) -> select();

        if (false === $list) return $this -> apiReturnErr($this -> model -> getError());
        $count = $this -> model -> where($map) -> count();
        return $this -> apiReturnSuc(["count" => $count, "list" => $list]);
    }

    /**
     * query
     * @param 查询条件|null $map
     * @param array|分页参数 $page
     * @param bool|排序参数 $order
     * @param bool|点击分页时带参数 $params
     * @param bool $fields
     * @return array
     * @internal param 查询条件 $map
     * @internal param 分页参数 $page
     * @internal param 排序参数 $order
     * @internal param 点击分页时带参数 $params
     */
    public function queryWithCount($map = null, $page = array('curpage'=>1,'size'=>10), $order = false, $params = false, $fields = false) {
          $query = $this->model;
        if(!empty($map)) $query = $query->where($map);
        if(false !== $order) $query = $query->order($order);
        if(false !== $fields) $query = $query->field($fields);

        $start = max(0,(intval($page['curpage'])-1)*intval($page['size']));
        $list = $query -> limit($start,$page['size']) -> select();
        // $list = $query -> page($page['curpage'] . ',' . $page['size']) -> select();

        if (false === $list) {
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        }
        $count = $this -> model -> where($map) -> count();
        return $this -> apiReturnSuc(["count" => $count, "list" => $list]);
    }
}

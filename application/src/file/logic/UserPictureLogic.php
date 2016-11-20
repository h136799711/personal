<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 14:49
 */

namespace app\src\file\logic;


use app\src\base\logic\BaseLogic;
use app\src\extend\upload\Upload;
use app\src\file\model\UserPicture;

class UserPictureLogic extends BaseLogic
{
    private $error;

    /**
     * @return mixed
     */
    protected function _init()
    {
        $this->setModel(new UserPicture());
    }

    public function getError(){
        return $this->error;
    }

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting,$extInfo, $driver = 'local', $config = null){
        // 获取表单input[name=image]上传文件 例如上传了001.jpg
        $files = request()->file('image');
        $rule = isset($setting['rule']) ? $setting['rule']:'md5';
        $path = isset($setting['rootPath']) ? $setting['rootPath']:'.'.DS . 'upload' . DS . 'userPicture';
        $relate_path = str_replace(DS, '/', $path.'/');
        $relate_path = ltrim($relate_path,'.');
        $return = [];
        if(is_object($files)) $files = [$files];
        foreach($files as $file){
            // 移动到框架应用根目录/public/upload/userPicture 目录下
            // TODO 上传验证
            $upload = $file->rule($rule)->move($path);//MD5覆盖相同
            if($upload){
                // 成功上传后 获取上传信息
                $s = $upload->getInfo();
                $s['sha1']     = $upload->hash('sha1');
                $s['md5']      = $upload->hash('md5');
                $s['ext']      = $upload->getExtension();
                $s['savename'] = $upload->getFilename();
                $s['path']     = $relate_path.substr($upload->hash($rule), 0, 2) . '/' . $s['savename'];
                $s['imgurl']   = $extInfo['show_url'].$s['path'];
                $s['uid']     =  $extInfo['uid'];

                $en = [
                    'path'        => $s['path'],
                    'uid'         => $extInfo['uid'],
                    'ori_name'    => $s['name'],
                    'savename'    => $s['savename'],
                    'size'        => $s['size'],
                    'url'         => '',//图片链接
                    'imgurl'      => $s['imgurl'],//完整显示地址
                    'md5'         => $s['md5'],
                    'sha1'        => $s['sha1'],
                    'status'      => 1,
                    'create_time' => NOW_TIME,
                    'type'        => $extInfo['type'],
                    'ext'         => $s['ext'],
                ];

                $result = $this->add($en);
                $id = intval($result['info']);

                if($result['status'] && is_int($id)){
                    $s['id'] = $id;
                    unset($s['key']);
                    unset($s['tmp_name']);
                    unset($s['md5']);
                    unset($s['sha1']);
                }else{
                    return $this->getError();
                }
                $return[] = $s;
            }else{
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
        return $return;
    }

    /**
     * curl 文件上传
     * @param  array $files
     *  $data : 文件字符串
     *  eg: $files  = ['fdata'=>$data,'ftype'=>$file['type'],'fname'=>$file['name'],'type'=>'other','uid'=>42];
     * @param  array $setting 文件上传配置
     * @param $extInfo
     * @param  string $driver 上传驱动名称
     * @param  array $config 上传驱动配置
     * @return array 文件上传成功后的信息
     */
    public function curl_upload($files, $setting,$extInfo, $driver = 'local', $config = null){
        /* 上传文件 */
        $setting['callback']    = [$this, 'isFile'];
        $setting['removeTrash'] = [$this, 'removeTrash'];
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->curl_upload($files);

        if($info){ //文件上传成功，记录文件信息
            $infos = ['image'=>$info];
            foreach ($infos as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }
                // dump($setting);exit;
                $value = array_merge($value,$extInfo);

                $value['ori_name'] = $value['name'];
                /* 记录文件信息 */
                $value['path'] = substr($value['savepath'],1).$value['savename'];   //在模板里的url路径
                $value['imgurl'] = $value['imgurl'].$value['path'];
                $value['url'] = '';
                $value['ext'] = '';
                $value['create_time'] = time();
                unset($value['name']);
                unset($value['savepath']);
                $result = $this -> add($value);

                if($result['status']){
                    $value['id'] = $result['info'];
                }
            }
            return $infos; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }


    /**
     * 下载指定文件
     * @param  number  $root 文件存储根目录
     * @param  integer $id   文件ID
     * @param  string   $args     回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    public function download($root, $id, $callback = null, $args = null){
        /* 获取下载文件信息 */
        $file = $this->find($id)->toArray();
        if(!$file){
            $this->error = '不存在该文件！';
            return false;
        }

        /* 下载文件 */
        switch ($file['location']) {
            case 0: //下载本地文件
                $file['rootpath'] = $root;
                return $this->downLocalFile($file, $callback, $args);
            case 1: //TODO: 下载远程FTP文件
                break;
            default:
                $this->error = '不支持的文件存储类型！';
                return false;

        }

    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param  array $file 文件上传数组
     * @return bool 文件信息， false - 不存在该文件
     * @throws \Exception
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
        $map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
        return $this->getModel()->field(true)->where($map)->find();
    }

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  callable $callback 下载回调函数，一般用于增加下载次数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private function downLocalFile($file, $callback = null, $args = null){
        if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
            /* 调用回调函数新增下载数 */
            is_callable($callback) && call_user_func($callback, $args);

            /* 执行下载 */ //TODO: 大文件断点续传
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['type']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($file['rootpath'].$file['savepath'].$file['savename']);
            exit;
        } else {
            $this->error = '文件已被删除！';
            return false;
        }
    }

    /**
     * 清除数据库存在但本地不存在的数据
     * @param $data
     */
    public function removeTrash($data){
        $this->getModel()->where(array('id'=>$data['id'],))->delete();
    }


}
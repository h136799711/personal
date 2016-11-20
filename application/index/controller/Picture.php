<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 14:45
 */
namespace app\index\controller;

use app\src\base\helper\ConfigHelper;
use app\src\extend\image\Image;
use app\src\file\logic\UserPictureLogic;
use app\src\user\logic\MemberLogic;
use think\Controller;

/**
 * 图片查看控制器
 * Picture ControllerClass
 * @author hebidu <email:346551990@qq.com>
 * @package app\index\controller
 */
class Picture extends Controller{

    protected function _initialize(){
        $sizes = ConfigHelper::getFilePictureCropSize();
        if(!$sizes || !is_array($sizes)) $sizes = [60,120,150,180,200];
        $this->accept_size = $sizes;
        header("X-Copyright:http://www.itboye.com");
    }

    public function returnDefaultImage(){
        //森森不传默认图片
        $imp = base64_decode($this->default);
        $im  = imagecreatefromstring($imp);
        if ($im !== false) {
            header('Content-Type: image/png'); //对应jpeg的类型
            imagepng($im);////也要对应jpeg的类型
            imagedestroy($im);
        }else{
            echo '图片未读入';
        }
        exit();
    }

    public function avatar(){
        $uid = $this->request->get('uid',0);
        $size = $this->request->get('size',0);

        if(empty($uid)) $this->returnDefaultImage();
        $logic = new MemberLogic();
        $result = $logic->getInfo(['uid'=>$uid]);

        if(!$result['status'] || empty($result['info'])){
            $this->returnDefaultImage();
        }

        $head = $result['info']['head'];

        $id = intval($head);


        if($id > 0){
            if($size == 0){
                $size = 180;
            }
            $this->getPicture($head,$size);
        }elseif (strpos($head,'http') == 0){
            redirect($head);
        }
        else{
            $this->returnDefaultImage();
        }

    }

    public function index(){

        //TODO: 带图片类型，对不同类型分批处理
        $id   = $this->request->get('id',0);
        $size = $this->request->get('size',0,'intval');

        $this->getPicture($id,$size);

    }

    private function getPicture($id,$size=0){

        if($id <= 0) $this->returnDefaultImage();

        if(in_array($size,$this->accept_size) === false) $size = 0;

        $picture = new UserPictureLogic();
        $result = $picture->getInfo(['id'=>$id]);
        if(empty($result)) $this->returnDefaultImage();
        $picture = $result['info'];
        $url = (('.'.$picture['path']));

        if(file_exists($url) === false) $this->returnDefaultImage();
        if($size > 30 && $size < 1024){
            $url = $this->generate($picture,$size);
        }

        if($url === false) $this->returnDefaultImage();
        //      图片缓存设置
        $time = filemtime($url);
        $dt =date("D, d M Y H:m:s GMT", $time );
        header("Last-Modified: $dt");
        header("Cache-Control: max-age=844000");
        header('Content-type: image/'.$picture['ext']);
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE']==$dt) {
            header("HTTP/1.0 304 Not Modified");
            exit;
        }

        $image = @readfile($url);
        if ($image == false) $this->returnDefaultImage();
        echo $image;
        exit();
    }

    /**
     * 生成缩略图
     * @param $info
     * @param $size
     * @return string
     */
    protected function generate($info,$size){
        $thumbnail_path = ConfigHelper::getFileThumbnailPath() .'/w'.$size.'/';
        $save_name = $info['savename'];

        $relative_path = $thumbnail_path.$save_name;

        if(file_exists($relative_path)) return $relative_path;

        $image = new Image();

        if(!file_exists(realpath('.'.$info['path']))) return false;

        $image->open( realpath('.'.$info['path']));

        if(!is_dir(($thumbnail_path))){
            if(!mkdir(($thumbnail_path))) return false;
        }

        $size_info = getimagesize(realpath('.'.$info['path']));
        $scale_size = $this->calcScale($size_info[0],$size_info[1],$size);
        $result = $image->thumb($scale_size['width'], $scale_size['height'],Image::IMAGE_THUMB_FIXED)->save($relative_path, null, 100);

        if(!file_exists(realpath($relative_path))) return false;

        return $relative_path;

    }
    protected function calcScale($w, $h, $size){
        $scale = $w / $h;

        if($w > $h){
            $dw = $size;
            $dh = intval($dw / $scale);
        }else{
            $dh = $size;
            $dw = intval($dh * $scale);
        }

        return ['width'=>$dw, 'height'=> $dh];
    }

    protected function getSiteURL(){
        return ConfigHelper::site_url();
    }

    public function test(){

        $image = new Image();
        $info['path'] = '/';
        if(!file_exists('.'.$info['path'])) return false;

        $image->open( realpath('.'.$info['path']));

        $thumbnail_path = config('thumbnail_path').'/'.date('Y-m-d',time()).'/';

        if(!is_dir(($thumbnail_path))){
            if(!mkdir(($thumbnail_path))) return false;
        }
    }

    //默认图片
    protected $default = "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";
    //支持裁减大小宽度
    protected  $accept_size = [];
}
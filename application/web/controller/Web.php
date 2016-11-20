<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 15:40
 */

namespace app\web\controller;

use app\src\post\logic\PostLogic;
use app\src\post\model\Post;
use think\Controller;

/**
 *
 * Web作为webview统一入口
 *
 * @author hebidu <email:346551990@qq.com>
 *
 * @package app\web\controller
 */
class Web extends Controller
{

    /**
     * 用户手册
     * @author hebidu <email:346551990@qq.com>
     * @return mixed
     */
    public function user_manual(){

        return $this->fetch();
    }


    /**
     * 关于
     * @author hebidu <email:346551990@qq.com>
     * @return mixed
     */
    public function about(){

        return $this->fetch();
    }

    /**
     * 联系我们
     * @author hebidu <email:346551990@qq.com>
     * @return mixed
     */
    public function contact_us(){

        return $this->fetch();
    }

    /**
     * 版权信息
     * @author hebidu <email:346551990@qq.com>
     * @return mixed
     */
    public function copyright(){

        return $this->fetch();
    }

    /**
     * 使用协议
     * @author hebidu <email:346551990@qq.com>
     * @return mixed
     */
    public function agreement(){
        return $this->fetch();
    }
    

    /**
     * 系统通知
     * @author hebidu <email:346551990@qq.com>
     * @return mixed
     */
    public function system_notice(){

        $result = (new PostLogic())->queryNoPaging(['post_category'=>Post::SYSTEM_NOTICE]);
        $list = $result['info'];

        foreach ($list as &$vo){
            $vo = $vo->toArray();
        }
        
        $this->assign("list",$list);

        return $this->fetch();
    }

}
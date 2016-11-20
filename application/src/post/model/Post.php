<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-14
 * Time: 11:22
 */

namespace app\src\post\model;

use think\Model;

class Post extends Model
{

    protected $table="v_post_info";
    
    /**
     * 系统通知
     * @author hebidu <email:346551990@qq.com>
     */
    const SYSTEM_NOTICE = "6054";
}
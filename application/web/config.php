<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 16:09
 */

return [

    'default_return_type'    => 'html',
    'view_replace_str' => [
        '__PUBLIC__' => __ROOT__ . '/public/static/default/' . request()->module() . '/',
        '__JS__' => __ROOT__ . '/public/static/default/' . request()->module() . '/js/',
        '__CSS__' => __ROOT__ . '/public/static/default/' . request()->module() . '/css/',
        '__IMG__' => __ROOT__ . '/public/static/default/' . request()->module() . '/img/',
        '__CDN__' => ITBOYE_CDN,
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH . '/web/view/dispatch/dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_PATH . '/web/view/dispatch/dispatch_jump.tpl',
];
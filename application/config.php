<?php

/**
 * warn: 往这个配置文件增加东西都需要通知到所有参与开发的人员
 * @author hebidu <email:346551990@qq.com>
 */

return [
    
    //支持的支付方式
    'app_support_payways'=>[
        ['name'=>'支付宝','type'=>1,'desc'=>'需要手机安装支付宝'],
        ['name'=>'Paypal','type'=>2,'desc'=>'支持paypal'],
        ['name'=>'微信支付','type'=>3,'desc'=>'需要手机安装微信'],
    ],

    //多语言支持
    'lang_support'=>[
        ['name'=>'简体中文','value'=>'zh-cn'],
        ['name'=>'한국','value'=>'ko'],
        ['name'=>'English','value'=>'en'],
        ['name'=>'Tiếng Việt','value'=>'vi'],
    ],

    //融丰支付配置
    'rf_pay_config'=>[
        //接口地址
        'api_url'=> "http://api.ktb.wujieapp.net",
        //
        'org_no'=> "99999999",
        'mer_no'=> "101607256868749",
        //
        'key'=> "bea91d7d61ecd36fcabfd4303c75a06f",
        //rsa私钥 base64形式 
        'pem_path'=> "/www/wwwroot/api.guannan.itboye.com/application/src/rfpay/pem/base64.pem",
        //订单创建成功后的回调地址
        "no_card_order_backUrl"=>"http://api.ihomebank.com/public/index.php/rfpay"
    ],

    // 加密salt定义
    'security_salt'=> [
        'password'=>'itboyep;[230',
    ],

    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用调试模式
    'app_debug'              => true,
    // 应用模式状态
    'app_status'             => 'local',
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用Trace
    'app_trace'              => false,
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'json',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'         => true,
    // 支持的语言列表
    'lang_list'     => ['zh-cn','en'],
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common','src','domain'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'test',
        // 日志保存目录
        //'path'  => LOG_PATH,
        // 日志记录级别
        //'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => 'global_',
        // 缓存有效期 0表示永久缓存
        'expire' => 24*3600,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    //mysql session配置
    'session'                => [
        'type'              => 'mysql', // 驱动方式 支持redis memcache memcached
        'auto_start'        => true,        // 是否自动开启 SESSION
        // Session驱动设置
        'session_expire'    =>  3600,        // Session有效期 单位：秒
        'session_prefix'    => 'itboye_',    // Session前缀
        'table_name'        => 'common_session',   // 表名（包含表前缀）
        'var_session_id'    => 'itboye_sid', //会话id
        'database'          =>  [
            'hostname'  => '121.40.52.122',     // 服务器地址
            'database'  => 'itboye_hutou',         // 数据库名
            'username'  => 'itboye_te',        // 用户名
            'password'  => 'itboye456',    // 密码
            'hostport'  => '3306',          // 端口
            'prefix'    => '',            // 表前缀（默认为空）
            'charset'   => 'utf8',          // 数据库编码
        ]
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    //队列
    'queue'=>[
        'type'=>'database', //驱动类型，可选择 sync(默认):同步执行，database:数据库驱动,redis:Redis驱动,topthink:Topthink驱动
        //或其他自定义的完整的类名
        'table' => 'queue_jobs'
    ],

    /* 图片上传相关配置 */
    'user_picture_upload' => [
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 500*1024, //上传的文件大小限制 (0-不做限制)
        'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => ['date', 'Y-m-d'], //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './upload/userPicture', //保存根路径
        'savePath' => '', //保存路径 eg: '1/'
        'saveName' => ['uniqid', ''], //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ],
    //图片上传相关配置（文件上传类配置）
    'picture_upload_driver'=>'local',

    //阿里百川
    'ALBAICHUAN_CFG'=>[
        'is_debug'   => true,//是否测试
        'app_key'    => '23456139',
        'app_secret' => '4647cb9e09046b8ef8e56c5aa5f95a61',
    ]
];

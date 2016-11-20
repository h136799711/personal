<?php
//api模块is8n 中文语言包 字母排序
return [
	"err_system"=>"系统错误",
	'success'=>'操作成功',
	'fail'=>'操作失败',
	"err_lang_not_support"=>"语言不支持",
	"err_not_find"=>"数据不存在",
	"exceed_limit"=>"超过限制，最多 {:limit} 条.",
	"err_return_is_not_null"=>"返回数据不能为null",
	"err_data_query"=>"数据查询错误",
    "page_index_need"=>"分页页码参数缺失",
    "page_size_need"=>"分页每页大小参数缺失",
	"tip_filter_by"=>"按",
	//index模块
	
	//hook模块
	"err_hook_pay_payed"=>"该订单已支付成功,不能重复处理",
	"err_hook_pay_no_trade_info"=>"找不到该订单交易信息",
	"tip_hook_pay_success"=>'支付成功通知消息',
	"tip_hook_pay_success_summary"=>'订单 {:content} 支付成功通知消息',
	"tip_hook_pay_success_content"=>'您的订单 {:content} 已支付成功',

	//快递web controller
	"err_auth_fail"=>"您无权访问该页面，请关闭该页面，再试一次",
	"auth_psw_need"=>"验证密码缺失",
	"err_no_order"=>"无此订单",
	"err_order_not_ship"=>"订单尚未发货",
	"err_no_express_info"=>"无此快递信息",
	//订单domain
		//订单action
	"err_address_id"=>"错误的收货地址id",
	"err_sku_ids"=>"错误的商品规格id",
	"err_cart_id"=>"错误的购物项id",
	"err_quantity_lack"=>"商品{:name} 库存不足",
		//订单logic
	"err_order_id"=>"订单id错误",
	"err_order_code"=>"订单编号错误",
	"err_cant_cancel_payed_order"=>"不能取消已支付订单",
	"err_order_status"=>"该订单不能执行当前操作",
	"err_order_not_updated"=>"订单未被更新",
	"err_order_status_add"=>"订单状态记录插入失败",
	"err_cart_item_count_invalid"=>"商品[{:name}]未达到最小起批数({:min})",
	"err_add_order_info_fail"=>"保存订单信息失败",
	"err_get_pay_info"=>"支付信息获取失败",
	"err_repay_param"=>"重新发起支付失败",
	"err_pay_status"=>"错误的支付状态",
			//addressDomain
		"err_address_not_exits"=>'地址不存在',
		"address_id_need"=>"收货地址ID缺失",
	"err_country_id_need"=>"国家id缺失",
	"err_country_id_error"=>"国家id非法",
		//FileController
		"err_file_not_accept_type"=>'不支持的上传文件类型',
	"err_file_user_id_not_exist"=>'用户id不存在',
	//categoryomain
	"err_category_cate_id_need"=>'类目id缺失',
	//goodsfeedbackdomain
	"err_goods_feedback_img_ids_need"=>"图片id缺失",
	//product_domain
	"err_product_has_expired"=>"该商品已下架",
	"err_product_shelf_off"=>"该商品已下架",
	//product_logic
	"err_product_status_expired"=>"该商品信息已无效",
	"err_product_status_shelf_off"=>"该商品已下架",
	"err_product_status_outsold"=>"该商品已售罄",
	"err_product_status_delete"=>"该商品已删除",
	//购物车domain
	"err_cart_count_need"=>"购买数量参数缺失",
	"err_cart_invalid_sku_pkid"=>"错误的商品规格id",
	"err_cart_min_buy_limit"=>"最小购买量 {:limit} ",
	"err_cart_max_buy_limit"=>"限购数量 {:limit} ",
	"err_cart_quantity"=>"库存不足,剩余库存 {:quantity}",
	//
	"tip_cart_status_1"=>"正常",
	"tip_cart_status_2"=>"该商品已下架",
	"tip_cart_status_3"=>"该商品信息已无效",
	"tip_cart_status_4"=>"该商品库存紧张",
    //favoritesDomain
    "err_type"=>"错误的收藏类型参数",

	// src / security code
	"country_tel_number_need"=>"国家手机号区号缺失",
	"err_invalid_code"=>  "验证码无效!",
	"err_code_used"=>  "验证码已使用!",
	"err_code_expired"=>  "验证码已过期!",

	"invalid_id"=>"错误或非法的id",
    "type_need"=>"type类型参数缺失",
	"count_need"=>"count数量参数缺失",
	"uid_need"=>'用户id缺失',
	"id_need"=>'id参数缺失',
	'username_need'=>'用户名缺失',
	'password_need'=>'密码缺失',
	'mobile_need'=>'手机号缺失',
	'email_need'=>'邮箱缺失',
	'reg_from_need'=>'注册来源信息缺失',
	'code_need'=>'验证码缺失',
	'code_type_need'=>'验证码用途类型缺失',
	'delay_do_msg_send'=> "请在 {:param} 秒后再尝试发送短信!",


	'tip_mobile_registered'=>'该手机号已注册',
	'tip_mobile_unregistered'=>'该手机号尚未注册',
	'tip_update_api_version'=>'请更新接口,最新版本:{:version}',
	'lack_parameter'=>'缺少 {:param} 参数',
	'invalid_parameter'=>'{:param}参数无效或非法',
	'invalid_request'=>'请求已无效，请重试',
	'err_sign'=>'签名错误',
	'err_404'=>'请求的资源不存在',
	"err_not_support_file_ext"=> "上传类型不支持!",
	'err_delete'=>'删除失败',
	"err_not_users_data"=>'不属于该用户的数据，无法操作',

	//src/domain模块
	'err_province_need'=>"省份不能为空",
	'err_city_need'=>"城市不能为空",
	'err_area_need'=>"详细地址不能为空",
	'err_province_id_need'=>"省份编码不能为空",
	'err_city_id_need'=>"城市编码不能为空",
	'err_area_id_need'=>"地区编码不能为空",
	'err_contact_name_need'=>'联系人不能为空',
	'err_contact_mobile_need'=>"联系电话不能为空",
	'err_contact_email_need'=>"邮政编码不能为空",

	"err_auto_login_code_need"=>"自动登录授权码已失效",
	'err_incorrect_password'=>'密码不正确',
	'err_invalid_idcard'=>'无效的身份证号',

	//src/user模块
	'tip_username_length'=> '用户名的长度必须大于6小于64',
	'tip_username'=>"账户名必须为字母、数字或下划线的组合且第一个必须为字母!",
	'tip_password_length'=> '密码的长度必须大于6小于64',
	'tip_password'=>'密码只能包含数字、英文字母以及如下字符 \,.?><;:!@#$%^&*()_+-=!',
	'tip_success'=>'删除成功',
	'err_account_unregistered'=>'账号尚未注册',
	'err_login_fail'=>'登录失败',
	"suc_login"=>"登录成功",
	'suc_modified'=>'修改成功',
	'err_modified'=>'修改失败',
	"err_auth_code"=>"授权码已失效",
	"err_re_login"=>"请重新登录",

	//src/base模块
	'tip_mobile_exist'=>'手机号已经被注册',
	'tip_username_exist'=>'用户名已经被注册',

	//src/service/IntentionalOrderCreateAction
	'err_id'=>"非法的id参数"

];
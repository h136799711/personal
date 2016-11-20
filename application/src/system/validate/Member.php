<?php
/**
 * Created by PhpStorm.
 * User: Zhoujinda
 * Date: 2016/9/28
 * Time: 16:01
 */

namespace app\system\validate;

use think\Validate;

class Member extends Validate{

    protected $rule = [
        /* 验证用户名 */
        'username' => 'length:6,64|checkDenyMember:|unique:Member',

        /* 验证密码 */
        'password' => 'length:6,64',

        /* 验证邮箱 */
        'email' => 'email|length:1,32|checkDenyEmail:|unique:Member',

        /* 验证手机号码 */
        'mobile' => 'regex:/^1\d{10}$/|checkDenyMobile:|unique:Member',
    ];
    protected $message = [
        'username.length' => -1,
        'username.checkDenyMember' => -2,
        'username.unique' => -3,
        'password' => -4,
        'email.email' => -5,
        'email.length' => -6,
        'email.checkDenyEmail' => -7,
        'email.unique' => -8,
        'mobile.regex' => -9,
        'mobile.checkDenyMobile' => -10,
        'mobile.unique' => -11,
    ];

    /**
     * 检测用户名是不是被禁止注册
     * @param  string $username 用户名
     * @return boolean          true - 未禁用，false - 禁止注册
     */
	protected function checkDenyMember($username,$rule,$data){
        return true; //TODO: 暂不限制，下一个版本完善
    }

	/**
     * 检测手机号是否存在
     * @param  string  $mobile 手机号码
     * @return boolean         true - 未禁用，false - 禁止注册
     */

	// protected function checkMobile($mobile){
	// 	$where = [];
	// 	$where['mobile']   = $mobile;
	// 	$where['username'] = 'M'.$mobile;
	// 	$where['_logic']   = 'or';
	// 	return $this->where($where)->find() ? false:true;
	// }
	//
	/**
     * 检测邮箱是不是被禁止注册
     * @param  string $email 邮箱
     * @return boolean       true - 未禁用，false - 禁止注册
     */
	protected function checkDenyEmail($email,$rule,$data){
        return true; //TODO: 暂不限制，下一个版本完善
    }

	/**
     * 检测手机是不是被禁止注册
     * @param  string $mobile 手机
     * @return boolean        true - 未禁用，false - 禁止注册
     */
	protected function checkDenyMobile($mobile,$rule,$data){
        return true; //TODO: 暂不限制，下一个版本完善
    }




}
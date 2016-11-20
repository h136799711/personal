<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-31
 * Time: 15:13
 */

namespace app\domain;


use app\src\base\helper\ConfigHelper;

class CustomerDomain extends BaseDomain
{
    /**
     * 所有客服信息
     * @author hebidu <email:346551990@qq.com>
     */
    public function all(){

        //客服列表数据
        $info = [
            [
                'group_name'=>'售前客服',
                'list'=>[
                    ['alibaichuan_uid'=>'TE1121477896392','uid'=>112, 'nickname'=>'小小受红','is_online'=>'1','desc'=>'全天在线'],
                    ['alibaichuan_uid'=>'TE1131477898709','uid'=>113, 'nickname'=>'小王小王小王','is_online'=>'1','desc'=>'工作日在线'],
                ]
            ],
            [
                'group_name'=>'售前客服',
                'list'=>[
                    ['alibaichuan_uid'=>'TE1141477898730','uid'=>114, 'nickname'=>'小绿(售后客服)','is_online'=>'1','desc'=>'天天在线'],
                    ['alibaichuan_uid'=>'TE1151477898747','uid'=>115, 'nickname'=>'小受小受小受小受小受(售后客服)','is_online'=>'0','desc'=>'全天在线']
                ]
            ]
        ];

        foreach ($info as $one){
            foreach ($one['list'] as $person){
                $person['avatar'] = ConfigHelper::getAvatarUrl($person['uid']);
            }
        }

        $this->apiReturnSuc($info);
    }
}
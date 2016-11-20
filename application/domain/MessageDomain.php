<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 15:32
 */

namespace app\domain;
use app\src\message\facade\MessageFacade;


/**
 * Class MessageDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class MessageDomain extends BaseDomain
{
    /**
     * 消息添加接口
     * @author hebidu <email:346551990@qq.com>
     */
    public function add(){
        $entity = $this->getParams(['extra','msg_type','summary','uid','to_uid','content','title']);

        $facade = new MessageFacade();
        $result = $facade->addMsg($entity);

        $this->exitWhenError($result,true);
    }

    /**
     * 消息查询接口
     * @author hebidu <email:346551990@qq.com>
     */
    public function query(){
        $uid = $this->_post('uid','',lang('uid_need'));
        $msg_type = $this->_post('msg_type','',lang('type_need'));
        $page_index = $this->_post('page_index','',lang('page_index_need'));
        $page_size = $this->_post('page_size','',lang('page_size_need'));
        $facade = new MessageFacade();

        $result = $facade->query($uid,$msg_type,$page_index,$page_size);

        $this->exitWhenError($result,true);

    }
}
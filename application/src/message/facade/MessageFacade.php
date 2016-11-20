<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 15:07
 */

namespace app\src\message\facade;


use app\src\message\enum\MessageStatus;
use app\src\message\logic\MessageBoxLogic;
use app\src\message\logic\MessageLogic;
use think\Db;

class MessageFacade
{
    /**
     * 发送消息
     * @param $entity
     * @return array
     */
    public function addMsg($entity){
        $uid = $entity['uid'];
        $toUid = $entity['to_uid'];
        $content = $entity['content'];
        $title = $entity['title'];
        $summary = $entity['summary'];
        $extra = $entity['extra'];
        $type = $entity['msg_type'];

        $logic = new MessageLogic();
        $boxLogix = new MessageBoxLogic();
        Db::startTrans();
        $flag = true;
        $info = "";

        $result = $logic->add([
            'dtree_type'=>$type,
            'content'=>$content,
            'title'=>$title,
            'create_time'=>time(),
            'send_time'=>time(),
            'from_id'=>$uid,
            'summary'=>$summary,
            'status'=>1,
            'extra'=>$extra
        ]);

        if(!$result['status']){
            $flag = false;
            $info = $result['info'];
        }
        $message_id = intval($result['info']);

        $result = $boxLogix->add([
            'to_id'=>$toUid,
            'msg_status'=>MessageStatus::NOT_READ,
            'msg_id'=>$message_id
        ]);

        if(!$result['status']){
            $flag = false;
            $info = $result['info'];
        }

        if($flag){
            Db::commit();

            return ['status'=>true,'info'=>$message_id];
        }else{

            Db::rollback();

            return ['status'=>false,'info'=>$info];
        }
    }

    /**
     * 获取消息
     * @param $uid  int 消息接收者的id
     * @param $type  int 消息类型
     * @param $page_index int 页码
     * @param $page_size int  每页大小
     * @return array
     */
    public function query($uid,$type,$page_index,$page_size){
        $page_index = max(1,intval($page_index) - 1);
        $page_size =  intval($page_size);

        $result = Db::table("itboye_message_box")->alias("box")
            ->join("itboye_message as msg","msg.id = box.msg_id","LEFT")
            ->where('box.to_id',$uid)
            ->where('msg.dtree_type',$type)
            ->limit($page_index * $page_size,$page_size)
            ->select();

        return ['status'=>true,'info'=>$result];
    }

    
    
}
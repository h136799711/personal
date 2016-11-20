<?php
/**
 * 周舟 hzboye010@163.com
 * addby sublime snippets
 */

namespace app\domain;

use app\src\base\helper\ConfigHelper;
use app\src\base\utils\CacheUtils;

class ConfigDomain extends BaseDomain{

    //系统配置
    public function app(){
        $this->checkVersion(100);

        $result = CacheUtils::getAppConfig(600);
        
        if($result === false){
            $this->apiReturnErr('请重新获取');
        }

        $this->apiReturnSuc($result);
    }

    /**
     * 系统支持的app支付方式
     * @author hebidu <email:346551990@qq.com>
     */
    public function supportPayways(){
        $this->checkVersion(100);
        $config = ConfigHelper::app_support_payways();

        $this->apiReturnSuc($config);
    }

//    public function version(){
//        $this->checkVersion(100);
//        $notes = "客户端" . $this->client_id . "，调用APP版本查询接口";
//        addLog("Config/version", $_GET, $_POST, $notes);
//        $r = [];
//        $from  = $this->_post("app_type","","缺少来源");
//        $result = apiCall(ConfigApi::QUERY_NO_PAGING,[['group'=>6]]);
//        if(false === $result['status']) $this->apiReturnErr($result['info']);
//        $info = $this->simpleResult($result['info']);
//        if(strcasecmp($from, 'ios') == 0){
//            $r['version']      = $info['ios_version'];
//            $r['download_url'] = $info['ios_download_url'];
//            $r['update_log']   = $info['ios_update_log'];
//        }else if(strcasecmp($from, 'android') == 0){
//            $r['version']      = $info['android_version'];
//            $r['download_url'] = $info['android_download_url'];
//            $r['update_log']   = $info['android_update_log'];
//        }
//        $this->apiReturnSuc($r);
//    }

//    private function simpleResult($result){
//        $result = is_array($result)?$result:[];
//        $simpleResult = [];
//        foreach($result as $vo){
//            $val  = $vo['value'];
//            $name = strtolower($vo['name']);
//            if($vo['type'] == 3){
//                $array = preg_split('/[,;\r\n]+/', trim($val, ",;\r\n"));
//                if (strpos($val, ':')) {
//                    $val = [];
//                    foreach ($array as $va) {
//                        list($k, $v) = explode(':', $va,2);
//
//                        $val[] = $v;
//                    }
//                } else {
//                    $val = $array;
//                }
//            }else{
//                $val = htmlspecialchars_decode($val);
//            }
//            $simpleResult[$name] = $val;
//        }
//        return $simpleResult;
//    }

}
<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 9:58
 */

namespace app\src\rfpay\po;
use app\src\rfpay\utils\AesUtils;
use app\src\rfpay\utils\RsaUtils;


/**
 * Class RfResponseBody
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 * 
 */
class RfBaseResp
{
    private $code;
    private $msg;
    private $rawData;
    private $payConfig;
    protected $decodeData;

    public function __construct($result,RfPayConfig $payConfig)
    {
        $this->decodeData = "";
        $this->rawData = "";
        $this->payConfig = $payConfig;
        if($this->payConfig == null){
            $this->payConfig = new RfPayConfig(null);
        }

        if(!$result['status']){
            $this->setCode("-1");
            $this->setMsg($result['info']);
        }else{
            $info = $result['info'];
            $this->rawData = $info;

            if(isset($info['encryptkey'])) {

                $encryptKey = $info['encryptkey'];

                //1. 解密key
                $encryptKey = RsaUtils::decrypt($encryptKey,$this->payConfig->getPemContent());
                $info['encryptkey'] = $encryptKey;

                //通过解密后的key 来解密内容
                if(isset($info['data'])){
                    //1. 解密数据
                    $data = $info['data'];
                    $data = AesUtils::decrypt($data,$encryptKey);
                    $info['data'] = $data;
                    $infoData = $info['data'];
                    $infoData = (json_decode($infoData,JSON_OBJECT_AS_ARRAY));

                    if(isset($infoData['code'])){
                        $this->setCode($infoData['code']);
                    }

                    if(isset($infoData['msg'])){
                        $this->setMsg($infoData['msg']);
                    }

                    $this->decodeData = $infoData;
                }
            }

        }

        if(isset($result['info'])){
            $this->rawData = $result['info'];
        }
    }

    public function getRawData(){
        return $this->rawData;
    }

    /**
     * 返回结果是否正确
     * @return bool
     */
    public function isSuccess(){
        return $this->getCode() == "000000" || $this->getCode() == "0";
    }

    /**
     * 000000:返回成功 其它失败
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-13
 * Time: 16:05
 */

namespace app\src\paypal\facade;


use PayPal\Api\CreditCard;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaypalFacade
{
    private $clientId = "ARgKIyq2jj2nCGV6zdBOIanF1Yl0ts4ARtUca3LUVp28wLaUbxICJyY6T5IksiGAyTPEc3KG6JiUiLKJ";
    private $clientSecret = "EBbnm07IAXfcFio14KO9fKjROwjlokkBD321A5jhhKrMNGEsxI1U9i9GxpfpJoHfOFLnVjV9psauZqQg";

    //正式
//    private $clientId ="Aagm50X_a6CozhtXtpyDs97lyWMgXtXJxAKIvbCd0kviDB9Yh4d-zMgtSaCzjT5dCOLMXwVtEQ0yU8x0";
//    private $clientSecret = "EHl8Yb5Xz2rw-E-L0hbRJ7mBUd0fStYF6NaEsuAYh7XIQ-LLaXRDnct2N6I1_1ZyhDstkWvIV-T-_eEZ";

    private $apiContext;

    public function __construct($config=null)
    {
        if(is_array($config)){
            if(isset($config['client_id'])){
                $this->clientId = $config['client_id'];
            }
            if(isset($config['client_secret'])){
                $this->clientSecret = $config['client_secret'];
            }
        }

        vendor("paypal.autoload");
        self::initApiContext();
    }

    public function initApiContext(){
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );
    }

    public function saveCreditCardInfoToVault(){
        $creditCard = new CreditCard();
        $creditCard->setType("visa")
            ->setNumber("4417119669820331")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Joe")
            ->setLastName("Shopper");

        try{
            $creditCard->create($this->apiContext);
            echo $creditCard;
        }catch (PayPalConnectionException $ex){
            echo $ex->getData();
        }

    }

}
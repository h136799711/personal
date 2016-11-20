<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 14:02
 */
$android_url = "http://api.guannan.itboye.com/public/app/android_1001.apk";
$ios_url = "https://itunes.apple.com/cn/app/mei-ren-yu-zhi-bo/id1152214216?l=zh&ls=1&mt=";
if(isset($_GET['v'])){
    $v = $_GET['v'];
    if($v == 'ios'){
        header('Location:'.$ios_url);
    }elseif($v == 'android'){
        header('Location: '.$android_url);
    }else{
        echo "参数非法!";
    }
    
    exit;
}

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
    // 非微信浏览器禁提示
    echo '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                </head>
                <body>
                <span style="font-size: 3rem">微信请点开右上角菜单选择在浏览器中打开</span>
                </body>
                </html>';
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
    //echo 'systerm is IOS';
    header('Location:'.$ios_url);
}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
    //echo 'systerm is Android';
    header('Location: '.$android_url);
}else{
    echo '';
}
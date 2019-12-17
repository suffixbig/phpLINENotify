<?php

/*
作者:suffixbig
作用:發送免費訊息
發送訊息-給特定人
錯誤處理
{
  "status": 400,
  "message": "LINE Notify account doesn't join group which you want to send."
}
LINE通知帳戶未加入您要發送的組。
將官方 @linenotify 帳號 加入群組
 */
//==========================================================================
$thisDir = "."; //config.inc.php檔的相對路徑
$_file   = basename(__FILE__); //自行取得本程式名稱
require $thisDir . "/config_in_file.php"; // 載入主參數設定檔

function lineNotifyMessage2($token, $message)
{
    //訊息
    $payload = array('message' =>  $message);
    $params = http_build_query($payload, null, '&');
    //檔頭
    $header = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Bearer ' . $token,
    );
    // 發送 發送多播消息 隨時向多個用戶發送推送消息。無法將消息發送到團體或房間。
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://notify-api.line.me/api/notify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Bearer ' . $token,
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//可以用PHP file_get_contents 發送 HTTPS POST 的方法
function lineNotifyMessage($token, $message)
{
    //訊息
    $payload = array('message' =>  $message);
    $postdata = http_build_query($payload, null, '&');
    $header = array(
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: " . strlen($postdata),
        'Authorization: Bearer ' . $token,
    );
    //POST方法
    $opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => $header,
        'content' => $postdata,
    ), 'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
    ));
    $context  = stream_context_create($opts);
    $response = file_get_contents('https://notify-api.line.me/api/notify', false, $context);
    return $response;
}


$message = ' Griffey很帥123!!!!!測試Linebot 主動發送訊息' . date("Y-m-d H:i:s"); //預設發送的訊息
$token = "JSBykMTRUDFsKBP8k6yjsnblLRJ0iLKknbAuVRbG9lv";//權杖

$ok = lineNotifyMessage($token, $message);

$okj = json_decode($ok, true);

if ($okj['status'] = 200) {
    echo "訊息發送成功\n";
} else {
    echo "訊息發送失敗\n" . print_r($okj);
}

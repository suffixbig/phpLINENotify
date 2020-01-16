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
//require $thisDir . "/config_in_file.php"; // 載入主參數設定檔

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

//檢查網站異常
function ckurlok($url)
{
    $toCheckURL = $url; //設定要檢查的url變數

    // 設定curl的函數
    $ch = curl_init(); //先初始化
    curl_setopt($ch, CURLOPT_URL, $toCheckURL); //需要獲取的URL地址
    curl_setopt($ch, CURLOPT_HEADER, true); //啟用時會將頭文件的信息作為數據流輸出
    curl_setopt($ch, CURLOPT_NOBODY, true); //啟用時將不對HTML中的body部分進行輸出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //執行curl_exec()獲取的信息以文件流的形式返回，而不是直接輸出
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //啟用時會將服務器服務器返回的「Location:」放在header中遞歸的返回給服務器，使用CURLOPT_MAXREDIRS可以限定遞歸返回的數量。
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //指定最多的HTTP重定向的數量，這個選項是和CURLOPT_FOLLOWLOCATION一起使用的。
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); //設置curl允許執行的最長秒數
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//有這2行才能https
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); //在發起連接前等待的時間，如果設置為0，則不等待。

    $data = curl_exec($ch);
    curl_close($ch);

    preg_match_all("/HTTP\/1\.[1|0]\s(\d{3})/", $data, $matches);
    $code = end($matches[1]);

    if (!$data) {
        //如果Url無法開啟
        //echo "網頁無法開啟";
        return false;
    } else {
        // Show the correct information based on the status code
        switch ($code) {
            case '200':
                //echo "Page Found";
                return true;
                break;
            case '401':
                //echo "Unauthorized";
                return false;
                break;
            case '403':
                //echo "Forbidden";
                return false;
                break;
            case '404':
                //echo "Page Not Found";
                return false;
                break;
            case '500':
                //echo "Internal Server Error";
                return false;
                break;
        } //end of switch
    } //end of if
} //end of function






$message = '123' . date("Y-m-d H:i:s"); //預設發送的訊息
$token = "ytAhbdYP7Yr8sDAtj13HeHbiRQUevw1wuXuQVeJ2fX4";//權杖


//檢查目標
$url="https://star-digitalapp.io/line_sato/index.html";
// 檢查網站是否開啟(存在)
$ok=ckurlok($url);
//var_dump($ok);
if($ok){
    $message =  $url."\n網站無異狀". date("Y-m-d H:i:s"); //預設發送的訊息
}else{
    $message =  $url."\n網站異常". date("Y-m-d H:i:s"); //預設發送的訊息
}

//發送通知
$ok = lineNotifyMessage($token, $message);
$okj = json_decode($ok, true);
if ($okj['status'] = 200) {
    echo "訊息發送成功\n";
} else {
    echo "訊息發送失敗\n" . print_r($okj);
}

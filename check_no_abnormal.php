<?php
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


$url="https://star-digitalapp.io/line_sato/index.html";

// 檢查網站是否開啟(存在)
$ok=ckurlok($url);
//var_dump($ok);
if($ok){
    echo "網站無異狀";
}else{
    echo "網站異常";
}

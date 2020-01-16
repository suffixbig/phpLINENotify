# phpLINENotify
裝網路監測工具，主機一但掛掉會自動回報
https://curler.amixr.io/
不過這是國外的線路，如果是台灣的線路有異常不會回報，有什麼工具可以監測嗎？

以下是我寫的程式
check_no_abnormal.php		PHP檢查網站是否異常
test_LINENotify_push.php	LINE免費發送訊息
check_no_abnormal2.php		PHP檢查網站是否異常+LINE免費發送訊息通知你

只需要把以上程式改成，正常不通知，異常才通知，然後加入排程每10分鐘執行一次就搞定了

https://github.com/suffixbig/phpLINENotify/                    我提供的github原碼下載處
------------------------------------------------------------------------------------------------------------------
# LINE 如何發送免費訊息

#不要看這篇
http://studyhost.blogspot.com/2016/12/linebot6-botline-notify.html 看到整個流程 這比 LINE BOT 的註冊還麻煩
#看這篇
https://bustlec.github.io/note/2018/07/10/line-notify-using-python/ 使用 Python 實作發送 LINE Notify 訊息

--------------------------------------------------------------------------
申請 LINE Notify 發行權杖
需擁有 LINE 帳號
至 https://notify-bot.line.me/zh_TW/ 進行登入
點選右上方 帳號名稱選單中的「個人頁面」=>點選發行存取權杖

@linenotify 官方帳號
https://notify-bot.line.me/doc/en/ 官方說明
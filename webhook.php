<?php
/**
 * @Guruhni reklamadan tozalaydigan bot kodi @pcode uchun
 * @author ShaXzod Jomurodov <shah9409@gmail.com>
 * @contact https://t.me/idFox AND https://t.me/ads_buy
 * @date 13.05.2021 14:50
 */

//sozlash
include 'Telegram.php';
include 'app/bot.class.php';
include 'app/config.php';

$time1 = date("r", time());
$time2 = explode(', ', $time1);
$vaqt = str_replace(' +0500', "", $time2[1]);

$telegram = new Telegram($bot_token);
$efede3 = $telegram->getData();

if(!$efede3) {
    $url = "https://api.telegram.org/bot{$bot_token}/setWebhook?url=https://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}";

    @file_get_contents($url);
    die("webhook o'rnatildi manimcha )");
}

//basic
$text = $efede3["message"]["text"];
if (!$text) {
    $text = $efede3["message"]["caption"];
}
$foto = $efede3["message"]["photo"];
$msg = $efede3["message"]["message_id"];
$sana = $efede3["message"]["date"];
$chat_id = $efede3["message"]["chat"]["id"];
$fileclass = $efede3["message"]["document"]["file_name"];
$file_id = $efede3["message"]["document"]["file_id"];
$documentmsg = $efede3["message"]["document"];


$entities = $efede3["message"]["entities"];
if (!$entities) {
    $entities = $efede3['message']['caption_entities'];
}
// chat
$cfname = $efede3['message']['chat']['first_name'];
$cid = $efede3["message"]["chat"]["id"];
$clast_name = $efede3['message']['chat']['last_name'];
$turi = $efede3["message"]["chat"]["type"];
$username = $efede3['message']['chat']['username'];
$cusername = $efede3['message']['chat']['username'];
$ctitle = $efede3['message']['chat']['title'];

//user info
$ufname = $efede3['message']['from']['first_name'];
$uname = $efede3['message']['from']['last_name'];
$ulogin = $efede3['message']['from']['username'];
$uid = $efede3['message']['from']['id'];
$user_id = $efede3['message']['from']['id'];

//reply info
$sreply = $efede3['message']['reply_to_message']['text'];
$reply_markup = $efede3['message']['reply_markup'];
$forward_from_chat = $efede3['message']['forward_from_chat'];
$forward_from_is_bot = $efede3['message']['forward_from']['is_bot'];

//via_bot info
$via_fname = $efede3['message']['via_bot']['first_name'];
$via_bot = $efede3['message']['via_bot']['is_bot'];
$via_login = $efede3['message']['via_bot']['username'];
$via_id = $efede3['message']['via_bot']['id'];

//new_chat_participant info
$nfname = $efede3['message']['new_chat_participant']['first_name'];
$nbot = $efede3['message']['new_chat_participant']['is_bot'];
$nlogin = $efede3['message']['new_chat_participant']['username'];
$nid = $efede3['message']['new_chat_participant']['id'];

//my_chat_member new
$my_chat_member = $efede3['my_chat_member'];
$new_title = $efede3['my_chat_member']['chat']['title'];
$new_username = $efede3['my_chat_member']['chat']['username'];
$new_id = $efede3['my_chat_member']['chat']['id'];
$new_type = $efede3['my_chat_member']['chat']['type'];

$chatm_admin = $efede3['my_chat_member']['new_chat_member']['status'];

if (!$uid) {
    $uid = $Callback_FromID;
}

if ($efede3['edited_message']) {
    $msg = $efede3["edited_message"]["message_id"];
    $sana = $efede3["edited_message"]["date"];
    $chat_id = $efede3["edited_message"]["chat"]["id"];
    $uid = $efede3['edited_message']['from']['id'];
    $user_id = $efede3['edited_message']['from']['id'];
    $ufname = $efede3['edited_message']['from']['first_name'];
    $turi = $efede3["edited_message"]["chat"]["type"];

    $text = $efede3["edited_message"]["text"];
    if (!$text) {
        $text = $efede3['edited_message']['caption'];
    }

    $entities = $efede3['edited_message']['entities'];
    if (!$entities) {
        $entities = $efede3['edited_message']['caption_entities'];
    }
}

if ($turi == 'private')
{
    if ($text == '/start' || mb_stripos($text,"/start") !==false){
        $option = [
            [ $telegram->buildInlineKeyBoardButton("Guruhga qushish", "http://t.me/{$bot_username}?startgroup=ru", '') ]
            ];
        $keyb = $telegram->buildInlineKeyBoard($option);

        $content = ['chat_id' => $chat_id, 'text' => "<b>Salom👋</b> \nMan arablani, reklamalani, sslkalani guruhlarda o'chirib beraman👮🏻‍♂️ \n\nIshimni boshlashim uchun ADMIN bo'lishim kerak😄 Admin qilganingizdan so'ng 1 daqiqa ichida to'liq ish faoliyatiga tushaman! \n📃 Bot Yangiliklari - <a href='https://t.me/pcode'> MANZIL </a>", 'reply_markup' => $keyb, 'parse_mode' => 'html'];
        $telegram->sendMessage($content);

        $azo = $sqldb->queryRow("SELECT * FROM members WHERE user_id = $uid");
        if(!$azo){
            addUser($uid, $vaqt);
        }

        sleep(5);
        $admin_home = [["❗️Bot holati"]];
        $keyb = $telegram->buildKeyBoard($admin_home, $onetime = false, $resize = true);

        $content = ['chat_id' => $chat_id, 'text' => "<b>Nimaga qarab turibsiz, botni guruhga olib keting 😅</b>", 'reply_markup' => $keyb, 'parse_mode' => 'html'];
        $telegram->sendMessage($content);
        die();
    }
    // END /start

    if ($text == "❗️Bot holati"){
        $user_count = $sqldb->querycount('SELECT * FROM members');
        $group_count = $sqldb->querycount('SELECT * FROM groups');

        $content = ['chat_id' => $chat_id, 'parse_mode' => 'markdown', 'reply_markup' => $keyb, 'text' => "*👥Bot a'zolari soni:* $user_count \n *♻️Botdagi guruhlar soni:* $group_count"];
        $telegram->sendMessage($content);

        sleep(5);
        $admin_home = [["❗️Bot holati"]];
        $keyb = $telegram->buildKeyBoard($admin_home, $onetime = false, $resize = true);

        $content = ['chat_id' => $chat_id, 'text' => "<b>Nimaga qarab turibsiz, botni guruhga olib keting 😅</b>", 'reply_markup' => $keyb, 'parse_mode' => 'html'];
        $telegram->sendMessage($content);
        die();
    }
}

if ($new_type == 'supergroup' || $new_type == 'group')
{
    if ($chatm_admin == 'administrator') {
        $content = ['chat_id' => $new_id, 'parse_mode' => 'markdown', 'text' => "*Men guruh qumondoniman, o'ylab-bilib yozasan endi 😎*"];
        $telegram->sendMessage($content);
    }
    addGroup($new_id, $vaqt);
    die();
}

if ($turi == 'supergroup' || $turi == 'group')
{
    if ($nid) {
        addGroup($chat_id, $vaqt);
    }

    if (preg_match('/[اأإء-ي]/ui', $text) || preg_match('/[اأإء-ي]/ui', $caption)) {
        del_ads($chat_id, $uid, $msg, 'arab');
    }

    if ($reply_markup) {
        foreach ($reply_markup["inline_keyboard"] as $value) {
            if($value[0]["url"]){
                del_ads($chat_id, $uid, $msg, 'via_bot');
                break;
            }
        }
    }

    if ($entities) {
        foreach ($entities as $value) {
            if($value["type"] == "bot_command"){
                del_ads($chat_id, $uid, $msg, 'bot');
                break;
            }

            if($value["type"] == "text_link" || $value["type"] == "url"){
                del_ads($chat_id, $uid, $msg, 'reklama');
                break;
            }
            if ($value["type"] == "mention" || $value["type"] == "text_mention") {
                if ($value["user"]["is_bot"]) {
                    del_ads($chat_id, $uid, $msg, 'bot_mention');
                    break;
                }

                // @username da 'bot' so'zi bo'lsa uni  bot deb hisoblaydi, aslida bu xato
                if (mb_stripos(strtolower($text),"bot") !==false) {
                    del_ads($chat_id, $uid, $msg, 'bot_mention');
                    break;
                }

                $begin = $value["offset"];
                $end = $value["length"];
                $qism = substr($text, $begin);
                $qism = substr($qism, 0, $end);
                $qism = str_replace("@", '', $qism);
                if (tekshir($qism)) {
                    del_ads($chat_id, $uid, $msg, 'channel_mention');
                    break;
                }
            }
        }
    }

    if ($forward_from_chat) {
        if ($forward_from_chat["type"] == 'channel') {
            del_ads($chat_id, $uid, $msg, 'for');
        }
    }

    if ($forward_from_is_bot) {
        del_ads($chat_id, $uid, $msg, 'forbot');
    }

    if ($via_bot) {

    }
    die();
}
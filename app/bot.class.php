<?php
/**
 * @Guruhni reklamadan tozalaydigan bot kodi @pcode uchun
 * @author ShaXzod Jomurodov <shah9409@gmail.com>
 * @contact https://t.me/idFox AND https://t.me/ads_buy
 * @date 13.05.2021 14:50
 */
  if (file_exists("app/sqldb.php")) {
    require("app/sqldb.php");
  } else {
    die('db not fount');
  }
  
  try {
    $sqldb = new Db('data/database.db');
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
	die('db not connect');
  }

function lg($text, $chat_id = false){
global $telegram, $uid;
	if ($uid) {
		$fefefe = "#debug <a href='tg://user?id={$uid}'>$uid</a>: {$text}";
	} else {
		$fefefe = "#debug: {$text}";
	}
    $content = ['chat_id' => '368844346', 'text' => $fefefe, 'parse_mode' => 'html'];
    $telegram->sendMessage($content);
}

function addUser($uid, $vaqt) {
	if (checkUID($uid)) return false;
	global $sqldb;

	$row = $sqldb->query("INSERT INTO members ('user_id', 'reg_date', 'status') VALUES ($uid, '$vaqt', '1')");
	if(!$row){
	  	return false;
	} else {
		return true;
	}
}

function checkUID($uid) {
global $sqldb;

	$row = $sqldb->queryRow("SELECT * FROM members WHERE user_id = $uid");
	if(!$row){
	  return false;
	} else { 
		return true;
	}
}

function addGroup($uid, $vaqt) {
  if (checkGROUP($uid)) return false;
  global $sqldb;

  $row = $sqldb->query("INSERT INTO groups ('user_id', 'reg_date', 'status') VALUES ($uid, '$vaqt', '1')");
  if(!$row){
    return false;
  } else {
    return true;
  }
}

function checkGROUP($uid) {
global $sqldb;

  $row = $sqldb->queryRow("SELECT * FROM groups WHERE user_id = $uid");
  if(!$row){
    return false;
  } else { 
    return true;
  }
}

function del_ads($chat_id, $uid, $msg, $type){
    global $telegram, $ufname;

    if($uid != 777000 and $uid != 1087968824){
        $content = ['chat_id' => $chat_id, 'message_id' => $msg];
        $telegram->deletemessage($content);

        if (file_exists("temp/{$chat_id}.msg_id")) {
            $msg_id = file_get_contents("temp/{$chat_id}.msg_id");

            $content = ['chat_id' => $chat_id, 'message_id' => $msg_id];
            $telegram->deletemessage($content);   
        }

        switch ($type) {
            case 'arab':
                $matn = "arab reklamalardan saqlanish uchun sizning xabaringiz o'chirildi!";
                break;
            case 'for':
                $matn = "iltimos forward xabar tarqatmang.";
                break;
            case 'edited':
                $matn = "iltimos forward xabar tarqatmang.";
                break;
            case 'reklama':
                $matn = "iltimos reklama tarqatmang.";
                break;
            case 'via_bot':
                $matn = "iltimos bot orqali reklama tarqatmang.";
                break;
            case 'bot':
                $matn = "iltimos bot buyruqlariga teginmang.";
                break;
            case 'bot_mention':
                $matn = "iltimos bot nomini tarqatmang.";
                break;
            case 'channel_mention':
                $matn = "iltimos kanal yoki guruh nomini tarqatmang.";
                break;
            case 'forbot':
              $matn = "iltimos botdan xabar uzatmang.";
              break;
            default:
                $matn = "iltimos har xil narsa yubormang guruhga";
                break;
        }

        $content = ['chat_id' => $chat_id, 'text' => "❗️ [$ufname](tg://user?id=$uid) *{$matn}*", 'parse_mode' => 'markdown'];
        $xabar = $telegram->sendMessage($content);
        $xabar_msg = $xabar["result"]["message_id"];
        @file_put_contents("temp/{$chat_id}.msg_id", $xabar_msg);
    }
    die();
}

function tekshir($post){

  $file = file_get_contents("https://t.me/".$post);

  if (mb_stripos($file,"subscribers") !== false || mb_stripos($file,"subscriber") !== false  || mb_stripos($file,"members") !== false || mb_stripos($file,"member") !== false) {
    return true;
  } else return false;
}

?>
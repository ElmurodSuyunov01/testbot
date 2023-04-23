<?php
//echo 'salom';

include 'Telegram.php';
$telegram = new Telegram('6217498588:AAHN-sQb0ULlowFN8MV7WXH6FvEWXxX5HII');

$chat_id = $telegram->ChatID();
$text = $telegram->Text();

$content= array('chat_id'=>$chat_id,'text'=>$text);
$telegram->sendMessage($content);

$orderTypes=["🧉 1kg", "🧉 2kg", "🧉 3kg"];
if ($text=="/start"){
    showStart();
}elseif ($text=="🧉 Batafsil malumot"){
    showBatafsil();
}elseif ($text=="🧉 Buyurtma berish"){
    showBuyutma();
}elseif (in_array($text,$orderTypes)){
    askContact();
}elseif ($text=="Orqaga"){
    showStart();
}

function showStart(){
    global $telegram,$chat_id;
    $option = array(
        //First row
        array($telegram->buildKeyboardButton("🧉 Batafsil malumot")),
        //Second row
        array($telegram->buildKeyboardButton("🧉 Buyurtma berish")) );
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);
    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "Assalomu alaykum botimizga hush kelibsiz");
    $telegram->sendMessage($content);
}
function showBatafsil(){
    global $telegram,$chat_id;
    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb,'text' => "Biz haqimizda malumot. <a href='https://telegra.ph/Turkiyadagi-navbatdagi-zilzila-yanada-da%D2%B3shatli-b%D1%9Elishi-mumkin-Bu-%D2%B3olda-Istanbul-zhiddij-zarar-k%D1%9Eradi-02-11'> Havola </a>", 'parse_mode'=>"html");
    $telegram->sendMessage($content);
}
function showBuyutma(){
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("🧉 1kg")),
        array($telegram->buildKeyboardButton("🧉 2kg")),
        array($telegram->buildKeyboardButton("🧉 3kg")),
        array($telegram->buildKeyboardButton("Orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);

    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "Assalomu alaykum botimizga hush kelibsiz");
    $telegram->sendMessage($content);
}
function askContact(){
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("raqamni janatish",true)));
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);

    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "hajm tanlandi, Endi telefon raqamingizni jonating");
    $telegram->sendMessage($content);
}
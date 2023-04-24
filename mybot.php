<?php
require_once 'Telegram.php';
require_once 'users/user.php';

$telegram = new Telegram('6217498588:AAHN-sQb0ULlowFN8MV7WXH6FvEWXxX5HII');
$ADMIN_CHAT_ID='2114654453';
$data = $telegram->getData();
$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$message = $data['message'];
$orderTypes=["🧉 1kg", "🧉 2kg", "🧉 3kg"];

if ($text=="/start"){
    showMain();
}else{
    switch (getPage($chat_id)){
        case 'main':
            if ($text=="🧉 Batafsil malumot"){
                showAbout();
            }elseif ($text=="🧉 Buyurtma berish"){
                showOrder();
            }else{
                chooseButtons();
            }
            break;
        case 'massa':
            if (in_array($text,$orderTypes)){
                setMass($chat_id,$text);
                showPhone();
            }elseif ($text=='orqaga'){
                showMain();
            }else{
                chooseButtons();
            }
            break;
        case 'phone':
            if ($message['contact']['phone_number']!=""){
                setPhone($chat_id,$message['contact']['phone_number']);
                showDeliveryType();
            }elseif ($text=="orqaga"){
                showOrder();
            }else{
                setPhone($chat_id,$text);
                showDeliveryType();

            }
            break;
        case 'delivery':
            if ($text=="Yetkazib berish"){
                showInputLocation();
            }elseif ($text=="Borib olish"){
                showReady();
            }elseif ($text=="orqaga"){
                showPhone();
            } else{
                chooseButtons();
            }
            break;
        case 'location':
            if ($message['location']['latitude']!=""){
                setLatitude($chat_id,$message['location']['latitude']);
                setLongitude($chat_id,$message['location']['longitude']);
                showReady();
            }elseif ($text=="locatsiya Jo'nata olmayman"){
                showDeliveryType();
            }else{
                chooseButtons();
            }
            break;
        case 'ready':
                if ($text=="Boshqa buyurtma berish"){
                    showMain();
                }else{
                    chooseButtons();
                }
            break;

    }
}
function showInputLocation()
{
    global $telegram,$chat_id;
    setPage($chat_id,'location');
    $option = array(
        //First row
        array($telegram->buildKeyboardButton("Locatsiya jo'natish",false,true)),
        //Second row
        array($telegram->buildKeyboardButton("locatsiya Jo'nata olmayman")),
        array($telegram->buildKeyboardButton("orqaga")) );
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);
    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "Yaxshi endi, Lokatsiya jo'nating");
    $telegram->sendMessage($content);
}

function showReady()
{
    global $telegram,$chat_id,$ADMIN_CHAT_ID;
    setPage($chat_id,'ready');
    $option = array(
        //First row
        array($telegram->buildKeyboardButton("Boshqa buyurtma berish")));
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);

    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb,'text' => "Sizning Buyurtmangiz qabul qilindi, Tez orada siz bilan bog'lanamiz , Murojaatingiz uchun rahmat");
    $telegram->sendMessage($content);

    /*admin send*/
    $textAdmin="Yangi buyurtma keldi!";
    $textAdmin.="\n";
    $textAdmin.="Hajm".getMass($chat_id);
    $textAdmin.="\n";
    $textAdmin.="Telefon Raqam".getPhone($chat_id);


    $content = array('chat_id' => $ADMIN_CHAT_ID,'text' => $textAdmin);
    $telegram->sendMessage($content);
    if (getLatitude($chat_id)!=""){
        $content = array('chat_id' => $ADMIN_CHAT_ID,'latitude' => getLatitude($chat_id),'longitude'=>getLongitude($chat_id));
        $telegram->sendLocation($content);
    }

    
}
function showDeliveryType()
{
    global $telegram,$chat_id;
    setPage($chat_id,'delivery');
    $option = array(
        //First row
        array($telegram->buildKeyboardButton("Yetkazib berish")),
        //Second row
        array($telegram->buildKeyboardButton("Borib olish")) ,
        array($telegram->buildKeyboardButton("orqaga")) );
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);
    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "Bizda Toshkent Shaxri bo'ylab yetgazib berish tekin");
    $telegram->sendMessage($content);

}
function showMain()
{
    global $telegram,$chat_id;
    setPage($chat_id,'main');
    $option = array(
        //First row
        array($telegram->buildKeyboardButton("🧉 Batafsil malumot")),
        //Second row
        array($telegram->buildKeyboardButton("🧉 Buyurtma berish")) );
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);
    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "Assalomu alaykum botimizga hush kelibsiz");
    $telegram->sendMessage($content);

}
function showOrder(){
    global $telegram, $chat_id;
    setPage($chat_id,'massa');
    $option = array(
        array($telegram->buildKeyboardButton("🧉 1kg")),
        array($telegram->buildKeyboardButton("🧉 2kg")),
        array($telegram->buildKeyboardButton("🧉 3kg")),
        array($telegram->buildKeyboardButton("orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);

    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "Quyidagilardan birini tanlang");
    $telegram->sendMessage($content);
}

function showAbout(){
    global $telegram,$chat_id;
    $content = array('chat_id' => $chat_id,'text' => "Biz haqimizda malumot. <a href='https://telegra.ph/Turkiyadagi-navbatdagi-zilzila-yanada-da%D2%B3shatli-b%D1%9Elishi-mumkin-Bu-%D2%B3olda-Istanbul-zhiddij-zarar-k%D1%9Eradi-02-11'> Havola </a>", 'parse_mode'=>"html");
    $telegram->sendMessage($content);
}
function chooseButtons()
{
    global $telegram,$chat_id;
    $content = array('chat_id' => $chat_id,'text' => "Iltimos quyidagi Tugmalarni birini tanlang");
    $telegram->sendMessage($content);


}
function showPhone(){
    global $telegram, $chat_id;
    setPage($chat_id,'phone');
    $option = array(
        array($telegram->buildKeyboardButton("raqamni janatish",true)),
        array($telegram->buildKeyboardButton("orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true);

    $content = array('chat_id' => $chat_id,'reply_markup' => $keyb, 'text' => "hajm tanlandi, Endi telefon raqamingizni jonating");
    $telegram->sendMessage($content);
}
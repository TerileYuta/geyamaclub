<?php
    use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
    use \LINE\LINEBot;
    use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
    use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
    use \LINE\LINEBot\Constant\HTTPHeader;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With");


    require "./config.php";
    require "./log/add_log.php";
    require "./vendor/autoload.php";

    Dotenv\Dotenv::createImmutable(__DIR__)->load();

    define('TOKEN', $_ENV["ACCESS_TOKEN"]);
    define('SECRET', $_ENV["CHANNEL_SECLET"]);
    define('GROUP_ID', $_ENV["GROUP_ID"]);

    $message = $_POST["message"];
    $status = $_POST["status"];
    $practice_id = $_POST["id"];

    if($status == "1"){
        $practice = ORM::for_table('plan')->where("id", $practice_id)->find_many();
        $practice[0] -> set("status", '1');
        $practice[0] -> save();
    }else{
        $practice = ORM::for_table('plan')->where("id", $practice_id)->find_one();
        $practice -> status = "0";
        $practice  -> save();
    }

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(TOKEN);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => SECRET]);

    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
    $response = $bot->pushMessage(GROUP_ID, $textMessageBuilder);

    add_log("main", "練習ID". $practice_id. "の練習参加の募集を始めました");
?>
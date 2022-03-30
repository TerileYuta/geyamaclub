<?php
    use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
    use \LINE\LINEBot;
    use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
    use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
    use \LINE\LINEBot\Constant\HTTPHeader;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With");

    require_once(__DIR__."/vendor/autoload.php");

    require_once "../config.php";
    require_once "../idiorm.php";
    require_once "../log/add_log.php";

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

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('YbZQjiQrxyMA2Il6wVBSRydaJ6C1lrF4fYOJji7MX/kzhC4wMv9Af1owBZQGLqLHtSKXJlj1TlGt0pGKa81wgjArmQyF8wvxcnqUxfpeKY4H4l/9IcNmakWgvnVJcVdVx4Vd3C/l14yvWITNd2va+gdB04t89/1O/w1cDnyilFU=');
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '23e57496ac4ca983afe0dd6ec66e0bad']);

    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
    $response = $bot->pushMessage('Cb47f4ae2e1dd7de087bfe37b351d99c3', $textMessageBuilder);

    add_log("main", "練習ID". $practice_id. "の練習参加の募集を始めました");
?>
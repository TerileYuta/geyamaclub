<?php
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\Constant\HTTPHeader;

require_once(__DIR__."/vendor/autoload.php");
require_once "../config.php";
require_once "../idiorm.php";
require_once "../log/add_log.php";

define('TOKEN', "YbZQjiQrxyMA2Il6wVBSRydaJ6C1lrF4fYOJji7MX/kzhC4wMv9Af1owBZQGLqLHtSKXJlj1TlGt0pGKa81wgjArmQyF8wvxcnqUxfpeKY4H4l/9IcNmakWgvnVJcVdVx4Vd3C/l14yvWITNd2va+gdB04t89/1O/w1cDnyilFU=");

$jsonAry = json_decode(file_get_contents('php://input'), true);

$type = $jsonAry['events'][0]['type'];
$replyToken = $jsonAry['events'][0]['replyToken'];
$data = $jsonAry['events'][0];

$reply_message = "エラーが発生しました";

$reply_message = main($type, $data);

if($reply_message != ""){
    $response = [
        'replyToken' => $replyToken,
        'messages' => array(array('type' => 'text', 'text' => $reply_message))
    ];
    
    sending($response);
}

function sending($response){
    $ch = curl_init('https://api.line.me/v2/bot/message/reply');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charser=UTF-8','Authorization: Bearer  ' . TOKEN ));
    $result = curl_exec($ch);
    curl_close($ch);
}

function main($type, $data){
    $reply = new Reply();
    $text = "エラーが発生しました";

    if($type == 'message'){
        $user_input = $data['message']["text"];
        switch($user_input){
            case "！新規会員登録":
                $text = $reply->sign_up($data);
                
                break;

            case "！行きます":
                $text = $reply->join_practice($data);

                break;
            
            case "！キャンセル":
                $text = $reply->cancel_practice($data);

                break;

            default:
                $text = "";
        }
    }else{

    }

    return $text;
}

class Reply{
    private function get_profile($line_id){
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('YbZQjiQrxyMA2Il6wVBSRydaJ6C1lrF4fYOJji7MX/kzhC4wMv9Af1owBZQGLqLHtSKXJlj1TlGt0pGKa81wgjArmQyF8wvxcnqUxfpeKY4H4l/9IcNmakWgvnVJcVdVx4Vd3C/l14yvWITNd2va+gdB04t89/1O/w1cDnyilFU=');
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '23e57496ac4ca983afe0dd6ec66e0bad']);
        
        $response = $bot->getProfile($line_id);
        if ($response->isSucceeded()) {
            $profile = $response->getJSONDecodedBody();
            $name = $profile['displayName'];
            $pic_url = $profile['pictureUrl'];
        }

        return array("name" => $name, "picUrl" => $pic_url);
    }

    public function sign_up($data){
        $line_id = $data["source"]["userId"];
        $profile = $this->get_profile($line_id);
        $name = $profile["name"];

        try{
            $person = ORM::for_table('member')->create();
            $person->name = $profile["name"];
            $person->line_id = $line_id;
            $person->pic_url = $profile["picUrl"];
            $person->admission_date = date("Y-m-d H:i:s");
            $person->save();

            add_log("Debug", $_SERVER["REMOTE_ADDR"], $name. "さんが会員登録を完了させました". "//Linebot/sign_up()");

            return "GeyamaWebへの会員登録ありがとうございます！！". $name. "さん！";
        }catch(Exception $e){
            add_log("Error", $_SERVER["REMOTE_ADDR"], "データベース接続エラー||".$e. "//Linebot/sign_up()");

            return  "エラーが発生しました。再度登録をお願いいたします";
        }
    }

    public function join_practice($data){
        $line_id = $data["source"]["userId"];
        $profile = $this->get_profile($line_id);
        $name = $profile['name'];

        $practice = ORM::for_table('plan')
                ->where(array(
                    'status' => "1",
                ))
                ->find_one();

        $practice_id = $practice["id"];
        $practice_member = $practice["member"];
        $practice_date = $practice["date"];
        $practice_time = $practice["time"];

        if(isset($practice_id)){
            if(!in_array($line_id, explode(".", $practice_member))){
                ORM::for_table('plan')->where(array("id" => $practice_id))->find_result_set()
                ->set('member', $line_id. ".". $practice_member)
                ->save();
        
                return $name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加を確認しました";
            }else{
                return $name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加を確認しました";
            }
        }else{
            return "現在募集中の練習はありません";
        }        
    }

    public function cancel_practice($data){
        $line_id = $data["source"]["userId"];
        $profile = $this->get_profile($line_id);
        $name = $profile['name'];
        
        $practice = ORM::for_table('plan')
                ->where(array(
                    'status' => "1",
                ))
                ->find_one();

        $practice_id = $practice["id"];
        $practice_member = $practice["member"];
        $practice_date = $practice["date"];
        $practice_time = $practice["time"];

        if(isset($practice_id)){
            $practice_member_list = explode(".", $practice_member);
    
            if(in_array($line_id, $practice_member_list)){
                $practice_member_list = array_diff($practice_member_list, array($line_id));
                $practice_member_list = array_values($practice_member_list);
    
                $practice_member = implode(".", $practice_member_list);
    
                ORM::for_table('plan')->where(array("id" => $practice_id))->find_result_set()
                ->set('member', $practice_member)
                ->save();
        
                return $name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加のキャンセルを確認しました";
            }else{
                return $name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加のキャンセルを確認しました";
            }
        }else{    
            return "現在募集中の練習はありません";
        }

    }
}
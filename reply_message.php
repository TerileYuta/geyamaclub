<?php
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\Constant\HTTPHeader;

require __DIR__."/vendor/autoload.php";
require "./config.php";
require "./log/add_log.php";
require "./vendor/autoload.php";

Dotenv\Dotenv::createImmutable(__DIR__)->load();

define('TOKEN', $_ENV["ACCESS_TOKEN"]);
define('SECRET', $_ENV["CHANNEL_SECLET"]);

$jsonAry = json_decode(file_get_contents('php://input'), true);

$type = $jsonAry['events'][0]['type'];
$replyToken = $jsonAry['events'][0]['replyToken'];
$data = $jsonAry['events'][0];

$reply_message = main($type, $data);

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(TOKEN);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => SECRET]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($reply_message);
$response = $bot->replyMessage($replyToken, $textMessageBuilder);

function main($type, $data){
    $line_id = $data["source"]["userId"];
    $group_id = $data["source"]["groupId"];

    $reply = new Reply($line_id, $group_id);
    $text = "";

    if($type == 'message'){
        $user_message = $data['message']["text"];

        switch($user_message){
            case "！新規会員登録":
                $text = $reply->sign_up();
                
                break;

            case "！行きます":
                $text = $reply->join_practice();

                break;
            
            case "！キャンセル":
                $text = $reply->cancel_practice($data);

                break;
        }
    }

    return $text;
}

class Reply{
    public $line_id;
    public $group_id;
    public $name;

    public function __construct($line_id, $group_id){
        $this->line_id = $line_id;
        $this->group_id = $group_id;

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(TOKEN);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => SECRET]);

        $response = $bot->getGroupMemberProfile($group_id, $line_id);
        if ($response->isSucceeded()) {
            $profile = $response->getJSONDecodedBody();
            $name = $profile['displayName'] || "";
        }

        $this->name = $name;
    }

    private function get_practice(){
        $practice = ORM::for_table('plan')
        ->where(array(
            'status' => "1",
        ))
        ->find_one();

        return array("id" => $practice['id'], "member" => $practice['member'], "date" => $practice['date'], "time" => $practice["time"]);
    }

    public function sign_up(){
        try{
            $person = ORM::for_table('member')->create();
            $person->name = $this->name;
            $person->line_id = $this->name;
            $person->pic_url = "";
            $person->admission_date = date("Y-m-d H:i:s");
            $person->save();

            add_log("main", $this->name. "さんが会員登録を完了させました");

            return "GeyamaWebへの会員登録ありがとうございます!!". $this->name. "さん！";
        }catch(Exception $e){
            add_log("error", "データベース接続エラー || ".$e);

            return  "エラーが発生しました。再度登録をお願いいたします";
        }
    }

    public function join_practice(){
        $practice = $this->get_practice();

        $practice_id = $practice["id"];
        $practice_member = $practice["member"];
        $practice_date = $practice["date"];
        $practice_time = $practice["time"];

        if(isset($practice_id)){
            if(!in_array($this->line_id, explode(".", $practice_member))){
                ORM::for_table('plan')->where(array("id" => $practice_id))->find_result_set()
                ->set('member', $this->line_id. ".". $practice_member)
                ->save();
        
                add_log("main", $this->name. "さんの参加を確認しました");

                return $this->name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加を確認しました";
            }else{
                return $this->name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加を確認しました";
            }
        }else{
            return "現在募集中の練習はありません";
        }        
    }

    public function cancel_practice(){        
        $practice = $this->get_practice();

        $practice_id = $practice["id"];
        $practice_member = $practice["member"];
        $practice_date = $practice["date"];
        $practice_time = $practice["time"];

        if(isset($practice_id)){
            $practice_member_list = explode(".", $practice_member);
    
            if(in_array($this->line_id, $practice_member_list)){
                $practice_member_list = array_diff($practice_member_list, array($this->line_id));
                $practice_member_list = array_values($practice_member_list);
    
                $practice_member = implode(".", $practice_member_list);
    
                ORM::for_table('plan')->where(array("id" => $practice_id))->find_result_set()
                ->set('member', $practice_member)
                ->save();

                add_log("main", $this->name. "さんの参加キャンセルを確認しました");
        
                return $this->name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加のキャンセルを確認しました";
            }else{
                return $this->name. "さんの". date("Y年n月j日", strtotime($practice_date)). "(". $practice_time. ")の練習への参加のキャンセルを確認しました";
            }
        }else{    
            return "現在募集中の練習はありません";
        }

    }
}
?>
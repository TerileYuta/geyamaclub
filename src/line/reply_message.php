<?php
class Reply{
    private $type;
    private $replyToken;
    private $data;
    private $token;
    private $secret;

    private $bot;

    private $line_id;
    private $group_id;
    private $name;
    private $user_message;


    public function __construct($token, $secret){
        $this->token = $token;
        $this->secret = $secret;

        $httpClient = new LINE\LINEBot\HTTPClient\CurlHTTPClient($this->token);
        $this->bot = new LINE\LINEBot($httpClient, ['channelSecret' => $this->secret]);

        $jsonAry = json_decode(file_get_contents('php://input'), true);

        if($jsonAry == null) {
            return;
        }else{
            $this->type = $jsonAry['events'][0]['type'];
            $this->replyToken = $jsonAry['events'][0]['replyToken'];
    
            $this->data = $jsonAry['events'][0];
    
            $this->line_id = $this->data["source"]["userId"];
            $this->group_id = $this->data["source"]["groupId"];
    
            $response = $this->bot->getGroupMemberProfile($this->group_id, $this->line_id);
            $name = "Anonymouse";
            if ($response->isSucceeded()) {
                $profile = $response->getJSONDecodedBody();
                $name = $profile['displayName'];
            }

            $person = ORM::for_table('member')
            ->where(array(
                'line_id' => $this->line_id,
            ))
            ->find_one();
                
            $this->name = $name;
            $this->user_message = $this->data['message']["text"];     

            if(!$person){
                $this->sign_up();
            }
        }

    }

    public function send_message($text){
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);
        $response = $this->bot->replyMessage($this->replyToken, $textMessageBuilder);
        if ($response->isSucceeded()) {
            return '200';
        }else{
            return '500';
        }
    }

    public function main(){
        $text = "";

        if($this->type == 'message'){    
            switch($this->user_message){
                case "?????????????????????":
                    $text = $this->sign_up();
                    
                    break;
    
                case "???????????????":
                    $text = $this->join_practice();
    
                    break;
                
                case "??????????????????":
                    $text = $this->cancel_practice();
    
                    break;
                
                case "???????????????":
                    $text = $this->group_id;
    
                    break;
            }
        }
    
        return $text;
    }

    private function get_practice(){
        $practice = ORM::for_table('plan')
        ->where(array(
            'status' => "1",
        ))
        ->find_one();

        return array("id" => $practice['id'], "member" => $practice['member'], "date" => $practice['date'], "time" => $practice["time"]);
    }

    private function sign_up(){
        try{
            $person = ORM::for_table('member')->create();
            $person->name = $this->name;
            $person->line_id = $this->line_id;
            $person->pic_url = "";
            $person->admission_date = date("Y-m-d H:i:s");
            $person->save();

            return "GeyamaWeb????????????????????????????????????????????????!!". $this->name. "?????????";
        }catch(Exception $e){
            return  "????????????????????????????????????????????????????????????????????????";
        }
    }

    private function join_practice(){
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

                return $this->name. "?????????". date("Y???n???j???", strtotime($practice_date)). "(". $practice_time. ")??????????????????????????????????????????";
            }else{
                return $this->name. "?????????". date("Y???n???j???", strtotime($practice_date)). "(". $practice_time. ")??????????????????????????????????????????";
            }
        }else{
            return "??????????????????????????????????????????";
        }        
    }

    private function cancel_practice(){        
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
        
                return $this->name. "?????????". date("Y???n???j???", strtotime($practice_date)). "(". $practice_time. ")????????????????????????????????????????????????????????????";
            }else{
                return $this->name. "?????????". date("Y???n???j???", strtotime($practice_date)). "(". $practice_time. ")????????????????????????????????????????????????????????????";
            }
        }else{    
            return "??????????????????????????????????????????";
        }

    }
}
?>
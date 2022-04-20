<?php
    class Push{
        private $token;
        private $secret;
        private $group_id;

        private $message;
        private $status;
        private $practice_id;

        public function __construct($token, $secret, $group_id){
            $this->token = $token;
            $this->secret = $secret;
            $this->group_id = $group_id;

            $this->message = $_POST["message"];
            $this->status = $_POST["status"];
            $this->practice_id = $_POST["id"];
        }

        public function push_message(){
            if($this->status == "1"){
                $practice = ORM::for_table('plan')->where("id", $this->practice_id)->find_many();
                $practice[0] -> set("status", '1');
                $practice[0] -> save();
            }else{
                $practice = ORM::for_table('plan')->where("id", $this->practice_id)->find_one();
                $practice -> status = "0";
                $practice  -> save();
            }
        
            $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($this->token);
            $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $this->secret]);
        
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($this->message);
            $response = $bot->pushMessage($this->group_id, $textMessageBuilder);
        }
    }
?>
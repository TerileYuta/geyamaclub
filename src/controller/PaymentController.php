<?php
    class Payment{
        public function index(){
            $payments_list = ORM::for_table('payments')->order_by_desc("id")->find_many();


            $latest_id = $payments_list[0]["id"];
            $payments = array();
            $date = array();
            foreach($payments_list as $payment){
                $list = array(
                    "id" => $payment["id"],
                    "name" => $payment['name'],
                    "balance" => $payment['balance'],
                    "action" => $payment['action'],
                    "type" => $payment['type'],
                    "memo" => $payment['memo'],
                    "practice_id" => $payment['practice_id'],
                    "insert_at" => $payment['update_date']
                );
    
                $id = $payment['id'];
                $month = date("Y-m", strtotime($payment["update_date"]));
    
                if(isset($date[$month])){
                    $date_list = $date[$month];
                }else{
                    $date_list = array();
                }
    
                array_push($date_list, $id);
                $date[$month] = $date_list;

                $payments[$id] = $list;
            }

            krsort($payments);

            echo json_encode(array($payments, $latest_id, $date));
        }

        public function settlement(){
            $name = $_POST['name'];
            $type = $_POST['type'];
            $action = intval($_POST['action']);

            $max_id = ORM::for_table('payments')->max("id");
            $balance = ORM::for_table('payments')->where(array('id' => $max_id))->find_one();
            $balance = $balance["balance"];

            if($action > 0){
                echo $balance;
                $balance += $action;
                echo $action;
                $action = "+". $action;
            }else{
                $balance -= $action;
            }

            $new_settlement = ORM::for_table('payments')->create();
            $new_settlement -> practice_id = 0;
            $new_settlement -> name = $name;
            $new_settlement -> type = $type;
            $new_settlement -> action = $action;
            $new_settlement -> balance = $balance;
            $new_settlement -> save();

            echo "200";
        }
    }
?>
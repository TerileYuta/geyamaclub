<?php
    class Practice{
        public function index(){
            $today = date("Y-m-d H:i:s");
    
            $today = date("Y-m-d H:i:s");
    
            try{
                $plan_list = ORM::for_table('plan')->find_many();
                $plan_date = array();
                
                foreach($plan_list as $plan){
                    array_push($plan_date, $plan["date"]);
                }
        
                $max_id = ORM::for_table('payments')->max('id'); 
                $balance = ORM::for_table('payments')->where(array("id" => $max_id))->find_one();
                $balance = $balance["balance"];
                $all_member_number = count(ORM::for_table('member')->find_result_set());      
                
                echo json_encode(array('plan_date' => $plan_date, 'balance' => $balance, 'all_member_numbers' => $all_member_number));
            }catch(Exception $e){
                
            }
        }

        public function get_data(){
            $date = $_POST['date']; 

            $plan = ORM::for_table('plan')->where(array("date" => $date))->find_one();

            $join_member_list = explode(".", $plan["member"]);
            array_pop($join_member_list);
            $member_list = array();

            foreach ($join_member_list as $member){
                $member_info = ORM::for_table('member')->where('line_id', $member)->find_one();

                $total_practice = $records = (int) ORM::for_table('plan')->where_gt('date', $member_info["admission_date"])->count();

                array_push($member_list ,array("id" => $member_info["id"],
                                        "name" => $member_info["name"],
                                        "attenance" => $member_info["attendance_number"],
                                        "total_practice" => $total_practice,
                                        "attendance_parcent" => strval(round(((int)$member_info["attendance_number"] / $total_practice) * 100)). "%",
                                        "last_entry" => $member_info["last_entry_date"],
                                        "join_at" => $member_info["admission_date"],
                                        "memo" => $member_info["memo"]));
            }

            $plan = array(
                'id' => $plan["id"],
                'title' => $plan["title"],
                "natural_date" => date("Y-m-d", strtotime($plan["date"])),
                "date" => date("Y年n月j日", strtotime($plan["date"])),
                "time" => $plan["time"],
                "place" => $plan["place"],
                "status" => $plan["status"],
                "member" => $member_list,
                "fee" => $plan["fee"],
                "income_other" => $plan["income_other"],
                "gym" => $plan["gym"],
                "shattle" => $plan["shattle"],
                "other" => $plan["other"],
                "settlement_id" => $plan["settlement_id"],
                "memo" => $plan["memo"],
                );

            echo json_encode($plan);
        }

        public function settlement(){
            $title = $_POST["title"];
            $date = $_POST['date'];
            $practice_id = $_POST['practice_id'];
            $fee = (int) $_POST['fee'];
            $income_other = (int) $_POST['income_other'];
            $income_other_memo = $_POST['income_other_memo'];
            $gym = (int) $_POST['gym'];
            $shattle = (int) $_POST['shattle'];
            $other = (int) $_POST['other'];
            $other_memo = $_POST['other_memo'];

            $max_id = ORM::for_table('payments')->max("id");
            $balance = ORM::for_table('payments')->where(array('id' => $max_id))->find_one();
            $balance = $balance["balance"];

            $plan = ORM::for_table("plan")->where(array("id" => $practice_id))->find_one();
            $plan->settlement_id = $practice_id;
            $plan->fee = $fee;
            $plan->income_other = $income_other;
            $plan->gym = $gym;
            $plan->shattle = $shattle;
            $plan->other = $other;
            $plan->save();

            
            if($fee > 0){
                $balance += $fee;
                $settlement = ORM::for_table("payments")->create();
                $settlement->practice_id = $practice_id;
                $settlement->name = $title;
                $settlement->action = "+". $fee;
                $settlement->balance = $balance;
                $settlement->type = "参加費";
                $settlement->save();
            }

            if($income_other > 0){       
                $balance += $income_other;
                $settlement = ORM::for_table("payments")->create();
                $settlement->practice_id = $practice_id;
                $settlement->name = $title;
                $settlement->action = "+". $income_other;
                $settlement->balance = $balance;
                $settlement->type = "その他の収入";
                $settlement-> memo = $income_other_memo;
                $settlement->save();
            }

            if($gym > 0){ 
                $balance -= $gym;
                $settlement = ORM::for_table("payments")->create();
                $settlement->practice_id = $practice_id;
                $settlement->name = $title;
                $settlement->action = "-". $gym;
                $settlement->balance = $balance;
                $settlement->type = "体育館代";
                $settlement->save();
            }

            if($shattle > 0){
                $balance -= $shattle;
                $settlement = ORM::for_table("payments")->create();
                $settlement->practice_id = $practice_id;
                $settlement->name = $title;
                $settlement->action = "-". $shattle;
                $settlement->balance = $balance;
                $settlement->type = "シャトル代";
                $settlement->save();
            }

            if($other > 0){
                $balance -= $other;
                $settlement = ORM::for_table("payments")->create();
                $settlement->practice_id = $practice_id;
                $settlement->name = $title;
                $settlement->action = "-". $other;
                $settlement->balance = $balance;
                $settlement->type = "その他の支出";
                $settlement-> memo = $other_memo;
                $settlement->save();
            
                echo $practice_id;
            }

            ORM::for_table('plan')->find_result_set()
            ->where(array("date" => $date))
            ->set('status', "Not Recruited")
            ->save();
            
            $plan = ORM::for_table('plan')->where(array("date" => $date))->find_one();
            $join_member_list = explode(".", $plan["member"]);
            array_shift($join_member_list);

            foreach($join_member_list as $name){
                $person = ORM::for_table('member')->where(array('name' => $name))->find_one();
                ORM::for_table('member')->find_result_set()
                ->where(array("name" => $name))
                ->set('attendance_number', $person["attendance_number"] + 1)
                ->set('last_entry_date', $date)
                ->save();
            }

            echo $practice_id;
        }

        public function save_memo(){
            $memo = $_POST["memo"];
            $practice_id = $_POST["practice_id"];
            
            $plan = ORM::for_table("plan")->where("id", $practice_id)->find_one();
            $plan->memo = $memo;
            $plan->save();

            echo "200";
        }

        public function new_member(){
            $name = $_POST['name'];

            $member_list = ORM::for_table("member")->where_like("name","%". $name. "%")->find_many();
        
            $res_list = array(); 
        
            foreach ($member_list as $member){
                array_push($res_list, array("id" => $member->id, "name" => $member->name));
            }
        
            echo json_encode($res_list);
        }

        public function add_member(){
            $user_id = $_POST['user_id'];
            $id = $_POST['id'];
        
            $new_member = ORM::for_table("member")->where("id", $user_id)->find_one();
            $line_id = $new_member->line_id;
        
            $practice = ORM::for_table("plan")->where("id", $id)->find_one();
            $practice_member = $practice["member"];
        
            if($practice_member == ""){
                ORM::for_table('plan')->where("id", $id)->find_result_set()
                ->set('member', $line_id. ".")
                ->save();
            }else{
                if(!in_array($line_id, explode(".", $practice_member))){
                    ORM::for_table('plan')->where("id", $id)->find_result_set()
                    ->set('member', $line_id. ".". $practice_member)
                    ->save();
                }
            }
        }

        public function delete_member(){
            $user_id = $_POST['user_id'];
            $id = $_POST['id'];
        
            $new_member = ORM::for_table("member")->where("id", $user_id)->find_one();
            $line_id = $new_member->line_id;
        
            $practice = ORM::for_table("plan")->where("id", $id)->find_one();
            $practice_member = $practice["member"];
        
            $practice_member_list = explode(".", $practice_member);
            
            if(in_array($line_id, $practice_member_list)){
                $practice_member_list = array_diff($practice_member_list, array($line_id));
                $practice_member_list = array_values($practice_member_list);
        
                $practice_member = implode(".", $practice_member_list);
        
                ORM::for_table('plan')->where(array("id" => $id))->find_result_set()
                ->set('member', $practice_member)
                ->save();
            }
        }
    }
?>
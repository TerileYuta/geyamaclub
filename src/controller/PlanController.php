<?php
    class Plan{
        public function index(){
            try{
                $plan_db = ORM::for_table('plan')->find_many();
                $plan_list = array();
                $date_list = array();
                $index = 0;
    
                foreach ($plan_db as $plan) {
                    $list = array(
                    "id" => $plan["date"],
                    "title" => $plan["title"],
                    "start" => $plan["date"],
                    "time" => $plan["time"],
                    "practice_id" => $plan["id"],
                    );
    
                    array_push($plan_list, $list);
                    $date_list[$plan["date"]] = $index;
    
                    $index ++;
                }
    
                echo json_encode(array($plan_list, $date_list));
            }catch(Exception $e){
                echo "error";
                exit;
            }
        }

        public function add_event(){
            try{
                $title = $_POST["title"];
                $date = $_POST["date"];
                $start_time = $_POST["start_time"];
                $end_time = $_POST["end_time"];
                $place = $_POST["place"];
            
                $add_event = ORM::for_table("plan")->create();
                $add_event -> title = $title;
                $add_event -> date = $date;
                $add_event -> time = $start_time. "-". $end_time;
                $add_event -> place = $place;
                $add_event -> save();
            }catch(Exception $e){
                echo "error";
                exit;
            }
        
        }

        public function delete_event(){
            try{
                $id = $_POST['id'];
    
                $plan = ORM::for_table('plan')->where('id', $id)->find_one();
                $plan -> delete();
    
            }catch(Exception $e){
                echo "error";
                exit;
            }
        }
    }
?>
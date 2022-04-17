<?php
    class Member{
        public function index(){
            $all_member = ORM::for_table('member')->find_many();
            $member_list = array();
    
            foreach($all_member as $member){
                $total_practice = $records = ORM::for_table('plan')->where_gt('date', $member["admission_date"])->count();
    
                if(($member["attendance_number"] != 0) && ($total_practice != 0)){
                    $join_parcent = round(($member["attendance_number"] / $total_practice) * 100);
                }else{
                    $join_parcent = "-";
                }
                
                $member_info = array("id" => $member["id"],
                                    "name" => $member["name"],
                                    "join_number" => $member["attendance_number"],
                                    "last_join" => $member["last_entry_date"],
                                    "join_at" => $member["admission_date"],
                                    "total_practice" => $total_practice,
                                    "join_parcent" => $join_parcent);
    
                array_push($member_list, $member_info);
            }
            
            echo json_encode($member_list);
        }
    }
?>
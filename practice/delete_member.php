<?php 
    require "../config.php";

    $name = $_POST['name'];
    $id = $_POST['id'];

    $new_member = ORM::for_table("member")->where("name", $name)->find_one();
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
?>
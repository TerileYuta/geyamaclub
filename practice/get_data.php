<?php
    require "../config.php";

    header("Content-Type: application/json; charset=utf-8");

    $date = $_POST['date']; 

    $plan = ORM::for_table('plan')->where(array("date" => $date))->find_one();

    $join_member_list = explode(".", $plan["member"]);
    array_pop($join_member_list);
    $member_list = array();

    foreach ($join_member_list as $member){
        $member_info = ORM::for_table('member')->where('line_id', $member)->find_one();

        $total_practice = $records = (int)ORM::for_table('plan')->where_gt('date', $member_info["admission_date"])->count();

        $member_list[$member_info["id"]] = array("id" => $member_info["id"],
                                            "name" => $member_info["name"],
                                            "attenance" => $member_info["attendance_number"],
                                            "total_practice" => $total_practice,
                                            "attendance_parcent" => strval(((int)$member_info["attendance_number"] / $total_practice) * 100). "%",
                                            "last_entry" => $member_info["last_entry_date"],
                                            "join_at" => $member_info["admission_date"],
                                            "memo" => $member_info["memo"]);
    }

    $plan = array(
        'id' => $plan["id"],
        'title' => $plan["title"],
        "natural_date" => date("Y-m-d", strtotime($plan["date"])),
        "date" => date("Y年n月j日", strtotime($plan["date"])). "(". $plan["time"]. ")",
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
?>
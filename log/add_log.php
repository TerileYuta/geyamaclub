<?php
    function add_log($type, $ip, $str){
        $now = date("Y-m-d H:i:s");
        $log = $now. " [". $type. "] (". $ip. ") ". " : ". $str. "\n"; 
        file_put_contents("./log.txt", $log, FILE_APPEND);
    }

?>
<?php
    function add_log($type, $str){
        $now = date("Y-m-d H:i:s");
        $log = "[". $now. "] (". $type. ")". " : ". $str. "\n"; 
        file_put_contents("../log/log.txt", $log, FILE_APPEND);
    }

?>
<?php   
    header("Content-type: text/html; charset=utf-8");

    session_start();

    //ファイル読み込み
    require_once('./vendor/autoload.php');
    require_once('./src/controller/HomeController.php');
    require_once('./src/controller/PracticeController.php');
    require_once('./src/controller/PlanController.php');
    require_once('./src/controller/PaymentController.php');
    require_once('./src/controller/MemberController.php');
    require_once("./config.php");

    //環境変数
    Dotenv\Dotenv::createImmutable(__DIR__)->load();

    define('ACCESS_TOKEN', $_ENV['ACCESS_TOKEN']);
    define('CHANNEL_SECLET', $_ENV['CHANNEL_SECLET']);
    define('PASSWORD', $_ENV['PASSWORD']);
    define('GROUP_ID', $_ENV['GROUP_ID']);
    define('DB_USER', $_ENV['DB_USER']);
    define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

    //インスタンスの作成
    $router = new AltoRouter();
    $router->setBasePath("/geyamaclub");

    $home = new Home(PASSWORD);
    $practice = new Practice();
    $plan = new Plan();
    $payment = new Payment();
    $member = new Member();

    if($home->login()){        
        $router->map('GET | POST','/dashboard', function(){
            require './view/index.html';
        }, "home");

        $router->map('GET | POST','/practice/index', function(){
            global $practice;
    
            $practice->index();
        }, "practice_index");
        
        $router->map('GET | POST','/practice/get_data', function(){
            global $practice;
    
            $practice->get_data();
        }, "practice_get_data");
        
        $router->map('GET | POST','/practice/settlement', function(){
            global $practice;
    
            $practice->settlement();
        }, "settlement");
        
        $router->map('GET | POST','/practice/save_memo', function(){
            global $practice;
    
            $practice->save_memo();
        }, "save_memo");
        
        $router->map('GET | POST','/practice/new_member', function(){
            global $practice;
    
            $practice->new_member();
        }, "new_member");
        
        $router->map('GET | POST','/practice/add_member', function(){
            global $practice;
    
            $practice->add_member();
        }, "add_member");
        
        $router->map('GET | POST','/practice/delete_member', function(){
            global $practice;
    
            $practice->delete_member();
        }, "delete_member");
        
        $router->map('GET | POST','/plan/index', function(){
            global $plan;
    
            $plan->index();
        }, "plan_index");
        
        $router->map('GET | POST','/plan/add_event', function(){
            global $plan;
    
            $plan->add_event();
        }, "add_event");
        
        $router->map('GET | POST','/plan/delete_event', function(){
            global $plan;
    
            $plan->delete_event();
        }, "delete_event");
        
        $router->map('GET | POST','/payment/index', function(){
            global $payment;
    
            $payment->index();
        }, "payment");
        
        $router->map('GET | POST','/payment/settlement', function(){
            global $payment;
    
            $payment->settlement();
        }, "payment_settlement");
        
        $router->map('GET | POST','/member/index', function(){
            global $member;
    
            $member->index();
        }, "member");
    }else{
        require './view/login.php';
    }

    $match = $router->match();

    if( is_array($match) && is_callable( $match['target'] ) ) {
        call_user_func_array( $match['target'], $match['params'] ); 
    } else {
        // no route was matched
        header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
?>  

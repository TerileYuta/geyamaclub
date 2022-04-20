<?php
    require_once '../idiorm.php';
    require "../vendor/autoload.php";

    Dotenv\Dotenv::createImmutable(__DIR__)->load();

    ORM::configure('mysql:host=localhost;dbname=geyamaclub');
    ORM::configure('username', $_ENV["DB_USER"]);
    ORM::configure('password', $_ENV["DB_PASSWORD"]);
    ORM::configure('driver_options', [
        PDO::MYSQL_ATTR_INIT_COMMAND       => 'SET NAMES utf8',
        PDO::ATTR_EMULATE_PREPARES         => false,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    ]);
?>
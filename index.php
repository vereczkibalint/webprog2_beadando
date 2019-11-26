<?php
define('APP_MODE', 'DEBUG');

include_once 'config.php';
include_once 'functions.php';

$page = isset($_GET["p"]) ? $_GET["p"] : 'home';

$db = db_connect();
$userdata = isset($_SESSION["user_id"]) ? fetch_user($_SESSION["user_id"]) : null;

if(file_exists("views/{$page}.php")){
    include_once "views/{$page}.php";
}else{
    include_once "views/404.php";
}
db_close();
?>
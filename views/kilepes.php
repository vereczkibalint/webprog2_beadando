<?php if(!defined("APP_MODE")) { exit; } ?>
<?php
include_once '_header.php';
if(isset($_SESSION["user_id"])){
    session_unset();
    session_destroy();
    redirect('home');
}else{
    redirect('belepes');
}
include_once '_footer.php';
?>

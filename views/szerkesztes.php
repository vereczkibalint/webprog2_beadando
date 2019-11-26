<?php if(!defined("APP_MODE")) { exit; } ?>
<?php
if(!loggedin()){
    redirect("home");
}
if(!isset($_GET["code_id"])){
    redirect("home");
}
?>
<?php
$code = get_code_by_id($_GET["code_id"]);
if($code == null){
    redirect('404');
}
include_once '_header.php';
?>
<div class="container">
<?php
if(isset($_GET['success'])):
?>
<div class="alert alert-success">
    Sikeres szerkesztés!
</div>
<?php endif; ?>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $errors = [];

    $code_id = $_GET["code_id"];
    $code_title = trim($_POST["code_title"]);
    $code_description = trim($_POST["code_description"]);
    $currDate = date("Y-m-d");

    if($code_title == null){
        $errors['code_title'][] = "Kötelező megadni a kód nevét!";
    }else{
        $min_code_title_length = 4;
        if(strlen($code_title) < $min_code_title_length){
            $errors['code_title'][] = "A kód neve legalább {$min_code_title_length} karakterből kell álljon!";
        }else{
            $max_code_title_length = 255;
            if(strlen($code_title) > $max_code_title_length){
                $errors['code_title'][] = "A kód neve legfeljebb {$min_code_title_length} karakterből állhat!";
            }
        }
    }
    
    if($code_description == null){
        $errors['code_description'][] = "Kötelező megadni a kód leírását!";
    }else{
        $min_code_description_length = 10;
        if(strlen($code_description) < $min_code_description_length){
            $errors['code_description'][] = "A kód leírása legalább {$min_code_description_length} karakterből kell álljon!";
        }
    }

    if(count($errors) == 0){
        $query = "UPDATE codes SET code_title = ? , code_description = ? WHERE code_id = ?";
        $sql = $db->prepare($query);
        $sql->bind_param("ssi", $code_title, $code_description, $code_id);
        $sql->execute();
        $sql->close();

        db_close();
        
        echo redirect('sajat_repok', ["edit_success" => "1"]);
    }
}
?>
    <form class="text-center" id="szerkesztes_form" action="<?php echo url('szerkesztes', ['code_id'=>$code['code_id']]); ?>" method="POST" enctype="multipart/form-data">
            <p class="h2 mb-4">Repo szerkesztése</p>
            <div id="edit-response"></div>
            <div class="form-group">
                <input type="text" id="text" class="form-control mb-4" placeholder="Repo neve" name="code_title" value="<?php echo $code['code_title']; ?>">
                <?php echo display_errors('code_title'); ?>
            </div>

            <div class="form-group">
                <textarea name="code_description" value="" placeholder="Repo leírása" class="form-control mb-4 disable-horizontal" rows="10"><?php echo $code['code_description']; ?></textarea>
                <?php echo display_errors('code_description'); ?>
            </div>

            <button class="btn btn-info btn-block my-4 btn_szerkeszt" type="submit" id="edit_code">Küldés</button>
        </form>
    </div>
<?php
include_once '_footer.php';
?>
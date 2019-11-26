<?php if(!defined("APP_MODE")) { exit; } ?>
<?php
if(!loggedin()){
    redirect('belepes');
}
?>
<?php
include_once '_header.php';
?>
<div class="container">
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $errors = [];
        
            $code_title = trim($_POST["code_title"]);
            $code_description = trim($_POST["code_description"]);
            $code_file = $_FILES["code_file"];
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
            if(!isset($code_file) || $code_file["size"] <= 0){
                $errors["code_file"][] = "Forráskód feltöltése kötelező!";
            }else{
                $code_target_dir = BASE_PATH . '/codes/';
                $code_target_filename = $currDate . "_" . $userdata["username"] . "_" . basename($code_file["name"]);
                $code_target_file = $code_target_dir . $code_target_filename;
            
                $codeExt = strtolower(pathinfo($code_target_file, PATHINFO_EXTENSION));
            
                if(file_exists($code_target_file)){
                    $errors["code_file"][] = "A fájl már létezik!";
                }else{
                    if($code_file["size"] > (MAX_UPLOAD_SIZE * 1000000)){
                        $errors["code_file"][] = "Maximum méret: {MAX_UPLOAD_SIZE}MB!";
                    }else{
                        $code_allowed_exts = ["zip"];
            
                        if(!in_array($codeExt, $code_allowed_exts)){
                            $errors["code_file"][] = "Nem engedélyezett fájltípus! Engedélyezett típusok: " . implode(", ", $code_allowed_exts);
                        }
                    }
                }
            }

            if(count($errors) == 0){
                if(move_uploaded_file($code_file["tmp_name"], $code_target_file)){
                    $currDate = date("Y-m-d");
                    $sql = $db->prepare("INSERT INTO codes (user_id, code_title, code_description, code_path, code_uploaded_at) VALUES(?,?,?,?,?)");
                    $sql->bind_param("issss", $_SESSION["user_id"], $code_title, $code_description, $code_target_filename, $currDate);
                    $sql->execute();
                    $sql->close();
        
                    db_close();
        
                    redirect("sajat_repok", ["upload_success" => "1"]);
                }else{
                    echo display_error("Sikertelen kódfeltöltés!");
                }
            }
        }
    ?>
    <form class="text-center" id="letrehozas_form" action="<?php echo url('letrehozas'); ?>" method="POST" enctype="multipart/form-data">
        <p class="h2 mb-4">Repo feltöltése</p>
        <div class="form-group">
            <input type="text" id="text" class="form-control mb-4" placeholder="Repo neve" name="code_title" value="<?php echo isset($code_title) ? $code_title : ''; ?>">
            <?php echo display_errors('code_title'); ?>
        </div>

        <div class="form-group">
            <textarea name="code_description" value="" placeholder="Repo leírása" class="form-control mb-4 disable-horizontal" rows="10"><?php echo isset($code_description) ? $code_description : ''; ?></textarea>
            <?php echo display_errors('code_description'); ?>
        </div>

        <div class="form-group">
            <label for="code_file">Forráskód:</label>
            <input type="file" id="code_file" name="code_file">
            <?php echo display_errors('code_file'); ?>
        </div>

        <button class="btn btn-info btn-block my-4 btn_letrehozas" type="submit" id="upload_code">Feltöltés</button>
    </form>
</div>
<?php
include_once '_footer.php';
?>
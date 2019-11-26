<?php if(!defined("APP_MODE")) { exit; } ?>
<?php

$code_id = isset($_GET["code_id"]) ? $_GET["code_id"] : null;

if($code_id == null){
    db_close();
    redirect('404');
}

$code = get_code_by_id($code_id);

if($code == null){
    db_close();
    redirect('404');
}

$errors = [];

$code_title = trim($_POST["code_title"]);
$code_description = trim($_POST["code_description"]);
$code_cover = $_FILES["code_cover"];
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
{
    $sql = $db->prepare("UPDATE codes SET code_title, code_description WHERE code_id = ?");
    $sql->bind_param("ssi", $code['code_title'], $code['code_description'], $code_id);
    $sql->execute();
    $sql->close();

    db_close();

    echo redirect("?success=1");
}
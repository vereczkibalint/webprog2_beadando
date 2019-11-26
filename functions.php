<?php if(!defined('APP_MODE')){ exit; } ?>
<?php

function db_connect(){
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($connection->connect_error){
        die("Adatbázis kapcsolódási hiba: (" . $connection->connect_error.")");
    }

    $connection->set_charset("utf-8");

    return $connection;
}

function db_close(){
    global $db;

    $db->close();
}

function url($page = 'home', $params = []){
    $url = DOMAIN."?p={$page}";
    if(is_array($params)){
        foreach($params as $param => $value){
            $url .= "&$param=$value";
        }
    }

    return $url;
}

function redirect($page = 'home', $params = []){
    $url = url($page, $params);
    header("Location: $url");
    die();
}

function asset($asset){
    return DOMAIN.$asset;
}

function display_errors($key){
    global $errors;

    $html = "";

    if(isset($errors[$key])){
        foreach($errors[$key] as $error){
            $html .= "<div class='alert alert-danger'>$error</div>";
        }
    }

    return $html;
}

function display_success($message){
    $html = "<div class='alert alert-success mt-2 w-50 mx-auto text-center'>$message</div>";

    return $html;
}

function display_error($message){
    $html = "<div class='alert alert-danger mt-2 w-50 mx-auto text-center'>$message</div>";

    return $html;
}

function fetch_user($userid){
    global $db;

    $sql = $db->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
    $sql->bind_param("s", $userid);
    $sql->execute();

    $result = $sql->get_result();

    if($result->num_rows == 1){
        return $result->fetch_assoc();
    }
}

function loggedin(){
    return isset($_SESSION["user_id"]);
}

function isset_username($username){
    global $db;

    $sql = $db->prepare("SELECT username FROM users WHERE username = ? LIMIT 1");
    $sql->bind_param("s", $username);
    $sql->execute();

    $result = $sql->get_result();

    return $result->num_rows == 1 ? true : false;
}

function isset_email($email){
    global $db;

    $sql = $db->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
    $sql->bind_param("s", $email);
    $sql->execute();

    $result = $sql->get_result();

    return $result->num_rows == 1 ? true : false;
}

function get_recent_codes(){
    global $db;

    $sql = $db->prepare("SELECT * FROM codes ORDER BY code_uploaded_at DESC LIMIT 5");
    $sql->execute();

    $result = $sql->get_result();

    return $result;
}

function get_all_codes(){
    global $db;

    $sql = $db->prepare("SELECT * FROM codes ORDER BY code_title ASC");
    $sql->execute();

    $result = $sql->get_result();

    return $result;
}

function get_uploader_name($userid){
    global $db;
    
    $sql = $db->prepare("SELECT username FROM users WHERE user_id = ?");
    $sql->bind_param("s", $userid);
    $sql->execute();

    $result = $sql->get_result();

    return $result->fetch_assoc();
}

function get_own_repos($userid){
    global $db;

    $sql = $db->prepare("SELECT * FROM codes WHERE user_id = ? ORDER BY code_title ASC");
    $sql->bind_param("s", $userid);
    $sql->execute();

    $result = $sql->get_result();

    return $result;
}

function delete_repo($code_id){
    global $db;

    $query = "SELECT * FROM codes WHERE code_id = ? LIMIT 1";
    $sql = $db->prepare($query);
    $sql->bind_param("s", $code_id);
    $sql->execute();

    $result = $sql->get_result();

    if($result->num_rows == 1){
        $repo_data = $result->fetch_assoc();
        if($repo_data["user_id"] == $_SESSION["user_id"]){
            $query = "DELETE FROM codes WHERE code_id = ?";
            $sql = $db->prepare($query);
            $sql->bind_param("s",$code_id);
            if($sql->execute()){
                if(file_exists("codes/{$repo_data['code_path']}")){
                    unlink("codes/{$repo_data['code_path']}");
                    redirect("sajat_repok");
                }else{
                    echo display_error("Kódfájl nem található!");
                }
            }else{ 
                echo display_error("Sikertelen kódtörlés!");
            }
        }else{
            echo display_error("Hozzáférés megtagadva!");
        }
    }else{
        echo display_error("Nem létező repo!");
    }
}

function get_code_by_id($code_id){
    global $db;
    $sql = $db->prepare("SELECT * FROM codes WHERE code_id = ? LIMIT 1");
    $sql->bind_param("s", $code_id);
    $sql->execute();
    $result = $sql->get_result();
    if($result->num_rows > 0){
        return $result->fetch_assoc();
    }else{
        return null;
    }
}
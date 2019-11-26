<?php
include_once '_header.php';
if(loggedin()){
    redirect('home');
}
?>

<div class="container">
    <?php
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["success"]) && $_GET["success"] == "1"){
        echo display_success("Sikeres regisztráció! Mostmár beléphetsz!");
    }?>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $errors = [];

            $email = trim($_POST["email"]);
            $jelszo = trim($_POST["jelszo"]);

            if($email == null){
                $errors['email'][] = "Email cím megadása kötelező!";
            }else{
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errors['email'][] = "Nem megfelelő email cím formátum!";
                }
            }

            if($jelszo == null){
                $errors['jelszo'][] = "Jelszó megadása kötelező!";
            }

            if(count($errors) == 0){
                $jelszo_hash = password_hash($jelszo, PASSWORD_DEFAULT);

                $sql = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
                $sql->bind_param("s", $email);
                $sql->execute();
                $result = $sql->get_result();

                if($result->num_rows == 1){
                    $userdata = $result->fetch_assoc();
                    if(password_verify($jelszo, $userdata["password"])){
                        // sikeres belépés, session beállítása, átirányítás
                        $_SESSION["user_id"] = $userdata["user_id"];
                        redirect('home');
                    }else{
                        $errors['login'][] = 'Helytelen jelszó!';
                    }
                }else{ 
                    $errors['login'][] = 'Sikertelen bejelentkezés!';
                }
            }

        }
    ?>
    <form class="text-center" id="login_form" action="<?php echo url('belepes'); ?>" method="POST">
        <p class="h2 mb-4">Belépés</p>
        <?php echo display_errors('login'); ?>
        <div class="form-group">
            <input type="email" id="email" class="form-control mb-4" placeholder="Email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
            <?php echo display_errors("email"); ?>
        </div>
        
        <div class="form-group">
            <input type="password" id="jelszo" class="form-control mb-4" placeholder="Jelszó" name="jelszo">
            <?php echo display_errors("jelszo"); ?>
        </div>
        
        <div class="d-flex justify-content-around">
            <div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember_me">
                    <label class="custom-control-label" for="remember_me">Emlékezz rám</label>
                </div>
            </div>
        </div>

        <button class="btn btn-info btn-block my-4 btn_login" type="submit">Belépés</button>

        <p>Nincs felhasználód?
            <a href="<?php echo url('regisztracio'); ?>">Regisztráció</a>
        </p>
    </form>
</div>

<?php
include_once '_footer.php';
?>
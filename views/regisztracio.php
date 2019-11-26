<?php
include_once '_header.php';
if(loggedin()){
    redirect('home');
}
?>

<div class="container">
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $errors = [];

            $felhasznalonev = trim($_POST["felhasznalonev"]);
            $jelszo = trim($_POST["jelszo"]);
            $jelszore = trim($_POST["jelszore"]);
            $email = trim($_POST["email"]);

            if($felhasznalonev == null){
                $errors['felhasznalonev'][] = "A felhasználónév megadása kötelező!";
            }else{
                $min_felhasznalonev_length = 3;
                if(strlen($felhasznalonev) < $min_felhasznalonev_length){
                    $errors['felhasznalonev'][] = "A felhasználónév legalább {$min_felhasznalonev_length} karakterből kell álljon!";
                }else{
                    $max_felhasznalonev_length = 50;
                    if(strlen($felhasznalonev) > $max_felhasznalonev_length){
                        $errors['felhasznalonev'][] = "A felhasználónév legfeljebb {$max_felhasznalonev_length} karakterből állhat!";
                    }else{
                        if(isset_username($felhasznalonev)){
                            $errors['felhasznalonev'][] = "Ezzel a felhasználónévvel már regisztráltak!";
                        }
                    }
                }
            }

            if($jelszo == null){
                $errors['jelszo'][] = "A jelszó megadása kötelező!";
            }else{
                $min_jelszo_length = 8;
                if(strlen($jelszo) < $min_jelszo_length){
                    $errors['jelszo'][] = "A jelszo legalább {$min_jelszo_length} karakterből kell álljon!";
                }
            }
            
            if($jelszore == null){
                $errors['jelszore'][] = "A jelszó megerősítése kötelező!";
            }else{
                if($jelszore != $jelszo){
                    $errors['jelszore'][] = "A két jelszó nem egyezik meg!";
                }
            }

            if($email == null){
                $errors['email'][] = "Az email cím megadása kötelező!";
            }else{
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errors['email'][] = "Helytelen email formátum!";
                }else{
                    if(isset_email($email)){
                        $errors['email'][] = "Ezzel az email címmel már regisztráltak!";
                    }
                }
            }

            if(count($errors) == 0){
                $jelszo_hash = password_hash($jelszo, PASSWORD_DEFAULT);
                $sql = $db->prepare("INSERT INTO users (username, password, email) VALUES(?, ?, ?)");
                $sql->bind_param("sss", $felhasznalonev, $jelszo_hash, $email);
                $sql->execute();
                $sql->close();

                redirect('belepes', ['success' => '1']);
            }
        }
    ?>
    <form class="text-center" id="reg_form" action="<?php echo url('regisztracio'); ?>" method="POST">

        <p class="h2 mb-4">Regisztráció</p>

        <div class="form-group">
            <input type="text" id="felhasznalonev" class="form-control mb-4" placeholder="Felhasználónév" name="felhasznalonev" value="<?php echo isset($felhasznalonev) ? $felhasznalonev : ''; ?>">
            <?php echo display_errors("felhasznalonev"); ?>
        </div>
        
        <div class="form-group">
            <input type="password" id="jelszo" class="form-control mb-4" placeholder="Jelszó" name="jelszo">
            <?php echo display_errors("jelszo"); ?>
        </div>

        <div class="form-group">
            <input type="password" id="jelszore" class="form-control mb-4" placeholder="Jelszó megerősítése" name="jelszore">
            <?php echo display_errors("jelszore"); ?>
        </div>

        <div class="form-group">
            <input type="email" id="email" class="form-control mb-4" placeholder="Email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
            <?php echo display_errors("email"); ?>
        </div>

        <button class="btn btn-info btn-block my-4 btn_reg" type="submit">Regisztráció</button>

        <p>Van már felhasználód?
            <a href="<?php echo url('belepes'); ?>">Belépés</a>
        </p>
    </form>
</div>
<?php
include_once '_footer.php';
?>
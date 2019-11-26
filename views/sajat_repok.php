<?php if(!defined('APP_MODE')) { exit; } ?>
<?php
if(!loggedin()){
    redirect('home');
}

$codes = get_own_repos($_SESSION["user_id"]);
?>
<?php
include_once '_header.php';
?>

<div class="container">
    <h1 class="text-center m-5">Saját repo-k</h1>
    <?php
    if(isset($_GET["edit_success"])):
    ?>
    <div class="alert alert-success">Sikeres adatmódosítás!</div>
    <?php endif; ?>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"]) && isset($_GET["id"]) && is_numeric($_GET["id"])){
            switch($_GET["action"]){
                case "torles":
                    delete_repo($_GET["id"]);
                break;
                default:
                    display_error("Hibás URL!");
                break;
            }
        }
    ?>
    <?php if($codes->num_rows <= 0): ?>
        <div class="alert alert-danger">
            Nincs megjeleníthető repo!
        </div>
    <?php else: ?>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Cím</th>
                    <th scope="col">Leírás</th>
                    <th scope="col">Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php while($code = $codes->fetch_assoc()): ?>
                    <tr>
                        <th scope="row"><?php echo $code['code_id']; ?></th>
                        <td><?php echo $code['code_title']; ?></td>
                        <td><?php echo $code['code_description']; ?></td>
                        <td><a href="<?php echo url('szerkesztes', ["code_id" => $code['code_id']]); ?>" class="btn btn-primary btn-sm">Szerkesztés</a> <a href="<?php echo url('sajat_repok'); ?>&action=torles&id=<?php echo $code['code_id']; ?>" class="btn btn-danger btn-sm">Törlés</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<?php
include_once '_footer.php';
?>
<?php if(!defined('APP_MODE')) { exit; } ?>
<?php
    $codes = get_recent_codes();
?>
<?php
include_once '_header.php';
?>

<div class="container">
    <h1 class="text-center m-5">Legutóbbi 5 repo</h1>
    <?php if($codes->num_rows <= 0): ?>
        <div class="alert alert-danger">
            Nincs megjeleníthető repo!
        </div>
    <?php else: ?>
        <div class="repo-list">
            <?php while($code = $codes->fetch_assoc()): ?>
                <?php include '_code_list_item.php'; ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php
include_once '_footer.php';
?>
<?php if(!defined("APP_MODE")){ exit; }?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="<?php echo url('home'); ?>">CodeRepo.io</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item <?php echo $page == 'osszes_repo' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo url('osszes_repo'); ?>">Összes repo</a>
      </li>
      <?php if(isset($_SESSION["user_id"])): ?>
        <li class="nav-item <?php echo $page == 'letrehozas' ? 'active' : ''; ?>">
          <a class="nav-link" href="<?php echo url('letrehozas'); ?>">Létrehozás</a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $userdata["username"]; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo url('sajat_repok'); ?>">Saját repo-k</a>
          <hr>
          <a class="dropdown-item" href="<?php echo url('kilepes'); ?>">Kilépés</a>
        </div>
      </li>
      <?php else: ?>
      <li class="nav-item <?php echo $page == 'belepes' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo url('belepes'); ?>">Belépés</a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
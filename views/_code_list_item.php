<?php if(!defined('APP_MODE')) { exit; } ?>
<div class="code">
    <a data-toggle="modal" id="showModal" data-target="#codeModal_<?php echo $code['code_id']; ?>">
        <img src="img/default_code.png" alt="<?php echo $code['code_title']; ?>">
    </a>
    <p class="code-title" title="<?php echo $code['code_title']; ?>">
        <a class="code_title_link" data-toggle="modal" data-target="#codeModal" data-id="<?php echo $code['code_id']; ?>"><?php echo $code['code_title']; ?></a>
    </p>
</div>
<!-- Code Modal -->
<div class="modal fade" id="codeModal_<?php echo $code['code_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="codeModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="codeModalLabel"><?php echo $code['code_title']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body" id="codeModalContent">
              <?php if($code["code_cover"]) : ?>
                  <img src="<?php echo $code['code_cover']; ?>" alt="<?php echo $code['code_title']; ?>">
              <?php else: ?>
                  <img src="img/default_code.png" alt="<?php echo $code['code_title']; ?>">
              <?php endif; ?>
              <br>
              <p class="font-weight-bold">Leírás:</p>
              <?php
              echo $code['code_description'];
              ?>
          </div>
          <div class="modal-footer">
              <?php $uploader_name = get_uploader_name($code['user_id']); ?>
              <p class="mr-auto"><b>Feltöltő:</b> <?php echo $uploader_name["username"]; ?></p>
              <a href="<?php echo 'codes/'.$code['code_path']; ?>" class="btn btn-primary">Forráskód letöltése</a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
          </div>
          </div>
      </div>
  </div>
<?php
if (($_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '172.17.0.1')) {
  //translaty
  $is_front = !strpos($_SERVER['SCRIPT_NAME'], 'module');
  $pos_trasn = strpos($_SERVER['SCRIPT_NAME'], 'translaty');
  $is_main = strpos($_SERVER['SCRIPT_NAME'], 'module_main');
?>
  <div id="btn-debug-code-container">
    <!-- debug mode -->
    <div class="btn-debug-code __event_debug_mode">

      <?= W_Structure::getfile('_framework/modules/main/detiw/icon/menu-operator-user-operator-user.svg') ?>
    </div>

    <!-- translaty -->
    <?php
    if (!($pos_trasn ||  $is_main)) {
      $link = '?is_front=false&path=' . getcwd();
      if ($is_front) {
        $link = '?is_front=true';
      }
    ?>
      <a href="<?= W_Structure::getLinkURL('_framework/resources/translaty/index.php' . $link) ?>" target="_blank">
        <div class="btn-debug-code">
          <?= W_Structure::getfile('_framework/modules/main/detiw/icon/translate.svg') ?>
        </div>
      </a>
    <?php
    }
    ?>
  </div>
<?php
}
?>
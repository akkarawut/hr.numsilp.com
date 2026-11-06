<?php
$count_module_user = count($module_user);
$count_module_other = count($module_other);
if ($_POST) {
  if (isset($_POST['submit_swap_module'])) {
    $current_id = $_POST['current_id'];
    $move_to_id = $_POST['move_to_id'];
    $result = Module::swapModule($current_id, $move_to_id);
  }

  W_Main::responseAPI();
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-12 mt-10px">
      <div class="title-module">
        <div class="title">
          <span> Program module</span>
          <span class="count"><?= $count_module_other ?></span>
        </div>
        <a href="module_add.php" class="btn-add-module">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/plus_icon.svg') ?>
          <span class="ml-10px">ADD MODULE</span>
        </a>
      </div>
      <?php if ($count_module_other > 0) { ?>
        <?php foreach ($module_other as $key => $module) { ?>
          <div class="list-module">
            <div class="icon">
              <?php
              $theme = W_Structure::getThemeClass();
              $icon = W_Structure::getLinkURL('_framework/structure/image/icon/icon-core.svg');
              if (isset($module[$theme . '_icon'])) {
                $data_file = W_Main::datafile($module[$theme . '_icon']);
                if (!$data_file['is_placeholder']) {
                  $icon = $module[$theme . '_icon'];
                }
              }

              ?>
              <img src=" <?= $icon ?>" alt="">
            </div>
            <div class="name">
              <?= $module['name'] ?>
            </div>
            <div class="line-separator"></div>
            <div class="count">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/web_icon.svg') ?>
              <span class="text-count">
                <?= W_Main::getCountMenuModule($module['module']) ?>
              </span>
            </div>
            <div class="line-separator"></div>
            <div class="module-title">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/cpu_icon.svg') ?>
              <span class="module-name">
                <?= $module['module'] ?>
              </span>
            </div>
            <div class="line-separator"></div>
            <div class="sort-module">
              <form method="post">
                <?php
                if (isset($module_other[$key - 1]['id'])) {
                  TiwForm::normal('hidden', $module['id'], ['name' => 'current_id']);
                  TiwForm::normal('hidden', $module_other[$key - 1]['id'], ['name' => 'move_to_id']);
                }
                ?>
                <button type='submit' class="btn-icon-module mr-10px <?= $key == 0 ? 'disabled' : '' ?>" name="submit_swap_module">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/arrow_up.svg') ?>
                </button>
              </form>
              <form method="post">
                <?php
                if (isset($module_other[$key + 1]['id'])) {
                  TiwForm::normal('hidden', $module['id'], ['name' => 'current_id']);
                  TiwForm::normal('hidden', $module_other[$key + 1]['id'], ['name' => 'move_to_id']);
                }
                ?>
                <button type='submit' class="btn-icon-module <?= $key + 1 == $count_module_other  ? 'disabled' : '' ?>" name="submit_swap_module">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/arrow_down.svg') ?>
                </button>
              </form>
            </div>
            <div class="line-separator"></div>
            <div class="info">
              <a href="module_detail.php?c=<?= $module['code'] ?>" class="btn-icon-module">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/info_icon.svg') ?>
              </a>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <div class="list-module justify-content-center">
          <span class="text-secondary">no data</span>
        </div>
      <?php } ?>
      <div class="title-module">
        <div class="title">
          <span> User management</span>
          <span class="count"><?= $count_module_user ?></span>
        </div>
        <a href="module_add.php" class="btn-add-module">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/plus_icon.svg') ?>
          <span class="ml-10px">ADD MODULE</span>
        </a>
      </div>
      <?php if ($count_module_user > 0) { ?>
        <?php foreach ($module_user as $key => $module) { ?>
          <div class="list-module">
            <div class="icon">
              <img src="  <?= W_Structure::getLinkURL('_framework/structure/image/icon/general/user.svg') ?>" alt="">
            </div>
            <div class="name">
              <?= $module['name'] ?>
            </div>
            <div class="line-separator"></div>
            <div class="count">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/web_icon.svg') ?>
              <span class="text-count">
                <?= 1 ?>
              </span>
            </div>
            <div class="line-separator"></div>
            <div class="module-title">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/cpu_icon.svg') ?>
              <span class="module-name">
                <?= $module['module'] ?>
              </span>
            </div>
            <div class="line-separator"></div>
            <div class="sort-module">
              <form method="post">
                <?php
                if (isset($module_user[$key - 1]['id'])) {
                  TiwForm::normal('hidden', $module['id'], ['name' => 'current_id']);
                  TiwForm::normal('hidden', $module_user[$key - 1]['id'], ['name' => 'move_to_id']);
                }
                ?>
                <button type='submit' class="btn-icon-module mr-10px <?= $key == 0 ? 'disabled' : '' ?>" name="submit_swap_module">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/arrow_up.svg') ?>
                </button>
              </form>
              <form method="post">
                <?php
                if (isset($module_user[$key + 1]['id'])) {
                  TiwForm::normal('hidden', $module['id'], ['name' => 'current_id']);
                  TiwForm::normal('hidden', $module_user[$key + 1]['id'], ['name' => 'move_to_id']);
                }
                ?>
                <button type='submit' class="btn-icon-module <?= $key + 1 == $count_module_user  ? 'disabled' : '' ?>" name="submit_swap_module">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/arrow_down.svg') ?>
                </button>
              </form>
            </div>
            <div class="line-separator"></div>
            <div class="info">
              <a href="module_detail.php?c=<?= $module['code'] ?>" class="btn-icon-module">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/info_icon.svg') ?>
              </a>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <div class="list-module justify-content-center">
          <span class="text-secondary">no data</span>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
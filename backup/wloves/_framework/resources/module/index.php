<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_module_setup'];
require_once '../../../_framework/import.php';
$module = Module::selectModule();
$module_user = [];
$module_other = [];
if ($module) {
  foreach ($module as $key => $value) {
    if ($value['module'] == 'User') {
      $module_user[] = $value;
    } else {
      $module_other[] = $value;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  W_Main::loadAsset('assets/css/module.css');
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>
  <?php
  if ($module) {
    include_once 'view/list_module.php';
  } else {
    include_once 'view/no_module.php';
  }
  ?>

  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>
</body>

</html>
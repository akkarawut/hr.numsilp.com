<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_module_setup'];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['boatnav', 'jform']);
$code = isset($_GET['c']) ? $_GET['c'] : '';
$template = isset($_GET['template']) ? $_GET['template'] : '';
$data_template =  Extra::getExtraTemplate($code, $template);
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : (isset($data_template['meta_data'][0]['tab_id']) ? $data_template['meta_data'][0]['tab_id'] : '');
$page = isset($_GET['mode']) ? $_GET['mode'] : 'edit_mode';
$get_module = Module::getModuleByCode($code);
$sys_function_id = $get_module['id'];
$get_module_by_id = Module::getModuleByID($sys_function_id);
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
  <?php if ($page == 'edit_mode') : ?>
    <?php include_once('view/module/user_detail_edit_mode.php'); ?>
  <?php elseif ($page == 'preview_mode') : ?>
    <?php include_once('view/module/user_detail_preview_mode.php'); ?>
  <?php endif; ?>

</body>

</html>
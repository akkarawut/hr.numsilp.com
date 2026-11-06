<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_program_setting_setup'];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['boatnav', 'input-pattern']);


$page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : 1;
$setting_type = isset($_GET['setting']) ? $_GET['setting'] : 'general';
$status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';

$module_code_selected = isset($_GET['module_code']) ? $_GET['module_code'] : '';

if (isset($_POST['submit_add_user'])) {
  $data = [
    'username' => $_POST['username'],
    'password' => $_POST['password']
  ];
  $api_result = User::addNewUser($module_code_selected, $data);

  if ($api_result['response_status']) {
    $user_id = $api_result['response_data']['insert_id'];
    $permission_code = explode(',', $_POST['permission_code']);
    User_Permission::trigger($user_id, $permission_code, 1);

    W_Main::notification($api_result['response_message'], 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
}

$_GET['setting'] = isset($_GET['setting']) ? $_GET['setting'] : 'general';
$data_nav = [
  'param_name'  => 'setting',
  'title' => 'Setting',
  'class' => '',
  'hide_icon' => true,
  'list' => [
    [
      'id'  => 'general',
      'name'  => Ty::get('program_info'),
      'icon'   => 'structure/image/icon/general/general.svg',
    ],
    [
      'id'  => 'language',
      'name'  => Ty::get('Language'),
      'icon'   => 'structure/image/icon/general/language.svg',
    ],
    [
      'id'  => 'theme',
      'name'  => Ty::get('program_theme'),
      'icon'   => 'structure/image/icon/general/theme.svg',
    ],
    [
      'id'  => 'alert',
      'name'  => Ty::get('alert'),
      'icon'   => 'structure/image/icon/general/alert.svg',
    ],
    [
      'id'  => 'user_type',
      'name'  => Ty::get('user_type'),
      'icon'   => 'structure/image/icon/general/user-type.svg',
    ],
  ]
];

$list_info                      = $program_wolves_setup;
$list_info['logo_image']        = $program_wolves_setup['logo_image'];
$list_info['signin_image']      = $program_wolves_setup['signin_image'];
$list_info['favicon']           =  $program_wolves_setup['favicon'];
$list_info['dark_logo_image']    =  $program_wolves_setup['dark_logo_image'];
// $list_info_person               = WLoves::selectContactList('*');


$is_error = W_WLoves::$initial_data['sidenav']['check_error'];

// Module
// $exists_modules = Func::selectExistsModule();
// $modules        = Doc::selectModule([], ['it_nav_wolves' => 1]);

$program = W_WLoves::$initial_data['program'];

?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta(); ?>
  <style>
    body #content-x {
      padding: 0;
    }
  </style>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>

  <?php Boatnav::topbar($data_nav, '?c='); ?>
  <?php
  if ($setting_type == 'general') {
    include 'view/program_setting/general_detail.php';
  } else if ($setting_type == 'language') {
    include 'view/program_setting/language.php';
  } else if ($setting_type == 'theme') {
    include 'view/program_setting/theme_color_new.php';
  } else if ($setting_type == 'database') {
  } else if ($setting_type == 'alert') {
    include 'view/program_setting/alert_list.php';
  } else if ($setting_type == 'user_type') {
    include 'view/program_setting/user_type.php';
  }
  ?>

  <?php include_once '../layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../../'); ?>
</body>

</html>
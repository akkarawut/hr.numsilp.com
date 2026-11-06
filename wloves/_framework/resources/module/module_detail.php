<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_module_setup'];
require_once '../../../_framework/import.php';
$code = isset($_GET['c']) ? $_GET['c'] : '';
$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
$page  = isset($_GET['page']) ? $_GET['page'] : 'detail';

if ($_POST) {
  if (isset($_POST['submit_update_permission_module'])) {
    $permission = $_POST['permission'];
    $function = $_POST['function'];
    $check_permission = isset($_POST['check_permission']) ? $_POST['check_permission'] : [];
    $check_function = isset($_POST['check_function']) ? $_POST['check_function'] : [];

    foreach ($permission as $key => $list) {
      $permission[$key] = isset($check_permission[$key]) ? 1 : 0;
    }
    foreach ($function as $key => $list) {
      $function[$key] = isset($check_function[$key]) ? 1 : 0;
    }

    $data = [
      'hidden_permission' => $permission,
      'setting_permission' => $function
    ];

    $result = Module::updateModule($_POST['id'], $data);
    $redirect = ($result['response_status']) ? '?c=' . $_GET['c'] . '&page=permission' : '';
  }
  W_Main::responseAPI();
}

$module_nav = [
  [
    'page' => 'detail',
    'name' => 'Module details',
    'icon' => '_framework/resources/module/assets/icon/info_icon.svg',
  ],
  [
    'page' => 'permission',
    'name' => 'Page access and Permission',
    'icon' => '_framework/resources/module/assets/icon/form_icon.svg',
  ],
  [
    'page' => 'page_setting',
    'name' => 'User page setting',
    'icon' => '_framework/resources/module/assets/icon/user_paper_icon.svg',
  ],
];
$get_module = Module::getModuleByCode($code);
$module_id = $get_module['id'];
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

  <div class="container-fluid">
    <div class="row">
      <div class="col-12 mt-10px">
        <div class="w-card">
          <div class="w-card-title-edit border-bottom">
            Program module > <span class="text-primary">Module details</span>
          </div>
          <?php if ($get_module['module'] == 'User') { ?>
            <div class="module-nav">
              <?php foreach ($module_nav as $key => $nav) { ?>
                <a href="?c=<?= $code ?>&page=<?= $nav['page'] ?>" class="<?= $page == $nav['page'] ? 'active' : '' ?>">
                  <?= W_Structure::getfile($nav['icon']) ?>
                  <span>
                    <?= $nav['name'] ?>
                  </span>
                </a>
              <?php  } ?>
            </div>
          <?php } ?>
          <?php
          if ($page == 'detail') {
            include_once 'view/module/detail.php';
          } else if ($page == 'permission') {
            include_once 'view/module/permission.php';
          } else if ($page == 'page_setting') {
            include_once 'view/module/page_setting.php';
          }
          ?>
        </div>
      </div>
    </div>
  </div>


  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>


  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>
</body>

</html>
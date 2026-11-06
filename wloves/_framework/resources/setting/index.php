<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_program_setting_setup'];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['boatnav', 'jform']);

$_GET['page'] = isset($_GET['page']) ? $_GET['page'] : 'general';
$page = $_GET['page'];
$link = '?';
$status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';

$type = isset($_GET['type']) ? $_GET['type'] : '';

$user_column_options = [
  'total_count' => true,
];

$code = isset($_GET['c']) ? $_GET['c']  : 'System';
$where = [
  'table_name' => 'sys_user'
];
$options = [
  'total_count' => true,
];
$data_list =  Extra::selectExtraColumns($code, $where, $options);
$total_count = $data_list['total_count'];

$data_nav = [
  'param_name'  => 'page',
  'type' => 'mutiple',
  'title_list' => [
    [
      'title' => 'Configure',
      'list' => [
        [
          'id'  => 'general',
          'name'  => 'Program general information',
        ],
        [
          'id'  => 'user_field',
          'name'  => 'User field & database column',
          'count' => $total_count,
        ],
        [
          'id'  => 'language',
          'name'  => 'Program language',
        ],
        [
          'id'  => 'theme',
          'name'  => 'Program theme & color',
        ],
        [
          'id'  => 'database',
          'name'  => 'Database connection',
        ],
      ]
    ],
  ],
];
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  W_Main::loadAsset('assets/css/setting.css');
  ?>
</head>

<body>
  <?php include '../layout/header-default.php'; ?>

  <div class="container-fluid h-md-100">
    <div class="row h-md-100">
      <div class="col-xl-3 p-0">
        <div class="w-card ">
          <div class="px-20px py-10px border-bottom mb-10px">
            <h1 class="font-Bold mb-0 font-18px">Program setting</h1>
          </div>
          <div class="card-scroll h-md-100">
            <?php Boatnav::wolves($data_nav, $link); ?>
          </div>
        </div>
      </div>
      <?php
      if ($page == 'general') {
        include_once('view/general.php');
      } else if ($page == 'user_field') {
        include_once('view/user_field.php');
      } else if ($page == 'language') {
        include_once('view/language.php');
      } else if ($page == 'theme') {
        include_once('view/theme.php');
      }
      ?>

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
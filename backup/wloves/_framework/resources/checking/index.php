<?php
$_PAGE['permission'] = ['core', 'core_dev', 'core_dev_checking'];
require_once '../../../_framework/import.php';

W_Structure::loadModules(['boatnav']);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$menu_checking = isset($_GET['menu_checking']) ? $_GET['menu_checking'] : 1;

$count = DB_Init::countDiagnoseErrorTable();
$count = isset($count['response_status']) ? 0 : $count;
$data_nav = [
  'param_name'  => 'page',
  'type' => 'mutiple',
  'title_list' => [
    [
      'title' => 'Program checking',
      'icon'   => '',
      'class' => '',
      'list' => [
        [
          'id'  => 1,
          'name'  => 'File & Server compare',
          'count' => $count
        ],
      ]
    ],
  ]
];
$link = 'checking.php';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>

  <div class="container-fluid h-100">
    <div class="row h-100">
      <div class="col-lg-2 col-md-4 p-0 border-right">
        <div class="w-card">
          <div class="px-20px py-10px">
            <div class="font-18px font-Bold">Checking</div>
          </div>
          <hr class="my-0">
          <?php Boatnav::wolves($data_nav, $link); ?>
        </div>
      </div>
      <div class="col-lg-10 col-md-8 p-10px">
        <?php if ($page == 1) { ?>
          <?php
          $data_nav_sub = [
            'param_name'  => 'menu_checking',
            'title' => '',
            'class' => '',
            'hide_icon' => true,
            'list' => [
              [
                'id'  => '1',
                'name'  => 'COMPARE',
              ],
              [
                'id'  => '2',
                'name'  => 'LOG HISTORY',
              ],
            ]
          ];
          ?>
          <!-- nav bar compare & log history -->
          <?php
          Boatnav::topbar($data_nav_sub, '?');

          if ($menu_checking == 1) {
            include_once 'views/server_compare.php';
          } else if ($menu_checking == 2) {
            include_once 'views/server_log.php';
          }
          ?>
        <?php } ?>
      </div>

    </div>
  </div>

  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>
</body>

</html>
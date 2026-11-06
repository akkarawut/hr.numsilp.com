<?php
$_PAGE['permission'] = ['core', 'core_template', 'itnav'];

require_once '../../_framework/import.php';

W_Structure::loadModules(['itnav']);

$link = 'itnav.php?c=';
$param_name  = 'param_name';
$param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '';
$data_wolves = [
  'title' => 'Test menu',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'SQL LOG',
      'icon'   => 'structure/image/layout/icon-news.svg',
      'count'  => 10,
      'status' => 'ชิบหาย',
      'color_status' => 'red-status',
    ],
    [
      'id'  => 2,
      'name'  => 'BACKEND LOG',
      'icon'   => 'structure/image/layout/icon-news.svg',
      'count'  => 1,
      'status' => 'เช็คด่วน',
      'color_status' => 'yellow-status',
    ],
    [
      'id'  => 3,
      'name'  => 'FRONTEND LOG',
      'icon'   => 'structure/image/layout/icon-news.svg',
      'count'  => 0,
      'status' => 'เยี่ยม',
      'color_status' => 'green-status',
    ]
  ]
];
$data_milkclub = [
  'class' => '',
  'list' => [
    [
      'name'  => 'User',
      'icon'   => '',
      'count'  => 10,
      'list'   => [
        [
          'name'  => 'Current User 01',
          'list'   => [
            [
              'id'  => 1,
              'name'  => 'Login',
              'icon'   => '',
              'count'  => '',
            ],
            [
              'id'  => 2,
              'name'  => 'Logout',
              'icon'   => '',
              'count'  => '',
            ]
          ]
        ],
        [
          'name'  => 'Current User 02',
          'list'   => []
        ]
      ]
    ],
    [
      'name'  => 'Lorem ipsum error',
      'icon'   => '',
      'count'  => 2,
      'list'   => [
        [
          'name'  => 'Current User 01',
          'list'   => []
        ],
        [
          'name'  => '',
          'list'   => [
            [
              'id'  => 3,
              'name'  => 'Login',
              'icon'   => '',
              'count'  => '',
            ],
            [
              'id'  => 4,
              'name'  => 'Logout',
              'icon'   => '',
              'count'  => '',
            ]
          ]
        ]
      ]
    ]
  ]
];
$data_dinner = [
  'class' => '',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'Module Details',
      'icon'   => 'structure/image/layout/icon-news.svg',
      'count'  => 10,
    ],
    [
      'id'  => 2,
      'name'  => 'User Type & User Setup',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 3,
      'name'  => 'Connected Module',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 4,
      'name'  => 'Module Details',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 5,
      'name'  => 'User Type & User Setup',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 6,
      'name'  => 'Connected Module',
      'icon'   => '',
      'count'  => '',
    ]
  ]
];
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php include_once 'structure/layout/header-default.php'; ?>
  <div class="col-12 my-5">
    <div class="row">
      <div class="col-4">
        <?php Itnav::wolves($data_wolves, $link, $param_name, $param_selected); ?>
      </div>
      <div class="col-4 bg-nav-test px-0">
        <?php Itnav::milkclub($data_milkclub, $link, $param_name, $param_selected); ?>
      </div>
      <div class="col-12">
        <?php Itnav::dinner($data_dinner, $link, $param_name, $param_selected); ?>
      </div>
    </div>
  </div>
  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>
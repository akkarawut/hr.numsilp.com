<?php
$_PAGE['permission']  = ['landing', '', ''];
$prefix = '../../../';
require $prefix . '_framework/import.php';
W_Structure::loadModules(['exceltable']);

?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta($prefix);
  W_Main::loadAsset('../../structure/css/animate.css');
  ?>
</head>

<body>
  <?php include '../layout/header-default.php'; ?>

  <?php

  $code = 'xlkkb';

  $column = [
    [
      // 'th' => 'Type',
      'data' => 'name',
    ],
    [
      // 'th' => 'Capacity',
      'data' => 'number',
    ],
    [
      // 'th' => 'ภาษา',
      'data' => 'status',
    ],
    [
      // 'th' => 'วันที่',
      'data' => 'insert_date_time',
    ],
  ];

  $api_select = [
    'code' => $code,
    'class' => 'tfa_test',
    'function' => 'selectTest',
  ];

  $api_update = [
    'code' => $code,
    'class' => 'tfa_test',
    'function' => 'updateTest',
  ];

  $options = [
    // 'is_checkbox_all' => true,
    // 'title' => 'หัวข้อ',
    // 'sub_title' => 'หัวย่อย'
  ];
  ?>

  <?php Exceltable::genTable('load_data_handson', $column, $api_select, $api_update, $options); ?>

  <?php
  include '../layout/footer.php';
  W_Structure::loadFooter($prefix);
  ?>
  <script>


  </script>
</body>

</html>
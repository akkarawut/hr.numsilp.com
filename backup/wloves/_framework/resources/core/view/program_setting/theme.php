<?php
$data_nav = [
  'param_name'  => 'page',
  'class' => '',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'Theme & Color',
    ],
    [
      'id'  => 2,
      'name'  => 'Decorate',
    ]
  ]
];
?>
<div class="col-lg-10 box-nav-top">
  <div class="editable-card-header bg-light border-radius-10-10-0-0px">
    <?php Boatnav::dinner($data_nav); ?>
  </div>
  <div class="detail-card-body container-detail" style="min-height: unset;">
    <?php
    if ($page == 1) {
      include '../../module_main/core/view/program_setting/theme_color.php';
    } else if ($page == 2) {
      include '../../module_main/core/view/program_setting/theme_decorate.php';
    }
    ?>
  </div>
</div>
<?php
$link_view = '?c=' . $code . '&page=preview_mode&template=' . $template . '&tab=' . $tab_id;
$nav_view = [
  'param_name'  => 'tab',
  'class' => '',
  'list'  => []
];

$select_tab = Extra::getExtraTemplate($code, $template);
$tab_detail = [];
foreach ($select_tab['meta_data'] as $key => $menu) {
  $nav_view['list'][] = [
    'id'     => $menu['tab_id'],
    'name'   => $menu['tab_name'],
  ];

  if ($menu['tab_id'] == $select_tab['meta_data'][0]['tab_id']) {
    $tab_detail = $menu;
  }
}
?>

<?= Jform::extra_template_header($code, $table_name = 'sys_user', $template, ['mode' => 'preview_mode']); ?>
<div class="w-card w-50 mx-auto">
  <?php if ($get_module_by_id['create_tab'] == 1) : ?>
    <?= Boatnav::topbar($nav_view, $link_view); ?>
  <?php endif; ?>

  <?= Jform::generate_form($code, $table_name = 'sys_user', $template, ['tab_id' => $tab_id], $is_edit = false); ?>
</div>
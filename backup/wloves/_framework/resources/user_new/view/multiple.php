<?php
$where = [
  'sys_function_id' => $module_setup['id'],
  'menu' => 'user_detail',
];
$template_name = (isset($is_profile) && $is_profile) ? 'profile' : 'user_detail';
$taps = Extra::getExtraTemplate($code, $template_name);
$_GET['page'] = isset($_GET['page']) ? $_GET['page'] : (isset($taps['meta_data'][0]['tab_id']) ? $taps['meta_data'][0]['tab_id'] : '');
$page = $_GET['page'];
$tap_id = $page;
$link_view = '?c=' . $code  . '&id=' . $id;
$nav_view = [
  'param_name'  => 'page',
  'class' => '',
  'list'  => []
];
$tap_detail = [];
if ($taps) {
  foreach ($taps['meta_data'] as $key => $menu) {
    $nav_view['list'][] = [
      'id'     => $menu['tab_id'],
      'name'   => $menu['tab_name'],
    ];
    if ($menu['tab_id'] == $tap_id) {
      $tap_detail = $menu;
    }
  }
}
$is_default = true;
if ($taps) {
  $is_default = $page == $taps['meta_data'][0]['tab_id'] ? true : false;
}
?>

<div class="w-card  <?= isset($is_profile) ? '' : 'border-left-2px'  ?>">
  <?php if ($taps) { ?>
    <?= Boatnav::topbar($nav_view, $link_view); ?>
  <?php } ?>
  <?php if ($is_default) { ?>
    <div class="w-card-title-bar border-bottom">
      <div class="d-flex flex-wrap align-items-center">
        <h3 class="title"><?= Ty::get('user_info') ?> - <span class="text-primary"><?= $get_user['username'] ?></span> </h3>
      </div>
      <div class="btn-edit-container">
        <?php if (!$is_edit_permission) { ?>
          <?php if (!$is_edit) { ?>
            <a href="?c=<?= $code ?>&id=<?= $id ?>&page=<?= $page ?>&is_edit=1" class="btn-edit edit"> <?= Ty::get('edit_data', [], ["case" => "ucfirst"]) ?></a>
            <?php if (!isset($is_profile)) { ?>
              <button type="button" class="btn-edit delete" <?= Tiwdal::register('modal_delete_user', $get_user); ?>></button>
            <?php } ?>
          <?php } else { ?>
            <a href="?c=<?= $code ?>&id=<?= $id ?>" class="btn-edit cancel"> <?= Ty::get('cancel') ?></a>
            <button type="submit" class="btn-edit save" id="event_submit_form" name="submit_edit_module"> <?= Ty::get('save', [], ["case" => "ucfirst"]) ?></button>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <?php
    if (!$is_edit_permission) {
      include_once 'view/user/account.php';
      include_once 'view/user/profile.php';
      include_once 'view/user/detail.php';
    }
    ?>
  <?php } else { ?>
    <div class="w-card-title-bar border-bottom">
      <div class="d-flex flex-wrap align-items-center">
        <h3 class="title"><?= Ty::get('user_info', [], ["case" => "ucfirst"]) ?> - <span class="text-primary"><?= $get_user['username'] ?></span> </h3>
      </div>
      <div class="btn-edit-container">
        <?php if (!$is_edit) { ?>
          <a href="?c=<?= $code ?>&id=<?= $id ?>&page=<?= $page ?>&is_edit=1" class="btn-edit edit"> <?= Ty::get('edit_data', [], ["case" => "ucfirst"]) ?></a>
        <?php } else { ?>
          <a href="?c=<?= $code ?>" class="btn-edit cancel"> <?= Ty::get('cancel', [], ["case" => "ucfirst"]) ?></a>
          <button type="submit" class="btn-edit save" id="event_submit_form" name="submit_edit_module"> <?= Ty::get('save', [], ["case" => "ucfirst"]) ?></button>
        <?php } ?>
      </div>
    </div>
    <?php include_once 'view/user/detail.php'; ?>
  <?php } ?>

  <!-- aow detail ma ward tod nee -->

  <!-- /// -->
  <?php
  if (!$is_edit) {
    if ($is_default) {
      include_once 'view/user/permission.php';
    }
  }
  ?>
</div>
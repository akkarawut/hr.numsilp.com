<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import.php';

$code = isset($_POST['code']) ? $_POST['code'] : '';
$page = isset($_POST['page']) ? $_POST['page'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';

$is_dev = W_User::isDev();
$permissions = W_Permission::$has_permissions;
$favorite = W_Main::cookie('_page_favorite');
$favorite = $favorite ? json_decode($favorite, true) : [];

$params = [
  'page' => $page,
  'name' => $name,
];
$favorite[$code] = $params;

W_Main::cookie('_page_favorite', json_encode($favorite));

foreach ($favorite as $key => $data) {
  $code_module = explode('_', $key)[0];
  if (isset($permissions[$key]) || ($code_module == 'core' && $is_dev)) {
?>
    <div class="nav-x-favorite-list-group">
      <div class="nav-x-favorite-list" data-code="<?= $key ?>">
        <a href="<?= $data['page'] ?>"> <span><?= $data['name'] ?></span> </a>
        <div class="btn-delete-nav-x-favorite" data-code="<?= $code ?>">
          <?= file_get_contents('../../../structure/image/etc/delete.svg') ?>
        </div>
      </div>
      <div class="nav-x-slash"></div>
    </div>
<?php
  }
}

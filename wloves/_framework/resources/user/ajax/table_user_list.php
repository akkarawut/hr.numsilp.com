<?php
require_once '../../../../_framework/import_for_ajax.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

$module_info = Module::getModuleByCode($_GET['c']);

$where = [
  'user_type_id' => $module_info['managed_user_type_id'],
  'id_like' => $_POST['id_like'],
  'full_name' => $_POST['full_name'],
];

$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$users = User::selectUser($_GET['c'], $where, $options);
?>

<tbody data-total_count="<?= $users['total_count'] ?>">
  <?php foreach ($users['list'] as $user) {
    $active = ($user_id == $user['id']) ? 'active' : '';
  ?>
    <tr class="tr-link cursor-pointer <?= $active ?>" data-link="?c=<?= $_GET['c'] ?>&user_id=<?= $user['id'] ?>">
      <td nowrap class="thin-cell"><?= ($user['user_code']) ? $user['user_code'] : $user['user_type'][0] . $user['id']; ?></td>
      <td nowrap><?= $user['full_name']; ?></td>
    </tr>
  <?php } ?>
</tbody>
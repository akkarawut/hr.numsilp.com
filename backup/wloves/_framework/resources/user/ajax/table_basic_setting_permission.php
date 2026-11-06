<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_setting'];
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$user_type = (isset($_GET['user_type']) && $_GET['user_type']) ? $_GET['user_type'] : '';
$type = (isset($_GET['type']) && $_GET['type']) ? $_GET['type'] : '';
$page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : '';

$where = [
  'user_type_id' => $user_type,
];
$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$templates = User_Basic_Setting::selectPermissionTemplate($where, $options);
?>

<tbody data-total_count="<?= $templates['total_count'] ?>">
  <?php foreach ($templates['list'] as $template) { ?>
    <tr class="tr-link cursor-pointer" data-link="user_basic_setting.php?c=&user_type=<?= $user_type ?>&type=<?= $type ?>&page=template_detail&id=<?= $template['id'] ?>">
      <td class="thin-cell"><?= $template['name'] ?></td>
      <td><?= $template['description'] ?></td>
      <td class="thin-cell"><?= ($template['is_enabled'] == '1') ? '<span class="text-primary">Activate</span>' : '<span class="text-danger">Deactivate</span>'; ?></td>
    </tr>
  <?php } ?>
</tbody>
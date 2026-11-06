<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_manage_basic_setting'];
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$user_type = (isset($_GET['user_type']) && $_GET['user_type']) ? $_GET['user_type'] : '';
$type = (isset($_GET['type']) && $_GET['type']) ? $_GET['type'] : '';

$where = [
  'user_type_id' => $user_type,
];
$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];
$teams = User_Basic_Setting::selectTeam($where, $options);
?>

<tbody data-total_count="<?= $teams['total_count'] ?>">
  <?php foreach ($teams['list'] as $team) { ?>
    <tr class="tr-link cursor-pointer" data-link="user_basic_setting.php?c=&user_type=<?= $user_type ?>&type=<?= $type ?>&page=team_detail&id=<?= $team['id'] ?>">
      <td class="thin-cell"><?= $team['name'] ?></td>
      <td><?= $team['description'] ?></td>
      <td class="text-right"><?= number_format($team['user_count'], 0) ?></td>
    </tr>
  <?php } ?>
</tbody>
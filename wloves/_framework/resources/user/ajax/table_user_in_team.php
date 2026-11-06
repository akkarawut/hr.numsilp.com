<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_manage_basic_setting'];
require_once '../../../../_framework/import_for_ajax.php';


$id = isset($_GET['id']) ? $_GET['id'] : 0;

$where = [
  'team_id' => $id,
];
$options = [
  'img_path'  => true,
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];
$teams = User_Basic_Setting::selectUserInTeam($where, $options);
?>

<tbody data-total_count="<?= $teams['total_count'] ?>">
  <?php foreach ($teams['list'] as $team) { ?>
    <tr>
      <td class="thin-cell py-5px">
        <div class="img-border-35px">
          <img src="<?= $team['img_path'] ?>">
        </div>
      </td>
      <td class="thin-cell"><?= $team['full_name'] ?></td>
      <td><?= $team['username'] ?></td>
    </tr>
  <?php } ?>
</tbody>
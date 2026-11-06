<?php
require_once '../../../../_framework/import_for_ajax.php';
Structure::loadMetaForAjax();

$id    = isset($_GET['id']) ? $_GET['id'] : '';
$where   = [
  // 'name' => isset($_POST['name']) ? $_POST['name'] : '',
  // 'module' => isset($_POST['module']) ? $_POST['module'] : '',
];

$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$module_list  =  Module::selectModule($where, $options);
?>

<tbody data-total_count="<?= $module_list['total_count'] ?>">
  <?php foreach ($module_list['list'] as  $data) {  ?>
    <?php
    $module_type =  $data['display'] == 'user' ? 'user' : 'other';
    ?>
    <tr class="tr-link cursor-pointer <?= $id == $data['id'] ? 'active' : '' ?>" data-link="?page=list&id=<?= $data['id'] ?>&module_type=<?= $module_type ?>&type=view">
      <td><?= $data['name'] ?></td>
      <td><?= $data['module']  ?></td>
    </tr>
  <?php } ?>
</tbody>
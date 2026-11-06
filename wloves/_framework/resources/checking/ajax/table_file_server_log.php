<?php
require_once '../../../../_framework/import_for_ajax.php';

$where = [
  'datetime' => $_POST['datetime'],
  'username' => $_POST['username'],
  'action'   => $_POST['action'],
  'type'     => $_POST['type'],
  'table_name' => $_POST['table_name'],
  'column_name' => $_POST['column_name'],
  'column_type' => $_POST['column_type']
];

$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];
$logs = DB_Init::selectDiagnoseLog($where, $options);
?>
<tbody data-total_count="<?= $logs['total_count'] ?>">
  <?php foreach ($logs['list'] as $key => $data) { ?>
    <tr class="">
      <td class="font-16px"><?= W_Main::formatDate($data['datetime'], 'd/m/Y, H:i'); ?></td>
      <td class="font-16px"><?= $data['username'] ?></td>
      <td class="font-16px"><?= ucfirst($data['action']) ?></td>
      <td class="font-16px">
        <?php
        if ($data['type'] == 'file_to_server') {
          echo 'File => Server';
        } else {
          echo ucfirst($data['type']);
        }
        ?>
      </td>
      <td class="font-16px"><?= $data['table_name'] ?></td>
      <td class="font-16px"><?= $data['column_name'] ?></td>
      <td class="font-16px"><?= $data['column_type'] ?></td>
    </tr>
  <?php } ?>
</tbody>
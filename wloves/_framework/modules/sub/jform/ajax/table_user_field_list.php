<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
W_Structure::loadModules(['jform']);
$code = isset($_GET['c']) ? $_GET['c'] : '';
$table_name = isset($_GET['table_name']) ? $_GET['table_name'] : '';
$is_module = isset($_GET['is_module']) ? $_GET['is_module'] : false;
$temp_table_name = str_replace('func_' . $code, '', $table_name);
$where = [
  'code' => $code,
  'table_name' => $temp_table_name,
  'name' => isset($_POST['name']) ? $_POST['name'] : '',
  'category' => isset($_POST['category']) ? $_POST['category'] : '',
  'type' => isset($_POST['type']) ? $_POST['type'] : '',
  'db_column_name' => isset($_POST['db_column_name']) ? $_POST['db_column_name'] : '',
];
if (strpos($temp_table_name, 'sys_') !== false) {
  unset($where['code']);
}
$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$data_list =  Extra::selectExtraColumns($code, $where, $options);
$total_count = $data_list['total_count'];
?>

<tbody data-total_count="<?= $total_count ?>">
  <?php foreach ($data_list['list'] as $data) : ?>
    <tr class="">
      <td nowrap><?= $data['name'] ?></td>
      <td nowrap><?= $data['category'] ?></td>
      <td nowrap><?= $data['type'] ?></td>
      <td nowrap>
        <div class="<?= $data['system_column'] == 1 ? 'text-primary' : '' ?>"><?= $data['db_column_name'] ?></div>
      </td>
      <td nowrap>
        <?= $data['remark'] ?>
      </td>
      <td class="thin-cell py-5px">
        <?= Jform::action_field($code, $data, $is_module); ?>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>
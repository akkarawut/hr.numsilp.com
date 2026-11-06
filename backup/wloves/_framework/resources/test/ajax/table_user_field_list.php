<?php

$prefix = '../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code = isset($_GET['c']) ? $_GET['c'] : '';
$where = [
  'name' => isset($_GET['name']) ? $_GET['name'] : '',
  'category' => isset($_GET['category']) ? $_GET['category'] : '',
  'type' => isset($_GET['type']) ? $_GET['type'] : '',
  'db_column_name' => isset($_GET['db_column_name']) ? $_GET['db_column_name'] : '',
];
$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$data_list = User::selectUserColumns($code, $where, $options);
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
        <?php if ($data['system_column'] != 1) : ?>
          <div class="d-flex align-items-center">
            <?php if ($data['type'] == 'Varchar') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_short_text', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'Longtext') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_long_text', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'Int') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_number', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'Decimal') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_decimal', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'Date') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_date', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'Time') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_time', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'DateTime') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_datetime', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php elseif ($data['type'] == 'Upload') : ?>
              <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => $prefix, 'modal_id' => 'edit_upload', 'modal_data' => $data, 'is_ajax' => true]); ?>
            <?php endif; ?>
            <?php
            TiwForm::normal('btn', '', ['type' => 'submit'], ['type' => 'delete', 'prefix' => $prefix, 'modal_id' => 'delete_user_field', 'modal_data' => $data]);
            ?>
          </div>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>
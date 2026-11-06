<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_setting'];
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$user_type = (isset($_GET['user_type']) && $_GET['user_type']) ? $_GET['user_type'] : '';

$where = [
  'user_type_id' => $user_type,
];
$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$categories = User_Basic_Setting::selectCategory($where, $options);
?>

<tbody data-total_count="<?= $categories['total_count'] ?>">
  <?php foreach ($categories['list'] as $data) { ?>
    <tr class="">
      <td class="thin-cell"><?= $data['name'] ?></td>
      <td><?= $data['description'] ?></td>
      <td class="thin-cell py-5px">
        <div class="d-flex align-items-center">
          <?php
          TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '../../../', 'modal_id' => 'edit_category_modal', 'modal_data' => $data]);
          TiwForm::normal('btn', '', ['type' => 'submit'], ['type' => 'delete', 'prefix' => '../../../', 'modal_id' => 'delete_category_modal', 'modal_data' => $data]);
          ?>
        </div>
      </td>
    </tr>
  <?php } ?>
</tbody>
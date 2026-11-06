<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_setting'];
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

$tags = User_Basic_Setting::selectTag($where, $options);
?>

<tbody data-total_count="<?= $tags['total_count'] ?>">
  <?php foreach ($tags['list'] as $tag) { ?>
    <tr>
      <td class="thin-cell"><?= $tag['name'] ?></td>
      <td><?= $tag['description'] ?></td>
      <td class="thin-cell"><span class="text-danger">Under Developer</span></td>
      <td class="thin-cell py-5px">
        <div class="d-flex align-items-center">
          <?php
          TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '../../../', 'modal_id' => 'edit_tag_modal', 'modal_data' => $tag]);
          TiwForm::normal('btn', '', ['type' => 'submit'], ['type' => 'delete', 'prefix' => '../../../', 'modal_id' => 'delete_tag_modal', 'modal_data' => $tag]);
          ?>
        </div>
      </td>
    </tr>
  <?php } ?>
</tbody>
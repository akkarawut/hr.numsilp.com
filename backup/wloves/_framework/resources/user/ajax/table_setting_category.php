<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_setting'];
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();


$code  = $_GET['c'];
$where = [];

$options = [
  'total_count' => true, //หลังบ้านส่งค่า จำนวนทั้งหมดให้
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : [] //หากเปิด ฟังก์ชัน Sort ต้องมี
];

$category_list = User_Basic_Setting::selectCategory($code, $where, $options);
$total_count   = isset($category_list['total_count']) ? $category_list['total_count'] : 0;
$category      = isset($category_list['list']) ? $category_list['list'] : [];
?>


<tbody data-total_count="<?= $total_count ?>">
  <?php foreach ($category as $idx => $data) { ?>
    <tr class="">
      <td><?= $data['name'] ?></td>
      <td><?= $data['description'] ?></td>
      <td class="text-right"><?= number_format($data['user_count'], 0); ?></td>
      <td class="thin-cell">
        <div class="d-flex">
          <button type="button" class="btn btn-icon-only-bg" <?= Tiwdal::register('edit_category_modal', $data); ?>><?= file_get_contents('../../../structure/image/icon/general/edit.svg') ?></button>
          <button type="button" class="btn btn-icon-only-bg" <?= Tiwdal::register('delete_category_modal', $data); ?>><?= file_get_contents('../../../structure/image/icon/general/delete.svg') ?></button>
        </div>
      </td>
    </tr>
  <?php } ?>
</tbody>
<?php
$_PAGE['permission'] = ['user', '', ''];
require_once '../../../../_framework/import_for_ajax.php';

$where = [
  'shop_name' => $_POST['shop_name'],
  'username'  => $_POST['username']
];
$options = [
  // 'view'        => true,
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'img_path'    => true,
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];
$selectShop  = User::selectVendor($_GET['c'], $where, $options);
$shop_list   = isset($selectShop['list']) ? $selectShop['list'] : [];
$total_count = isset($selectShop['total_count']) ? $selectShop['total_count'] : 0;
?>

<tbody data-total_count="<?= $total_count ?>">
  <?php foreach ($shop_list as $shop) { ?>
    <tr class="cursor-pointer event-owner" data-id="<?= $shop['owner_id']; ?>">
      <td>
        <p class="mb-0 ml-2"><?= $shop['shop_name'] ?></p>
      </td>
      <td>
        <p class="mb-0 ml-2"><?= $shop['username'] ?></p>
      </td>
    </tr>
  <?php } ?>
</tbody>
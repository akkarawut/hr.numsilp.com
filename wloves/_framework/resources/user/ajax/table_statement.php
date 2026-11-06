<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_management'];
require_once '../../../../_framework/import_for_ajax.php';
$admin_owner_id = W_Main::cookie('admin_owner_id');
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

$where = [
  'user_id' => $user_id
];

if (isset($admin_owner_id) && $admin_owner_id) {
  $where['owner_id'] = $admin_owner_id;
}
$options = [
  'total_count' => true,
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => (empty($_POST['data_sort']) || !isset($_POST['data_sort'])) ? ['insert_date_time' => 'DESC'] : $_POST['data_sort']
];

$user_account_more  = User_Accounting::selectAccounting($_GET['c'], $where, $options);
$total_count        = isset($user_account_more['total_count']) ? $user_account_more['total_count'] : 0;
$user_account       = isset($user_account_more['list']) ? $user_account_more['list'] : [];
?>


<tbody data-total_count="<?= $total_count ?>">
  <?php foreach ($user_account as $idx => $data) { ?>
    <tr class="">
      <td nowrap><?= W_Main::formatDate($data['slip_date_time'], 'd/m/Y, H:i'); ?></td>
      <td nowrap><?= ($data['type'] == 'deposit') ? 'เติมเงิน' : 'ถอนเงิน'; ?><?= ($data['remark']) ? ' - ' . $data['remark'] : ''; ?></td>
      <td nowrap class="text-right">
        <?php if ($data['type'] == 'deposit') { ?>
          <span class="text-success">+ <?= number_format($data['price'], 2); ?></span>
        <?php } else { ?>
          <span class="text-danger">- <?= number_format($data['price'], 2); ?></span>
        <?php } ?>
      </td>
      <td nowrap class="text-right"><?= number_format($data['after_price'], 2); ?></td>
    </tr>
  <?php } ?>
</tbody>
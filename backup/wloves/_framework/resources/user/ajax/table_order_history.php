<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_management'];
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
$admin_owner_id = W_Main::cookie('admin_owner_id');

$code    = $_GET['c'];
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$where   = [
  'user_id'  => $user_id,
  'platform' => ''
];
if (isset($admin_owner_id) && $admin_owner_id) {
  $where['owner_id'] = $admin_owner_id;
}
$options = [
  'total_count' => true, //หลังบ้านส่งค่า จำนวนทั้งหมดให้
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : [] //หากเปิด ฟังก์ชัน Sort ต้องมี
];
$user_order  = User_Order::selectBill($_GET['c'], $where, $options);
$total_count = isset($user_order['total_count']) ? $user_order['total_count'] : 0;
$order_list  = isset($user_order['list']) ? $user_order['list'] : [];
?>


<tbody data-total_count="<?= $total_count ?>">
  <?php foreach ($order_list as $idx => $data) {
    $data_modal = [
      'id'   => $data['id'],
      'code' => $code
    ];
    $options = [
      'is_ajax' => 1
    ];
  ?>
    <tr <?= Tiwdal::register('view_modal', $data_modal, $options); ?>>
      <td>
        <p class="mb-0"><?= ($data['platform'] == 'myshop') ? 'My Shop' : $data['platform'] ?></p>
        <p class="mb-0 text-secondary font-13px"><?= W_Main::formatDate($data['insert_date_time'], 'd/m/Y, H:i'); ?></p>
      </td>
      <td><?= $data['bill_no'] ?></td>
      <td><?= number_format($data['total_price'], 2) ?></td>
      <td>
        <?php
        $stock_status  = '';
        $color_balance = '';
        if ($data['status'] == 'draft') {
          echo ' <div class="bill-status status-draft">
                  <span>แบบร่าง</span>
                </div>';
        } else if ($data['status'] == 'wait_payment') {
          echo ' <div class="bill-status  status-wait_payment">
                  <span>รอชำระเงิน</span>
                </div>';
        } else if ($data['status'] == 'paid') {
          echo ' <div class="bill-status  status-paid">
                  <span>ชำระเงินแล้ว</span>
                </div>';
        } else if ($data['status'] == 'prepare_ship') {
          echo ' <div class="bill-status status-prepare_ship">
                  <span>เตรียมจัดส่ง</span>
                </div>';
        } else if ($data['status'] == 'shipped') {
          echo ' <div class="bill-status status-shipped">
                  <span>จัดส่งแล้ว</span>
                </div>';
        } else if ($data['status'] == 'canceled') {
          echo ' <div class="bill-status status-canceled">
                  <span>ยกเลิก</span>
                </div>';
        }
        ?>
      </td>
    </tr>
  <?php } ?>
</tbody>
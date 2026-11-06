<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$options = [
  'product_img_path' => true
];
$order = User_Order::getBillByID($_POST['code'], $_POST['id'], $options);

// $options          = [];
// $shipping_package = lg_shipping::getShippedPackageByID($_POST['code'], $_POST['id'], $options);
// $time_line        = $shipping_package['sending_status'];
?>

<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <div class="bill-header-status-group">
    <div class="bill-id"><?= $order['bill_no'] ?></div>
    <div class="tab-bill-status <?= $order['status'] ?>"><?= $order['status'] ?></div>
  </div>
</div>
<div class="modal-body history-modal">
  <div class="row h-100">
    <div class="col-md-4 border-right-custom px-0">
      <p class="mb-0 d-flex justify-content-center align-items-center p-15px border-bottom-custom">Shopping Cart</p>
      <div class="px-15px py-10px">
        <?php foreach ($order['item_list'] as $key => $data) { ?>
          <div class="d-flex justify-content-between mb-5px">
            <div class="d-flex">
              <div class="img-history mr-5px">
                <img src="<?= $data['product_img_path'] ?>" alt="">
              </div>
              <div class="detail-history">
                <p class="mb-0 font-14px"><?= $data['product_name'] ?></p>
                <p class="text-secondary mb-0 font-13px"><?= $data['product_code'] ?></p>
              </div>
            </div>
            <div class="text-right">
              <p class="mb-0 font-16px">฿<?= $data['sell_price'] ?></p>
              <p class="mb-0 font-16px">X <?= $data['amount'] ?></p>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="bg-hover-sec py-5px">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <p class="mb-0 font-16px font-Medium">Wolf Force Shipping</p>
          <div>
            <p class="mb-0 font-16px font-Medium">฿<?= number_format($order['shipping_price'], 2); ?></p>
          </div>
        </div>
      </div>
      <div class="bg-hover-sec py-5px">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <p class="mb-0 font-16px font-Medium">Discount</p>
          <div>
            <p class="mb-0 font-16px font-Medium">฿<?= number_format($order['discount_price'], 2); ?></p>
          </div>
        </div>
      </div>
      <div class="bg-hover py-5px">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <p class="mb-0 font-18px font-Medium">Total</p>
          <div>
            <p class="mb-0 font-18px font-Medium"><?= number_format($order['total_price'], 2); ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 border-right-custom px-0">
      <p class="mb-0 d-flex justify-content-center align-items-center p-15px border-bottom-custom">Shipping Information</p>
      <div class="px-15px py-10px">
        <div class="form-group">
          <div class="row border-bottom-custom">
            <div class="col-sm-12">
              <p class="text-secondary font-14px text-center">The customer has made payment by transferring to the following account via online bill. Please check with the bank. If correct, then press the button to receive payment.</p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <p class="text-secondary font-15px mb-0">Transfer to</p>
            </div>
            <div class="col-sm-8">
              <?php if ($order['payment_method'] != 'cash') { ?>
                <div class="d-flex">
                  <div class="img-history mr-5px">
                    <img src="<?= $order['slip_img_path'] ?>" alt="">
                  </div>
                  <div class="detail-history">
                    <p class="mb-0 font-14px"><?= $order['bank_abb'] ?></p>
                    <p class="text-secondary mb-0 font-13px"><?= $order['account_no'] ?></p>
                    <p class="text-secondary mb-0 font-13px"><?= $order['account_name'] ?></p>
                  </div>
                </div>
              <?php } else { ?>
                <p class="mb-0">Cash</p>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <p class="text-secondary font-14px mb-0">Transfer Date</p>
            </div>
            <div class="col-sm-8">
              <p class="mb-0"><?= W_Main::formatDate($order['paid_date_time'], 'd/m/Y, H:i'); ?></p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <p class="text-secondary font-14px mb-0">Transfer Amount</p>
            </div>
            <div class="col-sm-8">
              <p class="mb-0">฿ <?= number_format($order['paid_price'], 2); ?></p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <p class="text-secondary font-14px mb-0">Transfer Slip</p>
            </div>
            <div class="col-sm-8">
              <p class="mb-0">NA</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 px-0">
      <p class="mb-0 d-flex justify-content-center align-items-center p-15px border-bottom-custom">Payment</p>
      <div class="bg-hover py-5px">
        <div class="col-12 flex-center">
          <div class="icon-img-shipping mr-5px">
            <div class="icon">
              <?= file_get_contents('../assets/image/icon/icon-shipping.svg'); ?>
            </div>
          </div>
          <div>
            <p class="mb-0 font-16px font-Medium">Shipping Address</p>
          </div>
        </div>
      </div>
      <div class="px-15px py-10px">
        <?php if ($order['platform'] == 'myshop') { ?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-4">
                <p class="text-secondary font-14px mb-0">Shop</p>
              </div>
              <div class="col-sm-8">
                <p class="mb-0">My Shop</p>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-4">
                <p class="text-secondary font-14px mb-0">Facebook</p>
              </div>
              <div class="col-sm-8">
                <p class="mb-0">Wolf Force Express</p>
              </div>
            </div>
          </div>
        <?php } ?>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <p class="text-secondary font-14px mb-0">Consignee</p>
              <p class="mb-0"><?= $order['customer_name'] ?></p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <p class="text-secondary font-14px mb-0">Telephone Number</p>
              <p class="mb-0"><?= $order['customer_tel_no'] ?></p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <p class="text-secondary font-14px mb-0">Shipping Address</p>
              <p class="mb-0"><?= $order['shipping_address'] . ' ' . $order['shipping_sub_district'] . ' ' . $order['shipping_district'] . ' ' . $order['shipping_province'] . ' ' . $order['shipping_country'] . ' ' . $order['shipping_zipcode'] ?></p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <p class="text-secondary font-14px mb-0">Remark</p>
              <p class="mb-0"><?= $order['shipping_remark'] ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-hover py-5px">
        <div class="col-12 flex-center">
          <div class="icon-img-shipping mr-5px">
            <div class="icon">
              <?= file_get_contents('../assets/image/icon/icon-delivery.svg'); ?>
            </div>
          </div>
          <div>
            <p class="mb-0 font-16px font-Medium">Shipping Information</p>
          </div>
        </div>
      </div>
      <div class="px-15px py-10px">
        <div class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <p class="text-secondary font-14px mb-0">Shipping Partner</p>
            </div>
            <div class="col-sm-8">
              <p class="mb-0"><?= $order['carrier_name'] ?></p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <p class="text-secondary font-14px mb-0">Tracking Number</p>
              <p class="mb-0"><?= $order['shipping_tracking_no'] ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer history-modal">
  <div class="d-flex align-items-center flex-wrap">
    <p class="mb-0 text-secondary font-14px mr-10px">Online Tracking :</p>
    <div class="d-flex align-items-center">
      <div class="mr-10px">
        <?php /*file_get_contents('../assets/image/icon/icon-link.svg');*/ ?>
      </div>
      <p class="mb-0 font-14px">https://xd.adobe.com/view/80e2ddfe-fc84-4bff-9771-020156df55e4-d5b1/</p>
    </div>
  </div>
</div>
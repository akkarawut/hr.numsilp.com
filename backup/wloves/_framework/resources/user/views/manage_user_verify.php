<?php
$api = [
  'api'    => 'User::updateUser',
  'params' => [
    'id'   => $user_id,
    'data' => []
  ]
];

?>
<div class="container-detail p-15px">
  <div class="row">
    <div class="col-lg-12">
      <div class="title-detail">
        <div>
          <h3><?= Itlanguage::translate('VERVIFY DATA'); ?> </h3>
          <p><?= Itlanguage::translate('User’s verify data for approvel'); ?></p>
        </div>
      </div>

      <div class="form-group-detail">
        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="pt-5px">ชื่อ-นามสกุล</label>
            </div>
            <div class="col-md-8">
              <!-- <div class="d-flex align-items-center flex-wrap"> -->
              <?php
              if (isset($user_info['full_name'])) {
                $api['params']['data'] = [
                  'full_name' => '{full_name}'
                ];
                TiwForm::liveForm('text', 'full_name', $user_info['full_name'], $api);
              } else { ?>
                <p>-</p>
              <?php } ?>
              <!-- </div> -->
            </div>
          </div>
        </div>
        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="pt-5px">LINE ID</label>
            </div>
            <div class="col-md-8">
              <?php
              if (isset($user_info['line_id'])) {
                $api['params']['data'] = [
                  'line_id' => '{line_id}'
                ];
                TiwForm::liveForm('text', 'line_id', isset($user_info['line_id']) ? $user_info['line_id'] : '-', $api);
              } else {
                echo "<p>-</p>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="pt-5px">Email</label>
            </div>
            <div class="col-md-8">
              <?php
              if (isset($user_info['email'])) {
                $api['params']['data'] = [
                  'email' => '{email}'
                ];
                TiwForm::liveForm('text', 'email', $user_info['email'], $api);
              } else { ?>
                <p>-</p>
              <?php } ?>
              <?php /*
            <div class="d-flex align-items-center flex-wrap">
              <?php if (isset($user_info['email'])) {  ?>
                <span class="text-primary"><?= $user_info['email'] ?></span>
              <?php } else { ?>
                <p>-</p>
              <?php } ?>
            </div>
             */ ?>
            </div>
          </div>
        </div>
        <!-- TEl 1  -->
        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="pt-5px">เบอร์โทรศัพท์ที่ติดต่อไว้ 1</label>
            </div>
            <div class="col-md-8">
              <?php
              if (isset($user_info['tel'])) {
                $api['params']['data'] = [
                  'tel' => '{tel}'
                ];
                TiwForm::liveForm('text', 'tel', $user_info['tel'], $api);
              } else { ?>
                <p>-</p>
              <?php } ?>
              <?php  /* 
            <div class="d-flex align-items-center flex-wrap">
              <?php if (isset($user_info['tel'])) {  ?>
                <span class="text-primary"><?= $user_info['tel'] ?></span>
              <?php } else { ?>
                <p>-</p>
              <?php } ?>
            </div>
            */ ?>
            </div>
          </div>
        </div>
        <!-- Tel 2  -->
        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="pt-5px">เบอร์โทรศัพท์ที่ติดต่อไว้ 2</label>
            </div>
            <div class="col-md-8">
              <?php
              if (isset($user_info['tel_no'])) {
                $api['params']['data'] = [
                  'tel_no' => '{tel_no}'
                ];
                TiwForm::liveForm('text', 'tel_no', $user_info['tel_no'], $api);
              } else { ?>
                <p>-</p>
              <?php } ?>
              <?php /* 
            <div class="d-flex align-items-center flex-wrap">
              <?php if (isset($user_info['tel_no'])) {  ?>
                <span class="text-primary"><?= $user_info['tel_no'] ?></span>
              <?php } else { ?>
                <p>-</p>
              <?php } ?>
            </div>
            */ ?>
            </div>
          </div>
        </div>

        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="">Verify Status</label>
            </div>
            <div class="col-md-8">
              <?php
              if ($user_info['verify_status'] == 'verified') {
                // $text = 'Approve';
                $text = 'ยืนยันตัวตนสำเร็จ';
                $color = 'text-success';
              } else if ($user_info['verify_status'] == 'pending') {
                // $text = 'Waiting Approve';
                $text = 'ขอยืนยันตัวตน';
                $color = 'text-warning';
              } else if ($user_info['verify_status'] == 'not_verify') {
                // $text = 'Don’t Send';
                $text = 'รอยืนยันตัวตน';
                $color = 'text-warning';
              }
              /*
            if ($user_info['status'] == 'approved') {
              // $text = 'Approve';
              $text = 'ยืนยันตัวตนสำเร็จ';
              $color = 'text-success';
            } else if ($user_info['status'] == 'pending') {
              // $text = 'Waiting Approve';
              $text = 'ขอยืนยันตัวตน';
              $color = 'text-warning';
            } else if ($user_info['status'] == 'not_verify') {
              // $text = 'Don’t Send';
              $text = 'รอยืนยันตัวตน';
              $color = 'text-warning';
            }
            */
              ?>
              <span class=" <?= $color ?>"><?= $text ?></span>
            </div>
          </div>
        </div>
        <div class="form-detail">
          <div class="row">
            <div class="col-md-4">
              <label class="">Verify Date</label>
            </div>
            <div class="col-md-8">
              <?php if ($user_info['verify_confirm_datetime']) { ?>
                <span><?= W_Main::formatDate($user_info['verify_confirm_datetime'], 'd/m/Y H:i'); ?></span>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php
        if ($user_info['status'] == 'approved') { ?>
          <div class="form-detail">
            <div class="row">
              <div class="col-md-4">
                <label class="">Remark</label>
              </div>
              <div class="col-md-8">
                <p class="mb-0"><?= $user_info['approved_remark'] ?></p>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
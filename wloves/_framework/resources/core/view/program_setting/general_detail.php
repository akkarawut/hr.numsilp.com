<?php
if ($_POST) {
  if (isset($_POST['submit_info_program'])) {
    unset($_POST['submit_info_program']);
    $result = WLoves::setProgramSetup($_POST);
    if ($result['response_status']) {
      W_Main::redirect('program_setting.php?c=&setting=general');
    }
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}
?>

<div class="w-card">
  <form method="post" enctype="multipart/form-data" class="form-loading">
    <div class="w-card-title-bar">
      <div class="d-flex flex-wrap align-items-center">
        <h3 class="title"><?= Ty::get('program_detail') ?></h3>
        <p class="detail">
          <?= Ty::get('enter_detail') ?> <?= Ty::get('set_data') ?>
        </p>
      </div>
      <div class="btn-edit-container">
        <?php if ($status != 'edit') { ?>
          <a href="?c=&setting=general&status=edit" class="btn-edit edit"> <?= Ty::get('edit_data') ?></a>
        <?php } else { ?>
          <a href="?c=&setting=general" class="btn-edit cancel"> <?= Ty::get('cancel') ?></a>
          <button type="submit" class="btn-edit save" name="submit_info_program"> <?= Ty::get('save', [], ["case" => "ucfirst"]) ?></button>
        <?php } ?>
      </div>
    </div>
    <div class="py-15px px-20px">
      <?php
      if ($status == 'edit') {
      ?>
        <div class=" font-14px pb-20px pt-10px font-SemiBold text-info">
          <?= Ty::get('general_info') ?>
        </div>
        <div class="row form-group ">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_name') ?> </label>
          </div>
          <div class="col-sm-10 font-18px font-Medium">
            <?php TiwForm::normal('text', $program['program_name'], ['name' => 'program_name', 'placeholder' => Ty::get('enter')]); ?>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('description') ?> </label>
          </div>
          <div class="col-sm-10 font-16px font-Medium">
            <?php TiwForm::normal('textarea', $program['description'], ['name' => 'description', 'placeholder' => Ty::get('enter')]); ?>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_owner') ?> </label>
          </div>
          <div class="col-sm-10 font-16px font-Medium">
            <?php TiwForm::normal('text', $program['customer'], ['name' => 'customer', 'placeholder' => Ty::get('enter')]); ?>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_start') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <?php TiwForm::normal('date', $program['start_date'], ['name' => 'start_date', 'placeholder' => Ty::get('enter')]); ?>
          </div>
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_timeout') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <?php TiwForm::normal('time', $program['end_time'], ['name' => 'end_time', 'placeholder' => Ty::get('enter')]); ?>
          </div>
        </div>
        <div class=" font-14px pb-20px pt-10px font-SemiBold text-info">
          <?= Ty::get('program_basic') ?>
        </div>
        <div class="row form-group">
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"> <?= Ty::get('default_curren') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <?php
            $options = [
              'is_search' => true, //หากต้องการใช้ select แบบค้นหาได้ให้ใส่มา
              'list' => [
                [
                  'value' => '',
                  'name' => 'please select',
                  'disabled' => true
                ],
                [
                  'value' => 'thb',
                  'name' => 'Thai Baht'
                ],
                [
                  'value' => 'dhk',
                  'name' => 'Dollar hk'
                ],
              ]
            ];
            TiwForm::normal('select', $program['default_currency'], ['name' => 'default_currency'], $options);
            ?>
          </div>
        </div>
        <div class=" font-14px pb-20px pt-10px font-SemiBold text-info">
          <?= Ty::get('service_cntct') ?>
        </div>
        <div class="row form-group">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('service_email') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <?php TiwForm::normal('email', $program['support_email'], ['name' => 'support_email', 'placeholder' => Ty::get('enter')]); ?>
          </div>
        </div>
      <?php
      } else {
      ?>
        <div class=" font-14px pb-20px pt-10px font-SemiBold text-info">
          <?= Ty::get('general_info') ?>
        </div>
        <div class="row form-group">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_name') ?> </label>
          </div>
          <div class="col-sm-10 font-18px font-Medium text-primary">
            <span class="text_progress"><?= $program['program_name'] ?></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('description') ?> </label>
          </div>
          <div class="col-sm-10 font-16px font-Medium">
            <span class="text_progress"><?= $program['description'] ?></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-2 mb-0">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_owner') ?> </label>
          </div>
          <div class="col-sm-10 font-16px font-Medium">
            <span class="text_progress"><?= $program['customer'] ?></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_start') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <span class="text_progress"><?= W_Main::formatDate($program['start_date'], 'd/m/Y'); ?></span>
          </div>
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"> <?= Ty::get('program_timeout') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <span class="text_progress"><?= W_Main::formatDate($program['end_time'], 'H:i'); ?></span>
          </div>
        </div>
        <div class=" font-14px pb-20px pt-10px font-SemiBold text-info">
          <?= Ty::get('program_basic') ?>
        </div>
        <div class="row form-group">
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"> <?= Ty::get('default_curren') ?> </label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <span class="text_progress"><?= ($program['default_currency'] == 'thb') ? 'Thai Baht' : 'Dollar hk' ?></span>
          </div>
        </div>
        <div class=" font-14px pb-20px pt-10px font-SemiBold text-info">
          <?= Ty::get('service_cntct') ?>
        </div>
        <div class="row form-group">
          <div class="col-sm-2">
            <label class=" text-secondary font-14px"><?= Ty::get('service_email') ?></label>
          </div>
          <div class="col-sm-4 font-16px font-Medium">
            <span class="text_progress"><?= $program['support_email'] ?></span>
          </div>
        </div>
      <?php } ?>
    </div>
  </form>
</div>
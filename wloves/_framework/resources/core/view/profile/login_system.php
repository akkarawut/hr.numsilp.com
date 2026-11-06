<?php
if ($_POST) {
  if (isset($_POST['submit_change_user_password'])) {
    $result = W_User::changePassword($user_id, $_POST['old_password'], $_POST['password']);
  } else if (isset($_POST['submit_change_user_pin'])) {
    $result = W_User::changePin($user_id, $_POST['old_pin'], $_POST['pin']);
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
<div class="bg-card br-bottom-10px px-15px pt-15px pb-0 mb-10px">
  <div class="text-uppercase font-Medium text-info">Login System | <span class="text-primary"><?= $user_info['title'] . ' ' . $user_info['name'] . ' ' . $user_info['surname'] ?></span></div>
  <div class="font-14px text-secondary mb-15px">User log in account for used this system</div>
  <hr class="my-0 mx--15px">
  <div>
    <div class="row py-10px">
      <div class="col-md-4 col-lg-3 font-14px text-secondary d-flex align-items-center">Username</div>
      <div class="col-md-8 col-lg-9 d-flex align-items-center justify-content-between">
        <div class="text-info">Jannermol</div>
      </div>
    </div>
    <hr class="mx--15px my-0">
    <div class="row py-10px">
      <div class="col-md-4 col-lg-3 font-14px text-secondary d-flex align-items-center">Password</div>
      <div class="col-md-8 col-lg-9 d-flex align-items-center justify-content-between">
        <div class="text-info">Last Update <?= W_Main::formatDate($user_info['update_date_time'], 'd/m/Y, H:i') ?></div>
        <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'modal_id' => 'edit_password_modal', 'modal_data' => []]); ?>
      </div>
    </div>
    <?php if ($user_type_info['is_action_pin']) { ?>
      <hr class="mx--15px my-0">
      <div class="row py-10px">
        <div class="col-md-4 col-lg-3 font-14px text-secondary d-flex align-items-center">PIN</div>
        <div class="col-md-8 col-lg-9 d-flex align-items-center justify-content-between">
          <div class="text-info">••••</div>
          <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'modal_id' => 'edit_pin_modal', 'modal_data' => []]); ?>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<div class="bg-card br-10px px-15px pt-15px pb-0">
  <div class="text-uppercase font-Medium text-info">login with social media</div>
  <div class="font-14px text-secondary mb-15px">Connect social media for use login system later.</div>
  <hr class="my-0 mx--15px">
  <div class="social-container py-15px">
    <div class="social-area">
      <div class="social-name">
        <div class="icon-logo">
          <?= W_Structure::getfile('_framework/structure/image/icon/general/facebook.svg') ?>
        </div>
        <div class="name">Connect with Facebook</div>
        <?php if (false) { ?>
          <div class="icon-correct">
            <?= W_Structure::getfile('_framework/structure/image/icon/general/verify.svg') ?>
          </div>
        <?php } ?>
      </div>
      <div class="social-action">
        <?php if (false) { ?>
          <div class="social-link">
            <div class="link-group">
              <?= W_Structure::getfile('_framework/structure/image/icon/general/link-primary.svg') ?>
              <div class="link">Jannermolyncux@gmail.com</div>
            </div>
            <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-disconnect'], ['type' => '', 'text' => 'DISCONNECT']); ?>
          </div>
        <?php } else { ?>
          <div class="social-link">
            <div class="social-not-config mr-15px mb-5px">Synchronization is not configured</div>
            <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-connect'], ['type' => '', 'text' => 'CONNECT']); ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="social-area">
      <div class="social-name">
        <div class="icon-logo">
          <?= W_Structure::getfile('_framework/structure/image/icon/general/google.svg') ?>
        </div>
        <div class="name">Connect with Google</div>
        <?php if (false) { ?>
          <div class="icon-correct">
            <?= W_Structure::getfile('_framework/structure/image/icon/general/verify.svg') ?>
          </div>
        <?php } ?>
      </div>
      <div class="social-action">
        <?php if (false) { ?>
          <div class="social-link">
            <div class="link-group">
              <?= W_Structure::getfile('_framework/structure/image/icon/general/link-primary.svg') ?>
              <div class="link">Jannermolyncux@gmail.com</div>
            </div>
            <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-disconnect'], ['type' => '', 'text' => 'DISCONNECT']); ?>
          </div>
        <?php } else { ?>
          <div class="social-link">
            <div class="social-not-config mr-15px mb-5px">Synchronization is not configured</div>
            <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-connect'], ['type' => '', 'text' => 'CONNECT']); ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="social-area">
      <div class="social-name">
        <div class="icon-logo">
          <?= W_Structure::getfile('_framework/structure/image/icon/general/apple.svg') ?>
        </div>
        <div class="name">Connect with Apple Account</div>
        <?php if (false) { ?>
          <div class="icon-correct">
            <?= W_Structure::getfile('_framework/structure/image/icon/general/verify.svg') ?>
          </div>
        <?php } ?>
      </div>
      <div class="social-action">
        <?php if (false) { ?>
          <div class="social-link">
            <div class="link-group">
              <?= W_Structure::getfile('_framework/structure/image/icon/general/link-primary.svg') ?>
              <div class="link">Jannermolyncux@gmail.com</div>
            </div>
            <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-disconnect'], ['type' => '', 'text' => 'DISCONNECT']); ?>
          </div>
        <?php } else { ?>
          <div class="social-link">
            <div class="social-not-config mr-15px mb-5px">Synchronization is not configured</div>
            <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-connect'], ['type' => '', 'text' => 'CONNECT']); ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('edit_password_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="modal-header">
  <h5 class="modal-title">Edit Password</h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row aww-regex-box">
      <div class="col-md-3 font-14px text-secondary mt-10px">
        <label>Old Password<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?= TiwForm::normal('password', '', ['name' => 'old_password', 'placeholder' => 'Enter', 'required' => true, 'class' => 'mb-0']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary mt-10px">
        <label>New Password<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?= TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Enter', 'required' => true, 'class' => 'mb-0 check_password_condition']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary mt-10px">
        <label>Confirm Password<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?= TiwForm::normal('password', '', ['name' => 'confirm_password', 'placeholder' => 'Enter', 'required' => true, 'class' => 'mb-10 check_password_condition']); ?>
      </div>
      <div class="col-12 <?= $user_type_info['is_strict_password'] ? '' : 'd-none'; ?>">
        <?php
        $password_options = [
          'is-match' => true,
        ];
        if ($user_type_info['is_strict_password']) {
          $password_options['is-char'] = true;
          $password_options['is-word'] = true;
          $password_options['is-number'] = true;
        }
        TiwForm::checkForm('check_password_condition', 'btn_check_condition_event', $password_options);
        ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_change_user_password', 'type' => 'submit', 'class' => 'min-w-100px btn_check_condition_event'], ['text' => 'Confirm']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php
if ($user_type_info['is_action_pin']) {
  Tiwdal::startModal('edit_pin_modal', 'modal-md');
?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title">Edit PIN</h5>
  </div>
  <form method="post">
    <div class="modal-body">
      <div class="row aww-regex-box">
        <div class="col-md-3 font-14px text-secondary mt-10px">
          <label>Old PIN<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-9">
          <?php TiwForm::normal('number', '', ['name' => 'old_pin', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'mb-0']); ?>
        </div>
        <div class="col-md-3 font-14px text-secondary mt-10px">
          <label>New PIN<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-9">
          <?php TiwForm::normal('number', '', ['name' => 'pin', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'mb-0 check_pin_condition']); ?>
        </div>
        <div class="col-md-3 font-14px text-secondary mt-10px">
          <label>Confirm PIN<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-9">
          <?php TiwForm::normal('number', '', ['name' => 'pin', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'mb-10px check_pin_condition']); ?>
        </div>
        <div class="col-12">
          <?php
          $pin_options = [
            'is-char' => true, //ถ้าต้องการให้เช็ค ตัวอักษร 8 ตัวให้ใส่บรรทัดนี้มา
            'is-char-number' => 4,
            'title' => 'Create a PIN that:',
            'is-match' => true,
          ];
          TiwForm::checkForm('check_pin_condition', 'btn_check_pin_event', $pin_options);
          ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <?php
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
      TiwForm::normal('btn', '', ['name' => 'submit_change_user_pin', 'type' => 'submit', 'class' => 'min-w-100px btn_check_pin_event'], ['text' => 'Confirm']);
      ?>
    </div>
  </form>
<?php
  Tiwdal::endModal();
}
?>
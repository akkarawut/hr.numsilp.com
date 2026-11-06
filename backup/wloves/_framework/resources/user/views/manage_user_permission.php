<?php
$url = 'manage_user.php?c=' . $_GET['c'] . '&user_id=' . $user_id . '&page=2';
$hide_permission = User_type::getHiddenPermissionList($user_type_info['id']);
if ($_POST) {
  if (isset($_POST['submit_update_permission'])) {
    unset($_POST['submit_update_permission']);
    if (isset($_POST['permission_all']) && isset($_POST['checked'])) {
      $permission_all =  ($_POST['permission_all']) ? $_POST['permission_all'] : $hide_permission;
      foreach ($permission_all as $permission => $value) {
        $permission_all[$permission] = isset($_POST['checked'][$permission]) ? 1 : 0;
      }
    }
    if (!isset($_POST['checked'])) {
      $permission_all = $hide_permission;
      foreach ($permission_all as $key => $value) {
        $permission_all[$key] =  0;
      }
    }
    unset($_POST['checked']);

    $result = User_Permission::triggerUserPermission($user_id, $permission_all);
    $redirect = $url;
  } else if (isset($_POST['submit_change_user_password'])) {
    $data = [
      'password' => $_POST['password']
    ];
    $result = User::updateUser($user_id, $data);
  } else if (isset($_POST['submit_change_user_pin'])) {
    $result = User::updateUser($user_id, $_POST['pin']);
  }

  W_Main::responseAPI();
}



$is_edit = (isset($_GET['is_edit']) && $_GET['is_edit']) ? true : false;
$permission = User_Permission::selectPermissionByID($user_id);

function form2col($detail1 = '', $detail2 = '')
{
  echo '<div class="row mb-20px">
          <div class="col-md-4 col-lg-3 font-14px text-secondary">' . $detail1 . '</div>
          <div class="col-md-8 col-lg-9 text-info font-Medium">' . $detail2 . '</div>
        </div>';
}
?>
<div class="container-detail p-15px mb-10px">
  <div class="mb-10px">
    <div class="text-uppercase font-16px font-SemiBold text-info">Login & Permission - <span class="text-primary"><?= $user_info['title'] . ' ' . $user_info['full_name'] ?></span></div>
    <div class="font-14px text-secondary">Manage user account and configure user’s permission.</div>
  </div>
  <hr class="mt-0 mb-15px mx--15px">

  <div class="user_login">
    <?php
    form2col('Main Login Type', ucfirst($user_type_info['indentity_field']));

    $status = $user_info['is_ban'] ? '<span class="text-danger">(Block)</span>' : '<span class="text-primary">(Active)</span>';
    form2col('Username', $user_info['username'] . ' ' . $status);

    $password_html = '<div class="d-flex justify-content-between flex-wrap flex-sm-nowrap">
                        <div class="w-100">
                          <div>•••••••</div>
                          <div class="text-secondary">Last Edit: ' . W_Main::formatDate($user_info['update_date_time'], 'd/m/Y') . '</div>
                        </div>
                        <div class="cursor-pointer text-primary fotn-14px font-Medium min-w-120px" toggle-edit-modal="#edit_password_modal">Change Password</div>
                      </div>';
    form2col('Password', $password_html);

    if ($user_type_info['is_action_pin']) {
      $pin_html = '<div class="d-flex justify-content-between flex-wrap flex-sm-nowrap">
                        <div class="w-100">
                          <div>••••</div>
                          <div class="text-secondary">Last Edit: ' . W_Main::formatDate($user_info['update_date_time'], 'd/m/Y') . '</div>
                        </div>
                        <div class="cursor-pointer text-primary fotn-14px font-Medium min-w-80px" toggle-edit-modal="#edit_pin_modal">Change PIN</div>
                      </div>';
      form2col('PIN', $pin_html);
    }
    ?>
  </div>

  <div class="scocial_account">
    <div class="font-14px text-uppercase font-SemiBold text-info mb-10px">Social account</div>
    <div class="social-container">
      <div class="social-area">
        <div class="social-name">
          <div class="icon-logo">
            <?= file_get_contents('../../structure/image/icon/general/facebook.svg') ?>
          </div>
          <div class="name">Connect with Facebook</div>
          <?php if (false) { ?>
            <div class="icon-correct">
              <?= file_get_contents('../../structure/image/icon/general/verify.svg') ?>
            </div>
          <?php } ?>
        </div>
        <div class="social-action">
          <?php if (false) { ?>
            <div class="social-link">
              <div class="link-group">
                <?= file_get_contents('../../structure/image/icon/general/link-primary.svg') ?>
                <div class="link">Jannermolyncux@gmail.com</div>
              </div>
              <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-disconnect'], ['type' => '', 'text' => 'DISCONNECT']); ?>
            </div>
          <?php } else { ?>
            <div class="social-not-config">Synchronization is not configured</div>
          <?php } ?>
        </div>
      </div>

      <div class="social-area">
        <div class="social-name">
          <div class="icon-logo">
            <?= file_get_contents('../../structure/image/icon/general/google.svg') ?>
          </div>
          <div class="name">Connect with Google</div>
          <?php if (false) { ?>
            <div class="icon-correct">
              <?= file_get_contents('../../structure/image/icon/general/verify.svg') ?>
            </div>
          <?php } ?>
        </div>
        <div class="social-action">
          <?php if (false) { ?>
            <div class="social-link">
              <div class="link-group">
                <?= file_get_contents('../../structure/image/icon/general/link-primary.svg') ?>
                <div class="link">Jannermolyncux@gmail.com</div>
              </div>
              <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-disconnect'], ['type' => '', 'text' => 'DISCONNECT']); ?>
            </div>
          <?php } else { ?>
            <div class="social-not-config">Synchronization is not configured</div>
          <?php } ?>
        </div>
      </div>

      <div class="social-area">
        <div class="social-name">
          <div class="icon-logo">
            <?= file_get_contents('../../structure/image/icon/general/apple.svg') ?>
          </div>
          <div class="name">Connect with Apple Account</div>
          <?php if (false) { ?>
            <div class="icon-correct">
              <?= file_get_contents('../../structure/image/icon/general/verify.svg') ?>
            </div>
          <?php } ?>
        </div>
        <div class="social-action">
          <?php if (false) { ?>
            <div class="social-link">
              <div class="link-group">
                <?= file_get_contents('../../structure/image/icon/general/link-primary.svg') ?>
                <div class="link">Jannermolyncux@gmail.com</div>
              </div>
              <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-social-disconnect'], ['type' => '', 'text' => 'DISCONNECT']); ?>
            </div>
          <?php } else { ?>
            <div class="social-not-config">Synchronization is not configured</div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-detail px-15px pt-10px pb-10px br-10px">
  <form action="" method="post">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div>
        <h3 class="font-SemiBold font-16px mb-0 text-uppercase text-info">permission</h3>
        <p class="font-Regular font-14px mb-10px">Configure User’s Permission</p>
      </div>
      <div class="d-flex align-items-center mb-10px">
        <?php if ($is_edit) { ?>
          <a href="<?= $url ?>" class="btn btn-light h-35px mr-5px">CANCEL</a>
          <button class="btn btn-primary w-120px" type="submit" name="submit_update_permission">SAVE</button>
        <?php } else { ?>
          <a href="<?= $url . '&is_edit=1' ?>" class="btn btn-outline-info border">EDIT DATA</a>
        <?php }  ?>
      </div>
    </div>
    <hr class="my-0 mx--15px">
    <div class="px-15px pt-15px pb-0">
      <?= W_Permission::templateConfigPermission(['is_edit' => $is_edit, 'permission' => $permission, 'hide_permission' => $hide_permission]); ?>
    </div>
  </form>
</div>

<?php Tiwdal::startModal('edit_password_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="modal-header">
  <h5 class="modal-title">Change password</h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row aww-regex-box">
      <div class="col-md-3 font-14px text-secondary mt-10px">
        <label>New Password</label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Enter', 'required' => true, 'class' => 'mb-0 check_password_condition']); ?>
      </div>
      <?php if ($user_type_info['is_strict_password']) { ?>
        <div class="col-md-3"></div>
        <div class="col-md-9">
          <?php
          $password_options = [
            'is-char' => true, //ถ้าต้องการให้เช็ค ตัวอักษร 8 ตัวให้ใส่บรรทัดนี้มา
            'is-word' => true, //ถ้าต้องการให้เช็ค ตัวอักษร a-z ให้ใส่บรรทัดนี้มา
            'is-number' => true, //ถ้าต้องการให้เช็ค ตัวเลขและตัวอักษรพิเศษอื่น ๆ ให้ใส่บรรทัดนี้มา
          ];
          TiwForm::checkForm('check_password_condition', 'btn_check_condition_event', $password_options);
          ?>
        </div>
      <?php } ?>
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
    <h5 class="modal-title">Change PIN</h5>
  </div>
  <form method="post">
    <div class="modal-body">
      <div class="row aww-regex-box">
        <div class="col-md-3 font-14px text-secondary mt-10px">
          <label>New PIN</label>
        </div>
        <div class="col-md-9">
          <?php TiwForm::normal('number', '', ['name' => 'pin', 'placeholder' => 'PIN', 'required' => 'required', 'class' => 'mb-0 check_pin_condition']); ?>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-9">
          <?php
          $pin_options = [
            'is-char' => true, //ถ้าต้องการให้เช็ค ตัวอักษร 8 ตัวให้ใส่บรรทัดนี้มา
            'is-char-number' => 4,
            'title' => 'Create a PIN that:',
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

<script>
  $(document).ready(function() {
    $(document).on('keyup', '.password, .re_password', function(e) {
      var password = $('.password').val();
      var re_password = $('.re_password').val();

      if (password == re_password) {
        $('.check_pass').addClass('hidden');
        $('.btn_check_pass').prop('disabled', false);
      } else {
        $('.check_pass').removeClass('hidden');
        $('.btn_check_pass').prop('disabled', true);
      }
    });

    $(document).on('keyup', '.pin, .re_pin', function(e) {
      var pin = $('.pin').val();
      var re_pin = $('.re_pin').val();

      if (pin == re_pin) {
        $('.check_pin').addClass('hidden');
        $('.btn_check_pin').prop('disabled', false);
      } else {
        $('.check_pin').removeClass('hidden');
        $('.btn_check_pin').prop('disabled', true);
      }
    });

    $(document).on('change', '.change-status', function(e) {
      var form = $(this).parents('form');
      form.submit();
    });

    $(document).on('click', '.show-less', function(e) {
      var name = $(this).attr('data-permission_name');
      $(this).toggleClass('hide');
      $('.body_hide_' + name).toggleClass('hidden');

      var text = $('.text_more_' + name).text();
      if (text == 'Show More') {
        $('.text_more_' + name).text('Show Less');
      } else {
        $('.text_more_' + name).text('Show More');
      }
    });
  });
</script>
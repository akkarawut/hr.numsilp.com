<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../_framework/import.php';


if ($_POST) {

  if (isset($_POST['submit_login'])) {
    $response = User::login($_POST['username'], $_POST['password'], ['remember_me' => true]);

    if (isset($response['response_status']) && $response['response_status'] == true) {
      // User_Firebase::register($response['response_data']['id'], $_POST['firebase_registration_id'], ['type' => 'web']);
      W_Main::redirect('../landing/?action=welcome');
    } else {
      if (isset($response['response_detail'])) {
        W_Main::notification(($response['response_detail']['message']) ? $response['response_detail']['message'] : 'Empty response', 'error');
        W_Main::redirect('');
      } else if (isset($response['response_message'])) {
        W_Main::notification(($response['response_message']) ? $response['response_message'] : 'Empty response', 'error');
        W_Main::session('error_login', 'error');
        W_Main::session('user_login', $_POST['username']);
        W_Main::session('pass_login', $_POST['password']);
        W_Main::redirect('');
      }
    }
  }
}
if (!$program_wolves_setup) {
  W_Main::redirect('../error/404.php');
}
$logo_image = $program_wolves_setup['logo_image'];
$background = $program_wolves_setup['signin_image'];
if (strpos($background, '/placeholder/')  === false) {
  $background = $background;
} else {
  $background = '';
}
$program = $program_wolves_setup['program'];
if (!isset($program)) {
  die();
}

$link = 'index.php';

?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta(); ?>
</head>

<body>
  <?php include_once '../layout/blank_header.php'; ?>
  <div class="w-loves-login-container" style="background-image:url(<?= $background ? $background : '../../structure/image/bg-default.png' ?>)">
    <div class="content-left">
      <div class="login-container-wrap">
        <?php if (W_User::isDev()) { ?>
          <a href="../landing/index.php" class="btn-back">
            <?= file_get_contents('../../../_framework/structure/image/icon/arrow/left-arrow.svg') ?>
            <span>Back</span>
          </a>
        <?php } ?>
        <div class="logo-login-box">
          <div class="logo-element-group">
            <?php if (strpos($logo_image, '/placeholder/')  === false) { ?>
              <img src="<?= $logo_image  ?>" class="logo-items">
            <?php  } else { ?>
              <img src="<?= '../../../_framework/structure/image/logo/wloves-pro.svg' ?>" class="logo-items">
            <?php } ?>
          </div>
        </div>
        <form id="login" method="post" class="login-box">
          <div class="greeting-text-group text-center">
            <h6 class='font-14px font-SemiBold  mb-6px' style="color:<?= $program['color_highlight'] ?>">Welcome to</h6>
            <h2 class='font-20px font-SemiBold text-info'><?= $program['program_name'] ? $program['program_name'] : 'program name' ?> </h2>
          </div>
          <?php
          $class = '';
          if (W_Main::session('error_login') == 'error') {
            $class = 'is_error';
          }
          ?>
          <div class="form-group mb-15px ">
            <div class="d-flex flex-column-reverse">
              <input type="text" name="username" class="form-control <?= $class ?>" value="<?php echo (W_Main::session('user_login')) ? W_Main::session('user_login') : ''; ?>" required autofocus>
              <label class="control-label">Username</label>
            </div>
            <div class="empty-val-icon"></div>
            <span class="empty-val-text">Please enter an Username</span>
          </div>
          <div class="form-group mb-10px">
            <div class="d-flex flex-column-reverse">
              <input type="password" name="password" class="form-control <?= $class ?>" value="<?php echo (W_Main::session('pass_login')) ? W_Main::session('pass_login') : ''; ?>" required>
              <label class=" control-label">Password</label>
            </div>
            <div class="toggle-password"></div>
            <div class="empty-val-icon"></div>
            <span class="empty-val-text">Please enter an Password</span>
          </div>
          <div class="form-group mb-5px">
            <?= TiwForm::normal('checkbox', '', ['name' => 'show_password'], ['style' => '3', 'label' => 'Show password']); ?>
          </div>
          <div class="text-error-internet text-danger" style="display: none;">
            The username or password is not correct. <br>
            You entered is incorrect.
          </div>
          <?php if (W_Main::session('error_login') == 'error') { ?>
            <div class="text-error">
              The username or password is not correct. <br>
              You entered is incorrect.
            </div>
          <?php
            W_Main::removeSession('error_login');
            W_Main::removeSession('user_login');
            W_Main::removeSession('pass_login');
          }
          ?>
          <!-- <input type="hidden" name="firebase_registration_id"> -->
          <input type="hidden" name="submit_login">
        </form>
        <div class=" mt-50px d-flex justify-content-between">
          <button type="button" class="btn-login">
            <span>Login</span>
            <div class="mask"></div>
          </button>
        </div>
        <div class="mt-20px text-center  font-14px text-secondary">
          If you experience any problems, please contact us at <br>
          <u class="font-SemiBold"><?= ($program['support_email']) ? $program['support_email'] : 'w.lovesx@wolvescrop.com' ?></u>
        </div>
      </div>
    </div>
  </div>


  <?php include_once '../layout/footer.php'; ?>
  <?php W_Structure::loadFooter(); ?>

  <script>
    $(document).ready(function() {
      if ($('input[name=password]').val()) {
        $('.toggle-password').addClass('show');
      }
      $('.btn-login').on('click', function() {

        var username = $('#login').find('input[name=username]');
        var password = $('#login').find('input[name=password]');
        username.blur();
        password.blur();

        if (!username.val()) {
          username.addClass('empty-val');
          username.parents('.form-group').find('.empty-val-icon').addClass('show');
          username.parents('.form-group').find('.empty-val-text').text('Please enter an Username');
          username.parents('.form-group').find('.empty-val-text').show();
          // username.focus();
        } else {
          // password.focus();
        }
        if (!password.val()) {
          password.addClass('empty-val');
          password.parents('.form-group').find('.empty-val-icon').addClass('show');
          password.parents('.form-group').find('.empty-val-text').text('Please enter an Password');
          password.parents('.form-group').find('.empty-val-text').show();
        }
        if (username.val() && password.val()) {
          $('#login').submit();
        }
      });


      $('.toggle-password').click(function(e) {
        e.preventDefault();
        $(this).toggleClass('show');
        $(this).parent().find('input[name=password]').val('');
        $(this).parent().find('input[name=password]').addClass('empty-val');
        $(this).parents('.form-group').find('.empty-val-icon').addClass('show');
        $(this).parents('.form-group').find('.empty-val-text').text('This field is required!');
        $(this).parents('.form-group').find('.empty-val-text').toggle();
        $(this).parent().find('input[name=password]').focus();

      });
      $('input[name=show_password]').on('change', function() {
        if ($(this).is(':checked')) {
          $('input[name=password]').attr('type', 'text');
          $('input[name=password]').focus();
        } else {
          $('input[name=password]').attr('type', 'password');
        }
      });
      $('input[name=username]').on('keyup', function() {
        if ($(this).hasClass('empty-val')) {
          $(this).removeClass('empty-val');
          $(this).parents('.form-group').find('.empty-val-icon').removeClass('show');
          $(this).parents('.form-group').find('.empty-val-text').toggle();
        }
        var format = /[!@#$%^&*()+\-=\[\]{};':"\\|,.<>\/?]+/;
        if (format.test($(this).val())) {
          $(this).addClass('empty-val');
          $(this).parents('.form-group').find('.empty-val-text').text('Please Enter a Valid Username');
          $(this).parents('.form-group').find('.empty-val-text').toggle();
        } else {}
      });
      $('input[name=password]').on('keyup', function() {
        if ($(this).hasClass('empty-val')) {
          $(this).removeClass('empty-val');
          $(this).parents('.form-group').find('.empty-val-icon').removeClass('show');
          $(this).parents('.form-group').find('.empty-val-text').toggle();
        }

        if ($(this).val()) {
          if (!$('.toggle-password').hasClass('show')) {
            $('.toggle-password').addClass('show');
          }
        }
      });

      $(document).on("keydown", function(e) {
        if (e.keyCode === 13) {
          e.preventDefault();
          $('button.btn-login').trigger('click');
        }
      });

      $('.render-modal').click(function(e) {
        $('#ready_login').modal('hide')
      });

      <?php if (isset($_GET['success'])) { ?>
        $('#ready_login').modal('show');
        history.replaceState(null, null, 'index.php');
        setTimeout(() => {
          $('#ready_login').modal('hide');
        }, 6000);
      <?php } ?>
    });
  </script>
</body>

</html>
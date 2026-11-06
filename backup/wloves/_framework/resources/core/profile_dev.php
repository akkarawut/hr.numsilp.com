<?php
$_WLOVES['no_check_permission'] = 1;
$_PAGE['permission'] = ['index', '', 'profile'];
require_once '../../../_framework/import.php';

W_Structure::loadModules(['datatables', 'boatnav']);

$user_data = W_WLoves::$initial_data['current_user_data'];
$dev_info = $user_data['Dev'];

function profileTemplate($title = '', $detail = '', $options = [])
{
  echo '<div class="col-6 col-md-5 col-lg-3 col-xl-2 font-14px text-secondary pt-10px">' . $title . '</div>
        <div class="col-6 col-md-7 col-lg-3 col-xl-4 text-info pt-10px">' . $detail . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php
  include_once 'structure/layout/header.php';
  include_once 'structure/layout/sidenav.php';
  ?>

  <div class="top-card-back-title">Profile</div>

  <div class="bg-card br-bottom-10px">
    <div class="p-15px">
      <div class="text-uppercase font-Medium text-info">My Profile | <span class="text-primary"><?= $dev_info['username'] ?></span></div>
      <div class="font-14px text-secondary">View your General information profile.</div>
    </div>

    <hr class="my-0">
    <div class="form-with-profile border-bottom-0">
      <div class="form-profile">
        <div class="font-14px font-Medium text-info mb-10px">IMAGE</div>
        <?php
        $profile_options = [
          'width' => '140px',
          'height' => '100%',
          'bg-img' => '../../_framework/modules/tiwform/img/upload-profile.svg',
          'is_delete' => false,
          'is_upload' => false,
        ];
        TiwForm::normal('upload-img', $dev_info['profile_image'], ['name' => 'user_profile_image', 'is_placeholder' => true], $profile_options);
        ?>
      </div>
      <div class="form-detail">
        <div class="font-14px font-Medium text-info mb-10px text-uppercase">General information</div>
        <div class="row">
          <?php
          profileTemplate('ID', $dev_info['id']);
          echo '<div class="col-lg-6"></div>';
          profileTemplate('Username', $dev_info['username']);
          echo '<div class="col-lg-6"></div>';
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php
  include_once 'structure/layout/footer.php';
  W_Structure::loadFooter('../../');
  ?>
</body>

</html>
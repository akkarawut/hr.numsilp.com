<?php
$setting_profile = $module_setup['json_image_profile'];
$image_profile = User::getProfileImage($id);
if (!$setting_profile) {
  $setting_profile = [
    'profile_type' => 'circle',
    'profile_size' => '200',
    'alignment' => 'center',
    'is_show_detail' => '1',
    'is_show_add' => '1',
    'is_show_profile' => '1',
    'is_update' => '1',
    'is_required' => '1',
  ];
}
$alignment = '';
if ($setting_profile['alignment'] == 'left') {
  $alignment = 'flex-start';
} else if ($setting_profile['alignment'] == 'center') {
  $alignment = 'center';
} else if ($setting_profile['alignment'] == 'right') {
  $alignment = 'flex-end';
}
$is_show = true;
if (isset($is_profile) && !$setting_profile['is_show_profile']) {
  $is_show = false;
} else if (!$setting_profile['is_show_add']) {
  $is_show = false;
}
?>

<style>
  .profile-container {
    padding: 20px;
    display: flex;
    min-height: 200px;
    align-items: center;
    justify-content: <?= $alignment ?>;
  }

  .profile-image {
    width: <?= $setting_profile['profile_size'] . 'px' ?>;
  }

  .profile-image .live-once-img-area {
    border-radius: <?= $setting_profile['profile_type'] == 'circle' ? '50%' : '0' ?>;
    overflow: hidden;
  }
</style>
<?php if ($is_show) : ?>
  <form id="form_user_images" method="post" enctype="multipart/form-data">
    <?php TiwForm::normal('hidden', $id, ['name' => 'user_id']); ?>
    <div class="profile-container">
      <div class="profile-image">
        <?php
        $options = [
          'width' => $setting_profile['profile_size'] . 'px',
          'height' =>  '100%',
          'bg-img' => 'assets/image/user_default.png',
          'is_btn' => 0,
        ];
        TiwForm::normal('upload-img', $image_profile, ['name' => 'user_profile_image', 'accept' => "image/*"], $options);
        $data_file =   W_Main::datafile($image_profile);
        ?>
        <?php if ($setting_profile['is_update'] || $data_file['is_placeholder']) : ?>
          <button type="button" class="btn-upload-img" onclick="eventUpload(event)">
            <?= W_Structure::getfile('_framework/resources/user_new/assets/image/icon/upload-icon.svg') ?>
          </button>
        <?php endif; ?>
      </div>
    </div>
  </form>
<?php endif; ?>
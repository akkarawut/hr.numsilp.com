<?php
$setting_profile = $module_setup['json_image_profile'];
if (!$setting_profile) {
  $setting_profile = [
    'profile_type' => 'circle',
    'profile_size' => '200',
    'alignment' => 'center',
    'is_show_detail' => '1',
    'is_show_add' => '1',
    'is_show_profile' => '1',
    'is_update' => '1',
    'is_required' => '0',
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
$is_required = $setting_profile['is_required'] ? 'required' : '';
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
<?php if ($setting_profile['is_show_add']) : ?>
  <?php TiwForm::normal('hidden', 1, ['name' => 'is_profile']); ?>
  <div class="profile-container">
    <div class="profile-image">
      <?php
      $options = [
        'width' => $setting_profile['profile_size'] . 'px',
        'height' =>  '100%',
        'bg-img' => 'assets/image/user_default.png',
        'is_btn' => 0,
      ];
      TiwForm::normal('upload-img', '', ['name' => 'user_profile_image', $is_required => true, 'accept' => "image/*"], $options);
      ?>
      <button type="button" class="btn-upload-img" onclick="eventUploadAdd(event)">
        <?= W_Structure::getfile('_framework/resources/user_new/assets/image/icon/upload-icon.svg') ?>
      </button>
    </div>
  </div>
<?php else : ?>
  <?php TiwForm::normal('hidden', 0, ['name' => 'is_profile']); ?>
<?php endif; ?>
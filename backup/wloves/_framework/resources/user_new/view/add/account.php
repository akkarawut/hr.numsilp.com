<?php
$setting_account = $module_setup['json_tab_account_info'];

if (!$setting_account) {
  $setting_account = [
    'is_title' => '1',
    'title_name' => Ty::get('account_info_access_system'),
    'title_font_size' => '15',
    'title_font_bold' => 1,
    'title_font_color' => 'var(--v2-color-info)',
    'username_font_size' => '16',
    'username_font_bold' => '0',
    'username_font_color' => 'var(--v2-color-info)',
    'password_type' => 'circle',
    'is_edit_password' => '1',
    'is_verify_password' => '1',
    'is_update_date_time' => '1',
    'alignment' => 'left',
    'type_display' => 'row',
  ];
}
?>
<style>
  .title-account {
    color: <?= $setting_account['title_font_color'] ?>;
    font-size: <?= $setting_account['title_font_size'] . 'px' ?>;
    font-weight: <?= $setting_account['title_font_bold'] ? 'bold' : '' ?>;
  }

  .value-username {
    color: <?= $setting_account['username_font_color'] ?>;
    font-size: <?= $setting_account['username_font_size'] . 'px' ?>;
    font-weight: <?= $setting_account['username_font_bold'] ? 'bold' : '' ?>;
  }
</style>
<div class="p-20px">
  <p class=" font-15px font-Bold"><?= Ty::get('account_info_access_system') ?></p>
  <div class="input-group">
    <div class="label">
      <span class="required"><?= Ty::get('username') ?></span>
    </div>
    <div class="input">
      <?php TiwForm::normal('text', '', ['name' => 'username', 'placeholder' => Ty::get('fill_in'), 'required' => true]); ?>
    </div>
  </div>
  <div class="input-group">
    <div class="label">
      <span class="required"><?= Ty::get('password') ?></span>
    </div>
    <div class="input">
      <?php TiwForm::normal('password', '', ['name' => 'password', 'class' => 'new_password', 'minlength' => "6", 'placeholder' =>  Ty::get('fill_in'), 'required' => true]); ?>
    </div>
  </div>
  <div class="input-group">
    <div class="label">
      <span class="required"><?= Ty::get('confirmed_pass') ?></span>
    </div>
    <div class="input">
      <?php TiwForm::normal('password', '', ['name' => 'verify_password', 'oninput' => "eventVerifyPassword(event)", 'minlength' => "6", 'placeholder' => Ty::get('fill_in'), 'required' => true]); ?>
      <span class="text-danger text_verify"></span>
    </div>
  </div>
</div>

<hr class="border-color-bg-5px ">
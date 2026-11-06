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

  .input-group {
    flex-direction: <?= $setting_account['type_display']  ?>;
    justify-content: <?= $setting_account['alignment'] == 'left' ? 'flex-start' : 'flex-end'   ?>;
  }

  .input-group .input {
    width: auto;
  }
</style>
<div class="p-20px">
  <?php if ($setting_account['is_title']) : ?>
    <p class="title-account"><?= $setting_account['title_name'] ?></p>
  <?php endif; ?>
  <div class="input-group">
    <div class="label">
      <span><?= Ty::get('username') ?></span>
    </div>
    <div class="input">
      <span class="value-username my-auto"><?= $get_user['username'] ?></span>
    </div>
  </div>
  <div class="input-group">
    <div class="label">
      <span><?= Ty::get('password') ?></span>
    </div>
    <div class="input flex-column align-items-start">
      <div class="d-flex align-items-center">
        <span class="font-18px  font-SemiBold mr-30px">
          <?php if ($setting_account['password_type'] == 'circle') : ?>
            •••••••
          <?php elseif ($setting_account['password_type'] == 'star') : ?>
            ********
          <?php else : ?>
            ********
          <?php endif; ?>
        </span>
        <?php if ($setting_account['is_edit_password']) : ?>
          <div class=" font-Medium cursor-pointer text-primary text-underline" <?= Tiwdal::register('edit_password_modal', []); ?>>
            <?= Ty::get('pass_edit', [], ["case" => "ucfirst"]) ?>
          </div>
        <?php endif; ?>
      </div>
      <span class="font-14px text-secondary">
        <?php if ($setting_account['is_update_date_time']) : ?>
          <?php if ($get_user['password_datetime']) : ?>
            <?= Ty::get('last_edited') ?> <?= W_Main::formatDate($get_user['password_datetime'], 'd/m/Y, H:i'); ?> <?= Ty::get('by') ?> <?= $get_user['password_username']; ?>
          <?php endif; ?>
        <?php endif; ?>
      </span>
    </div>
  </div>
</div>
<hr class="border-color-bg-5px">

<?php Tiwdal::startModal('edit_password_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="modal-header">
  <h5 class="modal-title text-primary"><?= Ty::get('change_pass', [], ["case" => "ucfirst"]) ?></h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="input-group">
      <div class="label">
        <span class="required"><?= Ty::get('new_pass', [], ["case" => "ucfirst"]) ?></span>
      </div>
      <div class="input">
        <?php TiwForm::normal('password', '', ['name' => 'new_password', 'class' => 'new_password', 'minlength' => "6", 'placeholder' => '••••••', 'required' => true]); ?>
      </div>
    </div>
    <?php if ($setting_account['is_verify_password']) : ?>
      <div class="input-group">
        <div class="label">
          <span class="required"><?= Ty::get('confirm_new_password') ?></span>
        </div>
        <div class="input">
          <?php TiwForm::normal('password', '', ['name' => 'verify_new_password', 'minlength' => "6", 'oninput' => "eventVerifyPassword(event)", 'placeholder' => '••••••', 'required' => true]); ?>
          <span class="text-danger text_verify"></span>
        </div>
      </div>
    <?php else : ?>
      <input type="hidden" name="no_verify" value="1">
    <?php endif; ?>
  </div>
  <div class="modal-footer justify-content-between">
    <?php if ($setting_account['is_update_date_time']) : ?>
      <?php if ($get_user['password_datetime']) : ?>
        <span class="text-placeholder">
          <?= Ty::get('last_edited') ?> <?= W_Main::formatDate($get_user['password_datetime'], 'd/m/Y, H:i'); ?> <?= Ty::get('by') ?> <?= $get_user['password_username']; ?>
        </span>
      <?php endif; ?>
    <?php endif; ?>
    <div class="d-flex">
      <?php
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get('cancel')]);
      TiwForm::normal('btn', '', ['name' => 'submit_change_user_password', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => Ty::get('save_1')]);
      ?>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>
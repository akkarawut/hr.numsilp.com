<?php
$module_code = $user_information['id']; //from user_type.php

if ($_POST) {
  if (isset($_POST['submit_update_hide_permission'])) {
    unset($_POST['submit_update_hide_permission']);
    if (isset($_POST['permission_all']) && isset($_POST['checked'])) {
      foreach ($_POST['permission_all'] as $permission => $value) {
        $_POST['permission_all'][$permission] = isset($_POST['checked'][$permission]) ? 1 : 0;
      }
    }
    unset($_POST['checked']);
    $result = User_type::triggerHiddenPermission($id, $_POST['permission_all']);
    if ($result['response_status']) {
      $redirect = 'program_setting.php?setting=user_type&id=' . $id . '&nav_type=1';
    }
  }
  W_Main::responseAPI();
}

$is_edit = (isset($_GET['is_edit']) && $_GET['is_edit']) ? true : false;

$hide_checked = json_decode($user_information['hidden_permission'], true);
?>
<div class="row">
  <div class="col-lg-12 px-0">
    <form action="" method="post">
      <div class="d-flex align-items-center justify-content-between flex-wrap px-15px pt-10px">
        <div>
          <h3 class="font-SemiBold font-16px mb-0"><?= Ty::get('hide_permis') ?></h3>
          <p class="font-Regular font-14px mb-10px"><?= Ty::get('hide_page') ?></p>
        </div>
        <div class="d-flex align-items-center mb-10px">
          <?php
          if ($is_edit) {
            echo '<a href="program_setting.php?setting=user_type&id=' . $id . '&nav_type=1" class="btn btn-light h-35px mr-5px">' . Ty::get('cancel', [], ["case" => "strtoupper"]) . '</a>';
            TiwForm::normal('btn', 'submit', ['name' => 'submit_update_hide_permission'], ['text' => Ty::get('save')]);
          } else {
            echo '<a href="program_setting.php?setting=user_type&id=' . $id . '&nav_type=1&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => Ty::get('edit_data'), 'type' => '', 'is_return' => true]) . '</a>';
          }
          ?>
        </div>
      </div>
      <hr class="my-0">
      <div class="px-15px pt-15px pb-0">
        <div class="px-15px">
          <?= W_Permission::templateConfigHidePermission(['is_edit' => $is_edit, 'permission' => $hide_checked]); ?>
        </div>
      </div>
    </form>
  </div>
</div>
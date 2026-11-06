<?php
$hide_permission = User_type::getHiddenPermissionList($id);
if ($_POST) {
  if (isset($_POST['submit_update_auto_permission'])) {
    unset($_POST['submit_update_auto_permission']);
    if (isset($_POST['permission_all']) && isset($_POST['checked'])) {
      if ($hide_permission) {
        $permission_all = $hide_permission;
      } else {
        $permission_all = $_POST['permission_all'];
      }
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
    $result = User_type::triggerSettingPermission($id, $permission_all);
    $redirect = 'program_setting.php?setting=user_type&id=' . $id . '&nav_type=4';
  }
  W_Main::responseAPI();
}

$is_edit = (isset($_GET['is_edit']) && $_GET['is_edit']) ? true : false;

$permission = User_type::getSettingPermissionList($id);

?>
<div class="row">
  <div class="col-lg-12 px-0">
    <form action="" method="post">
      <div class="d-flex align-items-center justify-content-between flex-wrap px-15px pt-10px">
        <div>
          <h3 class="font-SemiBold font-16px mb-0">Auto Permission</h3>
          <p class="font-Regular font-14px mb-10px">
            Auto page it will be effect with module user used this user type in main.
          </p>
        </div>
        <div class="d-flex align-items-center mb-10px">
          <?php
          if ($is_edit) {
            echo '<a href="program_setting.php?setting=user_type&id=' . $id . '&nav_type=4" class="btn btn-light h-35px mr-5px">' . Ty::get('cancel', [], ["case" => "strtoupper"]) . '</a>';
            TiwForm::normal('btn', 'submit', ['name' => 'submit_update_auto_permission'], ['text' => Ty::get('save')]);
          } else {
            echo '<a href="program_setting.php?setting=user_type&id=' . $id . '&nav_type=4&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => Ty::get('edit_data'), 'type' => '', 'is_return' => true]) . '</a>';
          }
          ?>
        </div>
      </div>
      <hr class="my-0">
      <div class="px-15px pt-15px pb-0">
        <div class="px-15px">
          <?= W_Permission::templateConfigAutoPermission(['is_edit' => $is_edit, 'permission' => $permission, 'hide_permission' => $hide_permission]); ?>
        </div>
      </div>
    </form>
  </div>
</div>
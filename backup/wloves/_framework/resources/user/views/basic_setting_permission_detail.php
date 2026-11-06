<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_POST) {
  if (isset($_POST['submit_edit_template'])) {
    unset($_POST['submit_edit_template']);
    $data = [
      'name' => $_POST['name'],
      'description' => $_POST['description'],
      'is_enabled' => isset($_POST['is_enabled']) ? 1 : 0,
    ];
    $result = User_Basic_Setting::updatePermissionTemplate($id, $data);

    if ($result['response_status']) {
      if (isset($_POST['permission_all']) && isset($_POST['checked'])) {
        foreach ($_POST['permission_all'] as $permission => $value) {
          if (isset($_POST['checked'][$permission])) {
            unset($_POST['permission_all'][$permission]);
          }
        }
      }
      $permission_no_checked = array_keys($_POST['permission_all']);
      $permission_checked = array_keys($_POST['checked']);

      if ($permission_no_checked) {
        $result = User_Basic_Setting::triggerTemplateItem($id, $permission_no_checked, 0);
      }
      if ($permission_checked && $result['response_status']) {
        $result = User_Basic_Setting::triggerTemplateItem($id, $permission_checked, 1);
      }

      if ($result['response_status']) {
        $response_redirect = 'user_basic_setting.php?user_type=' . $user_type . '&type=' . $type . '&page=template_detail&id=' . $id;
      }
    }
  } else if (isset($_POST['submit_delete_template'])) {
    $result = User_Basic_Setting::deletePermissionTemplate($id);
    if ($result['response_status']) {
      $response_redirect = 'user_basic_setting.php?user_type=' . $user_type . '&type=' . $type;
    }
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

$template = User_Basic_Setting::getPermissionTemplateByID($id);
$permission_checked = User_Basic_Setting::getTemplatePermissionList($id);
$block_permission = User_type::getHiddenPermissionList($user_type);

function templateFormGroup($title, $detail)
{
  echo '<div class="col-md-5 col-lg-3 pt-10px font-14px text-secondary">' . $title . '</div>
        <div class="col-md-7 col-lg-9">' . $detail . '</div>';
}
?>

<div class="col-md-9">
  <div class="editable-card core-new">
    <div class="editable-card-header-back pl-15px py-10px font-13px">
      <a class="text-info mr-5px" href="global_member_setting.php?c=<?= $_GET['c'] ?>">Permission Template</a> |
      <span class="text-primary pl-5px"> Template Details</span>
    </div>
  </div>
  <form action="" method="post">
    <div class="editable-card core-new mb-15px">
      <div class="editable-card-body container-detail min-h-100px pt-10px">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h3 class="font-16px mb-0 d-flex align-items-center text-uppercase text-info">Permission template</h3>
            <p class="font-14px mb-10px">Manage your project details. | Last Update: <?= W_Main::formatDate($template['update_date_time'], 'd/m/Y, H:i'); ?> By <?= $template['update_user_username'] ? $template['update_user_username'] : 'Unknow'; ?></p>
          </div>
          <div class="mb-10px d-flex">
            <?php
            if ($is_edit) {
              echo '<a href="user_basic_setting.php?user_type=' . $user_type . '&type=' . $type . '&page=' . $page . '&id=' . $id . '" class="btn btn-light h-35px mr-5px">CANCEL</a>';
              TiwForm::normal('btn', 'submit', ['name' => 'submit_edit_template'], ['text' => 'SAVE']);
            } else {
              echo '<a href="user_basic_setting.php?user_type=' . $user_type . '&type=' . $type . '&page=' . $page . '&id=' . $id . '&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => 'EDIT DATA', 'type' => '', 'is_return' => true]) . '</a>';
            }
            ?>

            <div>
              <button type="button" class="btn btn-dropdown-3dot min-w-50px py-0 px-0 h-100 mr--15px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= file_get_contents('../../structure/image/icon/general/more.svg'); ?>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <button type="button" class="btn dropdown-item" <?= Tiwdal::register('delete_template_modal'); ?>>
                  <?= file_get_contents('../../structure/image/icon/general/rad-bin.svg') ?>
                  <span class="ml-5px text-danger">Delete Templete</span>
                </button>
              </div>
            </div>
          </div>
        </div>
        <hr class="mt-0 mx--15px">
        <div class="row">
          <div class="col-12 font-14px text-uppercase font-SemiBold text-info">general detail</div>
          <?php
          templateFormGroup('Template Name', TiwForm::normal('text', $template['name'], ['name' => 'name', 'class' => '', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]));

          templateFormGroup('Description', TiwForm::normal('textarea', $template['description'], ['name' => 'description', 'class' => '', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]));

          if ($is_edit) {
            $is_activate = $template['is_enabled'] ? '<span class="text-primary">Activate</span>' : '<span class="text-danger">Deactivate</span>';
            $is_checked = $template['is_enabled'] ? true : false;

            templateFormGroup('Activate', '<div class="mt-10px">' . TiwForm::normal('checkbox', 1, ['name' => 'is_enabled', 'class' => 'is_activate_event', 'checked' => $is_checked], ['style' => '1', 'label' => $is_activate, 'is_on_off' => true, 'is_return' => true]) . '</div>');
          } else {
            $activate_html = $template['is_enabled'] ? '<div class="text-primary d-flex align-items-center h-35px">Active</div>' : '<div class="text-danger d-flex align-items-center h-35px">Deactivate</div>';
            templateFormGroup('Activate', $activate_html);
          }
          ?>
        </div>
        <hr class="my-15px mx--15px">
        <div>
          <h3 class="font-14px mb-0 d-flex align-items-center text-uppercase text-info">default Permission configure</h3>
          <p class="font-14px mb-10px">Setting default module and page permission in template for use when you configure user permission.</p>
        </div>
        <div class="px-15px">
          <?= W_Permission::templateConfigPermission(['is_edit' => $is_edit, 'permission' => $permission_checked, 'block_permission' => $block_permission]); ?>
        </div>
      </div>
    </div>
  </form>

  <?php Tiwdal::startModal('delete_template_modal', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <form method="post">
    <div class="modal-body border-radius-10-10-0-0px">
      <div class="row">
        <div class="col-12 form-group text-center">
          <p class="font-SemiBold mb-5px mt-20px text-uppercase">Delete Permission template</p>
          <p>
            Are you sure to delete <span class="text-danger">“Permission template”</span> your delete is not effect with recent history
          </p>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <?php
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Maybe']);
      TiwForm::normal('btn', '', ['name' => 'submit_delete_template', 'type' => 'submit', 'class' => 'btn btn-danger'], ['text' => 'Yes!! I’m Sure']);
      ?>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <script>
    $(function() {
      $(document).on('change', '.is_activate_event input', function(e) {
        var is_checked = $(this).prop('checked');
        var scope = $(this).parents('.scope_box_event');
        if (is_checked) {
          scope.find('.label_text').html('<span class="text-primary">Activate</span>');
        } else {
          scope.find('.label_text').html('<span class="text-danger">Deactivate</span>');
        }
      });
    });
  </script>
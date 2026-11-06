<?php
$is_edit = (isset($_GET['is_edit']) && $_GET['is_edit']) ? true : false;

$module_info = Module::getModuleByCode($module_code);
$info_permission = Module::getExistsModuleByModule($module_info['module'])['config_list'];

if ($_POST) {
  if (isset($_POST['submit_edit_module_detail'])) {
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    unset($_POST['submit_edit_module_detail']);
    unset($_POST['id']);

    $result = Module::updateModule($id, $_POST);
    if ($result['response_status']) {
      if ($module_info['module'] == 'Content') {
        $_POST['tags'] = isset($_POST['tags']) ? 1 : 0;
        $result = Module_Permission::setPermssion($module_code, $module_code . '_content_tags', $_POST['tags']);
        $_POST['category'] = isset($_POST['category']) ? 1 : 0;
        $result = Module_Permission::setPermssion($module_code, $module_code . '_content_category', $_POST['category']);
      }

      if ($result) {
        $response_redirect = 'module_setting.php?type=module&module_code=' . $module_code;
      }
    }
  } else if (isset($_POST['submit_add_user'])) {
    unset($_POST['submit_add_user']);
    $result = User::addNewUser($module_code, $_POST);
    if ($result['response_status']) {
      $user_id = $result['response_data']['insert_id'];
      // $menu_code = W_Main::getMenuCode();
      $permission = [];
      $permission[$module_code . '_user'] = 1;
      User_Permission::triggerUserPermission($user_id, $permission);
    }
  } else if (isset($_POST['submit_delete_module'])) {
    $user_id = Dev::getCurrentUserID();
    $result = Dev::checkCurrentPassword($user_id, $_POST['password']);

    if ($result['response_status']) {
      $result = Module::deleteModule($_POST['id']);
      if ($result['response_status']) {
        $response_redirect = 'module_setting.php';
      }
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

function formTemplate($title, $detail, $options = [])
{
  $is_required = (substr($title, -3) == '{*}') ? '<span class="text-danger">*</span>' : '';
  $title = $is_required ? str_replace('{*}', '', $title) : $title;
  $main_class = (isset($options['main_class'])) ? $options['main_class'] : '';

  echo '<div class="row ' . $main_class . '">
          <div class="col-sm-3 font-14px text-secondary pt-10px">' . $title . $is_required . '</div>
          <div class="col-sm-9">
          ' . $detail . '
          </div>
        </div>';
}

//select display
$display_options = ['list_encode' => '[{"value":"user","name":"Inside User Management"},{"value":"notuser","name":"Outside User Management"}]', 'is_search' => true, 'is_return' => true, 'is_edit' => $is_edit];

// select module permission
$select_module_permission = [];
if ($module_info['module'] == 'Content') {
  $select_module_permission = Module_Permission::selectPermssion($module_code);
  $tags =  in_array($module_code . '_content_tags', $select_module_permission) ? 'checked' : '';
  $category = in_array($module_code . '_content_category', $select_module_permission) ? 'checked' : '';
}
?>
<div class="col-lg-9 col-xl-10 box-nav-top">
  <form method="post">
    <div class="editable-card core-new mb-10px">
      <div class="w-love-content-container-wrap px-15px pt-15px">

        <div class="d-flex align-items-center justify-content-between flex-wrap mx--15px">
          <div class="title-detail px-15px">
            <h3 class="text-uppercase font-16px font-SemiBold text-info mb-0">Module detail</h3>
            <p class="font-14px">Manage your module and configure basic setting.</p>
          </div>
          <div class="px-15px pb-10px">
            <?php if ($is_edit) { ?>
              <div class="d-flex">
                <a href="module_setting.php?type=module&module_code=<?= $module_code ?>" class="btn btn-light h-35px mr-5px">CANCEL</a>
                <?= TiwForm::normal('btn', 'submit', ['name' => 'submit_edit_module_detail'], ['text' => 'SAVE']); ?>
                <div class="dot3 ml-5px">
                  <button type="button" class="btn btn-dropdown-3dot min-w-35px py-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= W_Structure::getfile('_framework/structure/image/icon/general/more.svg'); ?>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button type="button" class="btn dropdown-item" <?= Tiwdal::register('delete_module_modal', ['id' => $module_info['id']]); ?>>
                      <?= W_Structure::getfile('_framework/structure/image/icon/general/rad-bin.svg') ?>
                      <span class="ml-5px text-danger">Delete Module</span>
                    </button>
                  </div>
                </div>
              </div>
            <?php
            } else {
              echo '<a href="module_setting.php?type=module&module_code=' . $module_code . '&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => 'EDIT DATA', 'type' => '', 'is_return' => true]) . '</a>';
            }
            ?>
          </div>
        </div>
        <hr class="mt-0 mx--15px">

        <div class="module_information">
          <div class="master-form-header-wrap">
            <div class="text-uppercase font-Medium">
              <h3 class="font-14px font-SemiBold text-info">MODULE INFORMATION</h3>
            </div>
          </div>
          <?php
          TiwForm::normal('hidden', $module_info['id'], ['name' => 'id']);

          formTemplate('Module Name', TiwForm::normal('text', $module_info['name'], ['name' => 'name', 'placeholder' => 'Enter', 'required' => true], ['is_return' => true, 'is_edit' => $is_edit]));

          formTemplate('Description', TiwForm::normal('textarea', $module_info['description'], ['name' => 'description', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]));

          $tab = $is_edit ? 'pl-5px' : '';
          formTemplate('Module Type', '<div class="text-primary font-SemiBold d-flex align-items-center min-h-40px ' . $tab . '">' . $module_info['module'] . '</div>');

          if (isset($info_permission['display'])) {
            $display_text = ($module_info['display'] == 'user') ? 'Inside User Management' : 'Outside User Management';
            formTemplate('Display', TiwForm::normal('select', ($is_edit ? $module_info['display'] : $display_text), ['name' => 'display', 'placeholder' => 'Select Display', 'class' => 'mb-10px max-w-300px', 'required' => true], $display_options));
          }
          ?>
        </div>

        <div class="module_configure">
          <?php if ($module_info['module'] == 'User') { ?>
            <div class="master-form-header-wrap  mt-15px">
              <div class="text-uppercase  font-Medium  pb-15px">
                <h3 class="font-14px font-SemiBold text-info">MODULE CONFIGURE</h3>
              </div>
            </div>
            <?php if (isset($info_permission['manage_user_type'])) { ?>
              <div class="master-form-body-wrap">
                <div class="master-form-items row">
                  <div class="col-md-3 ">
                    <label class="master-form-title font-14px">Manage User Type</label>
                  </div>
                  <div class="col-md-9">
                    <p class="text-primary font-18px font-SemiBold mb-0"><?= $module_info['managed_user_type_name']; ?></p>
                    <p class="master-form-text font-14px mb-0">Main User type for use on this module , If you add user in this module user will be used this user type.</p>
                  </div>
                </div>
              </div>
              <div class="line-dashed"></div>
            <?php } ?>
          <?php } ?>
          <?php if ($module_info['module'] == 'Content') { ?>
            <div class="line-dashed"></div>
            <div class="master-form-body-wrap">
              <div class="master-form-items row">
                <div class="col-md-3 ">
                  <label class="master-form-title font-14px">Tags</label>
                </div>
                <div class="col-md-9">
                  <?php $disabled = ($is_edit) ? '' : 'disabled'; ?>
                  <?= TiwForm::normal('checkbox', 1, ['name' => 'tags', $disabled => true, $tags => true], ['style' => '1', 'label' => 'Tags', 'is_on_off' => true]); ?>
                </div>
              </div>
            </div>
            <div class="line-dashed"></div>
            <div class="master-form-body-wrap">
              <div class="master-form-items row">
                <div class="col-md-3 ">
                  <label class="master-form-title font-14px">Category</label>
                </div>
                <div class="col-md-9">
                  <?php $disabled = ($is_edit) ? '' : 'disabled'; ?>
                  <?= TiwForm::normal('checkbox', 1, ['name' => 'category', $disabled => true, $category => true], ['style' => '1', 'label' => 'Category', 'is_on_off' => true]); ?>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>

        <div class="line-dashed mb-0"></div>
        <?php if (!$module_info['first_username'] && ($module_info['module'] == 'User')) { ?>
          <div class="w-auto text-primary toggle-modal h-50px d-flex align-items-center cursor-pointer" <?= Tiwdal::register('add_new_user', []); ?>>
            + ADD FIRST USER ON MODULE
          </div>
        <?php } ?>

        <?php if ($module_info['image_code_list']) { ?>
          <div class="pb-15px pt-10px">
            <div class="font-14px font-SemiBold mb-10px">IMAGE CODE</div>
            <div class="tag-group flex-wrap">
              <?php foreach ($module_info['image_code_list'] as $key => $image_code) { ?>
                <div class="tag-list"><?= $image_code ?></div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </form>
</div>

<?php Tiwdal::startModal('delete_module_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<form method="post">
  <div class="modal-body">
    <h3 class="text-center font-16px font-SemiBold text-uppercase">DELETE MODULE</h3>
    <p class="mb-0 text-center">
      Are you sure to delete <span class="text-danger text-uppercase">“Module”</span> form this program. Enter your password for make sure, it will be alert action.
    </p>
    <div class="line-dashed"></div>
    <?php
    formTemplate('Password{*}', TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Enter your password', 'required' => true], ['is_return' => true]));
    ?>
  </div>
  <div class="modal-footer d-flex justify-content-between">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Maybe']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_module', 'type' => 'submit', 'class' => 'btn btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('add_new_user', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title text-uppercase">Add First USER</h5>
</div>
<form method="POST">
  <div class="modal-body">
    <div class="row aww-regex-box">
      <div class="col-md-3">
        <label class="text-secondary font-14px mt-7px">Name<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-3 pr-5px">
            <?php
            $options = [
              'list' => [
                [
                  'value'    => '',
                  'name'     => 'Select',
                  'disabled' => true,
                  'selected' => true
                ],
                [
                  'value' => 'Mr.',
                  'name'  => 'Mr.'
                ],
                [
                  'value' => 'Mrs.',
                  'name'  => 'Mrs.'
                ],
                [
                  'value' => 'Ms.',
                  'name'  => 'Ms.'
                ]
              ]

            ];
            TiwForm::normal('select', 2, ['name' => 'title'], $options);
            ?>
          </div>
          <div class="col px-5px">
            <?php TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'First Name', 'required' => '']); ?>
          </div>
          <div class="col pl-5px">
            <?php TiwForm::normal('text', '', ['name' => 'surname', 'placeholder' => 'Surname']); ?>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <label class="text-secondary font-14px mt-7px">Telephone No.</label>
      </div>
      <div class="col-md-9 mb-10px">
        <?php TiwForm::normal('number', '', ['name' => 'tel_no', 'placeholder' => 'Enter']); ?>
      </div>
      <div class="col-md-3">
        <label class="text-secondary font-14px mt-7px">Username<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => 'username', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'check_username']); ?>
      </div>
      <div class="col-md-3">
        <label class="text-secondary font-14px mt-7px">Password<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php
        TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Enter', 'class' => 'check_input_password']);

        $input_class = 'check_input_password';
        $button_class = 'check_pass_btn';
        $options = [
          'is-char' => true,
          'is-word' => true,
          'is-number' => true,
        ];
        TiwForm::checkForm($input_class, $button_class, $options);
        ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px check_pass_btn', 'name' => 'submit_add_user'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>
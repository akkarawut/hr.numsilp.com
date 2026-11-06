<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_module_setup'];
require_once  '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

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

//select module
$modules = Module::selectExistsModule();
$key = [
  'value' => 'key',
  'name' => 'name',
];
$module_type_options = TiwForm::generateSelectData($modules, $key, ['is_search' => true, 'is_return' => true, 'is_data' => true]);

//select display
$display_options = ['list_encode' => '[{"value":"user","name":"Inside User Management"},{"value":"notuser","name":"Outside User Management"}]', 'is_return' => true, 'is_search' => true];

//select user type
$user_types = User_type::selectUserType();
$key = [
  'value' => 'id',
  'name' => 'name',
];
$user_type_options = TiwForm::generateSelectData($user_types, $key, ['is_search' => true, 'is_return' => true, 'is_data' => true]);

?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="modal-header">
  <h5 class="modal-title">Add new module</h5>
</div>
<form method="post">
  <div class="modal-body">
    <?php
    formTemplate('Module Type{*}', TiwForm::normal('select', '', ['name' => 'module', 'class' => 'mb-10px', 'onchange' => "selectData(event)", 'placeholder' => 'Select Module', 'required' => true], $module_type_options));

    formTemplate('Module Name{*}', TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter', 'required' => true], ['is_return' => true]));

    formTemplate('Description', TiwForm::normal('textarea', '', ['name' => 'description', 'placeholder' => 'Enter', 'class' => '
    min-h-70px'], ['is_return' => true]));
    ?>
    <div class="scope_user_module" style="display:none">
      <?php
      formTemplate('Display{*}', TiwForm::normal('select', '', ['name' => 'display', 'placeholder' => 'Select Display', 'class' => 'mb-10px scope_user_module_input', 'required' => true], $display_options), ['main_class' => 'scope_select_display_event']);
      ?>
      <div class="mx--15px line-dashed"></div>
      <div class="font-14px font-SemiBold text-info mb-10px">MODULE CONFIGURE</div>
      <?php
      formTemplate('Manage User Type{*}', TiwForm::normal('select', '', ['name' => 'managed_user_type_id', 'class' => 'mb-10px scope_user_module_input', 'placeholder' => 'Select User Type', 'required' => true], $user_type_options), ['main_class' => 'scope_manage_user_event']);
      ?>

    </div>
    <div class="modal-footer">
      <?php
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
      TiwForm::normal('btn', '', ['name' => 'submit_add_new_module', 'type' => 'submit', 'class' => 'min-w-100px'], ['text' => 'ADD']);
      ?>
    </div>
</form>

<script>
  function selectData(e) {
    var select = e.target;
    var select_id = e.target.value;
    var select_data = select.closest('.form-select-img').querySelector('.select-group .select_list_' + select_id).getAttribute('data-list');
    select_data = select_data ? JSON.parse(atob(select_data)) : [];
    var scope_user_module = document.querySelector('.scope_user_module');
    if (select_data.name == 'User') {
      scope_user_module.style.display = 'block';
      var input_other_module = scope_user_module.querySelectorAll('.scope_user_module_input .select-value-area input');
      input_other_module.forEach(function(input) {
        input.required = true;
      });
    } else {
      scope_user_module.style.display = 'none';
      var input_other_module = scope_user_module.querySelectorAll('.scope_user_module_input .select-value-area input');
      input_other_module.forEach(function(input) {
        input.required = false;
      });
    }
  }
</script>
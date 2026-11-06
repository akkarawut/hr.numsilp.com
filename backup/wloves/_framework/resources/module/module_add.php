<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_module_setup'];
require_once '../../../_framework/import.php';

if ($_POST) {
  if (isset($_POST['submit_add_module'])) {
    $light_icon = '';
    $dark_icon = '';
    if ($_POST['module'] == 'User') {
      $data = [
        'module' => $_POST['module'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'allow_login' => isset($_POST['allow_login']) ? 1 : 0,
        'module_addnew_user' => isset($_POST['module_addnew_user']) ? 1 : 0,
        'page_display' => $_POST['page_display'],
      ];
      if ($data['allow_login']) {
        $data['create_tab'] =  isset($_POST['create_tab']) ? 1 : 0;
      }
    } else {
      $data = [
        'module' => $_POST['module'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
      ];
      $light_icon = $_FILES['light_icon'];
      $dark_icon = $_FILES['dark_icon'];
    }

    $result = Module::addNewModule($data, $light_icon, $dark_icon);
    if ($result['response_status'] && $_POST['module'] == 'User') {
      $module_id = $result['response_data']['insert_id'];
      $module_code = $result['response_data']['code'];
      $menu_code = W_Main::getMenuCode();
      $menu_function_code = W_Main::getMenuFunctionCode();
      $permission = [];
      $function = [];
      foreach ($menu_code as $key => $menu) {
        $permission[$menu] = 1;
      }
      foreach ($menu_function_code as $key => $menu) {
        $function[$menu] = 1;
      }
      $permission[$module_code . '_user'] = 1;
      $function[$module_code . '_user'] = 1;
      $data_permission = [
        'hidden_permission' => $permission,
        'setting_permission' => $function
      ];

      Module::updateModule($module_id, $data_permission);
    }
    $redirect = 'index.php';
  }

  W_Main::responseAPI();
}
$modules = Module::selectExistsModule();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  W_Main::loadAsset('assets/css/module.css');
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-12 mt-10px">
        <div class="w-card">
          <?php
          $key = [
            'value' => 'key',
            'name' => 'name',
          ];
          $module_type_options = TiwForm::generateSelectData($modules, $key, ['is_search' => true, 'is_data' => true]);
          ?>
          <form method="post" enctype="multipart/form-data">
            <div class="w-card-title-edit border-bottom">
              Program module > <span class="text-primary">Add new module</span>
            </div>
            <div class="w-card-title-bar border-bottom">
              <div class="d-flex flex-wrap align-items-center">
                <h3 class="title text-primary">Add new module </h3>
              </div>
              <div class="btn-edit-container">
                <a href="index.php" class="btn-edit cancel">Cancel</a>
                <button type="submit" name="submit_add_module" class="btn-edit save">ADD</button>
              </div>
            </div>
            <div class="card-scroll has-title p-20px">
              <p class="font-Bold font-16px">General information</p>
              <div class="input-group">
                <div class="label">
                  <span>Module type <span class="text-danger">*</span></span>
                </div>
                <div class="input">
                  <?php TiwForm::normal('select', '', ['name' => 'module', 'class' => ' max-w-400px', 'onchange' => "selectData(event)", 'placeholder' => 'Select Module', 'required' => true], $module_type_options) ?>
                </div>
              </div>
              <div class="input-group">
                <div class="label">
                  <span>Module name <span class="text-danger">*</span></span>
                </div>
                <div class="input flex-column align-items-start">
                  <?php TiwForm::normal('text', '', ['name' => 'name', 'class' => 'max-w-400px', 'placeholder' => '', 'required' => true]) ?>
                  <span class="mt-5px text-danger">**ชื่อของ Module user จะถูกตั้งเป็นประเภทผู้ใช้งานระบบ (User type) โดยอัตโนมัติ (ไม่สามารถแก้ไขได้ภายหลัง)</span>
                </div>
              </div>
              <div class="input-group">
                <div class="label">
                  <span>Explanation</span>
                </div>
                <div class="input">
                  <?php TiwForm::normal('textarea', '', ['name' => 'description', 'class' => 'min-h-70px  max-w-400px']) ?>
                </div>
              </div>
              <div class="scope_user_module" style="display:none">
                <hr class="border-color-bg-1px">
                <p class="font-Bold font-16px">Module configure</p>
                <div class="input-group">
                  <div class="input flex-column align-items-start">
                    <div class="mb-10px">
                      <?php TiwForm::normal('checkbox', 1, ['name' => 'module_addnew_user', 'checked' => true], ['style' => '1', 'is_on_off' => true, 'label' => 'สามารถเพิ่มข้อมูลผู้ใช้ใหม่ได้']); ?>
                    </div>
                    <div class="mb-10px">
                      <?php TiwForm::normal('checkbox', 1, ['name' => 'allow_login', 'checked' => true, 'onchange' => "eventHideInput(event,'check_split_permission')"], ['style' => '1', 'is_on_off' => true, 'label' => 'Allow user in this type log-in to program.']); ?>
                    </div>
                    <div class="mb-10px check_split_permission">
                      <?php TiwForm::normal('checkbox', 1, ['name' => 'create_tab', 'checked' => true], ['style' => '1', 'is_on_off' => true, 'label' => 'แยกข้อมูลบัญชี (Username, Password) และ Permission ไปอีก Tab']); ?>
                      <p class="ml-55px font-15px text-secondary">ในกรณีที่ไม่แยก ข้อมูลส่วนนี้จะขึ้นมาก่อน</p>
                    </div>
                  </div>
                </div>
                <div class="input-group">
                  <div class="label">
                    <span>User page display</span>
                  </div>
                  <div class="input">
                    <div class="row w-100">
                      <div class="col-lg-4 col-md-6 checkbox-type">
                        <?php TiwForm::normal('radio', 'half', ['name' => 'page_display', 'checked' => true], ['style' => '2', 'label' => '50 : 50']); ?>
                        <p class="font-15px text-secondary">
                          แสดงข้อมูลในหน้าเดียว แบ่งเป็นรายการผู้ใช้งานครึ่งหน้า และ รายละเอียดผู้ใช้ครึ่งหน้า
                        </p>
                        <div class="img-type-module img-16by9 holder" onclick="eventClickRadio(event)">
                          <img src="assets/images/type-01.png">
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6 checkbox-type">
                        <?php TiwForm::normal('radio', 'full', ['name' => 'page_display'], ['style' => '2', 'label' => 'Full list and details page']); ?>
                        <p class="font-15px text-secondary">
                          แสดงหน้ารายการแบบเต็มหน้า และกดรายการเพื่อไปยัง หน้ารายละเอียด
                        </p>
                        <div class="img-type-module img-16by9 holder" onclick="eventClickRadio(event)">
                          <img src="assets/images/type-02.png">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="scope_other_module" style="display:none">
                <hr class="border-color-bg-1px">
                <p class="font-Bold font-16px">Module configure</p>
                <div class="input-group">
                  <div class="label p-0">
                    <span>Module icon (Light mode) </span>
                  </div>
                  <div class="input flex-column align-items-start">
                    <div class="max-w-300px">
                      <?php
                      $disabled = '';
                      $logo = W_Structure::getFileName('assets/images/no_image_trans.png');
                      TiwForm::normal('file', '', ['name' => 'light_icon', 'onchange' => "showImage(event, 'module_icon_light')", $disabled => true], []);
                      TiwForm::normal('hidden', 0, ['name' => 'delete_module_icon_light']);
                      ?>
                    </div>
                    <span class="mt-5px text-secondary">
                      Recommend: File .jpg, .svg or .png square 1:1 size 50x50 px
                    </span>
                    <div class="w-50px h-50px">
                      <div class=" img-1by1 holder">
                        <img src="<?= 'assets/images/no_image_trans.png' ?>" id="module_icon_light">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="input-group">
                  <div class="label p-0">
                    <span>Module icon (Dark mode)</span>
                  </div>
                  <div class="input flex-column align-items-start">
                    <div class="max-w-300px">
                      <?php
                      $disabled = '';
                      $logo = W_Structure::getFileName('assets/images/no_image_trans.png');
                      TiwForm::normal('file', '', ['name' => 'dark_icon', 'onchange' => "showImage(event, 'module_icon_dark')", $disabled => true], []);
                      TiwForm::normal('hidden', 0, ['name' => 'delete_module_icon_dark']);
                      ?>
                    </div>
                    <span class="mt-5px text-secondary">
                      Recommend: File .jpg, .svg or .png square 1:1 size 50x50 px
                    </span>
                    <div class="w-50px h-50px">
                      <div class=" img-1by1 holder">
                        <img src="<?= 'assets/images/no_image_trans.png' ?>" id="module_icon_dark">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>
  <script>
    function showImage(e, id) {
      var file = e.target.files[0];
      var output = document.getElementById(id);
      var parent = e.target.parentElement;
      var input = parent.nextElementSibling;
      if (file) {
        input.value = '0';
        var reader = new FileReader();
        reader.onload = function() {
          output.src = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        input.value = '1';
        output.src = "assets/images/no_image_trans.png";
      }
    }

    function selectData(e) {
      var select = e.target;
      var select_id = e.target.value;
      var select_data = select.closest('.form-select-img').querySelector('.select-group .select_list_' + select_id).getAttribute('data-list');
      select_data = select_data ? JSON.parse(atob(select_data)) : [];
      var scope_user_module = document.querySelector('.scope_user_module');
      var scope_other_module = document.querySelector('.scope_other_module');
      if (select_data.name == 'User') {
        scope_user_module.style.display = 'block';
        scope_other_module.style.display = 'none';
        var input_other_module = scope_other_module.querySelectorAll('input');
        input_other_module.forEach(function(input) {
          input.required = false;
        });
      } else {
        scope_user_module.style.display = 'none';
        scope_other_module.style.display = 'block';
        var input_other_module = scope_other_module.querySelectorAll('input');
        input_other_module.forEach(function(input) {
          /// check not input file
          if (input.type != 'file') {
            input.required = true;
          }
        });
      }
    }

    function eventClickRadio(e) {
      var radio = e.target.closest('.checkbox-type').querySelector('input[type="radio"]');
      radio.checked = true;
    }

    function eventHideInput(e, class_name) {
      var input = e.target.closest('.input-group').querySelector('.' + class_name);
      if (e.target.checked) {
        input.style.display = 'block';
      } else {
        input.style.display = 'none';
      }
    }
  </script>

</body>

</html>
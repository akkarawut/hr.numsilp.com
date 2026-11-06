<?php
if ($_POST) {
  if (isset($_POST['submit_edit_module'])) {
    $light_icon = '';
    $dark_icon = '';
    if ($get_module['module'] == 'User') {
      $data = [
        'allow_login' => isset($_POST['allow_login']) ? 1 : 0,
        'module_addnew_user' => isset($_POST['module_addnew_user']) ? 1 : 0,
        'page_display' => $_POST['page_display'],
      ];
      if ($data['allow_login']) {
        $data['create_tab'] =  isset($_POST['create_tab']) ? 1 : 0;
      }
    } else {
      $data = [
        // 'module' => $_POST['module'],
        'name' => $_POST['name'],
        // 'description' => $_POST['description'],
      ];
      $light_icon = $_FILES['light_icon'];
      $dark_icon = $_FILES['dark_icon'];
    }

    $result = Module::updateModule($get_module['id'], $data, $light_icon, $dark_icon);
    $redirect = '?c=' . $_GET['c'];
  } else if (isset($_POST['submit_add_user'])) {
    unset($_POST['submit_add_user']);
    $result = User::addNewUser($code, $_POST);
  } else if (isset($_POST['submit_delete_module'])) {
    $user_id = Dev::getCurrentUserID();
    $result = Dev::checkCurrentPassword($user_id, $_POST['password']);

    if ($result['response_status']) {
      $result = Module::deleteModule($get_module['id']);
      $redirect = 'index.php';
    }
  }

  W_Main::responseAPI();
}
?>

<form method="post" enctype="multipart/form-data">
  <div class="w-card-title-bar border-bottom">
    <div class="d-flex flex-wrap align-items-center">
      <h3 class="title ">Module details - <span class="text-primary"><?= $get_module['name'] ?></span></h3>
    </div>
    <div class="btn-edit-container">
      <?php if (!$is_edit) { ?>
        <?php if (!$get_module['first_username'] && $get_module['module'] == 'User') { ?>
          <button type="button" class="btn-edit save w-150px" <?= Tiwdal::register('modal_add_new_user', []); ?>>+ Add first user</button>
        <?php } ?>
        <a href="?c=<?= $code ?>&is_edit=1" class="btn-edit edit"> Edit data</a>
        <button type="button" class="btn-edit delete" <?= Tiwdal::register('modal_delete_module', []); ?>></button>
      <?php } else { ?>
        <a href="?c=<?= $code ?>" class="btn-edit cancel"> Cancel</a>
        <button type="submit" class="btn-edit save" name="submit_edit_module"> Save</button>
      <?php } ?>
    </div>
  </div>
  <div class="card-scroll has-title p-20px">
    <p class="font-Bold font-16px">General information</p>
    <div class="input-group">
      <div class="label">
        <span>Module type</span>
      </div>
      <div class="input">
        <span class="font-18px text-primary font-SemiBold my-auto"><?= $get_module['module'] ?></span>
      </div>
    </div>
    <div class="input-group">
      <div class="label">
        <span>Module name</span>
      </div>
      <div class="input flex-column align-items-start">
        <?php if ($get_module['module'] == 'User') { ?>
          <span class="font-16px text-info font-Medium my-auto"><?= $get_module['name'] ?></span>
          <span class="mt-5px font-15px text-secondary">
            **ชื่อของ Module user จะถูกตั้งเป็นประเภทผู้ใช้งานระบบ (User type) โดยอัตโนมัติ (ไม่สามารถแก้ไขได้ภายหลัง)
          </span>
        <?php } else { ?>
          <div class="input flex-column align-items-start">
            <?php TiwForm::normal('text',  $get_module['name'], ['name' => 'name', 'class' => 'max-w-400px', 'placeholder' => '', 'required' => true], ['is_edit' => $is_edit]) ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="input-group">
      <div class="label">
        <span>Explanation</span>
      </div>
      <div class="input">
        <span class="font-16px text-info font-Medium my-auto"><?= $get_module['description'] ?></span>
      </div>
    </div>
    <?php if ($get_module['module'] == 'User') { ?>
      <div class="scope_user_module">
        <hr class="border-color-bg-1px">
        <p class="font-Bold font-16px">Module configure</p>
        <div class="input-group">
          <div class="input flex-column align-items-start">
            <?php if ($is_edit) { ?>
              <div class="mb-10px">
                <?php TiwForm::normal('checkbox', 1, ['name' => 'module_addnew_user', 'checked' => $get_module['module_addnew_user']], ['style' => '1', 'is_on_off' => true, 'label' => 'สามารถเพิ่มข้อมูลผู้ใช้ใหม่ได้']); ?>
              </div>
              <div class="mb-10px">
                <?php TiwForm::normal('checkbox', 1, ['name' => 'allow_login', 'checked' => $get_module['allow_login'], 'onchange' => "eventHideInput(event,'check_split_permission')"], ['style' => '1', 'is_on_off' => true, 'label' => 'Allow user in this type log-in to program.']); ?>
              </div>
              <div class="mb-10px check_split_permission">
                <?php TiwForm::normal('checkbox', 1, ['name' => 'create_tab', 'checked' => $get_module['create_tab']], ['style' => '1', 'is_on_off' => true, 'label' => 'แยกข้อมูลบัญชี (Username, Password) และ Permission ไปอีก Tab']); ?>
                <p class="ml-55px font-15px text-secondary">ในกรณีที่ไม่แยก ข้อมูลส่วนนี้จะขึ้นมาก่อน</p>
              </div>
            <?php } else { ?>
              <div class="mb-10px d-flex align-items-center">
                <?php if ($get_module['module_addnew_user']) { ?>
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                  <span class="font-16px text-primary font-Medium ml-5px">สามารถเพิ่มข้อมูลผู้ใช้ใหม่ได้</span>
                <?php } else { ?>
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                  <span class="font-16px text-placeholder font-Medium ml-5px">สามารถเพิ่มข้อมูลผู้ใช้ใหม่ได้</span>
                <?php } ?>
              </div>
              <div class="mb-10px d-flex align-items-center">
                <?php if ($get_module['allow_login']) { ?>
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                  <span class="font-16px text-primary font-Medium ml-5px">Allow user in this type log-in to program.</span>
                <?php } else { ?>
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                  <span class="font-16px text-placeholder font-Medium ml-5px">Allow user in this type log-in to program.</span>
                <?php } ?>
              </div>
              <div class="mb-10px d-flex align-items-center">
                <?php if ($get_module['create_tab']) { ?>
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                  <span class="font-16px text-primary font-Medium ml-5px">แยกข้อมูลบัญชี (Username, Password) และ Permission ไปอีก Tab</span>
                <?php } else { ?>
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                  <span class="font-16px text-placeholder font-Medium ml-5px">แยกข้อมูลบัญชี (Username, Password) และ Permission ไปอีก Tab</span>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="input-group">
          <div class="label">
            <span>User page display</span>
          </div>
          <div class="input">
            <div class="row w-100">
              <?php if ($is_edit) { ?>
                <div class="col-lg-4 col-md-6 checkbox-type">
                  <?php TiwForm::normal('radio', 'half', ['name' => 'page_display', 'checked' => $get_module['page_display'] == 'half' ? true : false], ['style' => '2', 'label' => '50 : 50']); ?>
                  <p class="font-15px text-secondary">
                    แสดงข้อมูลในหน้าเดียว แบ่งเป็นรายการผู้ใช้งานครึ่งหน้า และ รายละเอียดผู้ใช้ครึ่งหน้า
                  </p>
                  <div class="img-type-module img-16by9 holder" onclick="eventClickRadio(event)">
                    <img src="assets/images/type-01.png">
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 checkbox-type">
                  <?php TiwForm::normal('radio', 'full', ['name' => 'page_display', 'checked' => $get_module['page_display'] == 'full' ? true : false], ['style' => '2', 'label' => 'Full list and details page']); ?>
                  <p class="font-15px text-secondary">
                    แสดงหน้ารายการแบบเต็มหน้า และกดรายการเพื่อไปยัง หน้ารายละเอียด
                  </p>
                  <div class="img-type-module img-16by9 holder" onclick="eventClickRadio(event)">
                    <img src="assets/images/type-02.png">
                  </div>
                </div>
              <?php } else { ?>
                <div class="col-lg-4 col-md-6 checkbox-type">
                  <span class="font-16px text-info font-Medium">
                    <?= $get_module['page_display'] == 'half' ? '50 : 50' : 'Full list and details page' ?>
                  </span>
                  <p class="font-15px text-secondary">
                    <?= $get_module['page_display'] == 'half' ? 'แสดงข้อมูลในหน้าเดียว แบ่งเป็นรายการผู้ใช้งานครึ่งหน้า และ รายละเอียดผู้ใช้ครึ่งหน้า' : 'แสดงหน้ารายการแบบเต็มหน้า และกดรายการเพื่อไปยัง หน้ารายละเอียด' ?>
                  </p>
                  <div class="img-type-module img-16by9 holder">
                    <img src=" <?= $get_module['page_display'] == 'half' ? 'assets/images/type-01.png' : 'assets/images/type-02.png' ?>">
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <div class="scope_other_module">
        <hr class="border-color-bg-1px">
        <p class="font-Bold font-16px">Module configure</p>
        <div class="input-group">
          <div class="label p-0">
            <span>Module icon (Light mode)</span>
          </div>
          <div class="input flex-column align-items-start">
            <?php if ($is_edit) { ?>
              <div class="max-w-300px">
                <?php
                $disabled = !$is_edit ? 'disabled' : '';
                $data_file = W_Main::datafile($get_module['light_icon']);
                TiwForm::normal('file', $data_file['is_placeholder'] ? '' : $get_module['light_icon'], ['name' => 'light_icon', 'onchange' => "showImage(event, 'module_light_icon')", $disabled => true], []);
                TiwForm::normal('hidden', 0, ['name' => 'delete_module_light_icon']);
                ?>
              </div>
              <span class="mt-5px text-secondary">
                Recommend: File .jpg, .svg or .png square 1:1 size 50x50 px
              </span>
            <?php }  ?>
            <div class="w-50px h-50px">
              <div class=" img-1by1 holder">
                <img src="<?= $get_module['light_icon'] ?>" id="module_icon_light">
              </div>
            </div>
          </div>
        </div>
        <div class="input-group">
          <div class="label p-0">
            <span>Module icon (Dark mode)</span>
          </div>
          <div class="input flex-column align-items-start">
            <?php if ($is_edit) { ?>
              <div class="max-w-300px">
                <?php
                $disabled = !$is_edit ? 'disabled' : '';
                $data_file = W_Main::datafile($get_module['dark_icon']);
                TiwForm::normal('file', $data_file['is_placeholder'] ? '' : $get_module['dark_icon'], ['name' => 'dark_icon', 'onchange' => "showImage(event, 'module_dark_icon')", $disabled => true], []);
                TiwForm::normal('hidden', 0, ['name' => 'delete_module_dark_icon']);
                ?>
              </div>
              <span class="mt-5px text-secondary"></span>
              Recommend: File .jpg, .svg or .png square 1:1 size 50x50 px
              </span>
            <?php }  ?>
            <div class="w-50px h-50px">
              <div class=" img-1by1 holder">
                <img src="<?= $get_module['dark_icon'] ?>" id="module_dark_icon">
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</form>



<?php Tiwdal::startModal('modal_add_new_user', 'modal-md'); ?>
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


<?php Tiwdal::startModal('modal_delete_module', 'modal-md'); ?>
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
    <div class="row aww-regex-box">
      <div class="col-md-3">
        <label class="text-secondary font-14px mt-7px">Password<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Enter your password', 'required' => true]); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Maybe']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_module', 'type' => 'submit', 'class' => 'btn btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

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
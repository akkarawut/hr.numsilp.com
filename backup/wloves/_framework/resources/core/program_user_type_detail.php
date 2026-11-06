<div class="editable-card border-radius-10px overflow-hidden">
  <div class="d-flex justify-content-between rounded-0 d-flex">
    <?php Itnav::dinner($data_nav_top, $link_top, 'nav_type', $nav_type_param); ?>
    <div class="dropdown-editable-card">
      <button type="button" class="btn btn-dropdown-3dot min-w-50px py-0 h-100" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= W_Structure::getfile('_framework/structure/image/icon/general/more.svg'); ?>
      </button>
      <div class="dropdown-menu dropdown-menu-right">
        <button type="button" class="btn dropdown-item" <?= Tiwdal::register('delete_user_type_modal'); ?>>
          <?= W_Structure::getfile('_framework/structure/image/icon/general/rad-bin.svg') ?>
          <span class="ml-5px text-danger"><?= Ty::get('delete_usertype') ?></span>
        </button>
      </div>
    </div>
  </div>
  <div class="w-love-master-form-container col border-radius-0">
    <?php if ($nav_type_param == 1) {
      include '../../module_main/core/view/program_setting/hide_permission.php';
    } else if ($nav_type_param == 2) {
      include '../../module_main/core/view/program_setting/information_setting.php';
    } else if ($nav_type_param == 3) {
      include '../../module_main/core/view/program_setting/user_type_details.php';
    }
    ?>
  </div>
</div>
<div id="tip-modal">
  <div class="tip-card">
    <div class="tip-card-head">
      <div class="d-flex">
        <img src='structure/image/icon/icon-tip.svg'>
        <h2>TIP</h2>
      </div>
      <button type="button" class="tip-close">
        <?= file_get_contents(F_ROOT_PHP . '/_framework/modules/detiw/icon/modal-close.svg') ?>
      </button>
    </div>
    <div class="tip-card-body">
      <h4>Lorem Tip</h4>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      </p>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('delete_user_type_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<form method="post">
  <div class="modal-body">
    <h3 class="text-center font-16px font-SemiBold text-uppercase"><?= Ty::get('delete_usertype') ?></h3>
    <p class="mb-0 text-center">
      <?= Ty::get('sure_delete', ['“USER TYPE”' => ' <span class="text-danger text-uppercase">"' . Ty::get('user_type') . '"</span>']) ?>
    </p>
  </div>
  <div class="modal-footer d-flex justify-content-between">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Maybe']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_user_type', 'type' => 'submit', 'class' => 'btn btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('module-set-pin'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title text-uppercase">Set PIN code format</h5>
</div>
<form method="POST">
  <div class="modal-body">
    <div class="col">
      <div class="row">
        <div class="col-12 form-group">
          <label>PIN Digit</label>
          <?php
          $options = [
            'list' => [
              [
                'value' => '1',
                'name' => '1'
              ],
              [
                'value' => '2',
                'name' => '2'
              ],
              [
                'value' => '3',
                'name' => '3'
              ],
              [
                'value' => '4',
                'name' => '4'
              ],
              [
                'value' => '5',
                'name' => '5'
              ],
              [
                'value' => '6',
                'name' => '6'
              ],
              [
                'value' => '7',
                'name' => '7'
              ],
              [
                'value' => '0',
                'name' => 'clear'
              ],
            ]
          ];
          TiwForm::normal('select', '', ['name' => '{pin_code_format_digit}'], $options);
          ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="submit_set_pin" class="btn btn-primary">Confirm</button>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('module-set-customer-address'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title text-uppercase">Set Customer address format</h5>
</div>
<form method="POST">
  <div class="modal-body">
    <div class="col px-0">
      <div class="row">
        <div class="col-12 font-14px font-Medium text-uppercase text-secondary mb-10px">Single address</div>
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_address_type}', 'full_address', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">Full address</p>
              <p class="font-13px text-secondary">บันทึกที่อยู่ลูกค้ายาวช่องเดียว</p>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_address_type}', 'multiple_fields', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">multi field</p>
              <p class="font-13px text-secondary">แบ่งรูปแบบการบันทึกที่อยู่ออกเป็น 6 Field คือ Address,Sub-District, District, City/Province, Zip code, Country</p>
            </div>
          </div>
        </div>
        <div class="col-12 font-14px font-Medium text-uppercase text-secondary mb-10px">Multiple address</div>
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_address_type}', 'multiple_address', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">multiple address</p>
              <p class="font-13px text-secondary">เปิดใช้งาน User Multiple Address รูปแบบการบันทึกเป็นการบันทึกแบบละเอียด มีข้อมูลเพิ่มเติมจากปกติคือชื่อผู้รับและเบอร์ติดต่อตามที่อยู่</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="submit_set_address" class="btn btn-primary">Confirm</button>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('module-set-customer-name'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title text-uppercase">Set Customer name format</h5>
</div>
<form method="POST">
  <div class="modal-body">
    <div class="col px-0">
      <div class="row">
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_name_type}', 'title_name_surname', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">Title / name / surname</p>
              <p class="font-13px text-secondary">แบ่งช่องบันทึกข้อมูลเป็น 3 ช่องคือ คำนำหน้า, ชื่อ, นามสกุล</p>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_name_type}', 'full_name', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">Fullname</p>
              <p class="font-13px text-secondary">บันทึกชื่อลูกค้าด้วย Field เดียว</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="submit_set_name" class="btn btn-primary">Confirm</button>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('module-set-customer-bank'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title text-uppercase">Set Customer Bank Account</h5>
</div>
<form method="POST">
  <div class="modal-body">
    <div class="col px-0">
      <div class="row">
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_bank_account_type}', 'single', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">Single Account</p>
              <p class="font-13px text-secondary">บันทึกข้อมูลบัญชีธนาคาร 1 บัญชี</p>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="d-flex">
            <?= TiwForm::checkboxRadio(2, 'radio', [], '{using_bank_account_type}', 'multiple', []); ?>
            <div class="ml-10px">
              <p class="font-14px font-Medium text-uppercase mb-5px">multiPle Account</p>
              <p class="font-13px text-secondary">บันทึกข้อมูลบัญชีธนาคารหลายบัญชี เลือกบัญชีตั้งต้นได้</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="submit_set_bank" class="btn btn-primary">Confirm</button>
  </div>
</form>
<?php Tiwdal::endModal() ?>


<script>
  $(document).ready(function() {
    function sendPermissionBlock(permission_code, value) {
      var user_type_id = '<?= $user_information['id'] ?>'; //from user_type.php
      $.post('<?= F_BRIDGE_API_URL; ?>', {
        params: [user_type_id, permission_code, value],
        class: 'User_Permission',
        function: 'triggerUserType',
        api_key: '<?= F_BRIDGE_API_KEY; ?>',
      }).done(function(response) {
        w.notification('success', 'Success!');
        location.reload();
      });
    };

    $(document).on('change', '.checkbox-post-block', function() {
      var permission_code = $(this).find('input').data('permission_code');
      var value = $(this).find('input').data('value');
      sendPermissionBlock(permission_code, value);
    });

    $(function() {
      $("#tip-modal").draggable({
        containment: "body",
        handle: '.tip-card-head',
      });
      $("#tip-modal").resizable();

      $(document).on('click', '.tip-dropdown', function(e) {
        e.preventDefault();
        $("#tip-modal").toggleClass('is_show');
      });

      $(document).on('click', '.tip-close', function(e) {
        e.preventDefault();
        $("#tip-modal").removeClass('is_show');
      });
    });
  });
</script>
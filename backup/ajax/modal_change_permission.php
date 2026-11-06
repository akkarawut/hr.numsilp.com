<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id = $_POST['id'];
$user_code = $_POST['user_code'];
$user_type = $_POST['user_type'];
$per_dropdown = phr_helper::dropdownRoleEmployee(API_CODE);
?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['สิทธิการใช้งานระบบ', '']) ?></div>
    <div class="text-center">
      <div class="col-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">เปลี่ยนสิทธิการใช้งานระบบ</div>
            <select name="user_code" class="input" required>
              <option value="" selected disabled>เลือก</option>
              <?php foreach ($per_dropdown as $val) { ?>
                <option value="<?= $val['code'] ?>" <?= $user_code == $val['code'] ? 'selected'  : '' ?>><?= $val['name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_change_permission', 'type' => 'submit', 'class' => 'btn-primary min-w-120px'], ['text' => Ty::get(['ยืนยัน', ''])]);
    ?>
  </div>
</form>
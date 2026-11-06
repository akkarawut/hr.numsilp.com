<?php
$link_back = 'employee_information.php';
$title_page = 'ข้อมูลพนักงาน';
if ($mode == 'probation') {
  $link_back = 'approve_probation.php';
} else if ($mode == 'my_profile') {
  $title_page = 'ข้อมูลส่วนตัว';
  $link_back = 'dashboard.php';
}

?>
<div class="container-fluid mt-5 mb-10px">
  <div class="mx-auto">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <div class="icon-back mr-10px">
          <a href="<?= $link_back ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
        </div>
        <h1 class="title-header m-0"><?= $title_page ?></h1>
      </div>
      <?php if (!$is_edit) { ?>
        <?php if ($mode == 'probation') { ?>
          <div class="icon-action">
            <div class="d-flex align-items-center">
              <div class="me-3">ประเมินผลการทดลองงาน : </div>
              <button type="button" class="btn btn-icon-squere danger mr-10px" <?= Tiwdal::register('approved_probation_reject', $emp, []) ?>>
                <?= file_get_contents('assets/icons/times.svg') ?>
              </button>
              <button type="button" class="btn btn-icon-squere success" <?= Tiwdal::register('approved_probation', $emp, ['is_ajax' => 1]) ?>>
                <?= file_get_contents('assets/icons/check-success.svg') ?>
              </button>
            </div>
          </div>
        <?php } else if ($mode != 'my_profile') { ?>
          <div class="icon-action">
            <div class="d-flex">
              <a href="employee_detail.php?id=<?= $id ?>&mode=<?= $mode ?>&is_edit=1" class="btn btn-c-icon circle btn-c-primary">
                <?= file_get_contents('assets/icons/pencil.svg') ?>
              </a>
              <button type="button" class="btn btn-c-icon circle btn-c-danger" <?php Tiwdal::register('delete_emp', $emp); ?>>
                <?= file_get_contents('assets/icons/delete.svg') ?>
              </button>
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
  <hr>

  <div class="d-flex justify-content-center mt-25px mb-15px">
    <!-- <form id="scope_form_upload_image" method="post" enctype="multipart/form-data">
      <input type="hidden" name="submit_change_profile"> -->
    <div class="box-img-upload">
      <?php
      $options = [
        'width' => '150px',
        'height' => '150px',
        'bg-img' => 'assets/images/placeholder.png',
        'is_btn' => 0,
        'is_view' => 0,
        'is_delete' => 0,
        'preview_name' => '{name}',
      ];
      TiwForm::normal('upload-img', $emp['profile_image'], ['id' => 'profile-avatar', 'class' => 'pe-none', 'name' => 'profile_image', 'readonly' => 1], $options);
      ?>
      <label for="profile-avatar" class="icon-upload-file">
        <img src="assets/images/btn-upload.png" alt="">
      </label>
    </div>
    <!-- </form> -->
  </div>

  <div class="row">
    <div class="col-lg-12 text-center">
      <div class="fw-semibold font-22px"><?= $emp['full_name'] ?></div>
      <div class="font-Medium text-secondary"><?= $emp['username'] ?></div>

      <?php if ($mode == 'my_profile') { ?>
        <div class="mt-10px">
          <a href="employee_detail.php?id=<?= $id ?>&mode=<?= $mode ?>&is_edit=1" class="btn btn-outline-default max-w-150px mx-auto">
            <?= file_get_contents('assets/icons/pencil.svg') ?> <div class="font-Medium text-secondary font-16px ms-2">แก้ไขข้อมูล</div>
          </a>
        </div>
      <?php } ?>
    </div>


  </div>

  <?= navTab::normal($data_nav, '?id=' . $emp['id'] . '&mode=' . $mode, 'mt-4 mb-4'); ?>

  <?php if ($page == 'emp_detail') { ?>
    <?php include_once('view/employee/employee_detail.php'); ?>
  <?php } else if ($page == 'emp_performance') { ?>
    <?php include_once('view/employee/performance.php'); ?>
  <?php } else if ($page == 'emp_financial') { ?>
    <?php include_once('view/employee/financial.php'); ?>
  <?php } else if ($page == 'emp_history_money') { ?>
    <?php include_once('view/employee/history.php'); ?>
  <?php } ?>
</div>


<?php Tiwdal::startModal('modal_change_password', 'modal-md modal-dialog-centered mx-auto  max-w-400px'); ?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['เปลี่ยนรหัสผ่าน', '']) ?></div>

    <div class="row mb-15px">
      <div class="col-lg-12">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">รหัสผ่านใหม่</div>
            <input id="event_password" type="password" class="input" name="password" value="" onkeyup="checkPassword(this,'event_confirm_password');" required>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-lg-12">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">ยืนยันรหัสผ่าน</div>
            <input id="event_confirm_password" type="password" class="input" name="confirm_password" value="" onkeyup="checkPassword(this,'event_password');" required>
          </div>
        </div>
      </div>
      <div class="col-lg-12 mt-10px">
        <span class="alert_txt_error text-danger"></span>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['id' => 'event_btn_password', 'disabled' => 1, 'name' => 'submit_change_password', 'type' => 'submit', 'class' => 'btn-primary min-w-120px'], ['text' => Ty::get(['ยืนยัน', ''])]);
    ?>
  </div>
</form>
<?php Tiwdal::endModal(); ?>

<?php Tiwdal::ajaxModal('change_permission', 'modal-md modal-dialog-centered mx-auto  max-w-400px'); ?>



<?php Tiwdal::ajaxModal('approved_probation', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>

<?php Tiwdal::startModal('approved_probation_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ไม่ผ่านการทดลองงาน', '']) ?></div>
    <div class="text-center mb-15px">
      <div>
        คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่ให้ผ่านการทดลองงาน</span> แก่
      </div>
      <div class="fw-semibold mb-10px" name="{full_name}"></div>
    </div>

    <div class="row mb-5px">
      <div class="col-lg-12">
        <span class="fw-semibold">บันทึกเพิ่มเติม</span>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-lg-12">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">สาเหตุที่ไม่อนุมัติ</div>
            <textarea name="remark" class="input"></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_probation_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติคำขอ']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal(); ?>
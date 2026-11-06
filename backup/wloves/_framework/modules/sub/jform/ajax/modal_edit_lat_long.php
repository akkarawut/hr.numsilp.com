<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
require_once '../php/user_field_form.php';
W_Structure::loadMetaForAjax($prefix);
?>

<form method="post">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">แก้ไขข้อมูล</h5>
  </div>
  <div class="modal-body px-0">
    <div class="main-option">
      <div id="event_show_form" class="right-side-option">
        <?= f_template_form_lat_long($is_edit = true, $_POST) ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?=
    TiwForm::normal('hidden', $_POST['id'], ['name' => 'id']);
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['name' => 'jf_submit_edit_data', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'ยินยัน', 'type' => '']);
    ?>
  </div>
</form>
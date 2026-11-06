<?php
require_once '../../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
?>

<button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'></button>
<div class="modal-header">
  <h5 class="modal-title">Ex. Edit Modal</h5>
</div>
<form action="" method="post">
  <div class="modal-body">
    <div class="row mb-15px">
      <div class="col-sm-4 pt-7px">Name<span class="text-danger">*</span></div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $_POST['name'], ['name' => 'name', 'placeholder' => 'Enter', 'class' => 'mb-0']) ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 pt-7px">Description</div>
      <div class="col-sm-8">
        <?= TiwForm::normal('textarea', $_POST['description'], ['name' => 'description', 'placeholder' => 'Enter', 'class' => 'mb-0 min-h-50px']) ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'ADD', 'type' => '']);
    ?>
  </div>
</form>
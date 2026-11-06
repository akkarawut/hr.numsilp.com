<?php
$_WLOVES['no_check_permission'] = 1;
require_once  '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="modal-header">
  <h5 class="modal-title">Add new module</h5>
</div>
<form method="post">
  <div class="modal-body">
    <div>
      <?php
      $options = [
        'is_search' => true,
        'list' => [
          [
            'value' => '1',
            'name' => '1',
          ],
          [
            'value' => '2',
            'name' => '2',
          ],
          [
            'value' => '3',
            'name' => '3',
          ],
        ],
      ];
      TiwForm::normal('select', '2', ['name' => ''], $options);
      ?>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_add_new_module', 'type' => 'submit', 'class' => 'min-w-100px'], ['text' => 'ADD']);
    ?>
  </div>
</form>
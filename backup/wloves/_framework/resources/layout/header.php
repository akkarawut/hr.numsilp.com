<?php
echo '<div class="main-content">'; // do not remove
// $notification = rand(0, 150);
$role_list = ['guardian', 'dev', 'tester'];
?>

<?php Tiwdal::startModal('logout_popup'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-body">
  <h3 class="text-center font-16px font-SemiBold text-uppercase">Confirm your log out</h3>
  <div class="text-center">Are you sure?</div>
</div>
<div class="modal-footer d-flex justify-content-between">
  <?php
  TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-close-modal min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
  ?>
  <a href="<?= W_Structure::getLinkURL('_framework/resources/logout/index.php') ?>">
    <?= TiwForm::normal('btn', '', ['name' => 'submit_delete_module', 'type' => 'button', 'class' => 'btn btn-danger'], ['text' => 'YES!!']); ?>
  </a>
</div>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('logout_user_popup'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-body">
  <h3 class="text-center font-16px font-SemiBold text-uppercase">Confirm your log out</h3>
  <div class="text-center">Are you sure?</div>
</div>
<div class="modal-footer d-flex justify-content-between">
  <?php
  TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-close-modal min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
  ?>
  <a href="<?= W_Structure::getLinkURL('_framework/resources/logout/index.php?type=user') ?>">
    <?= TiwForm::normal('btn', '', ['name' => 'submit_delete_module', 'type' => 'button', 'class' => 'btn btn-danger'], ['text' => 'YES!!']); ?>
  </a>
</div>
<?php Tiwdal::endModal() ?>
<?php
if ($_POST) {
  if (isset($_POST['submit_add_category'])) {
    unset($_POST['submit_add_category']);
    $_POST['user_type_id'] = $user_type;
    $result = User_Basic_Setting::addNewCategory($_POST);
  } else if (isset($_POST['submit_edit_category'])) {
    unset($_POST['submit_edit_category']);
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    $result = User_Basic_Setting::updateCategory($id, $_POST);
  } else if (isset($_POST['submit_delete_category'])) {
    $result = User_Basic_Setting::deleteCategory($_POST['id']);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}
?>

<div class="col-md-9">
  <div class="d-flex align-items-center justify-content-between flex-wrap">
    <div>
      <h6 class="font-16px font-SemiBold mb-0">User Categories</h6>
      <p class="font-14px text-secondary mb-10px">Manage your user categories for grouping user.</p>
    </div>
    <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['type' => '', 'modal_id' => 'add_category_modal', 'modal_data' => [], 'text' => '+ ADD NEW CATEGORY']); ?>
  </div>

  <div class="w-loves-card p-0">
    <div id="basic_setting_category" class="container-pagination" <?= Homepagify::createHomepagify('basic_setting_category', '?c=&user_type=' . $user_type, '', 'Categories') ?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th nowrap data-sort="name">Team Name</th>
              <th nowrap data-sort="description">Description</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('add_category_modal', 'modal-md'); ?>
<form method="post">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Add New Category</h5>
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-3 pt-5px">Category Name<span class="text-danger">*</span></div>
        <div class="col-md-9">
          <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter', 'required' => true]); ?>
        </div>
        <div class="col-md-3 pt-5px">Description</div>
        <div class="col-md-9">
          <?= TiwForm::normal('text', '', ['name' => 'description', 'placeholder' => 'Enter']); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer justify-content-between">
      <?php
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
      TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px', 'name' => 'submit_add_category'], ['text' => 'SAVE']);
      ?>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('edit_category_modal', 'modal-md'); ?>
<form method="post">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">edit Category</h5>
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-3 pt-5px">Category Name<span class="text-danger">*</span></div>
        <div class="col-md-9">
          <?= TiwForm::normal('text', '', ['name' => '{name}', 'placeholder' => 'Enter', 'required' => true]); ?>
        </div>
        <div class="col-md-3 pt-5px">Description</div>
        <div class="col-md-9">
          <?= TiwForm::normal('text', '', ['name' => '{description}', 'placeholder' => 'Enter']); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer justify-content-between">
      <?php
      TiwForm::normal('hidden', '', ['name' => '{id}']);
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
      TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px', 'name' => 'submit_edit_category'], ['text' => 'Confirm']);
      ?>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('delete_category_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="modal-body">
  <div class="text-center font-SemiBold mb-10px text-uppercase">Delete User category</div>
  <div class="text-center text-secondary font-14px">Are you sure to delete <span class="text-danger">“User Category”</span> your delete is not effect with recent history</div>
</div>
<form action="" method="post">
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Maybe']);
    TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-120px btn-danger', 'name' => 'submit_delete_category'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>
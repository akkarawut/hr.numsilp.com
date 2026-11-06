<?php
$user_type = (isset($_GET['user_type']) && $_GET['user_type']) ? $_GET['user_type'] : '';

if ($_POST) {
  if (isset($_POST['submit_add_permission_template'])) {
    unset($_POST['submit_add_permission_template']);
    $_POST['user_type_id'] = $user_type;
    if (!$_POST['ref_template_id']) {
      unset($_POST['ref_template_id']);
    }
    $result = User_Basic_Setting::addNewPermissionTemplate($_POST);

    if ($result['response_status']) {
      $id = $result['response_data']['insert_id'];
      $response_redirect = 'user_basic_setting.php?c=&user_type=' . $user_type . '&type=1&page=template_detail&id=' . $id;
    }
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

$templates = User_Basic_Setting::selectPermissionTemplate(['user_type_id' => $login_user_type_id]);
?>
<div class="col-md-9">
  <div class="d-flex align-items-center justify-content-between flex-wrap">
    <div>
      <h6 class="font-16px font-SemiBold mb-0">Permission Template</h6>
      <p class="font-14px text-secondary mb-10px">Create permission template for use configure basic permission on user.</p>
    </div>
    <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'mb-10px'], ['type' => '', 'modal_id' => 'add_permission_template_modal', 'modal_data' => [], 'text' => '+ ADD NEW TEMPLATE']); ?>
  </div>

  <div class="w-loves-card p-0">
    <div id="basic_setting_permission" class="container-pagination" <?= Homepagify::createHomepagify('basic_setting_permission', '?user_type=' . $user_type . '&type=' . $type . '&page=' . $page, '', 'Permission Template') ?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th nowrap data-sort="name">Template Name</th>
              <th nowrap data-sort="description">Description</th>
              <th nowrap data-sort="status">Activate</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('add_permission_template_modal', 'modal-md'); ?>
<form method="post">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Add New Permission Template</h5>
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-3 pt-5px">Template Name<span class="text-danger">*</span></div>
        <div class="col-md-9">
          <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter', 'required' => true]); ?>
        </div>
        <div class="col-md-3 pt-5px">Description</div>
        <div class="col-md-9">
          <?= TiwForm::normal('text', '', ['name' => 'description', 'placeholder' => 'Enter']); ?>
        </div>
        <div class="col-md-3 pt-5px">Ref Permission</div>
        <div class="col-md-9">
          <?php
          $key = [
            'value' => 'id',
            'name' => 'name',
          ];
          $options = TiwForm::generateSelectData($templates, $key, ['is_search' => true]);
          TiwForm::normal('select', '', ['name' => 'ref_template_id', 'placeholder' => 'None'], $options);
          ?>
        </div>
      </div>
    </div>
    <div class="modal-footer justify-content-between">
      <?php
      TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
      TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px', 'name' => 'submit_add_permission_template'], ['text' => 'SAVE']);
      ?>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>
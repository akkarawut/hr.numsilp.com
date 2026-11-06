<?php
if ($_POST) {
  if (isset($_POST['submit_add_team'])) {
    unset($_POST['submit_add_team']);
    $_POST['user_type_id'] = $user_type;
    $result = User_Basic_Setting::addNewTeam($_POST);
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
      <h6 class="font-16px font-SemiBold mb-0">Team</h6>
      <p class="font-14px text-secondary mb-10px">Manage user team and use for grouping user.</p>
    </div>
    <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'mb-10px'], ['type' => '', 'modal_id' => 'add_team_modal', 'modal_data' => [], 'text' => '+ ADD NEW TEAM']); ?>
  </div>

  <div class="w-loves-card p-0">
    <div id="basic_setting_team" class="container-pagination" <?= Homepagify::createHomepagify('basic_setting_team', '?user_type=' . $user_type . '&type=' . $type, '', 'Team') ?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th nowrap data-sort="name" class="min-w-150px">Team Name</th>
              <th nowrap data-sort="description">Description</th>
              <th nowrap data-sort="status" class="text-right thin-cell">User in this team</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('add_team_modal', 'modal-md'); ?>
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
        <div class="col-md-3 pt-5px">Team Name<span class="text-danger">*</span></div>
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
      TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px', 'name' => 'submit_add_team'], ['text' => 'SAVE']);
      ?>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>
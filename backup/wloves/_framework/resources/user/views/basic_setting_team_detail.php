<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_POST) {
  if (isset($_POST['submit_edit_team'])) {
    unset($_POST['submit_edit_team']);
    $result = User_Basic_Setting::updateTeam($id, $_POST);
    if ($result['response_status']) {
      $response_redirect = 'user_basic_setting.php?user_type=' . $user_type . '&type=' . $type . '&page=team_detail&id=' . $id;
    }
  } else if (isset($_POST['submit_delete_team'])) {
    $result = User_Basic_Setting::deleteTeam($id);
    if ($result['response_status']) {
      $response_redirect = 'user_basic_setting.php?user_type=' . $user_type . '&type=' . $type;
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

$team = User_Basic_Setting::getTeamByID($id);

function templateFormGroup($title, $detail)
{
  echo '<div class="col-md-5 col-lg-3 pt-10px font-14px text-secondary">' . $title . '</div>
        <div class="col-md-7 col-lg-9">' . $detail . '</div>';
}
?>

<div class="col-md-9">
  <div class="editable-card core-new">
    <div class="editable-card-header-back pl-15px py-10px font-13px">
      <div class="text-info mr-5px">Team</d> |
        <span class="text-primary pl-5px"> Team Details</span>
      </div>
    </div>
    <form action="" method="post">
      <div class="editable-card core-new mb-15px">
        <div class="editable-card-body container-detail min-h-100px pt-10px">
          <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
              <h3 class="font-16px mb-0 d-flex align-items-center text-uppercase text-info">Team Detail</h3>
              <p class="font-14px mb-10px">Manage team basic detail. | Last Update: <?= W_Main::formatDate($team['update_date_time'], 'd/m/Y, H:i'); ?> By <?= $team['update_user_username'] ? $team['update_user_username'] : 'Unknow'; ?></p>
            </div>
            <div class="mb-10px d-flex">
              <?php
              if ($is_edit) {
                echo '<a href="user_basic_setting.php?c=&user_type=' . $user_type . '&type=' . $type . '&page=' . $page . '&id=' . $id . '" class="btn btn-light h-35px mr-5px">CANCEL</a>';
                TiwForm::normal('btn', 'submit', ['name' => 'submit_edit_team'], ['text' => 'SAVE']);
              } else {
                echo '<a href="user_basic_setting.php?c=&user_type=' . $user_type . '&type=' . $type . '&page=' . $page . '&id=' . $id . '&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => 'EDIT DATA', 'type' => '', 'is_return' => true]) . '</a>';
              }
              ?>

              <div class="dropdown top-nav-dropdown">
                <button class="btn min-w-50px" type="button" data-toggle="dropdown">
                  <?= file_get_contents('assets/image/icon/icon-dot-dropdown.svg'); ?>
                </button>
                <div class="dropdown-menu">
                  <button type="button" class="dropdown-item" <?= Tiwdal::register('delete_team_modal', []); ?>>
                    <div class="d-flex align-items-center">
                      <?= file_get_contents('assets/image/icon/delete.svg'); ?>
                      <span class="ml-5px text-danger font-14px">Delete Team</span>
                    </div>
                  </button>
                </div>
              </div>

            </div>
          </div>
          <hr class="mt-0 mx--15px">
          <div class="row">
            <div class="col-12 font-14px text-uppercase font-SemiBold text-info">general detail</div>
            <?php
            templateFormGroup('Team Name', TiwForm::normal('text', $team['name'], ['name' => 'name', 'class' => '', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]));

            templateFormGroup('Description', TiwForm::normal('textarea', $team['description'], ['name' => 'description', 'class' => '', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]));
            ?>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="w-loves-card p-0">
    <div id="user_in_team" class="container-pagination" <?= Homepagify::createHomepagify('user_in_team', '?id=' . $id, '', 'User In This Team') ?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th></th>
              <th nowrap data-sort="full_name" class="min-w-150px">Name</th>
              <th nowrap data-sort="username">Username</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('delete_team_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
<form method="post">
  <div class="modal-body border-radius-10-10-0-0px">
    <div class="row">
      <div class="col-12 form-group text-center">
        <p class="font-SemiBold mb-5px mt-20px text-uppercase">Delete Team</p>
        <p>
          Are you sure to delete <span class="text-danger">USER TEAM” Have 5 user used this team</span> Before your will be delete it, you can change user in this team to other team
        </p>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'Maybe']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_team', 'type' => 'submit', 'class' => 'btn btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<script>
  $(function() {
    $(document).on('change', '.is_activate_event input', function(e) {
      var is_checked = $(this).prop('checked');
      var scope = $(this).parents('.scope_box_event');
      if (is_checked) {
        scope.find('.label_text').html('<span class="text-primary">Activate</span>');
      } else {
        scope.find('.label_text').html('<span class="text-danger">Deactivate</span>');
      }
    });
  });
</script>
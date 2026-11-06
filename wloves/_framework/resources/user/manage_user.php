<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_management'];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['boatnav']);

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';

$module_info = [];
$module_list = W_Permission::$functions_available;
foreach ($module_list as $key => $module) {
  if ($module['code'] == $_GET['c']) {
    $module_info = $module;
  }
}
if ($_POST) {
  if (isset($_POST['submit_add_user'])) {
    $template_id = (isset($_POST['template_id']) && $_POST['template_id']) ? $_POST['template_id'] : '';
    unset($_POST['submit_add_user']);
    unset($_POST['template_id']);
    unset($_POST['country_name']);

    $result = User::addNewUser($module_info['code'], $_POST);

    if ($result['response_status']) {
      $id = $result['response_data']['insert_id']; //user id

      //set permission from select template
      if ($template_id) {
        $permissions = User_Basic_Setting::getTemplatePermissionList($template_id);
        $permission_codes = array_keys($permissions);

        $result = User_Permission::triggerUserPermission($id, $permission_codes, 1);
      }

      $response_redirect = 'manage_user.php?c=' . $_GET['c'] . '&user_id=' . $id . '&page=1';
    }
  } else if (isset($_POST['submit_block_user'])) {
    $result = User::ban($user_id);
  } else if (isset($_POST['submit_unblock_user'])) {
    $result = User::unban($user_id);
  } else if (isset($_POST['submit_delete_user'])) {
    $result = User::deleteUser($user_id);
    if ($result['response_status']) {
      $response_redirect = 'manage_user.php?c=' . $_GET['c'];
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

$user_type_info = User_type::getUserTypeByID($module_info['managed_user_type_id']);

//menu
if ($user_id) {
  $data_nav = [
    'param_name'  => 'page',
    'class' => '',
    'list' => [
      [
        'id'  => 1,
        'name'  => 'General Information',
      ],
    ]
  ];

  if ($user_type_info['is_allow_program_login']) {
    $data_nav['list'][] = [
      'id'  => 2,
      'name'  => 'Login  & Permission',
    ];
  }
  if ($user_type_info['is_multiple_bank_account']) {
    $data_nav['list'][] = [
      'id'  => 3,
      'name'  => 'Bank Account',
    ];
  }
  if ($user_type_info['is_multiple_address']) {
    $data_nav['list'][] = [
      'id'  => 4,
      'name'  => 'Multiple Address',
    ];
  }
  if ($user_type_info['is_topbar_contact_person']) {
    $data_nav['list'][] = [
      'id'  => 5,
      'name'  => 'Contact Person',
    ];
  }
}

function messageTemplate($class = '')
{
?>
  <div class="<?= $class ?> mb-10px hidden">
    <div class="d-flex align-items-center">
      <?= file_get_contents('assets/image/icon/exclmation_mark.svg') ?>
      <span class="font-13px text-danger ml-5px"></span>
    </div>
  </div>
<?php
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta('../../../');
  W_Main::loadAsset('assets/css/user.css');
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 pr-lg-5px mb-10px">
        <div class="row">
          <div class="col-12">
            <div class="flex-between-center flex-wrap">
              <div class="topic">
                <h5><?= $module_info['name'] . ' List' ?></h5>
                <div class="font-14px text-secondary mb-10px">Manage your user data, permission and account</div>
              </div>
              <div class="btn-action-more">
                <?= TiwForm::normal('btn', '', ['class' => 'min-w-100px mb-10px', 'type' => 'button', 'data-bs-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'aria-expanded' => 'false'], ['text' => '+ ADD NEW']) ?>
                <div class="dropdown-menu dropdown-menu-right">
                  <button type="button" class="btn btn-light text-dark dropdown-item" <?= Tiwdal::register('add_new_user'); ?>>
                    Add 1 User
                  </button>
                  <!-- <a href="add_multiple_user.php?c=<?= $_GET['c'] ?>" class="btn dropdown-item text-primary">
                  Add Multiple User
                </a> -->
                </div>
              </div>
            </div>

            <div class="table-radius-10px">
              <div id="user_list" class="container-pagination no-radius" <?= Homepagify::createHomepagify('user_list', '?c=' . $_GET['c'] . '&user_id=' . $user_id, '', $module_info['name']) ?>>
                <div class="table-responsive">
                  <table class="table table-sort table-search">
                    <thead>
                      <tr>
                        <th nowrap data-sort="id" data-filter="<?= Homepagify::dataFilter('id_like', 'text') ?>"><?= $module_info['name'] ?> ID</th>
                        <th nowrap data-sort="full_name" data-filter="<?= Homepagify::dataFilter('full_name', 'text') ?>">Name</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <?php
      if ($user_id) {
        $user_info = User::getUserByID($user_id, ['img_path' => true]);
      ?>
        <div class="col-lg-8 pl-lg-5px box-nav-top">
          <div class="top-nav-radius">
            <?php Boatnav::dinner($data_nav); ?>
            <div class="top-nav-action">
              <div class="btn-action-more br-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= file_get_contents('../../structure/image/icon/general/more.svg') ?>
              </div>
              <div class="dropdown-menu dropdown-menu-right">
                <button type="button" class="btn dropdown-item" <?= Tiwdal::register(($user_info['is_ban']) ? 'unblock_user_modal' : 'block_user_modal'); ?>>
                  <?= file_get_contents('../../structure/image/icon/general/block.svg') ?>
                  <span class="ml-10px text-danger"><?= ($user_info['is_ban']) ? 'Unblock User' : 'Block User' ?></span>
                </button>
                <!-- <button type="button" class="btn dropdown-item" <?= Tiwdal::register('delete_user_modal'); ?>>
                <?= file_get_contents('../../structure/image/icon/general/rad-bin.svg') ?>
                <span class="ml-10px text-danger">Delete User</span>
              </button> -->
              </div>
            </div>
          </div>

          <?php
          if ($page == 1) {
            include 'views/manage_user_info.php';
          } else if ($page == 2 && $user_type_info['is_allow_program_login']) {
            include 'views/manage_user_permission.php';
          } else if ($page == 3 && $user_type_info['is_multiple_bank_account']) {
            include 'views/manage_user_bank.php';
          } else if ($page == 4 && $user_type_info['is_multiple_address']) {
            include 'views/manage_user_address.php';
          } else if ($page == 5 && $user_type_info['is_topbar_contact_person']) {
            include 'views/manage_user_contact_person.php';
          } else {
            echo '<div class="container-detail d-flex align-items-center justify-content-center font-14px text-secondary text-danger">NO PERMISSION</div>';
          }
          ?>

        </div>
      <?php } else { ?>
        <div class="col-lg-8 pl-lg-5px">
          <div class="w-loves-card px-0 py-0">
            <div class="w-loves-card-header px-15px mb-10px set-header-bg">
              <div class="d-flex align-items-center">
                <div class="d-flex align-items-center"><?= file_get_contents('assets/image/icon/lightbulb.svg') ?></div>
                <span class="font-16px font-weight-bold ml-5px">View <span class="text-lowercase"><?= $module_info['name'] ?></span> information</span>
              </div>
              <p class="font-13px text-secondary">Please select a system <span class="text-lowercase"><?= $module_info['name'] ?></span> You want to view information form the list on the left to show the details of the <span class="text-lowercase"><?= $module_info['name'] ?></span>.</p>
            </div>
            <div class="bg-manage-user">
              <?= file_get_contents('assets/image/icon/bg-user.svg') ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <?php Tiwdal::startModal('add_new_user', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <form method="post" class="">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ADD NEW USER</h5>
      </div>
      <div class="modal-body">
        <div class="row aww-regex-box">
          <div class="col-md-4">
            <label class="text-secondary font-14px mt-7px">Name<span class="text-danger">*</span></label>
          </div>

          <div class="col-md-8">
            <div class="row">
              <div class="col-4 pr-5px">
                <?= TiwForm::normal('select-title', '', ['name' => 'title', 'placeholder' => 'Select', 'required' => true], ['is_search' => true]) ?>
              </div>
              <div class="col px-5px">
                <?php TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'First Name', 'required' => true]); ?>
              </div>
              <div class="col pl-5px">
                <?php TiwForm::normal('text', '', ['name' => 'surname', 'placeholder' => 'Last Name']); ?>
              </div>
            </div>
          </div>

          <?php if ($user_type_info['is_tel']) { ?>
            <div class="col-md-4">
              <label class="text-secondary font-14px mt-7px">Telephone<span class="text-danger">*</span></label>
            </div>
            <div class="col-md-8">
              <?= TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no', 'placeholder' => 'Enter', 'required' => true], ['country_code' => 'tel_country_code']) ?>
            </div>
          <?php } ?>

          <?php
          if ($user_type_info['is_has_email']) { ?>
            <div class="col-md-4">
              <label class="text-secondary font-14px mt-7px">Email<span class="text-danger">*</span></label>
            </div>
            <div class="col-md-8">
              <?= TiwForm::normal('email', '', ['name' => 'email', 'placeholder' => 'Enter', 'required' => true, 'class' => 'check_email_event']) ?>
              <?= messageTemplate('msg_email_event'); ?>
            </div>
          <?php } ?>

          <div class="col-md-4">
            <label class="text-secondary font-14px mt-7px">Username<span class="text-danger">*</span></label>
          </div>
          <div class="col-md-8">
            <?= TiwForm::normal('text', '', ['name' => 'username', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'check_username_event']); ?>
            <?= messageTemplate('msg_username_event'); ?>
          </div>

          <div class="col-md-4">
            <label class="text-secondary font-14px mt-7px">Password<span class="text-danger">*</span></label>
          </div>

          <?php if ($user_type_info['is_strict_password']) { ?>
            <div class="col-md-8 mb-10px">
              <?php
              TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Password', 'class' => 'set_check_password', 'required' => true]);
              ?>
              <p class="text-danger mb-5px hide-duplicate hidden">Username Duplicate</p>
              <?php
              $input_class = 'set_check_password';
              $button_class = 'btn_add_user_event';
              $options = [
                'is-char' => true,
                'is-char-number' => 7,
                'is-word' => true,
                'is-number' => true,
              ];
              TiwForm::checkForm($input_class, $button_class, $options);
              ?>
            </div>
          <?php } else { ?>
            <div class="col-md-8">
              <?= TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => 'Password', 'required' => true]); ?>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="modal-footer">
        <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']); ?>
        <div class="d-flex m--5px">
          <?= TiwForm::normal('btn', '', ['name' => 'submit_add_user', 'type' => 'submit', 'class' => 'm-5px min-w-100px btn_add_user_event', 'disabled' => true], ['text' => 'Save']); ?>
        </div>
      </div>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('block_user_modal', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <form method="post" class="">
    <div class="modal-content">
      <div class="modal-body">
        <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">Block USER</div>
        <div class="text-secondary text-center">Are you sure to Block <span class="text-danger text-uppercase">“This user”</span><br>your block is not effect with recent history and<br>this user can’t login to this system</div>
      </div>
      <div class="modal-footer">
        <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'No…']); ?>
        <div class="d-flex m--5px">
          <?= TiwForm::normal('btn', '', ['name' => 'submit_block_user', 'type' => 'submit', 'class' => 'm-5px min-w-120px btn-danger'], ['text' => 'Yes!! I’m Sure']); ?>
        </div>
      </div>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('unblock_user_modal', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <form method="post" class="">
    <div class="modal-content">
      <div class="modal-body">
        <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">Unblock USER</div>
        <div class="text-secondary text-center">Are you sure to Unblock <span class="text-danger text-uppercase">“This user”</span><br>your unblock is not effect with recent history and<br>this user can login to this system</div>
      </div>
      <div class="modal-footer">
        <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'No…']); ?>
        <div class="d-flex m--5px">
          <?= TiwForm::normal('btn', '', ['name' => 'submit_unblock_user', 'type' => 'submit', 'class' => 'm-5px min-w-120px btn-danger'], ['text' => 'Yes!! I’m Sure']); ?>
        </div>
      </div>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('delete_user_modal', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <form method="post" class="">
    <div class="modal-content">
      <div class="modal-body">
        <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">Delete USER</div>
        <div class="text-secondary text-center">Are you sure to delete <span class="text-danger text-uppercase">“This user”</span><br>your delete is not effect with recent history</div>
      </div>
      <div class="modal-footer">
        <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'No…']); ?>
        <div class="d-flex m--5px">
          <?= TiwForm::normal('btn', '', ['name' => 'submit_delete_user', 'type' => 'submit', 'class' => 'm-5px min-w-120px btn-danger'], ['text' => 'Yes!! I’m Sure']); ?>
        </div>
      </div>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('verify_user', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
  <form method="post">
    <div class="modal-body border-top-radius-10px">
      <div class="row">
        <div class="col-12 form-group text-center">
          <h5 class="font-16px mb-5px mt-20px text-success">APPROVE</h5>
          <p class="text-secondary">Are you sure to Approve <span class="text-success">“User’s Verify Data”</span> please enter approval remark.</p>
        </div>

        <div class="col-md-3 form-group d-flex align-items-center">
          <label>Remark</label>
        </div>
        <div class="col-md-9">
          <?php TiwForm::normal('textarea', '', ['name' => 'pin', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'pin']); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <input type="hidden" name="{verify_status}">
      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-modal-confirm" name="submit_verify_user">Save</button>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('reject_user', 'modal-md'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
  <form method="post">
    <div class="modal-body border-top-radius-10px">
      <div class="row">
        <div class="col-12 form-group text-center">
          <h5 class="font-16px mb-5px mt-20px text-danger">REJECT</h5>
          <p class="text-secondary">Are you sure to Reject <span class="text-danger">“User’s Verify Data”</span> please enter reject remark.</p>
        </div>

        <div class="col-md-3 form-group d-flex align-items-center">
          <label>Remark</label>
        </div>
        <div class="col-md-9">
          <?php TiwForm::normal('textarea', '', ['name' => 'pin', 'placeholder' => 'Enter', 'required' => 'required', 'class' => 'pin']); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <input type="hidden" name="{verify_status}">
      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-modal-confirm" name="submit_reject_user">Save</button>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter('../../../');
  ?>

  <script>
    $(function() {
      var __check_username_format = true;
      var __check_username_already = false;
      var __check_email = true;

      $(document).on('change', '.check_username_event', async function(e) {
        var username = $(this).val();
        if (username) {
          //check username format
          if (!username.match(/^([a-z0-9])+$/i)) {
            __check_username_format = await false;
            checkAllowSaveEvent();
            return false;
          } else {
            __check_username_format = await true;
            checkAllowSaveEvent();
          }

          //check username already
          var url = 'ajax/ajax_check_username.php';
          var params = {
            'username': username,
          };
          $.post(url, params).done(async function(data) {
            if (data != 0) {
              __check_username_already = await false;
            } else {
              __check_username_already = await true;
              $('.msg_username_event').hide();
            }
            checkAllowSaveEvent();
          });
        } else {
          $('.msg_username_event').hide();
          $('.btn_add_user_event').attr('disabled', true);
        }
      });

      $(document).on('keyup change', '.check_email_event', async function(e) {
        var email = $(this).val();
        if (email) {
          //check username format
          if (!email.match(/^([a-zA-Z0-9@.])+$/i) || email.indexOf(".") <= 0) {
            __check_email = await false;
            checkAllowSaveEvent();

            $('.msg_email_event span').html('Sorry. only letters (a-z), number (0-9), and periods (.) are allowed.');
            $('.msg_email_event').show();
            $('.btn_add_user_event').attr('disabled', true);
          } else {
            __check_email = await true;
            checkAllowSaveEvent();
          }
        } else {
          $('.msg_email_event').hide();
          $('.btn_add_user_event').attr('disabled', true);
        }
      });

      function checkAllowSaveEvent() {
        if (!__check_username_format) {
          $('.msg_username_event span').html("Sorry, letter don't allow free space.");
          $('.msg_username_event').show();
          $('.btn_add_user_event').attr('disabled', true);
          return false;
        } else if (!__check_username_already && $('.check_username_event').val()) {
          $('.msg_username_event span').html('This username is already in use.');
          $('.msg_username_event').show();
          $('.btn_add_user_event').attr('disabled', true);
          return false;
        } else if (!__check_email) {
          return false;
        }

        $('.msg_username_event').hide();
        $('.msg_email_event').hide();
        $('.btn_add_user_event').attr('disabled', false);
      }
    });
  </script>

</body>

</html>
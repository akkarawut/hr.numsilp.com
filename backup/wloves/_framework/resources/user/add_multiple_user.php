<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_management'];
require_once '../../../_framework/import.php';

$module_info = Module::getModuleByCode($_GET['c']);

if ($_POST) {
  if (isset($_POST['submit_add_user']) || isset($_POST['submit_add_user_new'])) {
    unset($_POST['submit_add_user']);
    $new = isset($_POST['submit_add_user_new']) ? true : false;
    unset($_POST['submit_add_user_new']);
    unset($_POST['country_name']);

    foreach ($_POST['username'] as $key => $username) {
      $template_id = (isset($_POST['template_id'][$key]) && $_POST['template_id'][$key]) ? $_POST['template_id'][$key] : '';
      unset($_POST['template_id'][$key]);

      $data = [
        'title' => $_POST['title'][$key],
        'name' => $_POST['name'][$key],
        'surname' => $_POST['surname'][$key],
        'username' => $_POST['username'][$key],
        'password' => $_POST['password'][$key],
      ];

      if (isset($_POST['tel_no'][$key])) {
        $data['tel_country_code'] = $_POST['tel_country_code'][$key];
        $data['tel_no'] = $_POST['tel_no'][$key];
      }

      $result = User::addNewUser($module_info['code'], $data);

      $result_log[] = $result;

      if ($result['response_status']) {
        $id = $result['response_data']['insert_id']; //user id

        //set permission from select template
        if ($template_id) {
          $permissions = User_Basic_Setting::getTemplatePermissionList($template_id);
          $permission_codes = array_keys($permissions);

          $result = User_Permission::triggerUserPermission($id, $permission_codes, 1);
        }
      }
    }

    if ($result['response_status'] && !$new) {
      $response_redirect = 'manage_user.php?c=' . $_GET['c'] . '&user_id=' . $id . '&page=1';
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

$user_info   = W_User::getCurrentUser();
$permissions = $program_wolves_setup['user_permission'];

if (!isset($permissions[$_GET['c'] . '_user'])) {
  W_Main::notification('No User Permission', 'error');
  W_Main::redirect('../../module_main/login/logout.php');
}

$templates = User_Basic_Setting::selectPermissionTemplate(['user_type_id' => $user_type_info['id'], 'is_enabled' => 1]);

$options = [
  'page_no' => 1,
  'page_size' => 10,
  'sort' => ['id' => 'DESC']
];
$users = User::selectUser($_GET['c'], [], $options);

function messageTemplate($class = '')
{
?>
  <div class="<?= $class ?> hidden">
    <div class="d-flex align-items-start">
      <div class="mt-3px d-flex"><?= file_get_contents('assets/image/icon/exclmation_mark.svg') ?></div>
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
  W_Structure::loadMeta('../../');
  W_Main::loadAsset('assets/css/user.css');
  ?>
</head>

<body>
  <?php
  include_once '../../structure/layout/header-default.php';
  ?>

  <div class="top-card-back-title">
    <a href="manage_user.php?c=<?= $_GET['c'] ?>"><span class="text-secondary">User List</span></a> |
    <span class="text-primary">Add Multiple User</span>
  </div>
  <form action="" method="post">
    <div class="bg-card">
      <div class="table-responsive">
        <table class="table-bg-card-back full-border">
          <thead>
            <tr class="bg-card-navbar">
              <th nowrap>Title<span class="text-danger">*</span></th>
              <th nowrap class="min-w-150px">Name<span class="text-danger">*</span></th>
              <th nowrap class="min-w-150px">Surname</th>
              <th nowrap class="min-w-150px">Username<span class="text-danger">*</span></th>
              <th nowrap class="min-w-150px">Password<span class="text-danger">*</span></th>
              <?php if ($user_type_info['is_has_email']) { ?>
                <th nowrap class="min-w-150px">Email<span class="text-danger">*</span></th>
              <?php } ?>
              <?php if ($user_type_info['is_tel']) { ?>
                <th nowrap class="min-w-250px">Telephone Number<span class="text-danger">*</span></th>
              <?php } ?>
              <th nowrap class="min-w-150px">Permission Template</th>
              <th class="min-w-40px"></th>
            </tr>
          </thead>
          <tbody class="multiple_user_area_event">
            <?php for ($i = 0; $i < 2; $i++) { ?>
              <tr>
                <td class="py-5px">
                  <?= TiwForm::normal('select-title', '', ['name' => 'title[]', 'placeholder' => 'Select', 'required' => true, 'class' => 'mb-0'], ['is_search' => true]) ?>
                </td>
                <td class="py-5px">
                  <?php TiwForm::normal('text', '', ['name' => 'name[]', 'placeholder' => 'First Name', 'required' => true, 'class' => 'mb-0']); ?>
                </td>
                <td class="py-5px">
                  <?php TiwForm::normal('text', '', ['name' => 'surname[]', 'placeholder' => 'Last Name', 'class' => 'mb-0']); ?>
                </td>
                <td class="py-5px">
                  <?= TiwForm::normal('text', '', ['name' => 'username[]', 'placeholder' => 'Enter', 'required' => true, 'class' => 'mb-0 check_username_event']); ?>
                  <?= messageTemplate('msg_username_event'); ?>
                </td>
                <td class="py-5px">
                  <?= TiwForm::normal('password', '', ['name' => 'password[]', 'placeholder' => 'Password', 'required' => true, 'class' => 'mb-0']); ?>
                </td>
                <?php if ($user_type_info['is_has_email']) { ?>
                  <td class="py-5px">
                    <?= TiwForm::normal('email', '', ['name' => 'email[]', 'placeholder' => 'Enter', 'class' => 'mb-0 check_email_event', 'required' => true]) ?>
                    <?= messageTemplate('msg_email_event'); ?>
                  </td>
                <?php } ?>
                <?php if ($user_type_info['is_tel']) { ?>
                  <td class="py-5px">
                    <?= TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no[]', 'placeholder' => 'Enter', 'required' => true], ['country_code' => 'tel_country_code[]', 'country_name' => 'country_name[]', 'main_class' => 'mb-0']) ?>
                  </td>
                <?php } ?>
                <td class="py-5px">
                  <?php
                  $key = [
                    'value' => 'id',
                    'name' => 'name',
                  ];
                  $template_options = TiwForm::generateSelectData($templates, $key, ['is_search' => true]);

                  TiwForm::normal('select', '', ['name' => 'template_id[]', 'placeholder' => 'Select', 'class' => 'mb-0'], $template_options) ?>
                </td>
                <td class="thin-cell p-5px"></td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="10" class="p-10px">
                <div class="d-flex justify-content-center">
                  <?= TiwForm::normal('btn', '', ['type' => 'button'], ['text' => '+ ADD']); ?>
                </div>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div class="bg-card-back br-bottom-10px p-15px d-flex align-items-center justify-content-between flex-wrap mb-10px">
      <a href="manage_user.php?c=<?= $_GET['c'] ?>"><?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'min-w-80px btn-light'], ['text' => 'Cancel']); ?></a>
      <div class="d-flex">
        <?= TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-150px btn-primary-2 mr-10px btn_submit_add_user_event', 'name' => 'submit_add_user_new', 'disabled' => true], ['text' => 'Save & Create New']); ?>
        <?= TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-130px btn_submit_add_user_event', 'name' => 'submit_add_user', 'disabled' => true], ['text' => 'Save & Continue']); ?>
      </div>
    </div>
  </form>

  <div class="bg-card br-10px py-15px">
    <div class="mb-10px px-15px">
      <div class="text-uppercase font-16px font-SemiBold">Lasted 10 data</div>
      <div class="font-14px text-secondary">10 lasted data add to user list</div>
    </div>
    <hr class="my-0">
    <div class="table-responsive">
      <table class="table-bg-card-back">
        <thead>
          <tr>
            <th class="w-100px">Code</th>
            <th>Title</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Username</th>
            <?php if ($user_type_info['is_has_email']) { ?>
              <th class="thin-cell">Email</th>
            <?php } ?>
            <?php if ($user_type_info['is_tel']) { ?>
              <th class="thin-cell">Telephone Number</th>
            <?php } ?>
            <th>Add Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($users) {
            foreach ($users as $user) {
          ?>
              <tr>
                <td nowrap><?= $user['user_code'] ? $user['user_code'] : $user['id']; ?></td>
                <td nowrap class="thin-cell"><?= $user['title'] ?></td>
                <td nowrap><?= $user['name'] ?></td>
                <td nowrap><?= $user['surname'] ?></td>
                <td nowrap><?= $user['username'] ?></td>
                <?php if ($user_type_info['is_has_email']) { ?>
                  <td nowrap><?= $user['email'] ?></td>
                <?php } ?>
                <?php if ($user_type_info['is_tel']) { ?>
                  <td nowrap><?= $user['tel_no'] ? '+' . $user['tel_country_code'] . ' ' . $user['tel_no'] : ''; ?></td>
                <?php } ?>
                <td nowrap class="thin-cell"><?= W_Main::formatDate($user['insert_date_time'], 'd/m/Y, H:i'); ?></td>
              </tr>
            <?php
            }
          } else {
            $colsapn = 6;
            if ($user_type_info['is_has_email']) {
              $colsapn++;
            }
            if ($user_type_info['is_tel']) {
              $colsapn++;
            }
            ?>
            <tr>
              <td colspan="<?= $colsapn ?>" class="font-14px text-center text-secondary">Don't have data</td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <table class="template_multiple_user_event d-none">
    <tbody>
      <tr>
        <td class="py-5px">
          <?= TiwForm::normal('select-title', '', ['name' => 'title[]', 'placeholder' => 'Select', 'required' => true, 'class' => 'mb-0'], ['is_search' => true]) ?>
        </td>
        <td class="py-5px">
          <?php TiwForm::normal('text', '', ['name' => 'name[]', 'placeholder' => 'First Name', 'required' => true, 'class' => 'mb-0']); ?>
        </td>
        <td class="py-5px">
          <?php TiwForm::normal('text', '', ['name' => 'surname[]', 'placeholder' => 'Last Name', 'class' => 'mb-0', 'required' => true]); ?>
        </td>
        <td class="py-5px">
          <?= TiwForm::normal('text', '', ['name' => 'username[]', 'placeholder' => 'Enter', 'required' => true, 'class' => 'mb-0 check_username_event']); ?>
          <?= messageTemplate('msg_username_event'); ?>
        </td>
        <td class="py-5px">
          <?= TiwForm::normal('password', '', ['name' => 'password[]', 'placeholder' => 'Password', 'required' => true, 'class' => 'mb-0']); ?>
        </td>
        <?php if ($user_type_info['is_has_email']) { ?>
          <td class="py-5px">
            <?= TiwForm::normal('email', '', ['name' => 'email[]', 'placeholder' => 'Enter', 'class' => 'mb-0 check_email_event', 'required' => true]) ?>
            <?= messageTemplate('msg_email_event'); ?>
          </td>
        <?php } ?>
        <?php if ($user_type_info['is_tel']) { ?>
          <td class="py-5px">
            <?= TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no[]', 'placeholder' => 'Enter'], ['country_code' => 'tel_country_code[]', 'country_name' => 'country_name[]', 'main_class' => 'mb-0']) ?>
          </td>
        <?php } ?>
        <td class="py-5px">
          <?php
          $key = [
            'value' => 'id',
            'name' => 'name',
          ];
          $template_options = TiwForm::generateSelectData($templates, $key, ['is_search' => true]);

          TiwForm::normal('select', '', ['name' => 'template_id[]', 'placeholder' => 'Select', 'class' => 'mb-0'], $template_options) ?>
        </td>
        <td class="thin-cell p-5px">
          <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn_delete_user_event'], ['text' => '', 'type' => 'delete']); ?>
        </td>
      </tr>
    </tbody>
  </table>
  <?php
  include_once '../../structure/layout/footer.php';
  W_Structure::loadFooter('../../');
  ?>
  <script>
    $(function() {
      var __check_username_format = true;
      var __check_username_already = false;
      var __check_email = true;

      $(document).on('change', '.check_username_event', async function(e) {
        var scope = $(this).parents('td');
        var username = $(this).val();
        if (username) {
          //check username format
          if (!username.match(/^([a-z0-9])+$/i)) {
            __check_username_format = await false;
            checkAllowSaveEvent(scope);
            return false;
          } else {
            __check_username_format = await true;
            checkAllowSaveEvent(scope);
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
            checkAllowSaveEvent(scope);
          });
        } else {
          $('.msg_username_event').hide();
          $('.btn_submit_add_user_event').attr('disabled', true);
        }
      });

      $(document).on('keyup change', '.check_email_event', async function(e) {
        var scope = $(this).parents('td');
        var email = $(this).val();
        if (email) {
          //check username format
          if (!email.match(/^([a-zA-Z0-9@.])+$/i) || email.indexOf(".") <= 0) {
            __check_email = await false;

            scope.find('.msg_email_event span').html('Sorry. only letters (a-z), number (0-9), and periods (.) are allowed.');
            scope.find('.msg_email_event').show();
            $('.btn_submit_add_user_event').attr('disabled', true);

            checkAllowSaveEvent(scope);
          } else {
            __check_email = await true;
            checkAllowSaveEvent(scope);
          }
        } else {
          $('.msg_email_event').hide();
          $('.btn_submit_add_user_event').attr('disabled', true);
        }
      });

      function checkAllowSaveEvent(scope) {
        if (!__check_username_format) {
          scope.find('.msg_username_event span').html("Sorry, letter don't allow free space.");
          scope.find('.msg_username_event').show();
          $('.btn_submit_add_user_event').attr('disabled', true);
          return false;
        } else if (!__check_username_already && scope.find('.check_username_event').val()) {
          scope.find('.msg_username_event span').html('This username is already in use.');
          scope.find('.msg_username_event').show();
          $('.btn_submit_add_user_event').attr('disabled', true);
          return false;
        } else if (!__check_email) {
          return false;
        }

        scope.find('.msg_username_event').hide();
        scope.find('.msg_email_event').hide();
        $('.btn_submit_add_user_event').attr('disabled', false);
      }

      $(document).on('click', '.btn_add_user_event', function(e) {
        var template = $('.template_multiple_user_event tbody').html();
        $('.multiple_user_area_event').append(template);

        var intlt = $('.telephone-form-group .form-flags');
        var $intlt = intlt.intlTelInput({
          preferredCountries: ['th'],
          separateDialCode: true
        });
      });

      $(document).on('click', '.btn_delete_user_event', function(e) {
        var scope = $(this).parents('tr');
        scope.remove();
      });

    });
  </script>
</body>

</html>
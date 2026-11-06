<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_main_management'];
require_once '../../_framework/import.php';
W_Structure::loadModules(['datatables', 'input-pattern', 'itnav', 'itlanguage']);
$admin_owner_id = W_Main::cookie('admin_owner_id');

$managed_user_type_id = Module::getCurrentSetting($_GET['c'], 'User', 'managed_user_type_id');

if (!$managed_user_type_id) {
  W_Main::redirect('../../module_main/landing/?action=error');
}

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$options = [
  'view' => true
];
$user_data    = User::getUserByID($user_id, $options);
$top_nav      = isset($_GET['top_nav']) ? $_GET['top_nav'] : 1;
$address_user = User::getContactInfoByUserID($user_id, $options);

$user_crm = Module::get($_GET['c'], 'user_crm');

$data_nav = [
  'class' => '',
  'list'  => [
    [
      'id'    => 1,
      'name'  => Itlanguage::translate('Basic information'),
      'icon'  => '',
      'count' => ''
    ],
    [
      'id'    => 2,
      'name'  => Itlanguage::translate('Permission'),
      'icon'  => '',
      'count' => ''
    ],
    [
      'id'    => 3,
      'name'  => Itlanguage::translate('Contact'),
      'icon'  => '',
      'count' => ''
    ],
    [
      'id'    => 4,
      'name'  => Itlanguage::translate('Address'),
      'icon'  => '',
      'count' => ''
    ],
    [
      'id'    => 5,
      'name'  => Itlanguage::translate('Order History'),
      'icon'  => '',
      'count' => ''
    ],
    [
      'id'    => 6,
      'name'  => Itlanguage::translate('Bank Account'),
      'icon'  => '',
      'count' => ''
    ]
  ]
];

$link           = '?c=' . $_GET['c'] . '&user_id=' . $user_id;
$param_name     = 'top_nav';
$param_selected = isset($_GET[$param_name]) ? $_GET[$param_name] : 1;

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

  $m_setting        = Module::getCurrentSetting($_GET['c'], 'User');
  $text_manage_user = isset($m_setting['managed_user_type']) ? $m_setting['managed_user_type'] : 'User';
  if (isset($m_setting['is_viewas'])) {
    adminView('user_admin_view.php?c=' . $_GET['c']);
  }
  ?>

  <div class="row">
    <div class="col-lg-4 pr-lg-5px mb-10px">
      <div class="row">
        <div class="col-12">
          <div class="flex-between-center">
            <div class="topic">
              <h5><?= Itlanguage::translate('Small User'); ?></h5>
              <p class="mb-0"><?= Itlanguage::translate('Manage your users'); ?>
              <p>
            </div>
            <a href="" type="button" class="btn btn-blue toggle-modal" data-target="#operator-add-modal">
              + <?= Itlanguage::translate('Add User'); ?>
            </a>
          </div>
          <div class="w-loves-card p-0 mt-10px">
            <div id="small_user" class="container-pagination" <?= Homepagify::createHomepagify('small_user', '?c=' . $_GET['c'] . '&user_id=' . $user_id) ?>>
              <div class="table-responsive">
                <table class="table table-sort">
                  <thead>
                    <tr>
                      <th nowrap><?= Itlanguage::translate('Name'); ?></th>
                      <th nowrap><?= Itlanguage::translate('Username'); ?></th>
                      <th nowrap class="thin-cell"></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if ($user_data) {
    ?>
      <div class="col-lg-8 pl-lg-5px">
        <div class="top-nav">
          <?= Itnav::dinner($data_nav, $link, $param_name, $param_selected); ?>
          <div class="dropdown top-nav-dropdown">
            <button class="btn btn-primary" type="button" data-toggle="dropdown">
              <?= file_get_contents('assets/image/icon/icon-dot-dropdown.svg'); ?>
            </button>
            <div class="dropdown-menu">
              <a href="" type="button" class="dropdown-item">
                <?= file_get_contents('assets/image/icon/icon-bill.svg'); ?>
                <span class="ml-2">Create bill</span>
              </a>
              <a href="" type="button" class="dropdown-item">
                <?= file_get_contents('assets/image/icon/icon-chat.svg'); ?>
                <span class="ml-2">Chat</span>
              </a>
              <button type="button" class="dropdown-item" <?= Tiwdal::register('block_modal', $user_data); ?>>
                <?= file_get_contents('assets/image/icon/icon-block-user.svg'); ?>
                <span class="ml-2"><?= ($user_data['is_block'] == 0) ? Itlanguage::translate('Block') : Itlanguage::translate('Unblock'); ?></span>
              </button>
              <button type="button" class="dropdown-item" <?= Tiwdal::register('delete_modal', $user_data); ?>>
                <?= file_get_contents('assets/image/icon/icon-delete-user.svg'); ?>
                <span class="ml-2"><?= Itlanguage::translate('Delete'); ?></span>
              </button>
            </div>
          </div>
        </div>
        <div class="container-detail pt-20px">
          <?php
          if ($top_nav == 6) {
            include 'views/manage_user_bank.php';
          } else if ($top_nav == 5) {
            include 'views/manage_user_order_history.php';
          } else if ($top_nav == 4) {
            include 'views/manage_user_address.php';
          } else if ($top_nav == 3) {
            include 'views/manage_user_contact.php';
          } else if ($top_nav == 2) {
            include 'views/manage_user_permission.php';
          } else {
            include 'views/manage_user_info.php';
          }
          ?>
        </div>
      </div>
    <?php } else { ?>
      <div class="col-lg-8 pl-lg-5px">
        <div class="w-loves-card px-0 py-0">
          <div class="w-loves-card-header px-15px mb-10px set-header-bg">
            <div class="d-flex align-items-center">
              <div class="d-flex align-items-center"><?= file_get_contents('assets/image/icon/lightbulb.svg') ?></div>
              <span class="font-16px font-weight-bold ml-5px">View user information</span>
            </div>
            <p class="font-13px text-secondary">Please select a system user You want to view information form the list on the left to show the details of the user.</p>
          </div>
          <div class="bg-manage-user calc-135px">
            <?= file_get_contents('assets/image/icon/bg-user.svg') ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <div class="modal fade" id="operator-add-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <form method="post" class="aww-regex-form">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?= Itlanguage::translate('Add User'); ?></h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6 border-right-card">
                <div class="row">
                  <div class="col-12">
                    <h5 class="mb-5px font-16px"><?= Itlanguage::translate('Account information'); ?></h5>
                  </div>
                  <div class="col-12 form-group mb-0">
                    <label class="mb-0"><?= Itlanguage::translate('Username'); ?></label>
                    <?php TiwForm::normal('text', '', ['name' => 'username', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required', 'class' => 'check_username']); ?>
                  </div>
                  <div class="col-12 form-group aww-regex-box">
                    <label class="mb-0"><?= Itlanguage::translate('Password'); ?></label>
                    <?php TiwForm::normal('password', '', ['name' => 'password', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required', 'class' => 'aww-regex-input pass']); ?>
                    <label class="mb-0"><?= Itlanguage::translate('Re-enter password'); ?></label>
                    <?php TiwForm::normal('password', '', ['name' => 're-password', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required', 'class' => 'aww-regex-input re_pass']); ?>
                    <p class="text-danger mb-5px hide-duplicate hidden"><?= Itlanguage::translate('This username is already taken!'); ?></p>
                    <p class="text-danger mb-5px check_pass_text hidden"><?= Itlanguage::translate('Please make sure your passwords match.'); ?></p>
                    <label class="mb-0"><?= Itlanguage::translate('Create a password that'); ?> : </label>
                    <div class="aww-regex-warning-box error">
                      <div class="aww-regex-warning-icon">
                        <i class="far fa-check-circle icon-success"></i>
                        <i class="far fa-times-circle icon-error"></i>
                      </div>
                      <p class="aww-regex-warning-text" data-pattern="[\w]{8,}"><?= Itlanguage::translate('contains at least 8 characters'); ?></p>
                    </div>
                    <div class="aww-regex-warning-box error">
                      <div class="aww-regex-warning-icon">
                        <i class="far fa-check-circle icon-success"></i>
                        <i class="far fa-times-circle icon-error"></i>
                      </div>
                      <p class="aww-regex-warning-text" data-pattern="[A-Za-z]+"><?= Itlanguage::translate('contains both lower (a-z) and upper case letters (A-Z)'); ?></p>
                    </div>
                    <div class="aww-regex-warning-box error">
                      <div class="aww-regex-warning-icon">
                        <i class="far fa-check-circle icon-success"></i>
                        <i class="far fa-times-circle icon-error"></i>
                      </div>
                      <p class="aww-regex-warning-text" data-pattern="[0-9+]"><?= Itlanguage::translate('contains at least one number (0-9) or a symbol'); ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-12">
                    <h5 class="mb-5px font-16px"><?= Itlanguage::translate('General Information'); ?></h5>
                  </div>
                  <div class="col-12">
                    <label class="mb-0 text-secondary"><?= Itlanguage::translate('Title  / Name<span class="text-danger">*</span> / Surname'); ?></label>
                  </div>
                  <div class="col-3 pr-5px">
                    <?php
                    $options = [
                      'list' => [
                        [
                          'value'    => '',
                          'name'     => 'Select',
                          'disabled' => true,
                          'selected' => true
                        ],
                        [
                          'value' => 'Mr.',
                          'name'  => 'Mr.'
                        ],
                        [
                          'value' => 'Miss',
                          'name'  => 'Miss'
                        ],
                        [
                          'value' => 'Mrs.',
                          'name'  => 'Mrs.'
                        ]
                      ]
                    ];
                    TiwForm::normal('select', 2, ['name' => 'title'], $options);
                    ?>
                  </div>
                  <div class="col px-5px">
                    <?php TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Name', 'required' => '']); ?>
                  </div>
                  <div class="col pl-5px">
                    <?php TiwForm::normal('text', '', ['name' => 'surname', 'placeholder' => 'Surname']); ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <label class="mb-0 text-secondary"><?= Itlanguage::translate('Telephone No.'); ?> <span class="text-secondary font-12px"><?= Itlanguage::translate('(optional)'); ?></span></label>
                  </div>
                  <div class="col-8">
                    <?php TiwForm::normal('tel-flag', '+66', ['name' => 'tel', 'placeholder' => 'Telephone No.']); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <input type="hidden" name="submit_add_user">
            <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><?= Itlanguage::translate('Cancel'); ?></button>
            <button type="submit" class="btn btn-modal-confirm check_pass_btn" disabled><?= Itlanguage::translate('Save'); ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php
  if ($user_data) {
  ?>
    <?php Tiwdal::startModal('block_modal'); ?>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <form method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12 form-group text-center">
            <h5 class="font-16px mb-5px mt-20px"><?= ($user_data['is_block'] == 0) ? Itlanguage::translate('Block') : Itlanguage::translate('Unblock') ?><?= Itlanguage::translate('this username in this program'); ?> </h5>
            <p class="text-secondary"><?= Itlanguage::translate('Are you sure?'); ?> <span class="text-danger">“<?= ($user_data['is_block'] == 0) ? Itlanguage::translate('Block') : Itlanguage::translate('Unblock') ?><?= Itlanguage::translate('this username in this program'); ?>”</span>
              <?= ($user_data['is_block'] == 0) ? Itlanguage::translate('Are you sure to Block?') : Itlanguage::translate('Are you sure to Unblock?') ?>

              <?php if ($user_data['is_block'] == 0) {
                echo '<br>' . Itlanguage::translate("This user account can't log in.");
              } ?></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <input type="hidden" name="{is_block}">
        <input type="hidden" name="submit_block_user">
        <button type="button" class="btn btn-modal-cancel px-1" data-bs-dismiss="modal"><?= Itlanguage::translate('Maybe'); ?></button>
        <button type="submit" class="btn btn-danger width-100px">
          <p class="mb-0 px-25px"><?= Itlanguage::translate('Yes!! i’m Sure'); ?></p>
        </button>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>
  <?php
  }
  ?>
  <?php Tiwdal::startModal('delete_modal'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <form method="post">
    <div class="modal-body">
      <div class="row">
        <div class="col-12 form-group text-center">
          <h5 class="font-16px mb-5px mt-20px"><?= Itlanguage::translate('Delete User account'); ?></h5>
          <p class="text-secondary"><?= Itlanguage::translate('Are you sure to delete “This User account from system'); ?></p>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <input type="hidden" name="submit_delete_user">
      <button type="button" class="btn btn-modal-cancel px-1" data-bs-dismiss="modal"><?= Itlanguage::translate('Maybe'); ?></button>
      <button type="submit" class="btn btn-danger width-100px">
        <p class="mb-0 px-25px"><?= Itlanguage::translate('Yes!! i’m Sure'); ?></p>
      </button>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <script>
    $(document).ready(function() {

      function sendPermission(user_id, permission_code, value) {
        $.post('<?= F_BRIDGE_API_URL; ?>', {
          params: [user_id, permission_code, value],
          class: 'User_Permission',
          function: 'trigger',
          api_key: '<?= F_BRIDGE_API_KEY; ?>',
        }).done(function(response) {
          w.notification('success', 'Success!');
          location.reload();
        });
      }

      $(document).on('change', '.checkbox-post', function() {
        var user_id = $(this).find('input').data('user_id');
        var permission_code = $(this).find('input').data('permission_code');
        var value = $(this).find('input').data('value');
        var scope = $(this).parents('.permission-wrap');
        var scope_main = $(this).parents('.permission-body');
        var scope_sub = $(this).parents('.scope-group');

        var per_lv1 = scope.find('.per_lv1');
        var per_lv2_main = scope_main.find('.per_lv2 input');
        var per_lv2_sub = scope_sub.find('.per_lv2');
        var per_lv3_main = scope.find('.per_lv3');
        var per_lv3_sub = scope_sub.find('.per_lv3');
        var check_count2 = 1;
        var check_count3 = 1;

        var check_count = per_lv3_main.filter(':checked').length;
        if (check_count == 0 && per_lv2_main.length > 0) {
          var permission_code = per_lv1.find('input').data('permission_code');
          sendPermission(user_id, permission_code, value);
        } else {
          if (per_lv3_sub.length > 0) {
            check_count3 = per_lv3_sub.filter(':checked').length;
            if (check_count3 == 0) {
              var permission_code = per_lv2_sub.find('input').data('permission_code');
              sendPermission(user_id, permission_code, value);
            }
          }

          if (per_lv2_main.length > 0) {
            check_count2 = per_lv2_main.filter(':checked').length;
            if (check_count2 == 0) {
              var permission_code = per_lv1.find('input').data('permission_code');
              sendPermission(user_id, permission_code, value);
            }
          }

          sendPermission(user_id, permission_code, value);
        }


      });

      $(".toggle-permission-button").click(function(e) {
        e.preventDefault();
        let scope = $(this).parents(".permission-wrap");
        scope.toggleClass("hide");
      });

      $(document).on('keyup', '.pass, .re_pass', function(e) {
        var pass = $('.pass').val();
        var re_pass = $('.re_pass').val();

        if (pass == re_pass) {
          $('.check_pass_text').addClass('hidden');
          $('.check_pass_btn').prop('disabled', false);
        } else {
          $('.check_pass_text').removeClass('hidden');
          $('.check_pass_btn').prop('disabled', true);
        }
      });

      $(document).on('keyup', '.check_username', function() {
        var username = $(this).val();
        var url = 'ajax/ajax_check_username.php';
        var params = {
          'username': username,
        };
        $.post(url, params).done(function(data) {
          if (data == 0) {
            $('.hide-duplicate').addClass('hidden');
            $('.check_pass_btn').attr('disabled', false);
          } else {
            $('.hide-duplicate').removeClass('hidden');
            $('.check_pass_btn').attr('disabled', true);
          }
        })
      });

    });
  </script>

  <?php
  W_Main::loadAsset('assets/plugin/jquery.Thailand.js/dependencies/JQL.min.js');
  W_Main::loadAsset('assets/plugin/jquery.Thailand.js/dependencies/typeahead.bundle.js');
  W_Main::loadAsset('assets/plugin/jquery.Thailand.js/dists/jquery.Thailand.min.js');
  include_once '../../structure/layout/footer.php';
  W_Structure::loadFooter('../../');
  ?>

</body>

</html>
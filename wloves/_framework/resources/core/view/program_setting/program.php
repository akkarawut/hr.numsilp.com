<?php
// Config Itnav
$link = 'program_setting.php?type=' . $setting_type . '&module_code=' . $module_code_selected;
$nav_type_selected  = isset($_GET['nav_type']) ? $_GET['nav_type'] : '1';
$data_nav = [
  'class' => '',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'Program Basic Detail',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 2,
      'name'  => 'Manage User Type',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 3,
      'name'  => 'Alert  Lists',
      'icon'   => '',
      'count'  => '',
    ]
  ]
];

$result = [];
if (isset($_POST['submit_add_user_type'])) {
  $data = [
    'name' => $_POST['name'],
    'description' => $_POST['description'],
    'def_user_type_id' => $_POST['def_user_type_id']
  ];
  $result =  User_type::addNewUserType($data);
  if ($result['response_status'] == true) {
    W_Main::notification($result['response_message'], 'success');
    W_Main::redirect('program_user_type_detail.php?id=' . $result['response_data']['insert_id']);
  } else {
    W_Main::notification($result['response_message'], 'error');
    W_Main::redirect();
  }
} else if (isset($_POST['submit_delete_user_type'])) {
  $result =  User_type::deleteUserType($_POST['id']);
}

if ($result && $_POST) {
  if ($result['response_status'] == true) {
    W_Main::notification($result['response_message'], 'success');
    W_Main::redirect();
  } else {
    W_Main::notification($result['response_message'], 'error');
    W_Main::redirect();
  }
}

$data_user_type = User_type::selectUserType();
$api_updateUserType = [
  'api' => 'User_type::updateUserType',
  'params' => [
    'id' => '',
    'data' => [],
  ]
];
?>
<div class="col-lg-10">
  <div class="editable-card">
    <div class="editable-card-header">
      <?php Itnav::dinner($data_nav, $link, 'nav_type', $nav_type_selected); ?>
    </div>

    <div class="editable-card-body">
      <div class="row">
        <div class="col-12 mb-15px">
          <?php if ($nav_type_selected == '1') { ?>
            <div class="w-love-master-form-container">
              <div class="master-form-header-wrap">
                <div class="title-group">
                  <h3>PROGRAM BASIC DETAIL</h3>
                  <p>Set Program details and theme.</p>
                </div>
              </div>
              <div class="master-form-body-wrap">
                <div class="master-form-items row">
                  <div class="col-md-3 align-self-center">
                    <label class="master-form-title">Program name</label>
                  </div>
                  <div class="col-md-9">
                    <?php
                    $api = [
                      'api' => 'WLoves::setProgramSetup',
                      'params' => [
                        'data' => [
                          'program_name' => '{program_name}',
                          'customer' => $list_info['customer'],
                          'color_theme' => $list_info['color_theme'],
                          'start_date' => $list_info['start_date'],
                        ]
                      ]
                    ];

                    TiwForm::liveForm('text', 'program_name', $list_info['program_name'], $api);
                    ?>
                  </div>
                </div>
                <div class="master-form-items row">
                  <div class="col-md-3 align-self-center">
                    <label class="master-form-title">Program Owner</label>
                  </div>
                  <div class="col-md-9">
                    <?php
                    $api = [
                      'api' => 'WLoves::setProgramSetup',
                      'params' => [
                        'data' => [
                          'customer' => '{customer}',
                          'program_name' => $list_info['program_name'],
                          'color_theme' => $list_info['color_theme'],
                          'start_date' => $list_info['start_date'],
                        ]
                      ]
                    ];

                    TiwForm::liveForm('text', 'customer', $list_info['customer'], $api);
                    ?>
                  </div>
                </div>
                <div class="master-form-items row">
                  <div class="col-md-3">
                    <label class="master-form-title">Website Logo</label>
                  </div>
                  <div class="col-md-9">
                    <p class="master-description">Click image for upload (Recommend file .png size 60X60 pixel.)</p>
                    <form method="post" enctype="multipart/form-data">
                      <?php
                      $has_image = (strpos($list_info['logo_image'], 'file') !== false) ? 'has-img' : '';
                      ?>
                      <div class="avatar-box">
                        <div class="avatar-preview-box img-1by1 holder <?= $has_image; ?>">
                          <?php
                          if (strpos($list_info['logo_image'], 'file') !== false) {
                            echo '<img class="avatar-preview-img" src="' . $list_info['logo_image'] . '">';
                          }
                          ?>
                        </div>
                        <div class="avatar-trigger">
                          <div class="upload-btn">
                            <?= W_Structure::getfile('_framework/structure/image/icon/general/camera.svg'); ?>
                          </div>
                        </div>
                        <input type="file" name="logo_image" class="hidden img-upload-file" accept="image/x-png,image/jpeg">
                        <input type="hidden" name="submit_upload_logo_image">
                      </div>
                    </form>
                  </div>
                </div>
                <div class="master-form-items row">
                  <div class="col-md-3">
                    <label class="master-form-title">Responsive Logo</label>
                  </div>
                  <div class="col-md-9">
                    <p class="master-description">Click image for upload (Recommend file .png 250X60 pixel.)</p>
                    <form method="post" enctype="multipart/form-data">
                      <?php
                      $has_image = (strpos($list_info['mobile_logo_image'], 'file') !== false) ? 'has-img' : '';
                      ?>
                      <div class="avatar-box">
                        <div class="avatar-preview-box img-1by1 holder <?= $has_image; ?>">
                          <?php
                          if (strpos($list_info['mobile_logo_image'], 'file') !== false) {
                            echo '<img class="avatar-preview-img" src="' . $list_info['mobile_logo_image'] . '">';
                          }
                          ?>
                        </div>
                        <div class="avatar-trigger">
                          <div class="upload-btn">
                            <?= W_Structure::getfile('_framework/structure/image/icon/general/camera.svg'); ?>
                          </div>
                        </div>
                        <input type="file" name="mobile_logo_image" class="hidden img-upload-file" accept="image/x-png,image/jpeg">
                        <input type="hidden" name="submit_upload_mobile_logo_image">
                      </div>
                    </form>
                  </div>
                </div>
                <div class="master-form-items row">
                  <div class="col-md-3 align-self-center">
                    <label class="master-form-title">Highlight Color</label>
                  </div>
                  <div class="col-md-9">
                    <div class="master-form-content-wrap">
                      <div class="master-form-tex-group d-flex align-items-center">
                        <div class="theme-color-icon" style="background-color: <?= isset($list_info['color_theme']) ? $list_info['color_theme'] : '-'; ?>;"></div>
                        <span class="master-description ml-2 mb-0">Highlight Color used in Selected Menu and Button.</span>
                      </div>
                      <div class="master-form-edit-box">
                        <button type="button" class="btn btn-icon-table toggle-modal" data-target="#theme-color-info">
                          <?= W_Structure::getfile('_framework/structure/image/icon/general/edit.svg'); ?>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                /* Coming Soon!
                Language 
              <div class="master-form-items row">
                <div class="col-md-3 align-self-center">
                  <label class="master-form-title">Default Language</label>
                </div>
                <div class="col-md-9">
                  <div class="master-form-content-wrap">
                    <div class="master-form-tex-group align-self-center">
                      <div class="d-flex justify-content-between align-items-center">
                        <img src="structure/image/flag/en.png" class="mr-1">
                        <span>EN</span>
                      </div>
                    </div>
                    <div class="master-form-edit-box">
                      <button type="button" class="btn btn-icon-table toggle-modal" data-target="#">
                        <?= W_Structure::getfile('_framework/structure/image/icon/general/icon-right-arrow.svg'); ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="master-form-items row">
                <div class="col-md-3 align-self-center">
                  <label class="master-form-title">Secondary Language</label>
                </div>
                <div class="col-md-9">
                  <div class="master-form-content-wrap">
                    <div class="master-form-tex-group align-items-center">
                      <p class="master-description mb-0">When you choose secondary language. User can change language in some supported module.</p>
                    </div>
                    <div class="master-form-edit-box">
                      <button type="button" class="btn btn-icon-table toggle-modal" data-target="#">
                        <?= W_Structure::getfile('_framework/structure/image/icon/general/icon-right-arrow.svg'); ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              */ ?>
                <div class="master-form-items row">
                  <div class="col-md-3 align-self-center">
                    <label class="master-form-title">Start Date</label>
                  </div>
                  <div class="col-md-9">
                    <?php
                    $api = [
                      'api' => 'WLoves::setProgramSetup',
                      'params' => [
                        'data' => [
                          'start_date' => '{start_date}',
                          'program_name' => $list_info['program_name'],
                          'customer' => $list_info['customer'],
                          'color_theme' => $list_info['color_theme'],
                        ]
                      ]
                    ];

                    TiwForm::liveForm('text', 'start_date', $list_info['start_date'], $api);
                    ?>
                  </div>
                </div>
              </div>
            </div>
          <?php } else if ($nav_type_selected == '2') { ?>
            <div class="w-love-master-form-container">
              <div class="master-form-header-wrap">
                <div class="title-group">
                  <h3>User Type</h3>
                  <p>Manage user type on this program and Set user type details.</p>
                </div>
                <button class="btn btn-add-module w-auto" <?php Tiwdal::register('add-user-type'); ?>>
                  <?= W_Structure::getfile('_framework/structure/image/icon/general/icon-user-type-add.svg'); ?>
                  <p class="ml-2">ADD MORE USER TYPE</p>
                </button>
              </div>
              <div class="master-form-body-wrap px-15px user-tpye">
                <?php foreach ($data_user_type as $data) { ?>
                  <div class="wloves-box topic-container pb-0 mb-3">
                    <div class="topic-header-wrap mb-10px px-15px d-flex justify-content-between">
                      <div>
                        <h3 class="header-title"><?= $data['name']; ?></h3>
                        <p class="header-description mb-10px"><?= $data['description']; ?></p>
                      </div>
                      <div class="edit-dropdown">
                        <div class="dropdown top-nav-dropdown">
                          <button class="btn btn-primary" type="button" data-toggle="dropdown">
                            <?= W_Structure::getfile('_framework/structure/image/icon/general/3dots.svg'); ?>
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="program_user_type_detail.php?id=<?= $data['id']; ?>">
                              <div class="ml-2">
                                <?= W_Structure::getfile('_framework/structure/image/icon/general/icon-user-type-setting.svg'); ?>
                              </div>
                              <span class="ml-2">Settings</span>
                            </a>
                            <div class="border-top color-line dark mx-10px"></div>
                            <button type="button" class="dropdown-item" <?php Tiwdal::register('delete-user-type', $data); ?>>
                              <span class="ml-2">Delete User Type</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="deadboard p-15px">
                      <div class="list">
                        <div class="icon"><?= W_Structure::getfile('_framework/structure/image/icon/general/icon-user-type-people.svg'); ?></div>
                        <div class="detail">
                          <div class="percentage <?= ($data['basic_info_percent']) ? 'active' : ''; ?>"><?= $data['basic_info_percent']; ?>%</div>
                          <div class="show-view">
                            <?php
                            foreach ($data['basic_info_progress'] as $list) {
                              $active = ($list) ? 'active' : '';
                              echo '<div class="' . $active . '"></div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <div class="list">
                        <div class="icon"><?= W_Structure::getfile('_framework/structure/image/icon/general/icon-user-type-info.svg'); ?></div>
                        <div class="detail">
                          <div class="percentage <?= ($data['user_info_percent']) ? 'active' : ''; ?>"><?= $data['user_info_percent']; ?>%</div>
                          <div class="show-view">
                            <?php
                            foreach ($data['user_info_progress'] as $list) {
                              $active = ($list) ? 'active' : '';
                              echo '<div class="' . $active . '"></div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <div class="list">
                        <div class="icon"><?= W_Structure::getfile('_framework/structure/image/icon/general/icon-user-type-img.svg'); ?></div>
                        <div class="detail">
                          <div class="percentage <?= ($data['document_percent']) ? 'active' : ''; ?>"><?= $data['document_percent']; ?>%</div>
                          <div class="show-view">
                            <?php
                            foreach ($data['document_progress'] as $list) {
                              $active = ($list) ? 'active' : '';
                              echo '<div class="' . $active . '"></div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="function">
                      <div class="check-show">
                        <div class="title">function</div>
                        <div class="col">
                          <div class="detail row">
                            <div class="list col-lg-3">
                              <?php
                              $api_updateUserType['params']['data'] = ['is_format_user_code' => '{is_format_user_code}'];
                              $api_updateUserType['params']['id'] = $data['id'];
                              TiwForm::liveForm('checkbox', 'is_format_user_code', $data['is_format_user_code'], $api_updateUserType, ['type' => 3, 'class' => 'white']);
                              ?>
                              <label for="checkbox_1">ID Format</label>
                            </div>
                            <div class="list col-lg-3">
                              <?php
                              $api_updateUserType['params']['data'] = ['is_allow_login' => '{is_allow_login}'];
                              $api_updateUserType['params']['id'] = $data['id'];
                              TiwForm::liveForm('checkbox', 'is_allow_login', $data['is_allow_login'], $api_updateUserType, ['type' => 3, 'class' => 'white']);
                              ?>
                              <label for="checkbox_2">Allow Log in</label>
                            </div>
                            <div class="list col-lg-3">
                              <?php
                              $api_updateUserType['params']['data'] = ['is_use_pin_code' => '{is_use_pin_code}'];
                              $api_updateUserType['params']['id'] = $data['id'];
                              TiwForm::liveForm('checkbox', 'is_use_pin_code', $data['is_use_pin_code'], $api_updateUserType, ['type' => 3, 'class' => 'white']);
                              ?>
                              <label for="checkbox_3">PIN Code</label>
                            </div>
                            <div class="list col-lg-3">
                              <?php
                              $api_updateUserType['params']['data'] = ['is_has_leader' => '{is_has_leader}'];
                              $api_updateUserType['params']['id'] = $data['id'];
                              TiwForm::liveForm('checkbox', 'is_has_leader', $data['is_has_leader'], $api_updateUserType, ['type' => 3, 'class' => 'white']);
                              ?>
                              <label for="checkbox_4">Leader, Subordinate</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="line"></div>
                      <div class="show-hide">
                        <?= W_Structure::getfile('_framework/structure/image/icon/general/icon-user-type-arrow-show.svg'); ?>
                        <span class="text">Show Details</span>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          <?php } else if ($nav_type_selected == '3') { ?>
            <div class="w-love-master-form-container">
              <div class="master-form-header-wrap">
                <div class="title-group">
                  <h3>ALERT LISTS</h3>
                  <p>Please check to complete all list for perfect program.</p>
                </div>
              </div>
              <div class="master-form-body-wrap">
                <div class="master-form-items row">
                  <div class="col-12 align-self-center">
                    <?php if (!$is_error['is_exists']) {
                      echo W_Structure::getfile('_framework/structure/image/icon/general/alert_false.svg');
                    } else {
                      echo W_Structure::getfile('_framework/structure/image/icon/general/alert_true.svg');
                    } ?>
                    <span class="font-weight-bold text-white ml-10px">หา folder 'system/resource/file' ไม่พบ ระบบจะไม่สามารถอัพ image / file ต่างๆ ขึ้นระบบได้</span>
                  </div>
                </div>

                <div class="master-form-items row">
                  <div class="col-12 align-self-center">
                    <?php if (!$is_error['is_writable']) {
                      echo W_Structure::getfile('_framework/structure/image/icon/general/alert_false.svg');
                    } else {
                      echo W_Structure::getfile('_framework/structure/image/icon/general/alert_true.svg');
                    } ?>
                    <span class="font-weight-bold text-white ml-10px">folder 'system/resource/file' permission ไม่ใช่ 777 ผู้ใช้ทั่วไปจะไม่สามารถอัพ image / file ต่างๆ ขึ้นระบบได้</span>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('add-user-type'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title">ADD USER TYPE</h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <?php
      echo '<div class="col-12 form-group">';
      echo '<label>User Type Name</label>';
      echo TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter']);
      echo '</div>';
      echo '<div class="col-12 form-group">';
      echo '<label>Explanation (Optional)</label>';
      echo TiwForm::normal('textarea', '', ['name' => 'description', 'placeholder' => 'Enter']);
      echo '</div>';

      $options = [
        'list' => [
          [
            'value' => 0,
            'name' => 'No Reference',
          ]
        ]
      ];
      foreach ($data_user_type as $data) {
        array_push($options['list'], ['value' => $data['id'], 'name' => $data['name']]);
      }
      ?>
      <div class="col-12 form-group">
        <label>Reference User Type Function (Optional)</label>
        <?= TiwForm::normal('select', 2, ['name' => 'def_user_type_id'], $options); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="submit_add_user_type" class="btn btn-primary">Confirm</button>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('delete-user-type'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<form method="post">
  <div class="modal-body">
    <div class="center-modal-container">
      <div class="center-modal-title">
        <span class="text-danger">Delete User Type</span>
      </div>
      <div class="center-modal-message mt-2">
        <span class="text-secondary">If you sure to delete User Type</span>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="submit_delete_user_type" class="btn btn-primary">YES!!</button>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<script>
  $(document).ready(function() {
    $('.show-hide').click(function(e) {
      var scope = $(this).parent('.function').find('.check-show');
      if (scope.hasClass('active')) {
        scope.removeClass('active');
        $(this).find('.text').text('Show Details');
        $(this).find('svg').css('transform', 'rotate(180deg)');
      } else {
        scope.addClass('active');
        $(this).find('.text').text('Hide Details');
        $(this).find('svg').css('transform', 'rotate(0deg)');
      }
    });
  });
</script>
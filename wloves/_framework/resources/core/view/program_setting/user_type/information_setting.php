<?php

//checkbox permission and update
function randerCheckbox($options = [])
{
  global $id;

  $is_edit = (isset($options['is_edit']) && $options['is_edit']) ? true : false;
  $checked = (isset($options['checked']) && $options['checked']) ? true : false;
  $key     = (isset($options['key']) && $options['key']) ? $options['key'] : '';
  $checked = (isset($options['checked']) && $options['checked']) ? true : false;

  if ($key == 'full_name') {
    echo '<span class="text-primary">ON</span>';
    return true;
  }

  if ($is_edit) {
    if ($key) {
      $api = [
        'api' => 'User_type::updateUserType',
        'params' => [
          'id' => $id,
          'data' => [
            $key => '{' . $key . '}',
          ]
        ]
      ];
      TiwForm::liveForm('checkbox', $key, $checked, $api, ['is_on_off' => true]);
    }
  } else {
    if ($checked) {
      $checkbox = '<span class="text-primary">ON</span>';
    } else {
      $checkbox = '<span class="text-danger">OFF</span>';
    }
    echo $checkbox;
  }
}

$function_list = [
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('program_login') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('login_userpro') . '</span>',
    'key' => 'is_allow_program_login',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('app_login') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('login_userapp') . '</span>',
    'key' => 'is_allow_app_login',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('web_login') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('login_userweb') . '</span>',
    'key' => 'is_allow_login',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('pass_cond') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('user_passscond') . '</span>',
    'key' => 'is_strict_password',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('action_pin') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('pin_function') . '</span>',
    'key' => 'is_action_pin',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('permission_template') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('permission_user') . '</span>',
    'key' => 'is_permission_template',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('category') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('category_user') . '.</span>',
    'key' => 'is_has_category',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('team') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('team_user') . '</span>',
    'key' => 'is_has_team',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">Tag |</span> 
    <span class="font-14px text-secondary">' . Ty::get('tag_user') . '</span>',
    'key' => 'is_has_tag',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">Code / ID |</span> 
    <span class="font-14px text-secondary">' . Ty::get('code_id') . '</span>',
    'key' => 'is_use_pin_code',
  ],
];

$information_list = [
  [
    'title' => 'Profile',
    'list' => [
      [
        'title' => Ty::get('full_name'),
        'key'   => 'full_name',
      ],
      [
        'title' => Ty::get('preferred_name'),
        'key'   => 'is_has_nick_name',
      ],
      [
        'title' => Ty::get('profile_img'),
        'key'   => 'is_has_profile_image',
      ],
    ]
  ],
  [
    'title' => 'Contact Detail',
    'list' => [
      [
        'title' => Ty::get('address_type'),
        'key'   => 'is_user_address_type',
      ],
      [
        'title' => Ty::get('address'),
        'key'   => 'is_user_address',
      ],
      [
        'title' => Ty::get('tel_no'),
        'key'   => 'is_tel',
      ],
      [
        'title' => Ty::get('email'),
        'key'   => 'is_has_email',
      ],
      [
        'title' => Ty::get('line_id'),
        'key'   => 'is_has_line',
      ],
      [
        'title' => Ty::get('facebook'),
        'key'   => 'is_has_facebook',
      ],
      [
        'title' => Ty::get('wechat'),
        'key'   => 'is_has_wechat',
      ],
      [
        'title' => 'Twiter',
        'key'   => 'is_has_twitter',
      ],
      [
        'title' => Ty::get('instagram'),
        'key'   => 'is_has_instagram',
      ],
    ]
  ],
  [
    'title' => 'Emergency Contact Person',
    'list' => [
      [
        'title' => Ty::get('emergency_contact'),
        'key'   => 'is_has_emergency_contact',
      ],
      [
        'title' => Ty::get('emergency_tel'),
        'key'   => 'is_has_emergency_tel',
      ],
      [
        'title' => Ty::get('relationship'),
        'key'   => 'is_has_relationship',
      ],
    ]
  ],
  [
    'title' => 'Other Detail',
    'list' => [
      [
        'title' => Ty::get('idcard_no'),
        'key'   => 'is_has_id_card_no',
      ],
      [
        'title' => Ty::get('passport_no'),
        'key'   => 'is_has_passport_no',
      ],
      [
        'title' => Ty::get('passport_country'),
        'key'   => 'is_has_passport_country',
      ],
      [
        'title' => Ty::get('nationality'),
        'key'   => 'is_has_nationality',
      ],
      [
        'title' => Ty::get('religion'),
        'key'   => 'is_has_religion',
      ],
      [
        'title' => Ty::get('sex_gender'),
        'key'   => 'is_has_gender',
      ],
      [
        'title' => Ty::get('birthday_age'),
        'key'   => 'is_has_birthdate',
      ],
    ]
  ],
];

$other_page_list = [
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('multi_bank') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('open_bank') . '</span>',
    'key' => 'is_multiple_bank_account',
  ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('multi_address') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('open_address') . '</span>',
    'key' => 'is_multiple_address',
  ],
  // [
  //   'title' => '<span class="font-16px font-SemiBold text-uppercase">Verify Data |</span> 
  //   <span class="font-14px text-secondary">Open page “Verify” and verify function</span>',
  //   'key' => 'is_topbar_verify_data',
  // ],
  // [
  //   'title' => '<span class="font-16px font-SemiBold text-uppercase">WORKING DETAIL |</span> 
  //   <span class="font-14px text-secondary">Open page “Working Detail”</span>',
  //   'key' => 'is_topbar_working_detail',
  // ],
  [
    'title' => '<span class="font-16px font-SemiBold text-uppercase">' . Ty::get('contact_person') . ' |</span> 
    <span class="font-14px text-secondary">' . Ty::get('open_contact') . '</span>',
    'key' => 'is_topbar_contact_person',
  ],
];

//from user_type.php
?>

<div class="row">
  <div class="col-lg-12 px-0">
    <div class="master-form-header-wrap">
      <div class="title-group">
        <div class="d-flex">
          <div>
            <h3 class="text-uppercase font-SemiBold font-16px"><?= Ty::get('user_info_set') ?></h3>
            <p class="font-14px font-Regular"><?= Ty::get('config_user') ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="permission-container px-15px">
      <!-- function -->
      <div class="permission-module-group">
        <div class="permission-module-title">
          <div class="permission-module-name">
            <span class="font-SemiBold"><?= Ty::get('function') ?></span>
          </div>
          <div class="permission-module-action flex-grow-0">
            <div class="permission-hide-module-detail">
              <?= Ty::get('hide', [], ["case" => "ucfirst"]) ?>
              <?= W_Structure::getfile('_framework/structure/image/icon/general/arrow.svg') ?>
            </div>
          </div>
        </div>
        <div class="table-permission">
          <table>
            <thead>
              <tr>
                <th><?= Ty::get('page_function') ?></th>
                <th><?= Ty::get('action') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($function_list as $function) { ?>
                <tr>
                  <td>
                    <?= $function['title'] ?>
                    <?php if ($function['key'] == 'is_use_pin_code') {
                      $configure_code = [
                        'user_code_format_text1' => $user_information['user_code_format_text1'], 'user_code_format_text2' => $user_information['user_code_format_text2'], 'user_code_format_digit3' => $user_information['user_code_format_digit3']
                      ];
                      $configure_code_text = $user_information['user_code_format_text1'] . $user_information['user_code_format_text2'] . sprintf('%0' . $user_information['user_code_format_digit3'] . 'd', '');
                    ?>
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="font-14px text-uppercase text-secondary font-SemiBold text-info">
                          Starter ID: <span class="text-primary"><?= $configure_code_text ?></span>
                        </div>
                        <div class="font-14px font-SemiBold text-primary text-underline cursor-pointer" toggle-edit-modal="#modal_configure_code" data-register="<?= base64_encode(json_encode($configure_code)) ?>"><?= Ty::get('configure_code') ?></div>
                      </div>
                    <?php } ?>
                  </td>
                  <td class="thin-cell">
                    <div class="d-flex justify-content-center font-SemiBold">
                      <?php
                      $checked = isset($user_information[$function['key']]) ? $user_information[$function['key']] : '';
                      randerCheckbox(['key' => $function['key'], 'is_edit' => true, 'checked' => $checked]);
                      ?>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Basic Information -->
      <div class="permission-module-group">
        <div class="permission-module-title">
          <div class="permission-module-name">
            <span class="font-SemiBold"><?= Ty::get('basic_info') ?></span>
          </div>
          <div class="permission-module-action flex-grow-0">
            <div class="permission-hide-module-detail">
              <?= Ty::get('hide', [], ["case" => "ucfirst"]) ?>
              <?= W_Structure::getfile('_framework/structure/image/icon/general/arrow.svg') ?>
            </div>
          </div>
        </div>
        <div class="table-permission">
          <table>
            <thead>
              <tr>
                <th class="w-200px"><?= Ty::get('field') ?></th>
                <th><?= Ty::get('action', [], ["case" => "ucfirst"]) ?></th>
                <th class="w-200px"><?= Ty::get('field') ?></th>
                <th><?= Ty::get('action', [], ["case" => "ucfirst"]) ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($information_list as $list) {
                $data_key = 0;
              ?>
                <tr class="bg-card">
                  <td colspan="4"><?= $list['title'] ?></td>
                </tr>
                <?php
                for ($f = 0; $f < ceil(count($list['list']) / 2); $f++) {
                  $list_count = 0;
                  echo '<tr>';
                  for ($i = $list_count; $i < 2; $i++) {
                    $data = isset($list['list'][$data_key]) ? $list['list'][$data_key] : ['title' => '', 'key' => ''];
                ?>
                    <td><?= $data['title'] ?></td>
                    <td class="thin-cell">
                      <div class="d-flex justify-content-center font-SemiBold">
                        <?php
                        $checked = isset($user_information[$data['key']]) ? $user_information[$data['key']] : '';
                        randerCheckbox(['key' => $data['key'], 'is_edit' => true, 'checked' => $checked]);
                        ?>
                      </div>
                    </td>
                <?php
                    $data_key += 1;
                    $list_count += 1;
                  }
                  echo '</tr>';
                }
                ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Other Page -->
      <div class="permission-module-group">
        <div class="permission-module-title">
          <div class="permission-module-name">
            <span class="font-SemiBold"><?= Ty::get('function', [], ["case" => "ucfirst"]) ?></span>
          </div>
          <div class="permission-module-action flex-grow-0">
            <div class="permission-hide-module-detail">
              <?= Ty::get('hide', [], ["case" => "ucfirst"]) ?>
              <?= W_Structure::getfile('_framework/structure/image/icon/general/arrow.svg') ?>
            </div>
          </div>
        </div>
        <div class="table-permission">
          <table>
            <thead>
              <tr>
                <th><?= Ty::get('page') ?> / <?= Ty::get('function') ?></th>
                <th><?= Ty::get('action') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($other_page_list as $page) { ?>
                <tr>
                  <td><?= $page['title'] ?></td>
                  <td class="thin-cell">
                    <div class="d-flex justify-content-center font-SemiBold">
                      <?php
                      $checked = isset($user_information[$page['key']]) ? $user_information[$page['key']] : '';
                      randerCheckbox(['key' => $page['key'], 'is_edit' => true, 'checked' => $checked]);
                      ?>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('modal_configure_code', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title text-uppercase"><?= Ty::get('config_code_id') ?></h5>
</div>
<form method="POST">
  <div class="modal-body pb-5px">
    <div class="row">
      <div class="col-md-4 font-14px text-secondary pt-10px">
        <?= Ty::get('code_symb') ?>
      </div>
      <div class="col-md-8">
        <div class="row">
          <div class="col-sm-4">
            <?= TiwForm::normal('text', '', ['name' => '{user_code_format_text1}', 'placeholder' => 'Ex. EM, CT', 'required' => true]); ?>
          </div>
          <div class="col-sm-4">
            <?php
            $options = [
              'list' => [
                [
                  'value' => '-',
                  'name' => '-'
                ],
                [
                  'value' => '/',
                  'name' => '/'
                ],
                [
                  'value' => '#',
                  'name' => '#'
                ],
                [
                  'value' => '0',
                  'name' => 'None'
                ],
              ]
            ];
            TiwForm::normal('select', '', ['name' => '{user_code_format_text2}'], $options);
            ?>
          </div>
          <div class="col-sm-4">
            <?= TiwForm::normal('number', '', ['name' => '{user_code_format_digit3}', 'placeholder' => '0']); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get('cancel')]);
    TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px', 'name' => 'submit_format_code'], ['text' => Ty::get('save')]);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>
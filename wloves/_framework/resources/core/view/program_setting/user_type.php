<?php
$data_user_type = User_type::selectUserType();
$_GET['id'] = isset($_GET['id']) ? $_GET['id'] : '';
$id = $_GET['id'];
if ($_POST) {
  if (isset($_POST['submit_add_user_type'])) {
    unset($_POST['submit_add_user_type']);
    $result = User_type::addNewUserType($_POST);
    if ($result['response_status']) {
      $user_type_id = $result['response_data']['insert_id'];
      $menu_code = W_Main::getMenuCode();
      $permission = [];
      foreach ($menu_code as $key => $menu) {
        $permission[$menu] = 0;
      }
      User_type::triggerHiddenPermission($user_type_id, $permission);
      User_type::triggerSettingPermission($user_type_id, $permission);
    }
  } else if (isset($_POST['submit_delete_user_type'])) {
    $result = User_type::deleteUserType($id);

    if ($result['response_status']) {
      $response_redirect = 'program_setting.php?setting=' . $setting_type;
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

$nav_type_param = isset($_GET['nav_type']) ? $_GET['nav_type'] : '1';
$data_nav_top = [
  'param_name' => 'nav_type',
  'class' => '',
  'list' => [
    [
      'id' => 1,
      'name' => Ty::get('hide_permis'),
      'icon' => '',
      'count' => '',
    ],
    [
      'id' => 4,
      'name' => 'Auto Permission',
      'icon' => '',
      'count' => '',
    ],
    [
      'id' => 2,
      'name' => Ty::get('info_setting'),
      'icon' => '',
      'count' => '',
    ],
    [
      'id' => 3,
      'name' => Ty::get('user_detail'),
      'icon' => '',
      'count' => '',
    ],
  ],
];
$link_top = 'program_setting.php?setting=user_type&id=' . $id;

//select Reference User Type
$reference_user_options = [
  'list' => [
    [
      'value' => 0,
      'name' => 'No Reference',
    ]
  ]
];


$data_nav = [
  'param_name'  => 'id',
  'type' => 'mutiple',
  'title_list' => [
    [
      'title' =>  Ty::get('manage_user'),
      'list' => []
    ],
  ],
];
foreach ($data_user_type as $data) {
  array_push($reference_user_options['list'], ['value' => $data['id'], 'name' => $data['name']]);
  array_push($data_nav['title_list'][0]['list'], ['id' => $data['id'], 'name' => $data['name']]);
}

$link = 'program_setting.php?c=&setting=user_type';

?>
<div class="container-fluid h-100">
  <div class="row h-100">
    <div class="col-xl-3 p-0">
      <div class="w-card">
        <div class="w-card-title-bar border-bottom">
          <div class="d-flex flex-wrap align-items-center">
            <h3 class="title"><?= Ty::get('user_type') ?></h3>
          </div>
          <div class="btn-edit-container">
            <button type="button" class="btn-edit save" <?= Tiwdal::register('add_user_type_modal', []); ?>><?= Ty::get('add_newuser') ?></button>
          </div>
        </div>
        <?php Boatnav::wolves($data_nav, $link); ?>
      </div>
    </div>
    <div class="col-xl-9 ">
      <?php
      if ($id) {
        $user_information = User_type::getUserTypeByID($id);
        include 'user_type/detail.php';
      }
      ?>
    </div>
  </div>
</div>


<?php Tiwdal::startModal('add_user_type_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title"><?= Ty::get('add_usertype') ?></h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-3 pt-5px font-14px text-secondary"><?= Ty::get('usertype_name') ?><span class="text-danger">*</span></div>
      <div class="col-sm-9">
        <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => Ty::get('enter')]) ?>
      </div>
      <div class="col-sm-3 pt-5px font-14px text-secondary"><?= Ty::get('description') ?></div>
      <div class="col-sm-9">
        <?= TiwForm::normal('textarea', '', ['name' => 'description', 'placeholder' => Ty::get('enter')]); ?>
      </div>
      <div class="col-sm-3 pt-5px font-14px text-secondary"><?= Ty::get('ref_info') ?></div>
      <div class="col-sm-9">
        <?= TiwForm::normal('select', '', ['name' => 'ref_user_type_id'], $reference_user_options); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-light w-80px" data-bs-dismiss="modal"><?= Ty::get('cancel') ?></button>
    <button type="submit" name="submit_add_user_type" class="btn btn-primary w-100px"><?= Ty::get('save') ?></button>
  </div>
</form>
<?php Tiwdal::endModal() ?>
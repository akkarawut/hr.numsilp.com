<?php

$where = [
  'sys_function_id' => $module_id,
];

if ($_POST) {
  if (isset($_POST['submit_swap_module'])) {
    $current_id = $_POST['id'];
    $move_to_id = $_POST['to_id'];
    $result = Module::swap($current_id, $move_to_id);
  }
  W_Main::responseAPI();
}

$get_module_by_id = Module::getModuleByID($module_id);


$mockdata = [
  [
    'name' => 'ตั้งค่าหน้า List รายการ',
    'icon' => 'list.svg',
    'count' => 5,
    'sort' => '',
    'setting_action' => '#',
  ],
  [
    'name' => 'แสดงข้อมูลบางส่วนของผู้ใช้หลังชื่อหน้า',
    'icon' => 'close.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => '#',
  ],
  [
    'name' => 'ตั้งค่าหน้า : User details',
    'icon' => 'form.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => 'module_user_detail.php?c=' . $code . '&id=' . $module_id . '&template=user_detail',
  ],
  [
    'name' => 'ตั้งค่าการแสดงผลหน้า “โปรไฟล์” เมื่อผู้ใช้งานกดดูหน้าโปรไฟล์',
    'icon' => 'photo.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => 'module_user_detail.php?c=' . $code . '&id=' . $module_id . '&template=profile',
  ],
  [
    'name' => 'ตั้งค่าหน้าเพิ่มข้อมูลใหม่ (Create / Add)',
    'icon' => 'form.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => 'module_user_detail.php?c=' . $code . '&id=' . $module_id . '&template=add_data',
  ],
  [
    'name' => 'กำหนดรูปแบบการแสดงผลข้อมูลเฉพาะของ Module User',
    'icon' => '',
    'count' => '',
    'sort' => '',
    'setting_action' => '',
  ],
  [
    'name' => 'การแสดงผลข้อมูลบัญชีผู้ใช้ (Username, Password)',
    'icon' => 'icon_input.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => '#',
    'is_modal' => 'setting_account'
  ],
  [
    'name' => 'การแสดงผลข้อมูลสิทธิการใช้งานระบบ (Permission)',
    'icon' => 'icon_input.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => '#',
    'is_modal' => 'setting_permission'
  ],
  [
    'name' => 'การแสดงผลรูปโปรไฟล์',
    'icon' => 'icon_input.svg',
    'count' => '',
    'sort' => '',
    'setting_action' => '#',
    'is_modal' => 'setting_profile'
  ],
]

?>

<div class="w-card-title-bar border-bottom">
  <div class="d-flex flex-wrap align-items-center">
    <h3 class="title">User page setting - <span class="text-primary"><?= $get_module['name'] ?></span></h3>
    <div class="font-15px fw-medium text-secondary">รูปแบบการแสดงผล และแบบฟอร์มการบันทึกข้อมูลของ User</div>
  </div>
</div>

<?php foreach ($mockdata as $data) : ?>
  <div class="list-page-setting">
    <?php if ($data['setting_action']) : ?>
      <div class="icon">
        <img src=" <?= W_Structure::getLinkURL('_framework/resources/module/assets/icon/' . $data['icon'] . '') ?>" alt="">
      </div>
    <?php endif; ?>
    <div class="name <?= !$data['setting_action'] ? ' text-primary m-0 p-0 font-Bold' : '' ?>">
      <?= $data['name'] ?>
    </div>
    <?php if ($data['count'] != '') : ?>
      <div class="line-separator"></div>
      <div class="count">
        <a href="<?= $data['setting_action'] ?>" class="btn-icon-module">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/columns.svg') ?>
        </a>
        <span class="text-count">
          <?= $data['count'] ?>
        </span>
      </div>
    <?php endif; ?>
    <?php if ($data['setting_action']) : ?>
      <div class="line-separator"></div>
      <div class="info">
        <?php if (isset($data['is_modal']) && $data['is_modal']) : ?>
          <button class="btn-icon-module" <?= Tiwdal::register($data['is_modal'], $get_module, ['is_ajax' => true]); ?>>
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/setting-cog.svg') ?>
          </button>
        <?php else : ?>
          <a href="<?= $data['setting_action'] ?>" class="btn-icon-module">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/setting-cog.svg') ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endforeach; ?>



<?php Tiwdal::ajaxModal('setting_account', 'max-w-700px', ['slide' => true]); ?>
<?php Tiwdal::ajaxModal('setting_permission', 'max-w-700px', ['slide' => true]); ?>
<?php Tiwdal::ajaxModal('setting_profile', 'max-w-700px', ['slide' => true]); ?>


<script>
  function customInputTitleColor(t) {
    let scope_color_checkbox = t.closest('.scope_color_checkbox');
    let checkbox_color = scope_color_checkbox.querySelectorAll('input[type="radio"]');
    checkbox_color.forEach((e) => {
      e.checked = false;
    });
  }
</script>
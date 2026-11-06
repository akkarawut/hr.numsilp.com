<?php
$list_info                      = $program_wolves_setup;
$list_info['logo_image']        = $program_wolves_setup['logo_image'];
$list_info['signin_image']      = $program_wolves_setup['signin_image'];
$list_info['favicon']           = $program_wolves_setup['favicon'];
$list_info['dark_logo_image']   = $program_wolves_setup['dark_logo_image'];
$program = W_WLoves::$initial_data['program'];
if (isset($result)) {
  $response_message = isset($response_message) ? $response_message : $result['response_message'];
  $response_status = $result['response_status'] ? 'success' : 'error';
  $response_redirect = isset($response_redirect) ? $response_redirect : '';

  W_Main::notification($response_message, $response_status);
  W_Main::redirect($response_redirect);
}
if ($_POST) {
  if (isset($_POST['submit_edit'])) {
    unset($_POST['submit_edit']);
    $result = WLoves::setProgramSetup($_POST);
    $files = ['logo_image', 'dark_logo_image', 'signin_image', 'favicon'];
    foreach ($files  as $key => $file) {
      if ($_POST['delete_' . $file]) {
        File::delete($file);
      } else {
        File::add($file, $_FILES[$file]);
      }
    }
    $response_redirect = '?c=&setting=theme';
  } else if (isset($_POST['submit_default_light_topbar'])) {
    $data = [];
    $data['color_bg_top_bar'] = '#FFFFFF';
    $data['color_add_page_btn'] = '#676A6F';
    $data['color_selected_menu'] = '#F4F4F4';
    $data['color_base_icon'] = '#202125';
    $data['color_border'] = '#DBDCDF';
    $result = WLoves::setProgramSetup($data);
  } else if (isset($_POST['submit_default_dark_topbar'])) {
    $data = [];
    $data['dark_color_bg_top_bar'] = '#17171C';
    $data['dark_color_add_page_btn'] = '#9CA2AC';
    $data['dark_color_selected_menu'] = '#111117';
    $data['dark_color_base_icon'] = '#9CA2AC';
    $data['dark_color_border'] = '#222E45';
    $result = WLoves::setProgramSetup($data);
  } else if (isset($_POST['submit_default_light_navbar'])) {
    $data = [];
    $data['color_base_background'] = '#FFFFFF';
    $data['color_nav_selected'] = '#E3F5FF';
    $data['color_nav_hover'] = '#ECF3F7';
    $data['color_nav_font_primary'] = '#202125';
    $data['color_nav_font_secondary'] = '#676A6F';
    $data['color_nav_border'] = '#DBDCDF';
    $result = WLoves::setProgramSetup($data);
  } else if (isset($_POST['submit_default_dark_navbar'])) {
    $data = [];
    $data['dark_color_base_background'] = '#17171C';
    $data['dark_color_nav_selected'] = '#2C3341';
    $data['dark_color_nav_hover'] = '#111117';
    $data['dark_color_nav_font_primary'] = '#FFFFFF';
    $data['dark_color_nav_font_secondary'] = '#9CA2AC';
    $data['dark_color_nav_border'] = '#222E45';
    $result = WLoves::setProgramSetup($data);
  } else if (isset($_POST['submit_default_highlight'])) {
    $data = [];
    $data['color_highlight'] = '#235e88';
    $result = WLoves::setProgramSetup($data);
  }





  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}


function getfilename($url)
{
  $result = [
    'full_name' => '',
    'name' => '',
    'ext' => '',
    'is_placeholder' => false,
  ];
  $result['name'] = pathinfo($url, PATHINFO_FILENAME);
  $result['ext'] = pathinfo($url, PATHINFO_EXTENSION);
  $result['ext'] = explode('?', $result['ext'])[0];
  $result['full_name'] = $result['name'] . '.' . $result['ext'];
  $result['is_placeholder'] = $result['name'] == 'placeholder_square' ? true : false;
  return $result;
}
if (isset($program) && !isset($program['color_nav_hover']) || !isset($program['dark_color_nav_hover'])) {
  // light theme
  //color topbar
  $program['color_bg_top_bar'] = '#FFFFFF';
  $program['color_add_page_btn'] = '#676A6F';
  $program['color_selected_menu'] = '#F4F4F4';
  $program['color_base_icon'] = '#202125';
  $program['color_border'] = '#DBDCDF';
  //color nav
  $program['color_base_background'] = '#FFFFFF';
  $program['color_nav_selected'] = '#E3F5FF';
  $program['color_nav_hover'] = '#ECF3F7';
  $program['color_nav_font_primary'] = '#202125';
  $program['color_nav_font_secondary'] = '#676A6F';
  $program['color_nav_border'] = '#DBDCDF';

  // dark theme
  //color topbar
  $program['dark_color_bg_top_bar'] = '#17171C';
  $program['dark_color_add_page_btn'] = '#9CA2AC';
  $program['dark_color_selected_menu'] = '#111117';
  $program['dark_color_base_icon'] = '#9CA2AC';
  $program['dark_color_border'] = '#222E45';
  //color nav
  $program['dark_color_base_background'] = '#17171C';
  $program['dark_color_nav_selected'] = '#2C3341';
  $program['dark_color_nav_hover'] = '#111117';
  $program['dark_color_nav_font_primary'] = '#FFFFFF';
  $program['dark_color_nav_font_secondary'] = '#9CA2AC';
  $program['dark_color_nav_border'] = '#222E45';
}

$_GET['page_type'] = isset($_GET['page_type']) ? $_GET['page_type'] : 'main';
$page_type = $_GET['page_type'];
$link_view = '?page=' . $page;
$nav_view = [
  'param_name'  => 'page_type',
  'class' => '',
  'list'  => [
    [
      'id'     => 'main',
      'name'   => 'Theme & Login page',
    ],
    [
      'id'     => 'top_bar',
      'name'   => 'Customize top bar',
    ],
    [
      'id'     => 'side_nav',
      'name'   => 'Customize side nev.',
    ],
  ]
];
?>

<div class="col-lg-6 col-md-12">
  <div class="w-card">
    <?php Boatnav::topbar($nav_view, $link_view); ?>
    <?php if ($page_type == 'main') { ?>
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <h3 class="title">Program theme | Login page</h3>
        </div>
      </div>
      <div class="card-scroll p-20px">
        <div div class="d-flex align-items-center mb-15px">
          <?php TiwForm::normal('color', $program['color_highlight'], ['name' => 'color_highlight', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
          <div class="ml-10px">
            <span class=" font-Bold ">Highlight color :</span>
            <span class="scope_color font-Bold text-uppercase ml-5px  text-primary">
              <?= $program['color_highlight'] ?>
            </span>
            <p class="font-15px text-secondary mb-0">กำหนดสีไฮไลท์สำหรับใช้ในส่วนต่าง ๆ ภายในระบบ </p>
          </div>
        </div>
        <hr class="border-color-bg-1px mx--20px">
        <form method="POST">
          <button type="submit" class="btn-default-color font-Bold  p-0" name="submit_default_highlight">
            Use default color
          </button>
        </form>
        <hr class="border-color-bg-5px mx--20px mb-20px">
        <p class="font-Bold mb-0">Login page</p>
        <p class="mb-20px font-15px text-secondary">กำหนดภาพประกอบหน้า Login และโลโก้</p>
        <div class="row">
          <div class="col-md-12">
            <div class="font-16px text-primary mb-5px font-SemiBold d-flex align-items-center">
              <?= W_Structure::getfile('_framework/resources/setting/assets/icons/check_icon.svg') ?>
              <span class=" ml-5px"> Favicon image</span>
            </div>
          </div>
          <form method="post" id="favicon_form" enctype="multipart/form-data">
            <div class="col d-flex align-items-center justify-content-between">
              <div class="col-lg-6 p-0 ">
                <?php
                $favicon = getfilename($list_info['favicon']);
                TiwForm::normal('file', $favicon['is_placeholder'] ? '' : $list_info['favicon'], ['name' => 'favicon', 'onchange' => "showImage(event, 'favicon','favicon_form')"], []);
                TiwForm::normal('hidden', 0, ['name' => 'delete_favicon']);
                ?>
                <p class="text-secondary mb-0"><?= Ty::get('recommend_icon') ?></p>
              </div>
              <div class="w-50px h-50px">
                <div class=" img-1by1 holder">
                  <img src="<?= $list_info['favicon'] ?>" id="favicon" style="object-fit: contain;">
                </div>
              </div>
            </div>
          </form>
        </div>
        <hr class="border-color-bg-1px mx--20px">
        <div class="row">
          <div class="col-md-12">
            <div class="font-16px text-primary mb-5px font-SemiBold d-flex align-items-center">
              <?= W_Structure::getfile('_framework/resources/setting/assets/icons/check_icon.svg') ?>
              <span class=" ml-5px"> Program logo</span>
            </div>
          </div>
          <form method="post" id="logo_image_form" enctype="multipart/form-data">
            <div class="col">
              <div class="col-lg-6 p-0 mb-10px">
                <div class="max-w-300px">
                  <?php
                  $logo = getfilename($list_info['logo_image']);
                  TiwForm::normal('file', $logo['is_placeholder'] ? '' : $list_info['logo_image'], ['name' => 'logo_image', 'onchange' => "showImage(event, 'logo_image','logo_image_form')"], []);
                  TiwForm::normal('hidden', 0, ['name' => 'delete_logo_image']);
                  ?>
                </div>
              </div>
              <p class=" text-secondary mb-0">
                ระบบจะคำนวนรูปแบบการจัดวางให้อัตโนมัติหลังการอัปโหลด และตั้งเป็นค่า Default สำหรับส่วนอื่นด้วย
              </p>
              <div class="max-w-200px">
                <div class="img-16by9 holder">
                  <img src="<?= $list_info['logo_image'] ?>" id="logo_image" style="object-fit: contain;">
                </div>
              </div>
            </div>
          </form>
        </div>
        <hr class="border-color-bg-1px mx--20px">
        <div class="row">
          <div class="col-md-12">
            <div class="font-16px text-primary mb-5px font-SemiBold d-flex align-items-center">
              <?= W_Structure::getfile('_framework/resources/setting/assets/icons/check_icon.svg') ?>
              <span class=" ml-5px"> Login background</span>
            </div>
          </div>
          <form method="post" id="logo_signin_image" enctype="multipart/form-data">
            <div class="col">
              <div class="col-lg-6 p-0 mb-10px">
                <?php
                $signin_image = getfilename($list_info['signin_image']);
                TiwForm::normal('file',  $signin_image['is_placeholder'] ? '' : $list_info['signin_image'], ['name' => 'signin_image', 'onchange' => "showImage(event, 'signin_image','logo_signin_image')"], []);
                TiwForm::normal('hidden', 0, ['name' => 'delete_signin_image']);
                ?>
              </div>
              <p class=" text-secondary mb-10px"><?= Ty::get('recommend_bg') ?></p>
              <div class="max-w-440px">
                <div class="img-16by9 holder">
                  <img src="<?= $list_info['signin_image'] ?>" id="signin_image">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php } else if ($page_type == 'top_bar') { ?>
      <div class="card-scroll p-20px">
        <p class="font-18px font-Bold mb-0">Top bar color configure</p>
        <p class="mb-20px text-secondary font-15px">กำหนดสีและธีมของ Top bar</p>
        <ul class="nav nav-tabs nav-color-mode mb-3" id="pills-color-topnav-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="pills-light-topnav-tap" data-bs-toggle="pill" href="#pills-light-topnav" role="tab" aria-controls="pills-light-topnav" aria-selected="true">
              <img src="../../structure/image/icon/icon-light-mode.svg">
              <?= Ty::get('lightmode_set') ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-dark-topnav-tap" data-bs-toggle="pill" href="#pills-dark-topnav" role="tab" aria-controls="pills-dark-topnav" aria-selected="false">
              <img src="../../structure/image/icon/icon-dark-mode.svg">
              <?= Ty::get('darkmode_set') ?>
            </a>
          </li>
        </ul>
        <div class="tab-content" id="pills-color-topnav-content">
          <div class="tab-pane fade show active" id="pills-light-topnav" role="tabpanel" aria-labelledby="pills-light-topnav-tap">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_bg_top_bar'], ['name' => 'color_bg_top_bar', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Background color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_bg_top_bar'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังของแถบ Top bar </p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_add_page_btn'], ['name' => 'color_add_page_btn', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Add page button color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_add_page_btn'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีไอคอนและตัวหนังสือของปุ่ม “ADD PAGE”</p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_selected_menu'], ['name' => 'color_selected_menu', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Add page background hover stage :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_selected_menu'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังปุ่ม “ADD PAGE” เมื่อ Hover</p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_base_icon'], ['name' => 'color_base_icon', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_base_icon'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวหนังสือ (ชื่อหน้า) หลังจากเพิ่มทางลัดแล้ว รวมถึงสีชื่อโปรไฟล์</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_border'], ['name' => 'color_border', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Border color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_border'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีเส้นต่าง ๆ บน Topbar</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <form method="post">
              <button type="submit" class="btn-default-color font-Bold  p-0" name="submit_default_light_topbar">
                Use default color
              </button>
            </form>
            <hr class="border-color-bg-5px mx--20px">
          </div>
          <div class="tab-pane fade" id="pills-dark-topnav" role="tabpanel" aria-labelledby="pills-dark-topnav-tab">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_bg_top_bar'], ['name' => 'dark_color_bg_top_bar', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Background color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_bg_top_bar'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังของแถบ Top bar </p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_add_page_btn'], ['name' => 'dark_color_add_page_btn', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Add page button color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_add_page_btn'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีไอคอนและตัวหนังสือของปุ่ม “ADD PAGE”</p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_selected_menu'], ['name' => 'dark_color_selected_menu', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Add page background hover stage :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_selected_menu'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังปุ่ม “ADD PAGE” เมื่อ Hover</p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_base_icon'], ['name' => 'dark_color_base_icon', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_base_icon'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวหนังสือ (ชื่อหน้า) หลังจากเพิ่มทางลัดแล้ว รวมถึงสีชื่อโปรไฟล์</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_border'], ['name' => 'dark_color_border', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Border color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_border'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีเส้นต่าง ๆ บน Topbar</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <form method="POST">
              <button type="submit" class="btn-default-color font-Bold  p-0" name="submit_default_dark_topbar">
                Use default color
              </button>
            </form>
            <hr class="border-color-bg-5px mx--20px">
          </div>
        </div>
      </div>
    <?php } else if ($page_type == 'side_nav') { ?>
      <div class="card-scroll p-20px">
        <p class=" font-18px font-Bold mb-0">Top bar color configure</p>
        <p class="mb-20px text-secondary font-15px">กำหนดสีและธีมของ Top bar</p>
        <ul class="nav nav-tabs nav-color-mode mb-3" id="pills-color-navbar-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="pills-light-navbar-tap" data-bs-toggle="pill" href="#pills-light-navbar" role="tab" aria-controls="pills-light-navbar" aria-selected="true">
              <img src="../../structure/image/icon/icon-light-mode.svg">
              <?= Ty::get('lightmode_set') ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-dark-navbar-tap" data-bs-toggle="pill" href="#pills-dark-navbar" role="tab" aria-controls="pills-dark-navbar" aria-selected="false">
              <img src="../../structure/image/icon/icon-dark-mode.svg">
              <?= Ty::get('darkmode_set') ?>
            </a>
          </li>
        </ul>
        <div class="tab-content" id="pills-color-navbar-content">
          <div class="tab-pane fade show active" id="pills-light-navbar" role="tabpanel" aria-labelledby="pills-light-navbar-tap">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_base_background'], ['name' => 'color_base_background', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Background color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_base_background'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังของแถบ Side nav </p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_nav_selected'], ['name' => 'color_nav_selected', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Selected stage :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_nav_selected'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังเมนูเมื่อใช้งานเมนูนั้นอยู่</p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_nav_hover'], ['name' => 'color_nav_hover', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Hover stage :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_nav_hover'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังเมนูเมื่อ Hover</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_nav_font_primary'], ['name' => 'color_nav_font_primary', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Page name text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_nav_font_primary'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวหนังสือ (ชื่อหน้า/เมนู)</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_nav_font_secondary'], ['name' => 'color_nav_font_secondary', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Section text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_nav_font_secondary'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวอักษรของ Section title</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_nav_border'], ['name' => 'color_nav_border', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Section text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['color_nav_border'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวอักษรของ Section title</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <form method="POST">
              <button type="submit" class="btn-default-color font-Bold  p-0" name="submit_default_light_navbar">
                Use default color
              </button>
            </form>
            <hr class="border-color-bg-5px mx--20px">
          </div>
          <div class="tab-pane fade show" id="pills-dark-navbar" role="tabpanel" aria-labelledby="pills-dark-navbar-tap">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_base_background'], ['name' => 'dark_color_base_background', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Background color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_base_background'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังของแถบ Side nav </p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_nav_selected'], ['name' => 'dark_color_nav_selected', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Selected stage :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_nav_selected'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังเมนูเมื่อใช้งานเมนูนั้นอยู่</p>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_nav_hover'], ['name' => 'dark_color_nav_hover', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Hover stage :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_nav_hover'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีพื้นหลังเมนูเมื่อ Hover</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_nav_font_primary'], ['name' => 'dark_color_nav_font_primary', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Page name text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_nav_font_primary'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวหนังสือ (ชื่อหน้า/เมนู)</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_nav_font_secondary'], ['name' => 'dark_color_nav_font_secondary', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Section text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_nav_font_secondary'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวอักษรของ Section title</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['dark_color_nav_border'], ['name' => 'dark_color_nav_border', 'class' => 'input-color-custom', 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px">
                <span class=" font-Bold ">Section text color :</span>
                <span class="scope_color font-Bold text-uppercase ml-5px text-primary">
                  <?= $program['dark_color_nav_border'] ?>
                </span>
                <p class="font-15px text-secondary mb-0">สีตัวอักษรของ Section title</p>
              </div>
            </div>
            <hr class="border-color-bg-1px mx--20px">
            <form method="POST">
              <button type="submit" class="btn-default-color font-Bold  p-0" name="submit_default_dark_navbar">
                Use default color
              </button>
            </form>
            <hr class="border-color-bg-5px mx--20px">
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>


<script>
  //JavaScript



  function changeTextColor(e) {
    var color = e.target.value;
    var name = e.target.getAttribute("name");
    e.target.nextElementSibling.querySelector('.scope_color').innerHTML = color;

    var name = e.target.getAttribute("name");
    if (name == 'color_highlight') {
      document.documentElement.style.setProperty('--color-highlight', color);
    } else if (name == 'color_base_background' || name == 'dark_color_base_background') {
      document.documentElement.style.setProperty('--bg-nav', color);
    } else if (name == 'color_nav_selected' || name == 'dark_color_nav_selected') {
      document.documentElement.style.setProperty('--bg-nav-selected', color);
    } else if (name == 'color_nav_hover' || name == 'dark_color_nav_hover') {
      document.documentElement.style.setProperty('--bg-nav-hover', color);
    } else if (name == 'color_nav_font_primary' || name == 'dark_color_nav_font_primary') {
      document.documentElement.style.setProperty('--color-font-nav-primary', color);
    } else if (name == 'color_nav_font_secondary' || name == 'dark_color_nav_font_secondary') {
      document.documentElement.style.setProperty('--color-font-nav-secondary', color);
    } else if (name == 'color_nav_border' || name == 'dark_color_nav_border') {
      document.documentElement.style.setProperty('--color-border-nav', color);
    } else if (name == 'color_bg_top_bar' || name == 'dark_color_bg_top_bar') {
      document.documentElement.style.setProperty('--bg-top-bar', color);
    } else if (name == 'color_add_page_btn' || name == 'dark_color_add_page_btn') {
      document.documentElement.style.setProperty('--bg-top-bar-add-page', color);
    } else if (name == 'color_selected_menu' || name == 'dark_color_selected_menu') {
      document.documentElement.style.setProperty('--hover-top-bar-add-page', color);
    } else if (name == 'color_base_icon' || name == 'dark_color_base_icon') {
      document.documentElement.style.setProperty('--color-font-tor-bar', color);
    } else if (name == 'color_border' || name == 'dark_color_border') {
      document.documentElement.style.setProperty('--color-border-top-bar', color);
    }
    var data = {
      'name': name,
      'color': color,
    };

    updateDataThemeColor(data);
  }

  function showImage(e, id, form_id = '') {
    var name = e.target.getAttribute("name");
    var file = e.target.files[0];
    var output = document.getElementById(id);
    var parent = e.target.parentElement;
    var input = parent.nextElementSibling;
    if (file) {
      input.value = '0';
      var reader = new FileReader();
      reader.onload = function() {
        output.src = reader.result;
      };
      reader.readAsDataURL(file);
    } else {
      input.value = '1';
      output.src = "assets/images/upload_img.png";
    }

    updateFile(form_id);
  }

  function updateDataThemeColor(data) {

    const myRequest = new XMLHttpRequest();
    //send data
    myRequest.onload = () => {
      // if and status code is OK
      if (myRequest.status === 200) {
        var result = JSON.parse(myRequest.responseText);
        if (result.response_status == true) {
          w.notification("success", result.response_message);
        } else {
          w.notification("error", result.response_message);
        }
        // window.location.reload();
      } else {
        console.log('Something went wrong')
      }
    }

    link = 'ajax/ajax_data_update_theme.php';

    myRequest.open('POST', link, true);
    myRequest.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    myRequest.send(JSON.stringify(data));
  }

  function updateFile(form_id) {
    let url = 'ajax/ajax_file_update.php';
    wx.formData(url, form_id, function(data) {
      var result = JSON.parse(data);
      w.notification(result.response_status ? 'success' : 'error', result.response_message);
    });
  }
</script>
<?php

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
$disabled = '';
if ($status != 'edit') {
  $disabled = 'disabled';
}
?>

<div class="w-card has-nav">
  <form method="post" enctype="multipart/form-data" id="form_theme_program" class="form-loading">
    <div class="w-card-title-bar border-bottom">
      <div class="d-flex flex-wrap align-items-center">
        <h3 class="title"><?= Ty::get('program_theme') ?></h3>
        <p class="detail">
          <?= Ty::get('configure') ?> <?= Ty::get('edit_adjust') ?>
        </p>
      </div>
      <div class="btn-edit-container">
        <?php if ($status != 'edit') { ?>
          <a href="?c=&setting=theme&status=edit" class="btn-edit edit"> <?= Ty::get('edit_data') ?></a>
        <?php } else { ?>
          <a href="?c=&setting=theme" class="btn-edit cancel"> <?= Ty::get('cancel') ?></a>
          <button type="submit" class="btn-edit save" name="submit_edit"> <?= Ty::get('save', [], ["case" => "ucfirst"]) ?></button>
        <?php } ?>
      </div>
    </div>
    <div class="card-scroll has-theme-color">
      <div class="container-fluid h-100">
        <div class="row h-100">
          <div class="col-lg-4 mb-sm-0 mb-3 border-right">
            <h3 class="font-16px text-info mb-5px font-Bold mt-10px"><?= Ty::get('highlight_color') ?></h3>
            <p class="mb-15px text-secondary"><?= Ty::get('configure_highlight') ?> </p>
            <div div class="d-flex align-items-center mb-15px">
              <?php TiwForm::normal('color', $program['color_highlight'], ['name' => 'color_highlight', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
              <div class="ml-10px font-Medium text-info text-uppercase">
                <?= $program['color_highlight'] ?>
              </div>
            </div>
            <div div class="d-flex align-items-center mb-25px flex-wrap <?= $status != 'edit' ? 'd-none' : '' ?>">
              <button type="submit" class="btn-default-color" name="submit_default_highlight" <?= $disabled ?>>
                <?= Ty::get('highlight_default') ?>
              </button>
            </div>
            <hr class="border-color-bg mx--10px">
            <h3 class="font-16px text-info mb-30px font-Bold"><?= Ty::get('login_page') ?></h3>
            <div class="row mb-20px">
              <div class="col-md-12">
                <h3 class="font-16px text-secondary mb-15px font-SemiBold"><?= Ty::get('background_img') ?></h3>
              </div>
              <div class="col">
                <?php if ($status == 'edit') { ?>
                  <div class="col-lg-6 p-0 mb-10px">
                    <?php
                    $signin_image = getfilename($list_info['signin_image']);
                    TiwForm::normal('file',  $signin_image['is_placeholder'] ? '' : $signin_image['full_name'], ['name' => 'signin_image', 'onchange' => "showImage(event, 'signin_image')", $disabled => true], []);
                    TiwForm::normal('hidden', 0, ['name' => 'delete_signin_image']);
                    ?>
                  </div>
                  <p class=" text-secondary mb-10px"><?= Ty::get('recommend_bg') ?></p>
                <?php }  ?>
                <div class="max-w-440px">
                  <div class="img-16by9 holder">
                    <img src="<?= $list_info['signin_image'] ?>" id="signin_image">
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-10px">
              <div class="col-md-12">
                <h3 class="font-16px text-secondary mb-15px font-SemiBold"><?= Ty::get('favicon_img') ?></h3>
              </div>
              <div class="col">
                <?php if ($status == 'edit') { ?>
                  <div class="col-lg-6 p-0 mb-10px">
                    <?php
                    $favicon = getfilename($list_info['favicon']);
                    TiwForm::normal('file', $favicon['is_placeholder'] ? '' : $favicon['full_name'], ['name' => 'favicon', 'onchange' => "showImage(event, 'favicon')", $disabled => true], []);
                    TiwForm::normal('hidden', 0, ['name' => 'delete_favicon']);
                    ?>
                  </div>
                  <p class="text-secondary mb-10px"><?= Ty::get('recommend_icon') ?></p>
                <?php }  ?>
                <div class=" max-w-50px">
                  <div class=" img-1by1 holder">
                    <img src="<?= $list_info['favicon'] ?>" id="favicon">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 border-right">
            <h3 class="font-16px text-info mb-5px font-Bold  mt-10px"><?= Ty::get('top_bar') ?></h3>
            <p class="mb-15px text-secondary"><?= Ty::get('config_topbar') ?></p>
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
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('bg_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_bg_top_bar'], ['name' => 'color_bg_top_bar', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_bg_top_bar'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('addpage_button') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_add_page_btn'], ['name' => 'color_add_page_btn', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_add_page_btn'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('addpage_bg') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_selected_menu'], ['name' => 'color_selected_menu', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_selected_menu'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center  mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('font_topbar') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px ">
                    <?php TiwForm::normal('color', $program['color_base_icon'], ['name' => 'color_base_icon', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_base_icon'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('border_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px ">
                    <?php TiwForm::normal('color', $program['color_border'], ['name' => 'color_border', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_border'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap <?= $status != 'edit' ? 'd-none' : '' ?>">
                  <div class=" min-w-200px"></div>
                  <button type="submit" class="btn-default-color" name="submit_default_light_topbar" <?= $disabled ?>>
                    <?= Ty::get('light_default') ?>
                  </button>
                </div>
                <div class="row mb-20px">
                  <div class="col-md-12">
                    <h3 class="font-16px text-secondary mb-15px font-SemiBold"><?= Ty::get('logo') ?></h3>
                  </div>
                  <div class="col">
                    <?php if ($status == 'edit') { ?>
                      <div class="col-lg-6 p-0 mb-10px">
                        <div class="max-w-300px">
                          <?php
                          $logo = getfilename($list_info['logo_image']);
                          TiwForm::normal('file', $logo['is_placeholder'] ? '' : $logo['full_name'], ['name' => 'logo_image', 'onchange' => "showImage(event, 'logo_image')", $disabled => true], []);
                          TiwForm::normal('hidden', 0, ['name' => 'delete_logo_image']);
                          ?>
                        </div>
                      </div>
                      <p class=" text-secondary mb-10px"><?= Ty::get('recommend_logo') ?>.</p>
                    <?php }  ?>
                    <div class="max-w-200px">
                      <div class="img-16by9 holder">
                        <img src="<?= $list_info['logo_image'] ?>" id="logo_image" style="object-fit: contain;">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="pills-dark-topnav" role="tabpanel" aria-labelledby="pills-dark-topnav-tab">
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('bg_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_bg_top_bar'], ['name' => 'dark_color_bg_top_bar', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_bg_top_bar'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('addpage_button') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_add_page_btn'], ['name' => 'dark_color_add_page_btn', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_add_page_btn'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('addpage_bg') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_selected_menu'], ['name' => 'dark_color_selected_menu', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_selected_menu'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('font_topbar') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px ">
                    <?php TiwForm::normal('color', $program['dark_color_base_icon'], ['name' => 'dark_color_base_icon', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_base_icon'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('border_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px ">
                    <?php TiwForm::normal('color', $program['dark_color_border'], ['name' => 'dark_color_border', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_border'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-15px flex-wrap <?= $status != 'edit' ? 'd-none' : '' ?>">
                  <div class="min-w-200px"></div>
                  <button type="submit" class="btn-default-color" name="submit_default_dark_topbar" <?= $disabled ?>>
                    <?= Ty::get('darkmode_default') ?>
                  </button>
                </div>
                <div class="row mb-20px">
                  <div class="col-md-12">
                    <h3 class="font-16px text-secondary mb-15px font-SemiBold"><?= Ty::get('logo') ?></h3>
                  </div>
                  <div class="col">
                    <?php if ($status == 'edit') { ?>
                      <div class="col-lg-6 p-0 mb-10px">
                        <div class="max-w-300px">
                          <?php
                          $logo = getfilename($list_info['dark_logo_image']);
                          TiwForm::normal('file', $logo['is_placeholder'] ? '' : $logo['full_name'], ['name' => 'dark_logo_image', 'onchange' => "showImage(event, 'dark_logo_image')", $disabled => true], []);
                          TiwForm::normal('hidden', 0, ['name' => 'delete_dark_logo_image']);
                          ?>
                        </div>
                      </div>
                      <p class=" text-secondary mb-10px"><?= Ty::get('recommend_logo') ?></p>
                    <?php }  ?>
                    <div class="max-w-200px">
                      <div class="img-16by9 holder">
                        <img src="<?= $list_info['dark_logo_image'] ?>" id="dark_logo_image" style="object-fit: contain;">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <h3 class="font-16px text-info mb-5px font-Bold  mt-10px"><?= Ty::get('side_nav') ?></h3>
            <p class="mb-15px text-secondary"><?= Ty::get('sidenav_color') ?></p>
            <ul class="nav  nav-tabs nav-color-mode mb-3" id="pills-color-navbar-tab" role="tablist">
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
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('bg_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_base_background'], ['name' => 'color_base_background', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_base_background'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('selected_stage') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_nav_selected'], ['name' => 'color_nav_selected', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_nav_selected'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('hover_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_nav_hover'], ['name' => 'color_nav_hover', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_nav_hover'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('pri_font') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_nav_font_primary'], ['name' => 'color_nav_font_primary', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_nav_font_primary'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center   mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('sec_font') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['color_nav_font_secondary'], ['name' => 'color_nav_font_secondary', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_nav_font_secondary'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('border_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px ">
                    <?php TiwForm::normal('color', $program['color_nav_border'], ['name' => 'color_nav_border', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['color_nav_border'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap <?= $status != 'edit' ? 'd-none' : '' ?>">
                  <div class="min-w-200px"></div>
                  <button type="submit" class="btn-default-color" name="submit_default_light_navbar" <?= $disabled ?>>
                    <?= Ty::get('light_default') ?>
                  </button>
                </div>
              </div>
              <div class="tab-pane fade show" id="pills-dark-navbar" role="tabpanel" aria-labelledby="pills-dark-navbar-tap">
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('bg_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_base_background'], ['name' => 'dark_color_base_background', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_base_background'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('selected_stage') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_nav_selected'], ['name' => 'dark_color_nav_selected', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_nav_selected'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('hover_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_nav_hover'], ['name' => 'dark_color_nav_hover', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_nav_hover'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('pri_font') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_nav_font_primary'], ['name' => 'dark_color_nav_font_primary', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_nav_font_primary'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center   mb-10px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('sec_font') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px">
                    <?php TiwForm::normal('color', $program['dark_color_nav_font_secondary'], ['name' => 'dark_color_nav_font_secondary', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_nav_font_secondary'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap">
                  <div class="font-16px mr-10px font-SemiBold text-secondary min-w-200px mb-sm-0 mb-2">
                    <?= Ty::get('border_color') ?>
                  </div>
                  <div div class="d-flex align-items-center w-100 max-w-150px ">
                    <?php TiwForm::normal('color', $program['dark_color_nav_border'], ['name' => 'dark_color_nav_border', 'class' => 'mb-0 shadow-sm', $disabled => true, 'onchange' => 'changeTextColor(event)'], []); ?>
                    <div class="ml-10px font-Medium text-info text-uppercase">
                      <?= $program['dark_color_nav_border'] ?>
                    </div>
                  </div>
                </div>
                <div div class="d-flex align-items-center mb-25px flex-wrap <?= $status != 'edit' ? 'd-none' : '' ?>">
                  <div class="min-w-200px"></div>
                  <button type="submit" class="btn-default-color" name="submit_default_dark_navbar" <?= $disabled ?>>
                    <?= Ty::get('darkmode_default') ?>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  //JavaScript
  var disabled = '<?= $disabled ?>';
  if (disabled) {
    document.querySelectorAll(".btn-delete-file").forEach(function(el) {
      el.setAttribute("disabled", true);
    });
  }



  function changeTextColor(e) {
    var color = e.target.value;
    e.target.nextElementSibling.innerHTML = color;
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
  }

  function showImage(e, id) {
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
      output.src = "../../structure/image/placeholder_square.jpg";
    }
  }
</script>
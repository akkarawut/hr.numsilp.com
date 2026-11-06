<?php
$program = W_WLoves::$initial_data['program'];
$logo_image     = File::getPath('logo_image');
$signin_image     = File::getPath('signin_image');
$mobile_logo_image = File::getPath('mobile_logo_image');

if (isset($_POST['submit_info_program'])) {
  $data = [
    'program_name' => $_POST['program_name'],
    'description' => $_POST['description'],
    'customer' => $_POST['customer'],
    'start_date' => $_POST['start_date'],
    'end_time' => $_POST['end_time'],
    'email' => $_POST['email'],
    'default_language' => $_POST['default_language'],
    'second_language' => $_POST['second_language'],
    'default_currency' => $_POST['default_currency'],
    'color_highlight' => $program['color_highlight'],
    'color_base_background' => $program['color_base_background'],
    'color_selected_menu' => $program['color_selected_menu'],
    'color_base_icon' => $program['color_base_icon'],
  ];
  $api_result = WLoves::setProgramSetup($data);

  if ($api_result['response_status']) {
    W_Main::notification($api_result['response_message'], 'success');
    W_Main::redirect('program_setting.php?type=theme&module_code=1');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect();
  }
} else if (isset($_POST['submit_theme_program'])) {
  $data = [
    'program_name' => $program['program_name'],
    'description' => $program['description'],
    'customer' => $program['customer'],
    'start_date' => $program['start_date'],
    'end_time' => $program['end_time'],
    'default_language' => $program['default_language'],
    'second_language' => $program['second_language'],
    'default_currency' => $program['default_currency'],
    'color_highlight' => $_POST['color_highlight'],
    'color_base_background' => $_POST['color_base_background'],
    'color_selected_menu' => $_POST['color_selected_menu'],
    'color_base_icon' => $_POST['color_base_icon'],
    'theme' => $_POST['theme'],
  ];

  if ($_POST['default_theme'] == 'white') {
    $data['theme'] = 'white';
    $data['color_highlight'] = '#235E88';
    $data['color_base_background'] = '#fbfbfb';
    $data['color_selected_menu'] = '#eaeaea';
    $data['color_base_icon'] = '#778396';
  } else if ($_POST['default_theme'] == 'dark') {
    $data['theme'] = 'dark';
    $data['color_highlight'] = '#235E88';
    $data['color_base_background'] = '#313a41';
    $data['color_selected_menu'] = '#212f39';
    $data['color_base_icon'] = '#d8d9da';
  }

  $api_result = WLoves::setProgramSetup($data);
  if ($api_result['response_status']) {
    $file = File::add('logo_image', $_FILES['logo_image']);
    $file_test = File::add('signin_image', $_FILES['signin_image']);
    W_Main::notification($api_result['response_message'], 'success');
    W_Main::redirect('program_setting.php?type=theme&module_code=1&nav_type=2');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect();
  }
}


// Config Itnav
$status = isset($_GET['status']) ? $_GET['status'] : '';
$link = 'program_setting.php?type=' . $setting_type . '&module_code=' . $module_code_selected;
$nav_type_selected  = isset($_GET['nav_type']) ? $_GET['nav_type'] : '1';
$data_nav = [
  'class' => '',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'General Detail',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 2,
      'name'  => 'Theme',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 3,
      'name'  => 'Database Connection',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 4,
      'name'  => 'Alert List',
      'icon'   => '',
      'count'  => '',
    ],
  ]
];

?>
<div class="col-lg-10 box-nav-top">
  <div class="menu-nav-card mb-10px">
    <div class="multi-menu-header">
      <?php
      //Itnav::dinner($data_nav, $link, 'nav_type', $nav_type_selected); 
      ?>
    </div>
    <?php
    if ($nav_type_selected == 1) {
      include '../../module_main/core/view/program_setting/general_detail.php';
    } else if ($nav_type_selected == 2) {
      include '../../module_main/core/view/program_setting/theme.php';
    } else if ($nav_type_selected == 3) {
      include '../../module_main/core/view/program_setting/database_connect.php';
    } else if ($nav_type_selected == 4) {
      include '../../module_main/core/view/program_setting/alert_list.php';
    }
    ?>
  </div>
  <?php
  if ($nav_type_selected == 1) {
  ?>
    <div class="info-progress shadow-sm">
      <div class="flex-between-center">
        <div class="w-75 mr-2">
          <p class="mb-0 font-14px font-Medium">Program General Details</p>
          <div class="scope-progress">
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="percentage">0%</div>
          </div>
          <span class="font-14px text-secondary">by Admin</span>
        </div>
        <?php
        if ($status == 'edit') {
          echo '<div class="d-flex">';
          echo '<a href="' . $link . '1" class="btn btn-light h-35px mr-5px">CANCEL</a>';
          echo TiwForm::normal('btn', 'submit', ['form' => 'form_info_program'], ['text' => 'SAVE'], ['id' => 'form_info_program']);
          echo '</div>';
        } else {
          echo '<a href="' . $link . '1&status=edit" class="btn btn-outline-dark text-uppercase text-decoration-none w-25">Edit Data</a>';
        }
        ?>

      </div>
    </div>
  <?php } else if ($nav_type_selected == 3) { ?>
    <div class="install-group-bottom">
      <div class="group-title">
        <h5 class="mb-0 text-secondary">Reconnect Database</h5>
        <?php if ($status == 'edit') { ?>
          <p class="mb-0">Make sure on your self before click “Connect” if you make it while confused or funny you will be lay off.</p>
        <?php } else { ?>
          <p class="mb-0">Don’t be touch if you not already sour.</p>
        <?php } ?>
      </div>
      <?php if ($status == 'edit') { ?>
        <div class="d-flex">
          <a href="<?= $link ?>&nav_type=3" class="btn btn-light h-35px mr-5px">CANCEL</a>
          <?= TiwForm::normal('btn', 'submit', ['form' => 'form_database_connect'], ['text' => 'CONNECT'], ['id' => 'form_database_connect']) ?>
        </div>
      <?php } else { ?>
        <a href="<?= $link ?>&nav_type=3&status=edit" class="btn btn-outline-primary">RECONNECT</a>
      <?php } ?>
    </div>
  <?php } ?>
</div>


<script>
  var all_select = $('#form_info_program').find('select');
  all_select.addClass('read_progress');

  if ($(window).height() == $(document).height()) {
    $('.info-progress').addClass('progress-stick');
  }

  $(window).scroll(function() {
    if ($(window).scrollTop() + $('.content-wrap').height() >= $(document).height()) {
      $('.info-progress').addClass('progress-stick');
    } else {
      $('.info-progress').removeClass('progress-stick');
    }
  });

  $('.info-progress').width($('.box-nav-top').width() - 30);
  $(window).resize(function() {
    $('.info-progress').width($('.box-nav-top').width() - 30);
  });

  <?php if ($status == 'edit') { ?>

    var read_progress = $('.read-progress');
    progress(read_progress);
    $('.read-progress').on('click change keyup mouseup', function(e) {
      var read_progress = $('.read-progress');
      progress(read_progress);
    });

    // $('.form-select-img.read-progress .dropdown-menu .select-group .dropdown-item').on('click change keyup mouseup', function (e) {
    //   var data_id = $(this).data('id');
    //   if (data_id) {
    //     // number_value++;
    //     progress(read_progress);
    //   }
    // });

    $('.select-list-group').on('click', function(e) {
      var read_progress = $('.read-progress');
      setTimeout(() => {
        progress(read_progress);
      }, 100);
    });

    $(document).on('click', '.form-select-tag .remove-tag', function(e) {
      var read_progress = $('.read-progress');
      setTimeout(() => {
        progress(read_progress);
      }, 100);
    });

    function progress(read_progress) {
      var number_value = 0;
      for (let index = 0; index < read_progress.length; index++) {
        (read_progress[index].value) ? number_value++ : '';
      }
      var tag_form = $('.read-progress-leader .selected-area .selected-list');
      if (tag_form.length > 0) {
        number_value++;
      }

      var tag_form = $('.read-progress-subordinate .selected-area .selected-list');
      if (tag_form.length > 0) {
        number_value++;
      }

      var selected_form = $('.form-select-tag.read-progress').find(':selected');
      if (selected_form.length) {
        number_value++;
      }

      var selected_dropdown = $('.form-select-img.read-progress .dropdown-menu .select-group .dropdown-item').hasClass('selected');
      if (selected_dropdown == true) {
        number_value++;
      }

      // var selected_form = $('select.read-progress').find(':selected');
      // if (selected_form.length) {
      //   number_value++;
      // }

      var requis_form = $('#prerequisite', '.read-progress').find('.selected-list');
      if (requis_form.length) {
        number_value++;
      }
      var sum_progress = number_value * 100 / read_progress.length;
      $('.progress-bar').css('width', sum_progress + '%');
      $('.percentage').text(sum_progress.toFixed(0) + '%');
    }
  <?php } else { ?>
    var text_progress = $('.text_progress');
    progress_text(text_progress);

    function progress_text(text_progress) {
      var number_value = 0;
      for (let index = 0; index < text_progress.length; index++) {
        ($(text_progress[index]).text() != '') ? number_value++ : '';
      }
      var sum_progress = number_value * 100 / text_progress.length;
      $('.progress-bar').css('width', sum_progress + '%');
      $('.percentage').text(sum_progress.toFixed(0) + '%');
    }
  <?php } ?>
</script>
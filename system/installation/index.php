<?php

  // CONFIG PATH
  $sys_path = dirname(dirname(__FILE__));
  require_once 'class_install.php';
  require_once $sys_path.DIRECTORY_SEPARATOR.'required'.DIRECTORY_SEPARATOR.'info.php';

  // ===== Configable ===== //
  $config_file_path    = $sys_path.'/config/config.php';
  $file_to_check_list  = ['/resource/file', '/resource/setting', '/resource/temp'];
  $def_config_database = [
    'db_type'     => 'mysql',
    'db_server'   => 'private-wolves-db-pup-01-do-user-8122825-0.b.db.ondigitalocean.com',
    'db_name'     => '',
    'db_username' => '',
    'db_password' => '',
    'db_port'     => 25060
  ];

  // ===== Do not Edit DownThere ===== //
  $is_has_config_file = Installation::hasConfigFile($sys_path);

  if ($is_has_config_file) {
    include $config_file_path;
    if (defined('Config::db_type')) {
      $def_config_database['db_type'] = Config::db_type;
    }
    if (defined('Config::db_server')) {
      $def_config_database['db_server'] = Config::db_server;
    }
    if (defined('Config::db_port')) {
      $def_config_database['db_port'] = Config::db_port;
    }
    if (defined('Config::db_username')) {
      $def_config_database['db_username'] = Config::db_username;
    }
    if (defined('Config::db_password')) {
      $def_config_database['db_password'] = Config::db_password;
    }
    if (defined('Config::db_name')) {
      $def_config_database['db_name'] = Config::db_name;
    }
    // if (isset($_POST['db_server'])) {
    //   $result = run_installation($_POST);
    //   if (isset($result['result'])) {
    //     if ($result['result'] == 'ERROR') {
    //       die($result['message']);
    //     } else {
    //       die('index.php?result=ready');
    //     }
    //   } else {
    //     die('Result ไม่ถูกต้อง');
    //   }
    // }
    $schema = Installation::selectTableStructure();
    if (is_array($schema)) {
      $schema = 'This Database already has '.count($schema).' Table(s), Please DROP all of them before NEW INSTALL.';
    }
  } else {
    // [TODO] READ CONFIG
    // include $config_file_path;
    // die("SYSTEM READY");
  }

  $version = Info::version_main.'.'.Info::version_sub.'.'.Info::version_hotfix;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>AmstX<?php echo $version ?> SYSTEM INSTALLER</title>
    <style>
      .scrollable {
        max-height: 1000px;
        overflow-y: auto;
      }
    </style>
  </head>
</head>

<body class="white">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-9 col-md-8">
        <form method="post">
          <div class="p-20px">
            <div class="install-group-right">
              <div class="header">
                <h5 class="mb-0">WLOVES INSTALLATION PROCETURE</h5>
                <p class="mb-0">RECHECK CONFIG DATABASE CONNECTION AND UPDATE LASTEST</p>
              </div>
              <hr class="hr-install">

              <div class="body">
                <div class="sub-header mb-10px">
                  <h5 class="mb-0">1.CHECK FILE PERMISSION</h5>
                </div>
                <div class="form-row install-form">
                  <?php foreach ($file_to_check_list as $i_dir) {?>
                    <div class="col-lg-12">
                      <div class="permission-file permission-file-group">
                        <div class="icon-chmod mr-5px mb-5px">
                        <?php if (!checkFolderPermission($sys_path.$i_dir)) {?>
<?php $msg_class = 'text-danger';?>
                            <div class="false">
                              <img src="image/icon/icon-chmod-false.svg" alt="">
                              <span class="<?php echo $msg_class; ?> font-14px font-SemiBold text-check-active">chmod Folder<?php echo $sys_path.$i_dir; ?> to 777</span>
                            </div>
                          <?php } else {?>
<?php $msg_class = 'text-success';?>
                            <div class="true">
                              <img src="image/icon/icon-chmod-true.svg" alt="">
                              <span class="<?php echo $msg_class; ?> font-14px font-SemiBold text-check-active">chmod Folder<?php echo $sys_path.$i_dir; ?> to 777</span>
                            </div>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                  <?php }?>
                </div>
              </div>
              <hr class="hr-install dash">

              <div class="body">
                <div class="sub-header mb-25px">
                  <h5 class="mb-0">2.CHECK DATABASE CONNECTION</h5>
                </div>
                <div class="">
                  <div class="icon-chmod mr-5px mb-5px">
                    <?php if (!$is_has_config_file) {?>
<?php $msg_class = 'text-danger';?>
                      <div class="false">
                        <img src="image/icon/icon-chmod-false.svg" alt="">
                        <span class="<?php echo $msg_class; ?> font-14px font-SemiBold text-check-active">Config file '<?php echo $config_file_path; ?>' is not Exists.</span>
                      </div>
                    <?php } else {?>
<?php $msg_class = 'text-success';?>
                      <div class="true">
                        <img src="image/icon/icon-chmod-true.svg" alt="">
                        <span class="<?php echo $msg_class; ?> font-14px font-SemiBold text-check-active">Config file '<?php echo $config_file_path; ?>' is Ready.</span>
                      </div>
                    <?php }?>
                  </div>

                </div>
                <div class="form-row install-form">
                  <div class="col-lg-4">
                    <label>DATABASE TYPE</label>
                  </div>
                  <div class="col-lg-8">
                    <p>MySQL</p>
                  </div>
                  <div class="col-lg-4">
                    <label>DATABASE SERVER</label>
                  </div>
                  <div class="col-lg-8">
                    <input type="text" class="form-control progress-database" name="db_server" value="<?php echo isset($def_config_database['db_server']) ? $def_config_database['db_server'] : '' ?>" required>
                  </div>
                  <div class="col-lg-4">
                    <label>DATABASE NAME</label>
                  </div>
                  <div class="col-lg-8">
                    <div class="form-row">
                      <div class="col-6">
                      <input type="text" class="form-control progress-database" name="db_name" value="<?php echo isset($def_config_database['db_name']) ? $def_config_database['db_name'] : '' ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <label>DATABASE USERNAME</label>
                  </div>
                  <div class="col-lg-8">
                    <div class="form-row">
                      <div class="col-6">
                      <input type="text" class="form-control progress-database" name="db_username" value="<?php echo isset($def_config_database['db_username']) ? $def_config_database['db_username'] : '' ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <label>DATABASE PASSWORD</label>
                  </div>
                  <div class="col-lg-8">
                    <div class="form-row">
                      <div class="col-6">
                      <input type="text" class="form-control progress-database" name="db_password" value="<?php echo isset($def_config_database['db_password']) ? $def_config_database['db_password'] : '' ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <label>DATABASE PORT</label>
                  </div>
                  <div class="col-lg-8">
                    <div class="form-row">
                      <div class="col-3">
                      <input type="text" class="form-control progress-database" name="db_port" value="<?php echo isset($def_config_database['db_port']) ? $def_config_database['db_port'] : '' ?>" required>
                      </div>
                      <div class="col">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4"></div>
                  <div class="col-lg-8 mt-2">
                    <button type="button" class="btn btn-outline-secondary check_install" >TEST CONNECTION</button>
                    <button type="button" class="btn btn-outline-secondary update_config" >Update config</button>
                  </div>
                  <div class="col-lg-4"></div>
                  <div class="col-lg-8">
                    <?php echo isset($post_result) ? $post_result : ''; ?>
                    <div  id="test_message">
                      <span class="text-danger font-14px font-weight-normal mb-10px">Run TEST CONNECTION first</span>
                      <span class="text-success font-14px font-weight-normal mb-10px d-none">Connected</span>
                    </div>
                  </div>
                </div>
              </div>
              <hr class="hr-install dash">

              <div class="header">
                <h5 class="mb-0">3.[TODO] Check Database Health..</h5>
                <p class="mb-0"><?php echo isset($schema) ? json_encode($schema, JSON_UNESCAPED_UNICODE) : 'Run TEST CONNECTION first'; ?></p>
              </div>
              <hr class="hr-install dash">

            </div>
          </div>
          <div class="px-20px">
            <div class="install-group-bottom">
              <div class="group-title">
                <h5 class="mb-0">4. [TODO] Diagnosis system...</h5>
                <p class="mb-0">Make Sure your data before click “Generate Program” if you make it while confused or funny you will be lay off.</p>
                <p class="mb-0">ถ้าเช็ค 777 แล้ว เทส db ผ่านแล้ว ก็ไม่ต้องทำอะไรเพิ่มแล้ว หน้านี้ จะลบทิ้งเพื่อความปลอดภัย หรือเอาไว้อัพเดทการตั้งค่า ค่อยว่ากันอีกที</p>
              </div>
              <input type="hidden" name="cmd_gen_program" value="cmd_gen_program">
              <button type="submit" class="btn btn-check-generate btn-outline-secondary" >TODO..</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  <script>
    var progress_database = $('.progress-database');
    progress(progress_database);

    $('.progress-database').on('click change keyup mouseup', function (e) {
      var progress_database = $('.progress-database');
      progress(progress_database);
    });

    function progress(read_progress) {
      var number_value = 0;
      for (let index = 0; index < read_progress.length; index++) {
        (read_progress[index].value) ? number_value++ : '';
      }
      var sum_progress = number_value * 100 / read_progress.length;
      if (sum_progress >= 100) {
        $('.database').addClass('active');
      } else {
        $('.database').removeClass('active');
      }
    }

    var progress_guardian = $('.progress-guardian');
    progressGuardian(progress_guardian);

    $('.progress-guardian').on('click change keyup mouseup', function (e) {
      var progress_guardian = $('.progress-guardian');
      progressGuardian(progress_guardian);
    });

    function progressGuardian(read_progress) {
      var number_value = 0;
      for (let index = 0; index < read_progress.length; index++) {
        (read_progress[index].value) ? number_value++ : '';
      }
      var sum_progress = number_value * 100 / read_progress.length;
      if (sum_progress >= 100) {
        $('.guardian').addClass('active');
      } else {
        $('.guardian').removeClass('active');
      }
    }

    $(document).on('click', '.check_install', function() {
      var db_server = $('input[name="db_server"]').val();
      var db_name = $('input[name="db_name"]').val();
      var db_username = $('input[name="db_username"]').val();
      var db_password = $('input[name="db_password"]').val();
      var db_port = $('input[name="db_port"]').val();

      var url = 'ajax_check_connect_install.php';
      var params = {
        'cmd_update_database' : 1,
        'db_server': db_server,
        'db_name': db_name,
        'db_username': db_username,
        'db_password': db_password,
        'db_port': db_port,
      };
      $.post(url, params).done(function(response) {
        var permission_file_group = $('.permission-file-group');
        if (response == 'success') {
          console.log("show success");
          $('#test_message .text-success').removeClass('d-none');
          $('#test_message .text-danger').addClass('d-none');
          // $('.btn-check-generate').removeClass('btn-outline-secondary').addClass('btn-outline-primary');
          // $('.btn-check-generate').prop('disabled', false);
          // progressFile(permission_file_group);
        } else {
          $('#test_message .text-danger').removeClass('d-none').text('Can not connect. Please recheck server and config again.');
          $('#test_message .text-success').addClass('d-none');
          // $('.btn-check-generate').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
          // $('.btn-check-generate').prop('disabled', true);
          // progressFile(permission_file_group);
          console.log("show fail");
        }
      })
    });

    var permission_file = $('.permission-file');
    progressPermission(permission_file);

    function progressPermission(read_progress) {
      var number_value = 0;
      for (let index = 0; index < read_progress.length; index++) {
        // (read_progress[index].value) ? number_value++ : '';
        if ($(read_progress[index]).hasClass('active')) {
          number_value++
        }
      }
      var sum_progress = number_value * 100 / read_progress.length;
      if (sum_progress >= 100) {
        $('.file').addClass('active');
      } else {
        $('.file').removeClass('active');
      }
    }

    function progressFile(read_progress) {
      var number_value = 0;
      for (let index = 0; index < read_progress.length; index++) {
        // (read_progress[index].value) ? number_value++ : '';
        if ($(read_progress[index]).hasClass('active')) {
          number_value++
        }
      }
      var sum_progress = number_value * 100 / read_progress.length;
      console.log(sum_progress+'('+number_value+'/'+read_progress.length+')')
      if (sum_progress >= 100) {
        $('.btn-check-generate').prop('disabled', false);
      } else {
        $('.btn-check-generate').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
        $('.btn-check-generate').prop('disabled', false);
      }
    }

  </script>
</body>
</html>
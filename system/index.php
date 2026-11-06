<?php
  $config_file_path = 'config/config.php';
  if (file_exists($config_file_path)) {
    include 'required/import.php';

    $api = WLoves::initSystem();
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      if (isset($api['response_data']['api_key'])) {
        // SHOW FIRST API KEY 
        echo json_encode($api, JSON_UNESCAPED_UNICODE);
        die();
      }
    }

    if (isset($_GET['show_general_config'])) {
      $rep = [
        'time_zone'                 => TIMEZONE,
        'date_default_timezone_get' => date_default_timezone_get(),
        'timezone_offset'           => Util::getTimezoneOffset()
      ];
      echo json_encode($rep, JSON_UNESCAPED_UNICODE);
      die();
    }
    echo '<h2>THIS IS AmstX'.WLoves::getVersion().'!!!!</h2>';
    die();
  } else {
    echo '<h2>Config not set, RUN installation first.</h2>';
    die();
  }
?>
<h2>Don't allow</h2>
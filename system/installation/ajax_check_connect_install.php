<?php
if (file_exists('class_install.php')) {
  require_once 'class_install.php';

  if (isset($_POST['db_server'])) {
    $result = test_db_connection($_POST['db_server'], $_POST['db_name'], $_POST['db_username'], $_POST['db_password'], $_POST['db_port']);
    if ($result) {
      $root_path = dirname(dirname(__FILE__));
      if (isset($_POST['cmd_update_database'])) {
        $result = Installation::setConfig($root_path, $_REQUEST);
        // if (isset($result['result'])) {
        $post_result = json_encode($result, JSON_UNESCAPED_UNICODE);
      }
      echo 'success';
    } else {
      echo 'fail';
    }
  }
} else {
  echo 'No file';
}
?>
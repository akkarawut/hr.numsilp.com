<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../_framework/import.php';
$columns                        = $_POST['column'];
$params                         = $_POST['params'];
$params['options']['page_no']   = $_POST['page_no'];
$params['options']['page_size'] = $_POST['page_size'];
$params['options']['sort']      = isset($_POST['data_sort']) ? $_POST['data_sort'] : [];
$params['where']['search_text'] = isset($_POST['search_text']) ? $_POST['search_text'] : '';

if (isset($params['code'])) {
  $param1 = $params['code'];
  $param2 = isset($params['where']) ? $params['where'] : [];
  $param3 = $params['options'];
} else {
  $param1 = $params['where'];
  $param2 = $params['options'];
  $param3 = [];
}
$API   = $_POST['class'] . '::' . $_POST['function_api'];
$daily = $API($param1, $param2, $param3);
$daily = isset($daily['booking_list']) ? $daily['booking_list'] : $daily;

?>

<?php foreach ($daily as $data) {
?>
  <tr>
    <?php
    foreach ($columns as $key => $column) {
      if (strpos($column['html'], "{" . $key . "}") !== false) {
        if ($column['type'] == "date") {
          echo str_replace("{" . $key . "}", W_Main::formatDate($data[$key], 'd/m/Y, H:i'), $column['html']);
        } else {
          echo str_replace("{" . $key . "}", $data[$key], $column['html']);
        }
      } else {
        echo $column['html'];
      }
    }
    ?>
  </tr>
<?php } ?>
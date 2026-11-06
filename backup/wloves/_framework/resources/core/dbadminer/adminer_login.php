<?php
$_WLOVES['no_check_permission'] = 1;
include '../../../../_framework/import.php';
$dev = Dev::getCurrent();
if (isset($dev['id'])) {
  $config = WLoves::getDatabaseSetting();
  $adminer_path =  'adminer.php';
  $db_type     = $config['db_type'];
  $db_server   = $config['db_server'];
  $db_name     = $config['db_name'];
  $db_username = $config['db_username'];
  $db_password = $config['db_password'];
  $db_port     = $config['db_port'];
} else {
  header('HTTP/1.0 403 Forbidden');
  echo 'Not Allow';
  die();
}

function callExternalApi($method, $url, $data = [])
{
  $curl = curl_init();

  switch ($method) {
    case "POST":
      curl_setopt($curl, CURLOPT_POST, true);
      if ($data) {
        //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
      }
      break;
    case "PUT":
      curl_setopt($curl, CURLOPT_PUT, 1);
      break;
    default:
      if ($data) {
        $url = sprintf("%s?%s", $url, http_build_query($data));
      }
  }

  // Optional Authentication:
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($curl, CURLOPT_USERPWD, "username:password");

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
  // [20200820] PREVENT SSL ERROR :  "curl_error": "SSL: no alternative certificate subject name matches target host name 'www.vxlotto.com'"
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $result = curl_exec($curl);
  if (curl_errno($curl)) {
    $result = json_encode([
      'url'        => $url,
      'curl_error' => curl_error($curl)
    ]);
  }
  curl_close($curl);

  if (!$result) {
    $result = json_encode(['url' => $url]);
  }

  return $result;
}

function devGetCurrent($url_endpoint, $api_code, $api_key)
{
  $data = [
    'code'     => $api_code,
    'api_key'  => $api_key,
    'class'    => 'Dev',
    'function' => 'getCurrent',
    'params'   => []
  ];
  $json_result = callExternalApi('GET', $url_endpoint, $data);
  return json_decode($json_result, true);
}

?>
<!doctype html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

  <title>THIS IS AmstX3.0!!!!</title>
  <style>
    .scrollable {
      max-height: 1000px;
      overflow-y: auto;
    }
  </style>
</head>

<body>
  <h1>AmstX3.0 Adminer Auto Login to : <?= $db_name; ?></h1>
  <div class="container">
    <div class="row" style="display:none;">
      <div class="col-md">
        <form id="frm_create_table" action="<?php echo "{$adminer_path}?username={$db_username}"; ?>" method="POST">
          <div class="form-group">
            <label for="tbx_table_name">DRIVER</label>
            <input type="text" class="form-control form-control-sm" name="auth[driver]" value="server" required>
          </div>
          <div class="form-group">
            <label for="tbx_table_name">DRIVER</label>
            <input type="text" class="form-control form-control-sm" name="auth[server]" value="<?php echo "{$db_server}:{$db_port}"; ?>" required>
          </div>
          <div class="form-group">
            <label for="tbx_table_name">DRIVER</label>
            <input type="text" class="form-control form-control-sm" name="auth[username]" value="<?php echo "{$db_username}"; ?>" required>
          </div>
          <div class="form-group">
            <label for="tbx_table_name">DRIVER</label>
            <input type="password" class="form-control form-control-sm" name="auth[password]" value="<?php echo "{$db_password}"; ?>" required>
          </div>
          <div class="form-group">
            <label for="tbx_table_name">DRIVER</label>
            <input type="text" class="form-control form-control-sm" name="auth[db]" value="<?php echo "{$db_name}"; ?>" required>
          </div>
          <button id="btn_submit" type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  <script>
    // NOTWORKING T^T
    var url = '<?php echo "{$adminer_path}?username={$db_username}"; ?>';

    function renderPhpScript(data) {
      console.log('renderPhpScript');
      console.log(data);
      $.post(url, function(data, status) {
        alert("Data: " + data + "\nStatus: " + status);
      });
    }


    $(document).ready(function() {
      $("#frm_create_table").submit();

      // 	$('#btn_submit').click(function(e) {
      //    e.preventDefault();

      //    let form = $("#frm_create_table");
      //    input_params = form.serializeArray();
      //    renderPhpScript(input_params);

      //  });
    });
  </script>
</body>

</html>
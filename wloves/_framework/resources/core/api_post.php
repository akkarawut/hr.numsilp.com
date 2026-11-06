<?php
$_PAGE['permission'] = ['core', 'core_dev', 'core_dev_api'];

require_once '../../../_framework/import.php';

W_Structure::loadModules(['itnav']);

// initial data
$get_module_list = Doc::selectAllModule();
$get_module_class_list = [];
$get_module_class_function = [];

$module_selected = isset($_GET['module']) ? $_GET['module'] : '';
$module_type = isset($_GET['type']) ? $_GET['type'] : '';
$class_selected = isset($_GET['class']) ? $_GET['class'] : '';
$function_selected = isset($_GET['function']) ? $_GET['function'] : '';

if ($module_selected) {
  if ($module_type == 'module') {
    $get_module_class_list = Doc::selectModuleClass($module_selected, ['it_nav_milk' => 1]);
  } else if ($module_type == 'module_main') {
    $get_module_class_list = Doc::selectModuleMainClass($module_selected, ['it_nav_milk' => 1]);
  }
}

if ($module_selected && $class_selected && $function_selected) {
  if ($module_type == 'module') {
    $get_module_class_function = Doc::getExtractDoc($module_selected, $class_selected, $function_selected);
  } else if ($module_type == 'module_main') {
    $get_module_class_function = Doc::getExtractDocMain($module_selected, $function_selected);
  }
}

// FOR JS
$json_params_schema = '{}';
// W_Main::display($get_module_class_function['extract_data']);
if (isset($get_module_class_function['extract_data']['parameter']) && ($get_module_class_function['extract_data']['parameter'])) {
  $temp_params_schema = [];
  foreach ($get_module_class_function['extract_data']['parameter'] as $i_param) {
    $i_param_name = json_decode(json_encode($i_param), true);
    // Uncaught Error: Cannot use object of type ReflectionParameter as array in
    $temp_params_schema[$i_param_name['name']] = $i_param_name['name'];
  }
  $json_params_schema = json_encode(array_values($temp_params_schema));
}

function generateFunction($class, $function, $parameter)
{
  $parameter_text = '';

  foreach ($parameter as $idx => $param) {
    $param_name = '';
    if (gettype($param) == 'array') {
      $param_name = $param['name'];
    } else if (gettype($param) == 'object') {
      $param_name = $param->name;
    }
    if (count($parameter) == ($idx + 1)) {
      $parameter_text .= '$' . $param_name;
    } else {
      $parameter_text .= '$' . $param_name . ', ';
    }
  }

  $text = $class . '::' . $function . '(' . $parameter_text . ');';
  return $text;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta('../../');
  ?>
</head>

<body>
  <?php
  include_once 'structure/layout/header.php';
  include_once 'structure/layout/sidenav.php';
  ?>

  <div class="row">
    <div class="col-lg-2 mt-15px">
      <div class="w-loves-card px-0 pt-0">
        <div class="sub-topic-container">
          <div class="sub-topic-header-wrap">
            <h3 class="header-title font-16px">Module</h3>
          </div>
          <div>
            <?php Itnav::milkclub($get_module_list, 'api.php?c= ', 'module', $module_selected); ?>
          </div>
        </div>
      </div>
    </div>

    <?php
    if ($get_module_class_list) {
    ?>
      <div class="col-lg-2 mt-15px">
        <div class="w-loves-card px-0 pt-0">
          <div class="sub-topic-container">
            <div class="sub-topic-header-wrap">
              <h3 class="header-title font-16px">Class</h3>
            </div>
            <div>
              <?php Itnav::milkclub($get_module_class_list, 'api.php?type=' . $module_type . '&module=' . $module_selected, 'function', $function_selected); ?>
            </div>
          </div>
        </div>
      </div>
    <?php }  ?>

    <?php if ($get_module_class_function && $module_type == 'module') { ?>
      <?php $module_class_function = $get_module_class_function['extract_data']; ?>
      <div class="col-lg-8">
        <div class="w-love-content-container">
          <div class="w-love-content-container-wrap m-0 mt-15px">
            <div class="content-header-container">
              <h3 class="header-title">PARAMETER</h3>
            </div>
            <form id="form_module" action="http://128.199.207.248:8321/system/api/echo.php" method="POST" target="_blank" enctype="multipart/form-data">
              <div class="w-data-table-container border-radius-0">
                <table class="table parameter-table">
                  <thead>
                    <tr>
                      <th class="thin-cell">Name</th>
                      <th class="thin-cell">ส่ง</th>
                      <th class="">Input</th>
                      <th class="">Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($module_class_function['parameter_input_list'] as $idx => $i_parameter) { ?>
                      <tr>
                        <td>
                          <?= $i_parameter['name']; ?>
                        </td>
                        <?php if ($i_parameter['name'] == 'code') { ?>
                          <td>
                            <input type="checkbox" class="form-control chk_sent_param" checked disabled>
                          </td>
                          <td>
                            <select id="select_module" name="param[code]" class="form-control" data-toggle-select2="false">
                              <?php
                              foreach ($get_module_class_function['module_code_list'] as $module_code) {
                                echo '<option>' . $module_code . '</option>';
                              }
                              ?>
                            </select>
                          </td>
                        <?php } else if ($i_parameter['name'] == 'file') { ?>
                          <td>
                            <input id="chk_sent_param_<?php echo $idx; ?>" data-idx="<?php echo $idx; ?>" type="checkbox" class="form-control chk_sent_param" onclick="handleCheckSentParam(this);" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? 'checked disabled' : ''; ?>>
                          </td>
                          <td>
                            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                            <input id="tbx_param_<?php echo $idx; ?>" type="file" name="ff1" data-remark="ไม่ต้องใส่ ] ต่อท้าย" class="form-control text-secondary <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'tbx_param'; ?>" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'disabled'; ?>>
                          </td>
                        <?php } else { ?>
                          <td>
                            <input id="chk_sent_param_<?php echo $idx; ?>" data-idx="<?php echo $idx; ?>" type="checkbox" class="form-control chk_sent_param" onclick="handleCheckSentParam(this);" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? 'checked disabled' : ''; ?>>
                          </td>
                          <td>
                            <input id="tbx_param_<?php echo $idx; ?>" type="text" name="param[<?php echo $i_parameter['name']; ?>" data-remark="ไม่ต้องใส่ ] ต่อท้าย" class="form-control text-secondary <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'tbx_param'; ?>" value="<?php echo isset($i_parameter['default']) ? $i_parameter['default'] : ''; ?>" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'disabled'; ?>>
                          </td>
                        <?php } ?>
                        <td>
                          <?php echo $i_parameter['placeholder']; ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <input type="hidden" name="code">
              <input type="hidden" name="params">
              <input type="hidden" name="function" value="<?= $function_selected; ?>">
              <input type="hidden" name="class" value="<?= $class_selected; ?>">
              <input type="hidden" name="api_key" value="<?= F_BRIDGE_API_KEY; ?>">
              <button id="submit_module" type="submit" class="btn btn-block btn-call-api btn-primary border-radius-0">
                <img src="../../structure/image/icon/general/rocket.svg">
                <span class="ms-2">CALL API</span>
              </button>
            </form>
          </div>

          <div class="w-love-content-container-wrap m-0 mt-10px">
            <div class="content-header-container">
              <h3 class="header-title">DETAIL</h3>
            </div>
            <div class="content-body-container">
              <div class="body-group-text">
                <span class="label-text">Class :</span>
                <span><?= $module_class_function['class']; ?></span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Path :</span>
                <span class="w-loves-badge path-badge"><?= $get_module_class_function['file_name']; ?></span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Call :</span>
                <span>
                  <?= generateFunction($module_class_function['class'], $module_class_function['function'], $module_class_function['parameter']); ?>
                </span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Description :</span>
                <span><?= $module_class_function['description']; ?></span>
              </div>
              <div class="code-container">
                <pre class="mb-0">
                <?= trim($get_module_class_function['see_code']); ?>
              </pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } else if ($get_module_class_function && $module_type == 'module_main') { ?>
      <?php $module_class_function = $get_module_class_function['extract_data']; ?>
      <div class="col-lg-7">
        <div class="w-love-content-container">
          <div class="w-love-content-container-wrap m-0 mt-15px">
            <div class="content-header-container">
              <h3 class="header-title">PARAMETER</h3>
            </div>
            <form id="form_main_module" action="<?= F_BRIDGE_API_URL; ?>" method="POST" target="_blank" enctype="multipart/form-data">
              <div class="w-data-table-container border-radius-0">
                <table class="table parameter-table">
                  <thead>
                    <tr>
                      <th class="thin-cell">Name</th>
                      <th class="thin-cell">ส่ง</th>
                      <th class="">Input</th>
                      <th class="">Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($module_class_function['parameter_input_list'] as $idx => $i_parameter) { ?>
                      <tr>
                        <td>
                          <label for="chk_sent_param_<?php echo $idx; ?>"><?= $i_parameter['name']; ?></label>
                        </td>
                        <?php if ($i_parameter['name'] == 'file') { ?>
                          <td>
                            <input id="chk_sent_param_<?php echo $idx; ?>" data-idx="<?php echo $idx; ?>" type="checkbox" class="form-control chk_sent_param" onclick="handleCheckSentParam(this);" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? 'checked disabled' : ''; ?>>
                          </td>
                          <td>
                            <input id="tbx_param_<?php echo $idx; ?>" type="file" name="ff2[<?php echo $i_parameter['name']; ?>" data-remark="ไม่ต้องใส่ ] ต่อท้าย" class="form-control text-secondary <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'tbx_param'; ?>" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'disabled'; ?>>
                          </td>
                        <?php } else { ?>
                          <td>
                            <input id="chk_sent_param_<?php echo $idx; ?>" data-idx="<?php echo $idx; ?>" type="checkbox" class="form-control chk_sent_param" onclick="handleCheckSentParam(this);" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? 'disabled checked' : ''; ?>>
                          </td>
                          <td>
                            <input id="tbx_param_<?php echo $idx; ?>" type="text" name="param[<?php echo $i_parameter['name']; ?>" data-remark="ไม่ต้องใส่ ] ต่อท้าย" class="form-control text-secondary <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'tbx_param'; ?>" value="<?php echo isset($i_parameter['default']) ? $i_parameter['default'] : ''; ?>" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'disabled'; ?>>
                          </td>
                        <?php }  ?>
                        <td>
                          <?php echo $i_parameter['placeholder']; ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <input type="hidden" name="function" value="<?= $function_selected; ?>">
              <input type="hidden" name="class" value="<?= $module_selected; ?>">
              <input type="hidden" name="api_key" value="<?= F_BRIDGE_API_KEY; ?>">
              <input type="hidden" name="params">
              <button id="submit_main_module" type="submit" class="btn btn-block btn-primary btn-call-api border-radius-0">
                <img src="../../structure/image/icon/general/rocket.svg">
                <span class="ms-2">CALL API</span>
              </button>
            </form>
          </div>

          <div class="w-love-content-container-wrap m-0 mt-10px">
            <div class="content-header-container">
              <h3 class="header-title">DETAIL</h3>
            </div>
            <div class="content-body-container">
              <div class="body-group-text">
                <span class="label-text">Class :</span>
                <span><?= $module_class_function['class']; ?></span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Path :</span>
                <span class="w-loves-badge path-badge"><?= $get_module_class_function['file_name']; ?></span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Call :</span>
                <span>
                  <?php
                  echo generateFunction($module_class_function['class'], $module_class_function['function'], $module_class_function['parameter']);
                  ?>
                </span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Description :</span>
                <span><?= $module_class_function['description']; ?></span>
              </div>
              <div class="code-container">
                <pre class="mb-0">
                <?= trim($get_module_class_function['see_code']); ?>
              </pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } else if (FALSE && $get_module_class_function && $module_type == 'module') { ?>
      <?php $module_class_function = $get_module_class_function['extract_data']; ?>
      <div class="col-lg-8">
        <div class="w-love-content-container">
          <div class="w-love-content-container-wrap m-0 mt-15px">
            <div class="content-header-container">
              <h3 class="header-title">PARAMETER</h3>
            </div>
            <form id="form_module" action="<?= F_BRIDGE_API_URL; ?>" method="POST" target="_blank" enctype="multipart/form-data">
              <div class="w-data-table-container border-radius-0">
                <table class="table parameter-table">
                  <thead>
                    <tr>
                      <th class="thin-cell">Name</th>
                      <th class="thin-cell">ส่ง</th>
                      <th class="">Input</th>
                      <th class="">Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($module_class_function['parameter_input_list'] as $idx => $i_parameter) { ?>
                      <tr>
                        <td>
                          <?= $i_parameter['name']; ?>
                        </td>
                        <?php if ($i_parameter['name'] == 'code') { ?>
                          <td>
                            <input type="checkbox" class="form-control chk_sent_param" checked disabled>
                          </td>
                          <td>
                            <select id="select_module" name="param[code]" class="form-control" data-toggle-select2="false">
                              <?php
                              foreach ($get_module_class_function['module_code_list'] as $module_code) {
                                echo '<option>' . $module_code . '</option>';
                              }
                              ?>
                            </select>
                          </td>
                        <?php } else if ($i_parameter['name'] == 'file') { ?>
                          <td>
                            <input id="chk_sent_param_<?php echo $idx; ?>" data-idx="<?php echo $idx; ?>" type="checkbox" class="form-control chk_sent_param" onclick="handleCheckSentParam(this);" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? 'checked disabled' : ''; ?>>
                          </td>
                          <td>
                            <input id="tbx_param_<?php echo $idx; ?>" type="file" name="ff3[<?php echo $i_parameter['name']; ?>" data-remark="ไม่ต้องใส่ ] ต่อท้าย" class="form-control text-secondary <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'tbx_param'; ?>" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'disabled'; ?>>
                          </td>
                        <?php } else { ?>
                          <td>
                            <input id="chk_sent_param_<?php echo $idx; ?>" data-idx="<?php echo $idx; ?>" type="checkbox" class="form-control chk_sent_param" onclick="handleCheckSentParam(this);" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? 'checked disabled' : ''; ?>>
                          </td>
                          <td>
                            <input id="tbx_param_<?php echo $idx; ?>" type="text" name="param[<?php echo $i_parameter['name']; ?>" data-remark="ไม่ต้องใส่ ] ต่อท้าย" class="form-control text-secondary <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'tbx_param'; ?>" value="<?php echo isset($i_parameter['default']) ? $i_parameter['default'] : ''; ?>" <?php echo (isset($i_parameter['is_required']) && ($i_parameter['is_required'] == 1)) ? '' : 'disabled'; ?>>
                          </td>
                        <?php } ?>
                        <td>
                          <?php echo $i_parameter['placeholder']; ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <input type="hidden" name="code">
              <input type="hidden" name="params">
              <input type="hidden" name="function" value="<?= $function_selected; ?>">
              <input type="hidden" name="class" value="<?= $class_selected; ?>">
              <input type="hidden" name="api_key" value="<?= F_BRIDGE_API_KEY; ?>">
              <button id="submit_module" type="submit" class="btn btn-block btn-call-api btn-primary border-radius-0">
                <img src="../../structure/image/icon/general/rocket.svg">
                <span class="ms-2">CALL API</span>
              </button>
            </form>
          </div>

          <div class="w-love-content-container-wrap m-0 mt-10px">
            <div class="content-header-container">
              <h3 class="header-title">DETAIL</h3>
            </div>
            <div class="content-body-container">
              <div class="body-group-text">
                <span class="label-text">Class :</span>
                <span><?= $module_class_function['class']; ?></span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Path :</span>
                <span class="w-loves-badge path-badge"><?= $get_module_class_function['file_name']; ?></span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Call :</span>
                <span>
                  <?= generateFunction($module_class_function['class'], $module_class_function['function'], $module_class_function['parameter']); ?>
                </span>
              </div>
              <div class="body-group-text">
                <span class="label-text">Description :</span>
                <span><?= $module_class_function['description']; ?></span>
              </div>
              <div class="code-container">
                <pre class="mb-0">
                <?= trim($get_module_class_function['see_code']); ?>
              </pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <?php
  include_once 'structure/layout/footer.php';
  W_Structure::loadFooter('../../');
  ?>
  <script>
    $.fn.serializeControls = function() {
      var data = {};
      // [20211124] TULA : serial form into JSON
      function buildInputObject(arr, val) {
        if (arr.length < 1)
          return val;
        var objkey = arr[0];
        if (objkey.slice(-1) == "]") {
          objkey = objkey.slice(0, -1);
        }
        var result = {};
        if (arr.length == 1) {
          result[objkey] = val;
        } else {
          arr.shift();
          var nestedVal = buildInputObject(arr, val);
          result[objkey] = nestedVal;
        }
        return result;
      }

      $.each(this.serializeArray(), function() {
        var val = this.value;
        var c = this.name.split("[");
        var a = buildInputObject(c, val);
        $.extend(true, data, a);
      });

      return data;
    }

    function setTbxParamState() {
      // reset all textbox disable state
      $('.chk_sent_param').each(function() {
        handleCheckSentParam(this);
      });
    }

    function getParamsJson(schema, params) {
      // console.log('Set missing Params');
      max_i = 0;
      params_count = 0;
      // console.log(schema);
      // console.log(params);
      for (let i = 1; i <= schema.length; i++) {
        i_key = schema[i - 1];
        // console.log(i_key+":"+params[i_key]);
        if (typeof params[i_key] !== "undefined") {
          max_i = i;
          params_count++;
        }
      }
      // console.log(max_i);
      // console.log(params_count);

      if (params_count != max_i) {
        // Missing some
        new_params = {}; // create ORDERED object
        for (let i = 1; i <= schema.length; i++) {
          i_key = schema[i - 1];
          if (typeof params[i_key] === "undefined") {
            new_params[i_key] = null; // Set missing
          } else {
            new_params[i_key] = params[i_key];
          }
        }
        // return JSON.stringify(new_params, null, 2) // EzRead
        return JSON.stringify(new_params)
      }
      return JSON.stringify(params)
    }

    function handleCheckSentParam(e) {
      idx = $(e).data('idx');
      // console.log('handleCheckSentParam');
      // console.log(e.id);
      // console.log($(e).prop('checked'));
      if ($(e).prop('checked') == true) {
        $('#tbx_param_' + idx).removeAttr("disabled");
      } else {
        $('#tbx_param_' + idx).prop("disabled", true);
      }
    }

    const toBase64 = file => new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = error => reject(error);
    });

    $(document).ready(function() {

      // แก้บัค API ต้องการ 1 2 3 ส่ง 1 กับ 3 ได้ 
      var params_schema = JSON.parse('<?php echo isset($json_params_schema) ? $json_params_schema : '{}'; ?>');
      $("#form_module").submit(function(e) {
        console.log('Selecteer een afbeelding!');
        var file_data = $('input[type="file"]')[0].files;
        console.log(file_data);
      });

      $('#submit_module').click(function(e) {
        e.preventDefault();
        let form = $(this).parents('#form_module');
        let get_code = form.find('#select_module').val();

        console.log(form.find('input[type="file"]').length);

        if (get_code) {
          let code = form.find('input[name="code"]');
          code.val(get_code);
        }

        // Param to JSON
        input_params = form.serializeControls();
        console.log(input_params);

        // FILE_INPUT
        var file_data = $('input[type="file"]')[0].files;
        console.log(file_data);
        // for (var i = 0; i < file_data.length; i++) {
        //   input_params["my_file"] = await toBase64(file_data[i]);
        // }

        // console.log(input_params.param);
        $('.tbx_param').prop("disabled", true);

        json_params = '{}';
        if (typeof input_params.param !== "undefined") {
          json_params = getParamsJson(params_schema, input_params.param);
        }
        // console.log(json_params);

        $('input[name="params"]').val(json_params);
        // console.log($('input[name="params"]').val());
        form.submit();
        setTbxParamState();
      });

      $('#submit_main_module').click(function(e) {
        e.preventDefault();
        let form = $(this).parents('#form_main_module');
        let get_code = form.find('#select_module').val();

        if (get_code) {
          let code = form.find('input[name="code"]');
          code.val(get_code);
        }

        // Param to JSON
        input_params = form.serializeControls();
        // console.log(input_params);
        // console.log(input_params.param);
        // Disable After serializeControls
        $('.tbx_param').prop("disabled", true);
        json_params = '{}';
        if (typeof input_params.param !== "undefined") {
          json_params = getParamsJson(params_schema, input_params.param);
        }
        // console.log(json_params);
        $('input[name="params"]').val(json_params);
        // console.log($('input[name="params"]').val());
        form.submit();
        setTbxParamState();
      });
    });
  </script>
</body>

</html>
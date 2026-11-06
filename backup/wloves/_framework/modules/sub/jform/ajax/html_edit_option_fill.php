<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
require_once '../php/option.php';
require_once '../php/setting_form.php';
$sys_function_id = isset($_POST['sys_function_id']) ? $_POST['sys_function_id'] : '';
$index = isset($_POST['index']) ? $_POST['index'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$meta_data = (json_decode($_POST['meta_data'], true));
?>

<?php Tiwdal::startModal('modal_edit_option_fill', 'max-w-1200px', ['slide' => true]); ?>
<form id="form_edit_option">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">แก้ไขช่องกรอกข้อมูล</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmEditFill(<?= $index ?>);">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-lg-7">
          <div class="row">
            <div class="col-sm-12 mt-20px mb-20px">
              <div class="font-15px fw-bold">เชื่อมโยงข้อมูล</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">เชื่อมโยงกับ</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-dark fw-semibold"><?= isset($meta_data['field_name']) && $meta_data['field_name'] ? $meta_data['field_name'] : '' ?></div>
              <input type="hidden" name="field_name" value="<?= isset($meta_data['field_name']) && $meta_data['field_name'] ? $meta_data['field_name'] : '' ?>">
              <input type="hidden" name="table_name" value="<?= isset($meta_data['table_name']) && $meta_data['table_name'] ? $meta_data['table_name'] : '' ?>">
              <input type="hidden" name="code" value="<?= isset($meta_data['code']) && $meta_data['code'] ? $meta_data['code'] : '' ?>">
              <input type="hidden" name="category" value="<?= isset($meta_data['category']) && $meta_data['category'] ? $meta_data['category'] : '' ?>">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">DB Column name</div>
            </div>
            <div class="col-sm-8">
              <?php $db_column = isset($meta_data['db_column_name']) && $meta_data['db_column_name'] ? $meta_data['db_column_name'] : '' ?>
              <div class="font-16px text-primary fw-semibold" data-db_column_name="<?= $db_column ?>"><?= $db_column ?></div>
              <input type="hidden" name="db_column_name" value="<?= $db_column ?>">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-sm-8">
              <?php
              $type = isset($meta_data['type']) && $meta_data['type'] ?  $meta_data['type'] : '';
              $type_text = '';
              if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Tag') {
                $type_text = 'Tag';
              } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Dropdown') {
                $type_text = 'Dropdown';
              } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Auto address') {
                $type_text = 'Auto address';
              } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Google map link') {
                $type_text = 'Google map link';
              } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Link') {
                $type_text = 'Link';
              } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Latitude_Longtitude') {
                $type_text = 'Latitude_Longtitude';
              } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Youtube_embed') {
                $type_text = 'Youtube embed';
              } else {
                $type_text = $type;
              }
              ?>
              <?php  ?>
              <div class="font-16px text-dark fw-semibold" data-type="<?= $type ?>"><?= $type_text ?></div>
              <input type="hidden" name="type" value="<?= $type ?>">
            </div>
          </div>
          <input type="hidden" name="field_id" value="<?= isset($meta_data['field_id']) && $meta_data['field_id'] ?  $meta_data['field_id'] : ''; ?>">
          <hr>

          <?php
          $not_varchar = ['Tag', 'Checkbox', 'Switch', 'Radio', 'Dropdown', 'Auto address', 'Google map link', 'Latitude_Longtitude', 'Link', 'Youtube_embed'];
          if (($meta_data['type'] == 'Varchar' || $meta_data['type'] == 'Longtext') && !in_array($meta_data['category'], $not_varchar)) {
            echo template_form_text($is_edit = true, $meta_data, $meta_data);
          } else if ($meta_data['type'] == 'Int' || $meta_data['type'] == 'Bigint' || $meta_data['type'] == 'Tinyint') {
            echo template_form_number($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Decimal') {
            echo template_form_decimal($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Date') {
            echo template_form_date($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Time') {
            echo template_form_time($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'DateTime' || $meta_data['type'] == 'Datetime') {
            echo template_form_datetime($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Upload') {
            echo template_form_upload($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Tag') {
            echo template_form_select_tag($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Auto address') {
            echo template_form_auto_address($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Google map link') {
            echo template_form_google_map($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Latitude_Longtitude') {
            echo template_form_latitude_longitude($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Link') {
            echo template_form_link($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Youtube_embed') {
            echo template_form_youtube_embed($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Dropdown') {
            echo template_form_select_dropdown($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Radio') {
            echo template_form_radio($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Checkbox') {
            echo template_form_checkbox($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Switch') {
            echo template_form_switch($is_edit = true, $meta_data);
          } else {
            echo template_form_text($is_edit = true, $meta_data);
          }
          ?>
        </div>
        <div class="col-lg-5">
          <?php
          if ($meta_data['type'] == 'Upload') {
            echo template_form_condition($is_edit = true, $meta_data, $type = 'Upload');
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Auto address') {
            echo template_form_condition($is_edit = true, $meta_data, $type = 'Auto address');
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Google map link') {
            echo template_form_condition($is_edit = true, $meta_data, $type = 'Google map link');
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Link') {
            echo template_form_condition($is_edit = true, $meta_data, $type = 'Link');
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Youtube_embed') {
            echo template_form_condition($is_edit = true, $meta_data, $type = 'Youtube_embed');
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Tag') {
            echo template_form_setting_condition_tag($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Dropdown') {
            echo template_form_setting_condition_dropdown($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Radio') {
            echo template_form_setting_condition_radio($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Checkbox') {
            echo template_form_setting_condition_checkbox($is_edit = true, $meta_data);
          } else if ($meta_data['type'] == 'Varchar' && $meta_data['category'] == 'Switch') {
            echo template_form_setting_condition_switch($is_edit = true, $meta_data);
          } else {
            echo template_form_condition($is_edit = true, $meta_data, '');
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>
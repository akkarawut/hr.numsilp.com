<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
require_once '../php/setting_form.php';
W_Structure::loadMetaForAjax($prefix);
$code = isset($_GET['c']) ? $_GET['c'] : '';
$template = isset($_POST['template']) ? $_POST['template'] : '';
$get_module = Module::getModuleByCode($code);
$id = $get_module['id'];
$get_data = Extra::getExtraTemplate($code, $template);
$data = isset($get_data['meta_data'][0]['setting']) ? $get_data['meta_data'][0]['setting'] : '';
?>

<form id="form_setting_page">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">ตั้งค่าหน้ากระดาษ</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmSettingPage();">Confirm</button>
        </div>
      </div>
    </div>
    <?php if ($template != 'add_data') { ?>
      <?= template_setting_page($is_edit = true, $data); ?>
    <?php } else { ?>
      <?= template_setting_page_addpage($is_edit = true, $data); ?>
    <?php } ?>
  </div>
</form>
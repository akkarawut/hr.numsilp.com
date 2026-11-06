<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
// $data_post_json =  base64_decode($_POST['data_send'], true);
// $data_post = json_decode($data_post_json, true);
$clean = strtr($_POST['data_send'], ' ', '+');
$data_post_json  = base64_decode($clean);
$data_post = json_decode($data_post_json, true);
$rand_id = TiwForm::randomName(5);

$alignment = '';
$width = '';
$font_bold = '';
$color = '';
$star_required = '';
$color_inner = '';
$placeholder = '';
$size_input = '';
if (isset($data_post['alignment']) && $data_post['alignment'] == 'left') {
  $alignment = 'me-auto';
} else if (isset($data_post['alignment']) && $data_post['alignment'] == 'right') {
  $alignment = 'ms-auto';
}
if (isset($data_post['size_display'])  && $data_post['alignment']) {
  $width = 'width:' . $data_post['size_display'] . '%;';
}
if (isset($data_post['font_bold']) && $data_post['font_bold'] == 1) {
  $font_bold = 'font-Bold';
}
if (isset($data_post['is_required_fill']) && $data_post['color'] != '') {
  $color = 'color:' . $data_post['color'] . ';';
} else {
  $color = 'color:' . '#000' . ';';
}
if (isset($data_post['is_required_fill']) && $data_post['is_required_fill'] == 1) {
  $star_required = '<span class="text-danger">*</span>';
}
if (isset($data_post['color_inner']) && $data_post['color_inner'] != '') {
  $color_inner = 'color:' . $data_post['color_inner'] . ';';
} else {
  $color_inner = 'color:' . '#000' . ';';
}

if (isset($data_post['is_show_placeholder']) && $data_post['is_show_placeholder'] == 1) {
  $placeholder = $data_post['placeholder_text'];
}
if (isset($data_post['size_input']) && $data_post['size_input'] != '') {
  $size_input = 'width:' . $data_post['size_input'] . '%;';
}
?>

<div class="card-sortable <?= $alignment ?>" data-fill="<?= $data_post['field_id'] ?>" style="<?= $width ?>">
  <input type="hidden" name="list[<?= $rand_id ?>][dataset]" value='<?= $data_post_json ?>'>
  <div class="handle">
    <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
  </div>
  <div class="body-fill">
    <?php (isset($data_post['type_display']) && $data_post['type_display'] == 2) ? '<div class="input-reverse">' : '' ?>
    <div class="__field-name">
      <div class="<?= $font_bold ?>" style="<?= $color ?>">
        <?= (isset($data_post['input_title']) &&  $data_post['input_title']) ? $data_post['input_title'] : 'Loading' . ' ' . $star_required ?>
      </div>
    </div>
    <div class="__field-input">
      <div class="ml-20px spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <?php (isset($data_post['type_display']) && $data_post['type_display'] == 2) ? '</div>' : '' ?>
    <div class="__field-action">
      <button type="button" class="btn-action __btn--delete js-remove"></button>
      <button type="button" class="btn-action __btn--edit js-edit"></button>
    </div>
  </div>
</div>
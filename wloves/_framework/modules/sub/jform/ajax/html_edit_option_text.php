<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
require_once '../php/option.php';
$sys_function_id = isset($_POST['sys_function_id']) ? $_POST['sys_function_id'] : '';
$index = isset($_POST['index']) ? $_POST['index'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$meta_data = (json_decode($_POST['meta_data'], true));

$title_text = (isset($meta_data['title_text']) && $meta_data['title_text']) ? $meta_data['title_text'] : '';
$font_family = (isset($meta_data['font_family']) && $meta_data['font_family']) ? $meta_data['font_family'] : '';
$font_size_value = (isset($meta_data['font_size']) && $meta_data['font_size']) ? $meta_data['font_size'] : '';
$font_bold = (isset($meta_data['font_bold']) && $meta_data['font_bold']) ? $meta_data['font_bold'] : '';
$font_italic = (isset($meta_data['font_italic']) && $meta_data['font_italic']) ? $meta_data['font_italic'] : '';
$custom_color_text = (isset($meta_data['custom_color_text']) && $meta_data['custom_color_text']) ? $meta_data['custom_color_text'] : '';
$align_text = (isset($meta_data['align_text']) && $meta_data['align_text']) ? $meta_data['align_text'] : '';
$alignment_display = (isset($meta_data['alignment_display']) && $meta_data['alignment_display']) ? $meta_data['alignment_display'] : '';
$size_display_text = (isset($meta_data['size_display_text']) && $meta_data['size_display_text']) ? $meta_data['size_display_text'] : '';
?>

<?php Tiwdal::startModal('modal_edit_option_text', 'max-w-700px', ['slide' => true]); ?>
<form id="form_edit_option_text">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">แก้ไขหัววข้อความ</div>
        </div>
        <div class="btn-edit-container">
          <input type="hidden" name="type" value="text">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmEditText(<?= $index  ?>);">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-lg-12">
          <div id="scope_customize_edit_title">

            <div class="row mt-10px mb-15px">
              <div class="col-sm-12">
                <div class="font-15px fw-bold">การปรับแต่งตัวหนังสือ</div>
              </div>
            </div>

            <div class="row mb-15px">
              <div class="col-sm-4">
                <div class="font-16px text-secondary fw-semibold required">ข้อความที่แสดง</div>
              </div>
              <div class="col-sm-8">
                <?= TiwForm::normal('text', $title_text, ['id' => 'event_preview_text_edit', 'name' => 'title_text', 'required' => 'true'], []); ?>
              </div>
            </div>
            <div class="row mb-15px">
              <div class="col-sm-4">
                <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
              </div>
              <div class="col-sm-8">
                <div class="d-flex">
                  <div class="max-w-150px mx-10px w-100">
                    <?php $font_family = ['list_encode' => '[{"value":"Sarabun","name":"Sarabun"}]',]; ?>
                    <?= TiwForm::normal('select', $font_family, ['name' => 'font_family', 'class' => "input-bordered"], $font_family); ?>
                  </div>
                  <div class="max-w-100px  mx-10px w-100">
                    <?= TiwForm::normal('select', $font_size_value, ['name' => 'font_size', 'class' => "input-bordered"], $font_size); ?>
                  </div>
                  <div class="mx-10px pr-10px border-right">
                    <div class="checkbox-bold">
                      <input type="checkbox" value="1" name="font_bold" id="checkbox-title-bold_edit" onchange="previewEditText()" <?= $font_bold == 1 ? 'checked' : '' ?>>
                      <label for="checkbox-title-bold_edit">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/bold.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="mx-10px pr-10px border-right">
                    <div class="checkbox-bold">
                      <input type="checkbox" value="1" name="font_italic" id="checkbox-title-italic_edit" onchange="previewEditText()" <?= $font_italic == 1 ? 'checked' : '' ?>>
                      <label for="checkbox-title-italic_edit">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/italic.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <label class="btn-action-icon" for="custom_color_text_edit">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                      <input id="custom_color_text_edit" type="color" name="custom_color_text" value="<?= $custom_color_text ?>" class="input-color-sm" onchange="previewEditText()">
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-15px">
              <div class="col-sm-4">
                <div class="font-16px text-secondary fw-semibold">ตำแหน่งตัวอักษร</div>
              </div>
              <div class="col-sm-8">
                <div class="d-flex pr-10px mx-10px">
                  <div class="checkbox-bold mr-10px">
                    <input type="radio" value="left" name="align_text" id="checkbox-align-text-left_edit" onchange="previewEditText()" <?= $align_text == 'left' ? 'checked' : '' ?>>
                    <label for="checkbox-align-text-left_edit">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-align.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold mr-10px">
                    <input type="radio" value="center" name="align_text" id="checkbox-align-text-center_edit" onchange="previewEditText()" <?= $align_text == 'center' ? 'checked' : '' ?>>
                    <label for="checkbox-align-text-center_edit">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/align-center.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold">
                    <input type="radio" value="right" name="align_text" id="checkbox-align-text-right_edit" onchange="previewEditText()" <?= $align_text == 'right' ? 'checked' : '' ?>>
                    <label for="checkbox-align-text-right_edit">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/right-align.svg') ?>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div id="scope_customize_input">
            <div class="row">
              <div class="col-sm-12 mb-20px">
                <div class="font-15px fw-bold">การปรับแต่งการแสดงผล</div>
              </div>
            </div>
            <div class="row mb-15px">
              <div class="col-sm-4">
                <div class="font-16px text-secondary fw-semibold">ขนาดช่อง<span class="text-danger">*</span></div>
              </div>
              <div class="col-sm-8">
                <div class="max-w-150px w-100">
                  <?= TiwForm::normal('select', $meta_data['size_display_text'], ['id' => 'size_display_text', 'name' => 'size_display_text'], $size_display); ?>
                </div>
              </div>
            </div>
            <div class="row mb-15px">
              <div class="col-sm-4">
                <div class="font-16px text-secondary fw-semibold">การแสดงผล</div>
              </div>
              <div class="col-sm-8">
                <div class="d-flex pr-10px mx-10px">
                  <div class="checkbox-bold mr-10px">
                    <input type="radio" value="left" name="alignment_display" id="checkbox-align-display-left" <?= $alignment_display == 'left' ? 'checked' : '' ?>>
                    <label for="checkbox-align-display-left">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold mr-10px">
                    <input type="radio" value="center" name="alignment_display" id="checkbox-align-display-center" <?= $alignment_display == 'center' ? 'checked' : '' ?>>
                    <label for="checkbox-align-display-center">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold">
                    <input type="radio" value="right" name="alignment_display" id="checkbox-align-display-right" <?= $alignment_display == 'right' ? 'checked' : '' ?>>
                    <label for="checkbox-align-display-right">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
        </div>
      </div>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>
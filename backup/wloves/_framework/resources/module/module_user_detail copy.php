<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_module_setup'];
require_once '../../../_framework/import.php';
$code = isset($_GET['c']) ? $_GET['c'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
$page  = isset($_GET['page']) ? $_GET['page'] : 'edit_mode';

$get_module = Module::getModuleByCode($code);
$sys_function_id = $get_module['id'];

if ($_POST) {
  W_Main::responseAPI();
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  W_Main::loadAsset('assets/css/module.css');
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>


  <div class="w-card h-auto mx-10px my-10px">
    <div class="w-card-title-bar border-bottom">
      <div class="d-flex flex-wrap align-items-center">
        <div class="icon">
          <img src=" <?= W_Structure::getLinkURL('_framework/resources/module/assets/icon/form.svg') ?>" alt="">
        </div>
        <div class="font-16px fw-semibold ml-10px">ตั้งค่าหน้า : User details</div>
      </div>
      <div class="btn-edit-container">
        <a href="?c=<?= $code ?>&is_edit=1" class="btn-edit cancel min-w-150px"> Edit Mode</a>
        <a href="?c=<?= $code ?>" class="btn-edit cancel min-w-150px"> Preview mode</a>
      </div>
    </div>

    <?php
    $tools_data = [
      [
        'name' => 'ตัวหนังสือ',
        'icon' => 'font.svg',
        'action' => '',
        'modal' => '',
        'is_ajax' => false
      ],
      [
        'name' => 'ช่องป้อนข้อมูล',
        'icon' => 'forms.svg',
        'action' => '',
        'modal' => 'add_fill',
        'is_ajax' => true
      ],
      [
        'name' => 'การตกแต่งหน้ากระดาษ',
        'icon' => 'customize.svg',
        'action' => '',
        'modal' => 'customize_page',
        'is_ajax' => false
      ],
      [
        'name' => 'แทรกตาราง',
        'icon' => 'table.svg',
        'action' => '',
        'modal' => '',
        'is_ajax' => false
      ],
      [
        'name' => 'นำเข้าข้อมูล',
        'icon' => 'import.svg',
        'action' => '',
        'modal' => '',
        'is_ajax' => false
      ],
      [
        'name' => 'ตั้งค่าหน้ากระดาษ',
        'icon' => 'setting-cog.svg',
        'action' => '',
        'modal' => 'setting_page',
        'is_ajax' => false
      ],
    ]
    ?>
    <div class="container-toolbar">
      <div class="inner-toolbar">
        <?php foreach ($tools_data as $tool) : ?>
          <?php $is_ajax = $tool['is_ajax'] == true ? true : false ?>
          <div class="__tool-item event_select_tool" <?= $tool['modal'] != '' ? Tiwdal::register($tool['modal'], [], ['is_ajax' => $is_ajax]) : '' ?>>
            <div class="__icon">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/' . $tool['icon']) ?>
            </div>
            <div class="__name">
              <?= $tool['name'] ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="container-dropzone">
    <form id="scope_list_fill" class="list-fill" method="post">
    </form>
  </div>

  <?php Tiwdal::ajaxModal('add_fill', 'modal-lg', ['is_ajax' => true]); ?>

  <?php
  // Tiwdal::ajaxModal('option_add_fill', 'max-w-700px', ['is_ajax' => true, 'slide' => true]);
  ?>

  <?php Tiwdal::startModal('modal_add_option_fill', 'max-w-700px', ['slide' => true]); ?>
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">เพิ่มช่องกรอกข้อมูล</div>
        </div>
        <div class="btn-edit-container">
          <input type="hidden" value="1" name="id_item" id="event_count_item_id">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmAddFill();">Confirm</button>
        </div>
      </div>
    </div>
    <form id="form_option">
      <div class="container-fluid px-20px">
        <div id="scope_connect_data"></div>
        <hr>

        <!-- การปรับแต่งหัวข้อช่องกรอก -->
        <div id="scope_customize_topic">
          <div class="row">
            <div class="col-sm-12 mb-20px">
              <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
            </div>
            <div class="col-sm-8">
              <?= TiwForm::normal('text', '', ['id' => 'event_input_name', 'name' => ''], []); ?>
              <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex">
                <div class="max-w-100px w-100">
                  <?php $font_size_input = ['list_encode' => '[{"value":"16","name":"16 px"}]',]; ?>
                  <?= TiwForm::normal('select', '', ['id' => 'event_font_size_input', 'name' => 'font_size_input', 'class' => "input-bordered"], $font_size_input); ?>
                </div>
                <div class="ml-5px">
                  <button type="button" id="event_button_bold" data-bold="0" class="btn-action-icon">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/bold.svg') ?>
                  </button>
                </div>
                <div class="d-flex align-items-center">
                  <div id="event_input_color" class="select-color-group">
                    <div class="option-color bg-dark active" data-color="bg-dark"></div>
                    <div class="option-color bg-secondary" data-color="bg-secondary"></div>
                    <div class="option-color bg-grey" data-color="bg-grey"></div>
                  </div>
                  <label class="btn-action-icon" for="event_custom_color">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                    <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this);">
                  </label>
                  <input type="hidden" name="color">
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div id="scope_customize_input">
          <div class="row">
            <div class="col-sm-12 mb-20px">
              <div class="font-15px fw-bold">การปรับแต่งช่องกรอกข้อมูล</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ขนาดช่องกรอก<span class="text-danger">*</span></div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center">
                <div class="max-w-100px w-100">
                  <?php $size_input = ['list_encode' => '[{"value":"100","name":"100 %"}]',]; ?>
                  <?= TiwForm::normal('select', '', ['id' => 'event_input_size', 'name' => 'size_input'], $size_input); ?>
                </div>
                <div class="fonf-16px fw-medium">: 410 pixel</div>
              </div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex">
                <div class="max-w-100px w-100">
                  <?php $font_size_input = ['list_encode' => '[{"value":"16","name":"16 px"}]',]; ?>
                  <?= TiwForm::normal('select', '', ['id' => 'event_input_inner_size', 'name' => 'input_inner_size', 'class' => 'input-bordered'], $font_size_input); ?>
                </div>
                <div class="d-flex align-items-center">
                  <div id="event_input_inner_color" class="select-color-group ml-5px">
                    <div class="option-color bg-dark active" data-color="bg-dark"></div>
                    <div class="option-color bg-secondary" data-color="bg-secondary"></div>
                    <div class="option-color bg-primary" data-color="bg-primary"></div>
                  </div>
                  <label class="btn-action-icon" for="event_input_inner_color">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                    <input id="event_input_inner_color" type="color" name="" class="input-color-sm" onchange="customInputInnerColor(this)">
                  </label>
                  <input type="hidden" name="color_inner">
                </div>
              </div>
              <div class="my-15px">
                <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder'], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
              </div>

            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
            </div>
            <div class="col-sm-8">
              <?= TiwForm::normal('text', '', ['id' => 'placeholder_text', 'name' => ''], []); ?>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
            </div>
            <div class="col-sm-8">
              <label class="btn-action-icon" for="event_color_placeholder">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm">
              </label>
            </div>
          </div>
        </div>

        <hr>

        <div id="scope_condition">
          <div class="row mb-20px">
            <div class="col-sm-12">
              <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12 mb-15px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill'], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
            </div>
            <div class="col-sm-12 mb-15px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill'], ['style' => '1', 'label' => 'ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ <br>ไม่สามารถบันทึกแบบฟอร์มได้', 'is_on_off' => true]); ?>
            </div>
            <div class="col-sm-12 mb-15px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill'], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
            </div>
          </div>
        </div>
        <hr>

        <div class="scope_display">
          <div class="row mb-20px">
            <div class="col-sm-12">
              <div class="font-15px fw-bold">การแสดงผล</div>
            </div>
          </div>

          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center">
                <div class="max-w-100px w-100">
                  <?php $size_display = ['list_encode' => '[{"value":"100","name":"100 %"}]',]; ?>
                  <?= TiwForm::normal('select', '', ['name' => 'size_display'], $size_display); ?>
                </div>
                <div class="fonf-16px fw-medium">ของพื้นที่</div>
              </div>
            </div>
          </div>

          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
            </div>
            <div class="col-sm-8">
              <div id="scope_button_group_display" class="d-flex">
                <div class="mr-10px">
                  <button type="button" class="btn-action-icon secondary active" data-alignment="left">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-alignment.svg') ?>
                  </button>
                </div>
                <div>
                  <button type="button" class="btn-action-icon secondary" data-alignment="right">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/right-alignment.svg') ?>
                  </button>
                </div>
                <input type="hidden" name="position" value="left">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12 mb-15px">
              <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
            </div>
            <div class="col-sm-12 mb-15px">

              <div class="d-flex">
                <div class="mr-10px">
                  <?= TiwForm::normal('radio', '1', ['name' => 'type_display', 'checked' => 'true'], ['style' => '2']); ?>
                </div>
                <div>
                  <div class="font-16px fw-medium">
                    ซ้าย - ขวา
                    <span data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title='
                    <div class="text-start px-10px py-10px">
                      <div class="font-16px font-SemiBold">การแสดงผลแบบ ซ้าย - ขวา</div>
                      <div class="text-pre">หัวข้อช่องกรอกอยู่ด้านข้าง</div>
                      <div class="example-input mt-5px">
                        <div class="font-16px font-semibold text-dark mr-20px">หัวข้อช่องกรอก</div>
                        <div class="font-16px text-secondary border-blue input-preview">ข้อความตัวอย่าง</div>
                      </div>
                    </div>
                 '>
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/info.svg') ?>
                    </span>
                  </div>
                  <div class="text-secondary">หัวข้อช่องกรอกอยู่ด้านข้าง</div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 mb-15px">
              <div class="d-flex">
                <div class="mr-10px">
                  <?= TiwForm::normal('radio', '2', ['name' => 'type_display'], ['style' => '2']); ?>
                </div>
                <div>
                  <div class="font-16px fw-medium">
                    บน - ล่าง
                    <span data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title='
                    <div class="text-start px-10px pt-10px pb-20px">
                      <div class="font-16px font-SemiBold">การแสดงผลแบบ บน - ล่าง</div>
                      <div class="text-pre">หัวข้อช่องกรอกอยู่ด้านบน และช่องกรอกอยู่ด้านล่าง</div>
                      <div class="example-input mt-5px flex-column align-items-start">
                        <div class="font-16px font-semibold text-dark mr-20px mb-10px">หัวข้อช่องกรอก</div>
                        <div class="font-16px text-secondary border-blue input-preview text-start">ข้อความตัวอย่าง</div>
                      </div>
                    </div>
                    '>
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/info.svg') ?>
                    </span>
                  </div>
                  <div class="text-secondary">หัวข้อช่องกรอกอยู่ด้านบน และช่องกรอกอยู่ด้านล่าง</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
    </form>
  </div>
  <?php Tiwdal::endModal() ?>


  <?php Tiwdal::startModal('modal_set_image_profile', 'max-w-700px', ['slide' => true]); ?>
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">กำหนดรูปแบบรูปโปรไฟล์</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold">การปรับแต่ง</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">รูปทรง<span class="text-danger">*</span></div>
        </div>
        <div class="col-sm-8">
          <div class="mb-15px">
            <?= TiwForm::normal('radio', '', ['name' => ''], ['style' => '2', 'label' => 'วงกลม']); ?>
          </div>
          <div>
            <?= TiwForm::normal('radio', '', ['name' => ''], ['style' => '2', 'label' => 'สี่เหลี่ยมจตุรัส']); ?>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาด<span class="text-danger">*</span></div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('text', '', ['name' => ''], []); ?>
            </div>
            <div class="fonf-16px fw-medium">Pixel</div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ตำแหน่ง</div>
        </div>
        <div class="col-sm-8">
          <div id="scope_button_group_alignment" class="d-flex">
            <div class="mr-10px">
              <button type="button" class="btn-action-icon secondary active">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-alignment.svg') ?>
              </button>
            </div>
            <div class="mr-10px">
              <button type="button" class="btn-action-icon secondary">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
              </button>
            </div>
            <div>
              <button type="button" class="btn-action-icon secondary">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/right-alignment.svg') ?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">การใช้งาน</div>
        </div>
        <div class="col-sm-8">
          <?= TiwForm::normal('checkbox', '', ['name' => '', 'checked' => true], ['style' => '1', 'label' => 'เปิดใช้งาน', 'is_on_off' => true]); ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold pt-10px">รูปโปรไฟล์ตั้งต้น</div>
        </div>
        <div class="col-sm-8">
          <div><?= TiwForm::normal('file', '', ['name' => ''], []); ?></div>
          <div class="text-secondary font-15px mt-5px">
            <span class="fw-semibold">ข้อแนะนำ</span>: ไฟล์รูปภาพ .jpg หรือ .png สี่เหลี่ยมจตุรัส (1:1) <br>
            ขนาดไฟล์ไม่เกิน 25MB
          </div>
          <div class="img-preview mt-10px">
            <img src="assets/icon/User Default photo.png" alt="">
          </div>
        </div>
      </div>

      <hr>
      <div class="row mb-20px">
        <div class="col-sm-12">
          <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 mb-15px">
          <div class="d-flex align-items-center">
            <div>
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/check-primary.svg') ?>
            </div>
            <div class="text-primary ml-10px">
              จำกัดการอัปโหลดเพียง 1 รูป
            </div>
          </div>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '', ['name' => ''], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล (อัปโหลดเพื่อแก้ไขภายหลัง)', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '', ['name' => ''], ['style' => '1', 'label' => 'ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '', ['name' => ''], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
        </div>
      </div>
      <hr>
    </div>
  </div>
  <?php Tiwdal::endModal() ?>


  <?php Tiwdal::startModal('customize_page', 'max-w-700px'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-body bg-inner-modal">
    <div class="row mb-20px mt-30px">
      <div class="col-lg-12 text-center">
        <div class="font-20px fw-bold text-primary">การตกแต่งหน้ากระดาษ</div>
        <div class="font-17px fw-medium text-secondary mt-10px">เลือกรูปแบบการตกแต่งที่คุณต้องการ</div>
      </div>
    </div>
    <div class="d-flex align-items-center justify-content-center mb-30px">
      <div class="card-box mx-10px" data-bs-dismiss="modal" <?= Tiwdal::register('modal_set_line', []); ?>>
        <div class="__body">
          <div class="__icon">
            <img src="assets/images/customize-line.png" alt="">
          </div>
          <div class="__header">
            เส้น
          </div>
          <div class="__sub-text">
            วาดเส้นตรง ใช้สำหรับคั่น
            ส่วนต่าง ๆ หรือตกแต่ง
          </div>
        </div>
      </div>
      <div class="card-box mx-10px" data-bs-dismiss="modal" <?= Tiwdal::register('modal_set_image', []); ?>>
        <div class="__body">
          <div class="__icon">
            <img src="assets/images/customize-image.png" alt="">
          </div>
          <div class="__header">
            ภาพประกอบ
          </div>
          <div class="__sub-text">
            วางภาพประกอบเพื่อการ
            ตกแต่งหน้ากระดาษ
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php Tiwdal::endModal() ?>


  <?php Tiwdal::startModal('modal_set_line', 'max-w-700px'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">การตกแต่งหน้ากระดาษ</h5>
  </div>
  <div class="modal-body p-0">
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-primary">กำหนดรูปแบบเส้น</div>
        </div>
      </div>
      <div class="row mb-20px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">รุปแบบเส้น</div>
        </div>
        <div class="col-sm-8">
          <div class="font-16px fw-medium">เส้นตรง</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดเส้น</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('text', '', ['name' => ''], []); ?>
            </div>
            <div class="fonf-16px fw-medium">Pixel</div>
          </div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">สี</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex">
            <div id="scope_select_line_color" class="select-color-group">
              <div class="option-color bg-dark active" data-color="bg-dark"></div>
              <div class="option-color bg-secondary" data-color="bg-secondary"></div>
              <div class="option-color bg-grey" data-color="bg-grey"></div>
              <div class="option-color bg-light" data-color="bg-light"></div>
            </div>
            <label class="btn-action-icon" for="header_color">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
              <input id="header_color" type="color" name="color" class="input-color-sm">
            </label>
          </div>
          <div class="text-secondary font-15px mt-5px">ในกรณีที่คุณกำหนดเอง สีจะไม่เปลี่ยนเมื่อสลับธีมระบบ</div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-dark">การแสดงผล</div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?php $size_display_screen = ['list_encode' => '[{"value":"100","name":"100 %"}]',]; ?>
              <?= TiwForm::normal('select', '', ['name' => ''], $size_display_screen); ?>
            </div>
            <div class="fonf-16px fw-medium">ของพื้นที่</div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div id="scope_alignment_line" class="d-flex">
            <div class="mr-10px">
              <button type="button" class="btn-action-icon secondary active">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-alignment.svg') ?>
              </button>
            </div>
            <div class="mr-10px">
              <button type="button" class="btn-action-icon secondary">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
              </button>
            </div>
            <div>
              <button type="button" class="btn-action-icon secondary">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/right-alignment.svg') ?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
  <div class="modal-footer">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['id' => '', 'name' => '', 'type' => 'button', 'class' => 'min-w-120px'], ['text' => 'ยินยัน', 'type' => '']);
    ?>
  </div>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('modal_set_image', 'max-w-700px'); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">การตกแต่งหน้ากระดาษ</h5>
  </div>
  <div class="modal-body p-0">
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-primary">กำหนดการแสดงรูปภาพ</div>
        </div>
      </div>
      <div class="row mb-20px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">อัปโหลดรูปภาพ</div>
        </div>
        <div class="col-sm-8">
          <div>
            <?= TiwForm::normal('file', '', ['name' => ''], []); ?>
          </div>
          <div class="text-secondary font-15px">
            <span class="font-semibold">ข้อแนะนำ:</span> ไฟล์รูปภาพ .jpg หรือ .png ขนาดไฟล์ไม่เกิน 25MB <br>
            *อัปโหลดไฟล์ที่พอดีกับขนาดจอเพื่อการแสดงผลที่ดีที่สุด
          </div>
          <div class="img-preview mt-20px">
            <img src="assets/images/mock.png" alt="">
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดรูปภาพ (pixel)</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('text', '', ['name' => ''], []); ?>
              <div class="mt-5px text-secondary">กว้าง</div>
            </div>
            <div class="mx-5px">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/lock.svg') ?>
            </div>
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('text', '', ['name' => ''], []); ?>
              <div class="mt-5px text-secondary">สูง</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-dark">การแสดงผล</div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?php $size_display_screen = ['list_encode' => '[{"value":"100","name":"100 %"}]',]; ?>
              <?= TiwForm::normal('select', '', ['name' => ''], $size_display_screen); ?>
            </div>
            <div class="fonf-16px fw-medium">ของพื้นที่</div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div id="scope_alignment_image" class="d-flex">
            <div class="mr-10px">
              <button type="button" class="btn-action-icon secondary active">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-alignment.svg') ?>
              </button>
            </div>
            <div class="mr-10px">
              <button type="button" class="btn-action-icon secondary">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
              </button>
            </div>
            <div>
              <button type="button" class="btn-action-icon secondary">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/right-alignment.svg') ?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
  <div class="modal-footer">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['id' => '', 'name' => '', 'type' => 'button', 'class' => 'min-w-120px'], ['text' => 'ยินยัน', 'type' => '']);
    ?>
  </div>
  <?php Tiwdal::endModal() ?>


  <?php Tiwdal::startModal('setting_page', 'max-w-700px', ['slide' => true]); ?>
  <form id="form_setting_page">
    <div class="modal-body p-0">
      <div class="w-card h-auto">
        <div class="w-card-title-bar border-bottom">
          <div class="d-flex flex-wrap align-items-center">
            <div class="font-16px fw-semibold text-primary">ตั้งค่าหน้ากระดาษ</div>
          </div>
          <div class="btn-edit-container">
            <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
            <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px">Confirm</button>
          </div>
        </div>
      </div>

    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php
  include_once '../layout/footer.php';
  W_Main::loadAsset('../../library/sortable-js/Sortable.js');
  W_Structure::loadFooter();
  ?>

  <script>
    // init Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    var data_set = [];
    var current_id = 1;
    window.addEventListener('DOMContentLoaded', (event) => {
      getListFill();
    });

    // Sortable
    const scope_list_fill = document.getElementById('scope_list_fill');
    var sortable = Sortable.create(scope_list_fill, {
      ghostClass: "ghost",
      handle: '.handle',
      group: {
        name: 'order',
      },
      dataIdAttr: 'data-order',
      filter: ".js-remove, .js-edit",
      onFilter: function(evt) {
        var item = evt.item,
          ctrl = evt.target;

        if (Sortable.utils.is(ctrl, ".js-remove")) { // Click on remove button
          var order_item = item.getAttribute('data-order');
          data_set.splice(order_item, 1);
          deleteFill(data_set);
          item.parentNode.removeChild(item);
        }
      },
      store: {
        get: function(sortable) {
          var order = localStorage.getItem(sortable.options.group.name);
          return order ? order.split('|') : [];
        },
        set: function(sortable) {
          var order = sortable.toArray();
          localStorage.setItem(sortable.options.group.name, order.join('|'));
          console.log(order);
        },
      },
      onEnd: function( /**Event*/ evt) {
        var itemEl = evt.item; // dragged HTMLElement
        evt.to; // target list
        evt.from; // previous list
        evt.oldIndex; // element's old index within old parent
        evt.newIndex; // element's new index within new parent
        evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
        evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
        evt.clone // the clone element
        evt.pullMode; // when item is in another sortable: `"clone"` if cloning, `true` if moving
      },
    });

    // Ajax HTTP
    const sendHttpRequest = (method, url, data) => {
      const promise = new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.responseType = 'json';
        if (data) {
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        xhr.onload = () => {
          if (xhr.status >= 400) {
            reject(xhr.response);
          } else {
            resolve(xhr.response);
          }
        }
        xhr.onerror = () => {
          reject('Someting wrong');
        }
        xhr.send(data);
      });
      return promise;
    }

    // GetListFill Page
    const getListFill = () => {
      sendHttpRequest('GET', 'ajax/ajax_get_list_fill.php?c=<?= $code ?>&id=<?= $id ?>').then(responseData => {
        if (responseData.meta_data.length > 0) {
          let html = ``;
          scope_list_fill.innerHTML = '';
          let metadata = responseData.meta_data;
          // console.log(metadata);
          metadata.forEach(function(data, key) {
            let is_required = data.is_required == 1 ? '<span class="text-danger">*</span> ' : ''
            html +=
              `
            <div id="card_order_${data.id}" class="card-sortable" data-order='${key}' data-id="${data.id}" data-fill="${data.fill_id}" data-set='${JSON.stringify(data)}'>
              <input type="hidden" name="data-set">
              <div class="handle">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
              </div>
              <div class="body-fill">
                <div class="__field-name">
                  ${data.name}  ${is_required}
                </div>
                <div class="__field-input">
                  <input type="text" value="" class="form-text " name="${data.db_column_name}" id="" placeholder="${data.placeholder.is_placeholder == 1 ? data.placeholder.text : ''}" required="${data.is_required == 1 ? true : false}">
                </div>
                <div class="__field-action">
                  <button class="btn-action __btn--delete js-remove"></button>
                  <button class="btn-action __btn--edit js-edit" data-bs-toggle="modal" data-bs-target="#modal_add_option_fill" onclick=editOptionSelectField('${data.fill_id}','card_order_${data.id}')></button>
                </div>
              </div>
            </div>
            `;
          })
          scope_list_fill.insertAdjacentHTML('beforeend', html);
          data_set = responseData.meta_data;
        }
      }).catch(err => {
        console.log(err);
      })
    }

    // Select Field
    const selectUserField = (id) => {
      sendHttpRequest('GET', 'ajax/ajax_select_field.php?c=<?= $code ?>&id=' + id).then(responseData => {
        let html = ``;
        let scope_show = document.getElementById('scope_show_detail');
        scope_show.innerHTML = ''
        let data = responseData;
        html +=
          `
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-9">
              ${data.category}
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">จำกัดจำนวน<span class="text-danger">*</span></div>
            </div>
            <div class="col-9">
             -
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">DB Column Name</div>
            </div>
            <div class="col-9">
              ${data.db_column_name}
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">หมายเหตุ</div>
            </div>
            <div class="col-9">
              ${data.remark ? data.remark : '-'}
            </div>
          </div>
          `;
        scope_show.insertAdjacentHTML('beforeend', html);
        hideOptionOnSelect();
      }).catch(err => {
        console.log(err);
      })
    }

    function hideOptionOnSelect() {
      var card_sortable = document.querySelectorAll('.card-sortable');
      var fill_arr = []
      card_sortable.forEach((e) => {
        var fill_id_value = e.getAttribute('data-fill');
        fill_arr.push(fill_id_value);
      });

      fill_arr.forEach(function(data) {
        let option = event_select_field.querySelectorAll('option');
        option.forEach((opt) => {
          if (opt.value == data) {
            opt.setAttribute('hidden', 'hidden');
          }
          if (opt.value != data) {
            opt.selected = true
          }
        })
      });
    }

    const getOptionSelectField = (id) => {
      sendHttpRequest('GET', 'ajax/ajax_select_field.php?c=<?= $code ?>&id=' + id).then(responseData => {
        let html = ``;
        let scope_show = document.getElementById('scope_connect_data');
        scope_show.innerHTML = ''
        let data = responseData;
        html +=
          `
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
              <div class="font-16px text-dark fw-semibold">${data.name}</div>
              <input type="hidden" name="name">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">DB Column name</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-primary fw-semibold" id="event_db_column_name" data-db_column_name="${data.db_column_name}">${data.db_column_name}</div>
              <input type="hidden" name="db_column_name">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-dark fw-semibold" id="event_type" data-type="${data.type}">${data.type}</div>
              <input type="hidden" name="type">
            </div>
          </div>
          <input type="hidden" name="fill_id" value="${data.id}">
          `;
        scope_show.insertAdjacentHTML('beforeend', html);
      }).catch(err => {
        console.log(err);
      })
    }

    const editOptionSelectField = (id, order) => {
      sendHttpRequest('GET', 'ajax/ajax_select_field.php?c=<?= $code ?>&id=' + id).then(responseData => {
        let html = ``;
        let scope_show = document.getElementById('scope_connect_data');
        let card_order = document.getElementById(order);
        let meta_data = card_order.getAttribute('data-set');
        let order_key = card_order.getAttribute('data-id');
        scope_show.innerHTML = ''
        let data = responseData;
        html +=
          `
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
              <div class="font-16px text-dark fw-semibold">${data.name}</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">DB Column name</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-primary fw-semibold" id="event_db_column_name" data-db_column_name="${data.db_column_name}">${data.db_column_name}</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-dark fw-semibold" id="event_type" data-type="${data.type}">${data.type}</div>
            </div>
          </div>
          <input type="hidden" name="fill_id" value="${data.id}">
          `;
        scope_show.insertAdjacentHTML('beforeend', html);
        getEditData(meta_data, order_key);

      }).catch(err => {
        console.log(err);
      })
    }

    const confirmAddFill = async () => {
      const myForm = document.getElementById('form_option');
      const formData = new FormData(myForm);
      let form_data = {};
      for (const [key, value] of formData) {
        form_data[key] = value;
      }
      let form_data_string = JSON.parse(JSON.stringify(form_data));
      console.log(form_data_string);
    }

    const deleteFill = async (dataset) => {
      let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + JSON.stringify(dataset);
      sendHttpRequest('POST', 'ajax/ajax_save_page.php?c=<?= $code ?>&id=<?= $id ?>', params).then(responseData => {
        getListFill();
      }).catch(err => {
        console.log(err);
      })
    }

    selectColorTitle();
    selectColorInputInner();
    selectColorLine();

    function selectColorTitle() {
      let scope_customize_topic = document.getElementById('scope_customize_topic');
      let scope_option_color = scope_customize_topic.querySelector('.select-color-group');
      let option_select_color = scope_option_color.querySelectorAll('.option-color');
      option_select_color.forEach((e) => {
        e.addEventListener('click', function() {
          let has_active = scope_option_color.querySelectorAll('.option-color.active');
          console.log(has_active);
          has_active.forEach((current) => {
            current.classList.remove('active');
          });
          e.classList.add('active');
          let input_color = document.getElementsByName('color')[0];
          if (e.classList.contains('active')) {
            input_color.value = e.getAttribute('data-color');
            let colortext = input_color.value;
          }
        });
      })
    }

    function customInputTitleColor(t) {
      let scope_customize_topic = document.getElementById('scope_customize_topic');
      let scope_option_color = scope_customize_topic.querySelector('.select-color-group');
      let option_select_color = scope_option_color.querySelectorAll('.option-color');
      let input_custom_color = t;
      let input_color = document.getElementsByName('color')[0];
      input_color.value = t.value;
      option_select_color.forEach((e) => {
        e.classList.remove('active');
      });
    }


    function selectColorInputInner() {
      let scope_customize_input = document.getElementById('event_input_inner_color');
      let option_select_color = scope_customize_input.querySelectorAll('.option-color');
      option_select_color.forEach((e) => {
        e.addEventListener('click', function() {
          let has_active = scope_customize_input.querySelectorAll('.option-color.active');
          console.log(has_active);
          has_active.forEach((current) => {
            current.classList.remove('active');
          });
          e.classList.add('active');
          let input_color = document.getElementsByName('color_inner')[0];
          if (e.classList.contains('active')) {
            input_color.value = e.getAttribute('data-color');
            let colortext = input_color.value;
          }
        });
      })
    }

    function customInputInnerColor(t) {
      let scope_customize_input = document.getElementById('event_input_inner_color');
      let option_select_color = scope_customize_input.querySelectorAll('.option-color');
      let input_custom_color = t;
      let input_color = document.getElementsByName('color_inner')[0];
      input_color.value = t.value;
      option_select_color.forEach((e) => {
        e.classList.remove('active');
      });
    }


    async function getEditData(meta_data, order_key) {
      let str_data = meta_data;
      let json = JSON.parse(str_data);

      console.log(order_key);
      // Name
      let event_input_name = document.getElementById('event_input_name').value = json.name;
      // DB Column Name
      let event_db_column_name = document.getElementById('event_db_column_name').getAttribute('data-db_column_name');
      // Type
      let event_type = document.getElementById('event_type').getAttribute('data-type');
      // Font Size
      let font_size_input = document.getElementById('event_font_size_input').value = json.input.font_size;
      // Width Input
      let event_input_size = document.getElementById('event_input_size').value = json.input.width;
      // Inner Input Font size
      let event_input_inner_size = document.getElementById('event_input_inner_size').value = json.input.inner.font_size;
      // Placholder Text
      let event_text_placeholder = document.getElementById('event_text_placeholder').value = json.placeholder.text;

      // Selector Input Color
      let event_input_color = document.getElementById('event_input_color');
      let option_input_color = event_input_color.querySelectorAll('.option-color');
      let current_input_color = event_input_color.querySelector('.option-color.active');
      let input_color = current_input_color.getAttribute('data-color');

      // Selector Input Inner Color
      let event_input_inner_color = document.getElementById('event_input_inner_color');
      let option_input_inner_color = event_input_inner_color.querySelectorAll('.option-color');
      let current_input_inner_color = event_input_inner_color.querySelector('.option-color.active');
      let input_inner_color = event_input_inner_color.getAttribute('data-color');

      // Selector Position
      let scope_button_group_display = document.getElementById('scope_button_group_display');
      let position = scope_button_group_display.querySelector('.btn-action-icon.active').getAttribute('data-alignment');


      // Is Edit
      let is_edit_fill = document.getElementsByName('is_edit_fill')[0].checked = json.is_edit == 1 ? true : false;;
      // Is Required
      let is_required_fill = document.getElementsByName('is_required_fill')[0].checked = json.is_required == 1 ? true : false;;
      // Show List 
      let is_show_list_fill = document.getElementsByName('is_show_list_fill')[0].checked = json.is_show_list == 1 ? true : false;;
      // Show Placeholder
      let event_show_placeholder = document.getElementsByName('event_show_placeholder')[0].checked = json.placeholder.is_placeholder == 1 ? true : false;
      // Size Box
      let event_size_display = document.getElementsByName('event_size_display')[0].value = json.display_width;
      // Fill Id
      let fill_id = document.getElementsByName('fill_id')[0].value = json.fill_id;


      // Input Color
      option_input_color.forEach((opt_color) => {
        if (json.input.color != input_color) {
          let has_active = event_input_color.querySelectorAll('.option-color.active');
          has_active.forEach((current) => {
            current.classList.remove('active');
          });
        } else {
          opt_color.classList.add('active');
        }
      })

      // Inner Input Color
      option_input_inner_color.forEach((opt_inner_color) => {
        if (json.input.inner.color != input_color) {
          let has_active = event_input_inner_color.querySelectorAll('.option-color.active');
          has_active.forEach((current) => {
            current.classList.remove('active');
          });
        } else {
          opt_inner_color.classList.add('active');
        }
      })
    }

    async function convertData() {
      let event_input_name = document.getElementById('event_input_name').value;
      let event_db_column_name = document.getElementById('event_db_column_name').getAttribute('data-db_column_name');
      let event_type = document.getElementById('event_type').getAttribute('data-type');
      let font_size_input = document.getElementById('event_font_size_input').value;

      let event_input_color = document.getElementById('event_input_color');
      let event_input_size = document.getElementById('event_input_size').value;
      let event_input_inner_size = document.getElementById('event_input_inner_size').value;
      let event_text_placeholder = document.getElementById('event_text_placeholder').value;
      let input_color = event_input_color.querySelector('.option-color.active').getAttribute('data-color');

      let scope_button_group_display = document.getElementById('scope_button_group_display');
      let position = scope_button_group_display.querySelector('.btn-action-icon.active').getAttribute('data-alignment');

      let event_input_inner_color = document.getElementById('event_input_inner_color');
      let input_inner_color = event_input_inner_color.querySelector('.option-color.active').getAttribute('data-color');
      let is_edit_fill = document.getElementsByName('is_edit_fill')[0].value;
      let is_required_fill = document.getElementsByName('is_required_fill')[0].value;
      let is_show_list_fill = document.getElementsByName('is_show_list_fill')[0].value;
      let event_show_placeholder = document.getElementsByName('event_show_placeholder')[0].value;
      let event_size_display = document.getElementsByName('event_size_display')[0].value;
      let fill_id = document.getElementsByName('fill_id')[0].value;

      let data = {};
      data.input = {};
      data.input.style = {};
      data.input.inner = {};
      data.placeholder = {};
      // data.id = id_item;
      data.db_column_name = event_db_column_name
      data.type = event_type;
      data.name = event_input_name;
      data.input.font_size = font_size_input;
      data.input.color = input_color;
      data.input.style.bold = '';
      data.input.style.italic = '';
      data.input.style.underline = '';
      data.input.width = event_input_size;
      data.input.inner.font_size = event_input_inner_size;
      data.placeholder.is_placeholder = event_show_placeholder;
      data.placeholder.text = event_text_placeholder;
      data.placeholder.color = input_inner_color;
      data.is_edit = is_edit_fill;
      data.is_required = is_required_fill;
      data.is_show_list = is_show_list_fill;
      data.display_width = event_size_display;
      data.position = position;
      data.type_display = '';
      data.fill_id = fill_id;
      data_set.push(data);
      return data_set;
    }

    function formDataSet() {

    }

    function templateText(data) {
      let html = `
      <div id="card_order_${data.id}" class="card-sortable" data-order='${key}' data-id="${data.id}" data-fill="${data.fill_id}" data-set='${JSON.stringify(data)}'>
        <input type="hidden" name="dataset" value="${data}">
        <div class="handle">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-name">
            ${data.name}  ${is_required}
          </div>
          <div class="__field-input">
            <input type="text" value="" class="form-text " name="${data.db_column_name}" id="" placeholder="${data.placeholder.is_placeholder == 1 ? data.placeholder.text : ''}" required="${data.is_required == 1 ? true : false}">
          </div>
          <div class="__field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button class="btn-action __btn--edit js-edit" data-bs-toggle="modal" data-bs-target="#modal_add_option_fill" onclick=editOptionSelectField('${data.fill_id}','card_order_${data.id}')></button>
          </div>
        </div>
      </div>
      `;

    }


    function selectColorLine() {
      let scope_select_line_color = document.getElementById('scope_select_line_color');
      let option_select_color = scope_select_line_color.querySelectorAll('.option-color');
      option_select_color.forEach((e) => {
        e.addEventListener('click', function() {
          let has_active = scope_select_line_color.querySelectorAll('.option-color.active');
          console.log(has_active);
          has_active.forEach((current) => {
            current.classList.remove('active');
          });
          e.classList.add('active');
        });
      })
    }


    // Action Button
    let event_button_bold = document.getElementById('event_button_bold');
    event_button_bold.addEventListener('click', function() {
      let is_bold = event_button_bold.getAttribute('data-bold');
      if (is_bold == 1) {
        event_button_bold.classList.remove('active');
        event_button_bold.setAttribute('data-bold', '0')
      } else {
        event_button_bold.classList.add('active');
        event_button_bold.setAttribute('data-bold', '1')
      }
    });

    let event_button_group = document.getElementById('scope_button_group_display');
    let button_align = event_button_group.querySelectorAll('.btn-action-icon');
    button_align.forEach((e) => {
      e.addEventListener('click', function() {
        let has_active = event_button_group.querySelectorAll('.btn-action-icon.active');
        has_active.forEach((current) => {
          current.classList.remove('active');
        });
        e.classList.add('active');
        let position_value = e.getAttribute('data-alignment');
        let input_position = document.getElementsByName('position')[0];
        input_position.value = position_value;
      });
    })


    let event_button_group_alignment = document.getElementById('scope_button_group_alignment');
    let button_alignment = event_button_group_alignment.querySelectorAll('.btn-action-icon');
    button_alignment.forEach((e) => {
      e.addEventListener('click', function() {
        let has_active = event_button_group_alignment.querySelectorAll('.btn-action-icon.active');
        has_active.forEach((current) => {
          current.classList.remove('active');
        });
        e.classList.add('active');
      });
    })

    let scope_alignment_line = document.getElementById('scope_alignment_line');
    let button_alignment_line = scope_alignment_line.querySelectorAll('.btn-action-icon');
    button_alignment_line.forEach((e) => {
      e.addEventListener('click', function() {
        let has_active = scope_alignment_line.querySelectorAll('.btn-action-icon.active');
        has_active.forEach((current) => {
          current.classList.remove('active');
        });
        e.classList.add('active');
      });
    })


    let scope_alignment_image = document.getElementById('scope_alignment_image');
    let button_alignment_image = scope_alignment_image.querySelectorAll('.btn-action-icon');
    button_alignment_image.forEach((e) => {
      e.addEventListener('click', function() {
        let has_active = scope_alignment_image.querySelectorAll('.btn-action-icon.active');
        has_active.forEach((current) => {
          current.classList.remove('active');
        });
        e.classList.add('active');
      });
    })

    function templateText(data) {
      let id = w.tiwform.randomName(10);
      let html = `
      <div id="scope_template_text_${id}" class="card-sortable" data-fill="">
        <input type="hidden" name="type" value='text'>
        <input type="hidden" name="list[${id}][dataset]" value=''>
        <div class="handle">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
        </div>

        <div class="flex-column w-100">
          <div class="body-fill">
            <div class="__field-input">
              <input type="text" value="" class="form-text " name="input_text" id="" placeholder="">
            </div>
            <div class="__field-action">
              <button type="button" class="btn-action __btn--clone js-clone"></button>
              <button type="button" class="btn-action __btn--delete js-remove"></button>
              <button type="button" class="btn-action __btn--edit"></button>
            </div>
          </div>

          <div class="footer-fill mt-5px mb-5px">
            <div class="__action-tool">
              <div class="d-flex justify-content-between">
                <div class="__action-left d-flex align-items-center">
                  <div class="max-w-150px w-100 mr-5px">
                    <select class="form-select input-bordered" name="input_inner_size">
                      <option value="font_example">font_example</option>
                    </select>
                  </div>
                  <div class="max-w-100px w-100 mr-5px">
                    <select class="form-select input-bordered" name="input_inner_size">
                      <option value="16">16 px</option>
                    </select>
                  </div>
                  <div class="d-flex align-items-center mx-10px pr-10px border-right">
                    <label class="btn-action-icon" for="event_custom_color">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                      <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this);">
                    </label>
                  </div>
                  <div class="d-flex align-items-center mr-10px pr-10px border-right">
                    <div class="checkbox-bold">
                      <input type="checkbox" value="1" name="font_bold" id="checkbox-bold">
                      <label for="checkbox-bold">
                        <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/bold.svg') ?>
                        </div>
                      </label>
                    </div>
                    <div class="checkbox-bold">
                      <input type="checkbox" value="1" name="font_italic" id="checkbox-italic">
                      <label for="checkbox-italic">
                        <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/italic.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="d-flex">
                    <div class="checkbox-bold mr-10px">
                      <input type="radio" value="left" name="align-text" id="checkbox-align-text-left">
                      <label for="checkbox-align-text-left">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-align.svg') ?>
                        </div>
                      </label>
                    </div>
                    <div class="checkbox-bold mr-10px">
                      <input type="radio" value="center" name="align-text" id="checkbox-align-text-center">
                      <label for="checkbox-align-text-center">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/align-center.svg') ?>
                        </div>
                      </label>
                    </div>
                    <div class="checkbox-bold">
                      <input type="radio" value="right" name="align-text" id="checkbox-align-text-right">
                      <label for="checkbox-align-text-right">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/right-align.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="__action-right d-flex align-items-center">
                  <div class="d-flex pr-10px mx-10px border-right">
                    <div class="checkbox-bold mr-10px">
                      <input type="radio" value="left" name="alignment-line" id="checkbox-align-line-left">
                      <label for="checkbox-align-line-left">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                        </div>
                      </label>
                    </div>
                    <div class="checkbox-bold mr-10px">
                      <input type="radio" value="center" name="alignment-line" id="checkbox-align-line-center">
                      <label for="checkbox-align-line-center">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
                        </div>
                      </label>
                    </div>
                    <div class="checkbox-bold">
                      <input type="radio" value="right" name="alignment-line" id="checkbox-align-line-right">
                      <label for="checkbox-align-line-right">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div class="max-w-150px w-100">
                    <select class="form-select input-bordered" name="size_title_text">
                      <option value="100">100%</option>
                    </select>
                  </div>

                  <button class="btn btn-primary min-w-100px mx-5px">
                    <div class="font-16px fw-semibold">บันทึก</div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      `;
      scope_list_fill.insertAdjacentHTML('beforeend', html);
      // let json_data_form = w.serializeJSON(form_json);
      // savePage(json_data_form);
    }
  </script>
</body>

</html>
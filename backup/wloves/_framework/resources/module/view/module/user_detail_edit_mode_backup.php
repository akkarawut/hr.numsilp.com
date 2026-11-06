<div class="main-container-page">
  <?php if ($get_module_by_id['create_tab'] == 1 && $menu_name != 'add_data' && $menu_name != 'profile') : ?>
    <div class="container-create-tab">
      <div class="__header">
        <div class="font-16px fw-semibold">จัดการ Tab (หน้าเพิ่มเติม)</div>
      </div>
      <button type="button" class="btn btn-primary w-100 border-radius-0" <?= Tiwdal::register('modal_add_tab', []); ?>>
        <div class="font-16px fw-semibold">+ เพิ่ม Tab ใหม่</div>
      </button>
      <div class="__body">
        <div class="font-15px fw-bold text-primary mb-10px">รายการ Tab</div>
        <div id="scope_list_tab" class="list_tab">
          <?php if (count($tab_data) > 0) : ?>
            <?php foreach ($tab_data as $key_tab => $tab) : ?>
              <div class="card-list-tab <?= $tab['id'] == $tab_id ? "active" : '' ?>">
                <div class="__handle-tab">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
                </div>
                <a href="module_user_detail.php?c=<?= $code ?>&id=<?= $sys_function_id ?>&menu=user_detail&tab=<?= $tab['id'] ?>" class="__menu-name">
                  <div class="text-dark"><?= $tab['name'] ?></div>
                </a>
                <div class="__menu-action">
                  <button class="btn-action __btn--edit" <?= Tiwdal::register('modal_edit_tab', $tab); ?>></button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="font-18px fw-bold text-danger">ยังไม่มี Tab</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="container-dropzone">
    <form id="form_json" method="post">
      <div id="scope_list_fill" class="list-fill"></div>
    </form>
  </div>
</div>

<div id="scope_modal_edit_option"></div>
<div id="scope_modal_edit_text"></div>
<div id="scope_modal_edit_line"></div>
<div id="scope_modal_edit_image"></div>

<?php Tiwdal::ajaxModal('add_fill', 'modal-lg', ['is_ajax' => true]); ?>
<?php Tiwdal::ajaxModal('page_setting', 'max-w-700px', ['is_ajax' => true, 'slide' => true]); ?>

<?php Tiwdal::startModal('modal_add_option_fill', 'max-w-1200px', ['slide' => true]); ?>
<form id="form_option">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">เพิ่มช่องกรอกข้อมูล</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" class="btn-edit save min-w-150px" onclick="confirmAddFill();">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-lg-7">
          <div id="scope_connect_data"></div>
          <hr>
          <div id="scope_show_template">
            <?= template_form_datetime(); ?>
          </div>
          <hr>
        </div>
        <div class="col-lg-5">
          <?= template_form_condition(); ?>
        </div>
      </div>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('modal_add_text', 'max-w-700px', ['slide' => true]); ?>
<form id="form_option_text">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary">เพิ่มหัวข้อความ</div>
        </div>
        <div class="btn-edit-container">
          <input type="hidden" name="type" value="text">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmAddText();">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-lg-12">
          <div id="scope_customize_title">

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
                <?= TiwForm::normal('text', '', ['id' => 'event_preview_text', 'name' => 'title_text', 'required' => 'true'], []); ?>
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
                    <?= TiwForm::normal('select', '', ['name' => 'font_family', 'class' => "input-bordered"], $font_family); ?>
                  </div>
                  <div class="max-w-100px  mx-10px w-100">
                    <?= TiwForm::normal('select', '16', ['name' => 'font_size', 'class' => "input-bordered"], $font_size); ?>
                  </div>
                  <div class="mx-10px pr-10px border-right">
                    <div class="checkbox-bold">
                      <input type="checkbox" value="1" name="font_bold" id="checkbox-title-bold" onchange="previewText()">
                      <label for="checkbox-title-bold">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/bold.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="mx-10px pr-10px border-right">
                    <div class="checkbox-bold">
                      <input type="checkbox" value="1" name="font_italic" id="checkbox-title-italic" onchange="previewText()">
                      <label for="checkbox-title-italic">
                        <div class="checkbox-img ">
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/italic.svg') ?>
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <label class="btn-action-icon" for="custom_color_text">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                      <input id="custom_color_text" type="color" name="custom_color_text" class="input-color-sm" onchange="previewText()">
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
                    <input type="radio" value="left" name="align_text" id="checkbox-align-text-left" checked onchange="previewText()">
                    <label for="checkbox-align-text-left">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/left-align.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold mr-10px">
                    <input type="radio" value="center" name="align_text" id="checkbox-align-text-center" onchange="previewText()">
                    <label for="checkbox-align-text-center">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/align-center.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold">
                    <input type="radio" value="right" name="align_text" id="checkbox-align-text-right" onchange="previewText()">
                    <label for="checkbox-align-text-right">
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
                  <?= TiwForm::normal('select', '100', ['id' => 'size_display_text', 'name' => 'size_display_text'], $size_display); ?>
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
                    <input type="radio" value="left" name="alignment_display" id="checkbox-align-display-left" checked>
                    <label for="checkbox-align-display-left">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold mr-10px">
                    <input type="radio" value="center" name="alignment_display" id="checkbox-align-display-center">
                    <label for="checkbox-align-display-center">
                      <div class="checkbox-img ">
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
                      </div>
                    </label>
                  </div>
                  <div class="checkbox-bold">
                    <input type="radio" value="right" name="alignment_display" id="checkbox-align-display-right">
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
<form id="form_option_line">
  <input type="hidden" name="type" value="line">
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
              <?= TiwForm::normal('number', '', ['name' => 'size_line'], []); ?>
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
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_line" id="radio-info--line">
              <label for="radio-info--line" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_line" id="radio-secondary--line">
              <label for="radio-secondary--line" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_line" id="radio-primary--line">
              <label for="radio-primary--line" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color_line" class="input-color-sm" onchange="customInputTitleColor(this);">
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
              <?= TiwForm::normal('select', '100', ['name' => 'size_display_line'], $size_display); ?>
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
          <div class="d-flex">
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="left" name="alignment_line" id="checkbox-align-line-left">
              <label for="checkbox-align-line-left">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="center" name="alignment_line" id="checkbox-align-line-center">
              <label for="checkbox-align-line-center">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold">
              <input type="radio" value="right" name="alignment_line" id="checkbox-align-line-right">
              <label for="checkbox-align-line-right">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                </div>
              </label>
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
    TiwForm::normal('btn', '', ['id' => '', 'name' => '', 'type' => 'button', 'class' => 'min-w-120px', 'data-bs-dismiss' => 'modal', 'onclick' => 'confirmAddLine();'], ['text' => 'ยินยัน', 'type' => '']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('modal_set_image', 'max-w-700px'); ?>
<form id="form_set_image" method="post" enctype="multipart/form-data">
  <input type="hidden" name="type" value="image">
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
            <?= TiwForm::normal('file', '', ['id' => 'event_upload_file', 'name' => 'image', 'onchange' => "uploadImage(event,'event_show_preview_img')"], []); ?>
          </div>
          <div class="text-secondary font-15px">
            <span class="font-semibold">ข้อแนะนำ:</span> ไฟล์รูปภาพ .jpg หรือ .png ขนาดไฟล์ไม่เกิน 25MB <br>
            *อัปโหลดไฟล์ที่พอดีกับขนาดจอเพื่อการแสดงผลที่ดีที่สุด
          </div>

          <div id="event_show_preview_img" class="img-preview">
            <img src="" alt="" class="mt-20px mw-100">
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
              <?= TiwForm::normal('number', '', ['name' => 'img_width'], []); ?>
              <div class="mt-5px text-secondary">กว้าง</div>
            </div>
            <div class="mx-5px">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/lock.svg') ?>
            </div>
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('number', '', ['name' => 'img_height'], []); ?>
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
              <?= TiwForm::normal('select', '', ['name' => 'size_display_image'], $size_display); ?>
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
          <div class="d-flex">
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="left" name="alignment_img" id="checkbox-align-image--left" checked>
              <label for="checkbox-align-image--left">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="center" name="alignment_img" id="checkbox-align-image--center">
              <label for="checkbox-align-image--center">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold">
              <input type="radio" value="right" name="alignment_img" id="checkbox-align-image--right">
              <label for="checkbox-align-image--right">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                </div>
              </label>
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
    TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_set_image_page', 'type' => 'button', 'class' => 'min-w-120px', 'onclick' => "confirmImage();"], ['text' => 'ยินยัน', 'type' => '']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>


<?php Tiwdal::startModal('setting_page_test', 'max-w-700px', ['slide' => true]); ?>
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
    <?= template_setting_page(); ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('modal_import', 'max-w-600px'); ?>
<form id="form_import">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">นำเข้าข้อมูล</h5>
  </div>
  <div class="modal-body px-0">
    <div class="row mb-20px px-20px">
      <div class="col-12">
        <div class="font-16px fw-semibold text-primary">การนำเข้าข้อมูล</div>
        <div class="font-15px text-secondary">คุณสามารถนำเข้าข้อมูลที่ปรับแต่งไว้จากหน้าอื่น เข้ามายังหน้านี้ได้</div>
      </div>
    </div>
    <div id="scope_event_click_import" class="d-flex justify-content-center px-20px mb-20px">
      <div class="card-list-select mx-10px active" data-type="import_all" onclick="selectTypeImport(this)">
        <div class="__img">
          <img src="assets/images/import-1.png" alt="">
        </div>
        <div class="__title">
          นำเข้าข้อมูลทั้งหมด
        </div>
        <div class="__des">
          นำเข้าข้อมูลทั้งหมดจาก
          Template อื่น
        </div>
      </div>
      <div class="card-list-select mx-10px" data-type="import" onclick="selectTypeImport(this)">
        <div class="__img">
          <img src="assets/images/import-2.png" alt="">
        </div>
        <div class="__title">
          เจาะจงการนำเข้าข้อมูล
        </div>
        <div class="__des">
          เลือกนำเข้าข้อมูล 1 อย่าง
          จาก Template อื่น
        </div>
      </div>
    </div>
    <hr>
    <div id="scope_show_form_import">
      <?= template_form_import_all(); ?>
    </div>
  </div>
  <div class="modal-footer">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['id' => '', 'name' => '', 'type' => 'button', 'class' => 'min-w-120px'], ['text' => 'นำเข้า', 'type' => '']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<!-- Modal Create Tab -->
<?php Tiwdal::startModal('modal_add_tab', 'max-w-700px'); ?>
<form id="form_add_tab" method="post">
  <input type="hidden" name="type" value="line">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">เพิ่ม Tab ใหม่</h5>
  </div>
  <div class="modal-body p-0">
    <div class="container-fluid px-20px">
      <div class="row mt-10px mb-20px">
        <div class="col-lg-12">
          <div class="font-15px fw-bold text-primary">รายละเอียด Tab</div>
        </div>
      </div>
      <div class="row mb-15px align-items-center">
        <div class="col-sm-4">
          <div class="font-16px fw-semibold text-secondary required">ชื่อ Tab</div>
        </div>
        <div class="col-sm-8">
          <?= TiwForm::normal('text', '', ['name' => 'name_tab', 'required' => 'true'], []); ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px fw-semibold text-secondary">หมายเหตุ</div>
        </div>
        <div class="col-sm-8">
          <?= TiwForm::normal('textarea', '', ['name' => 'remark_tab', 'placeholder' => 'กรอกหากมี', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="sys_id" value="<?= $sys_function_id ?>">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_add_tab', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'เพิ่ม', 'type' => '']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('modal_edit_tab', 'max-w-700px'); ?>
<form id="form_edit_tab" method="post">
  <input type="hidden" name="type" value="line">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">ตั้งค่า Tab</h5>
  </div>
  <div class="modal-body p-0">
    <div class="container-fluid px-20px">
      <div class="row mt-10px mb-20px">
        <div class="col-lg-12">
          <div class="font-15px fw-bold text-primary">รายละเอียด Tab</div>
        </div>
      </div>
      <div class="row mb-15px align-items-center">
        <div class="col-sm-4">
          <div class="font-16px fw-semibold text-secondary required">ชื่อ Tab</div>
        </div>
        <div class="col-sm-8">
          <?= TiwForm::normal('text', '', ['name' => '{name}', 'required' => 'true'], []); ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px fw-semibold text-secondary">หมายเหตุ</div>
        </div>
        <div class="col-sm-8">
          <?= TiwForm::normal('textarea', '', ['name' => '{remark}', 'placeholder' => 'กรอกหากมี', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <input type="hidden" name="{sys_function_id}" value="<?= $sys_function_id ?>">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_edit_tab', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'เพิ่ม', 'type' => '']);
    ?>
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

  window.addEventListener('DOMContentLoaded', (event) => {
    getListFill();
  });

  const form_json = document.getElementById('form_json');
  let test = w.serializeJSON(form_json);
  console.log('start', test);
  const scope_list_fill = document.getElementById('scope_list_fill');

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
  const getListFill = async () => {
    sendHttpRequest('GET', 'ajax/ajax_get_list_fill.php?c=<?= $code ?>&tab=<?= $tab_id ?>&sys_function_id=<?= $sys_function_id ?>&menu_name=<?= $menu_name ?>').then(responseData => {
      if (responseData.list) {
        let html = ``;
        scope_list_fill.innerHTML = '';
        let list = responseData.list.meta_data;
        let tab_setting = responseData.tab_setting;
        list.forEach(function(data, inx) {
          let id = w.tiwform.randomName(10);
          let file_path = responseData.list.file_path[inx];
          let type = '';
          if (data.type) {
            type = data.type;
          } else {
            type = data.option_file.type;
          }
          if (type == 'line') {
            let alignment_line = data.alignment_line ?? data.alignment_line;
            if (alignment_line == 'left') {
              alignment_line = 'me-auto';
            } else if (alignment_line == 'center') {
              alignment_line = 'mx-auto';
            } else if (alignment_line == 'right') {
              alignment_line = 'ms-auto';
            } else {
              alignment_line = '';
            }
            html +=
              `     
                <div id="scope_list_${inx}" class="card-sortable ${alignment_line}" style="width:${data.size_display_line ? data.size_display_line+'%' : ''};">
                  <input type="hidden" class="event_get_dataset" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
                  <div class="handle">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
                  </div>
                  <div class="body-fill">
                    <div class="__field-input">
                      <div style="background-color:${data.color_line};height:${data.size_line}px;"></div>
                    </div>
                    <div class="__field-action">
                      <button type="button" class="btn-action __btn--clone js-clone" onclick="cloneItem('scope_list_${inx}','line');"></button>
                      <button type="button" class="btn-action __btn--delete js-remove"></button>
                      <button type="button" class="btn-action __btn--edit js-edit" onclick="editLine('scope_list_${inx}','${inx}')"></button>
                    </div>
                  </div>
                </div>
              `;
          } else if (type == 'text') {
            let align_text = data.align_text ?? data.align_text;
            let color = data.custom_color_text ?? data.custom_color_text;
            let font_size = data.font_size ?? data.font_size;
            let font_family = data.font_family ?? data.font_family;
            let size_display_text = data.size_display_text ?? data.size_display_text;
            let alignment_display = data.alignment_display ?? data.alignment_display;
            if (alignment_display == 'left') {
              alignment_display = 'me-auto';
            } else if (alignment_display == 'center') {
              alignment_display = 'mx-auto';
            } else if (alignment_display == 'right') {
              alignment_display = 'ms-auto';
            } else {
              alignment_display = '';
            }

            let size = '';
            if (tab_setting.page_col == 1) {
              size = '100%'
            } else {
              size = data.size_display_text ? data.size_display_text + '%' : '';
            }

            html += `
                <div id="scope_list_${inx}" class="card-sortable ${alignment_display}" style="width:${size}" class="${alignment_display}">
                  <input type="hidden"  class="event_get_dataset" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
                  <div class="handle">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
                  </div>
                  <div class="body-fill">
                    <div class="__field-input">
                      <div style="color:${color};text-align:${align_text};font-size:${font_size}px;font-family:${font_family};" class="${data.font_bold && data.font_bold == 1 ? 'fw-bold' : ''} ${data.font_italic && data.font_italic == 1 ? 'fst-italic' : ''}">${data.title_text}</div>
                    </div>
                    <div class="__field-action">
                      <button type="button" class="btn-action __btn--clone js-clone" onclick="cloneItem('scope_list_${inx}','text');"></button>
                      <button type="button" class="btn-action __btn--delete js-remove"></button>
                      <button type="button" class="btn-action __btn--edit js-edit" onclick="editText('scope_list_${inx}','${inx}')"></button>
                    </div>
                  </div>
                </div>`;
          } else if (type == 'image') {
            let alignment_img = data.option_file.alignment_img ?? data.option_file.alignment_img;
            if (alignment_img == 'left') {
              alignment_img = 'me-auto';
            } else if (alignment_img == 'center') {
              alignment_img = 'mx-auto';
            } else if (alignment_img == 'right') {
              alignment_img = 'ms-auto';
            } else {
              alignment_img = '';
            }

            let size = '';
            if (tab_setting.page_col == 1) {
              size = '100%'
            } else {
              size = data.option_file.size_display_image ? data.option_file.size_display_image + '%' : '';
            }
            html += `
              <div id="scope_list_${inx}" class="card-sortable" style="width:${size};">
                <div class="handle">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
                </div>  
                <div class="flex-column w-100">
                  <div class="${alignment_img}" style="max-width:100%;width:${data.option_file.img_width}px;height:${data.option_file.img_height}px;">
                    <input type="hidden" class="event_get_dataset" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
                    <img src="${file_path}" class="w-100 h-100">
                  </div>  
                  <div class="__field-action d-flex justify-content-center text-align-center">
                    <button type="button" class="btn-action __btn--clone js-clone" onclick="cloneItem('scope_list_${inx}','image');"></button>
                    <button type="button" class="btn-action __btn--delete js-remove"></button>
                    <button type="button" class="btn-action __btn--edit js-edit" onclick="editImage('scope_list_${inx}','${inx}')"></button>
                  </div>
                </div>
              </div>
              `;
          } else {
            let is_required = data.is_required_fill && data.is_required_fill == 1 ? '<span class="text-danger">*</span> ' : ''
            let alignment = data.alignment ?? data.alignment;
            if (alignment == 'left') {
              alignment = 'me-auto';
            } else if (alignment == 'right') {
              alignment = 'ms-auto';
            } else {
              alignment = '';
            }

            let size = '';
            if (tab_setting.page_col == 1) {
              size = '100%'
            } else {
              size = data.size_display ? data.size_display + '%' : '';
            }

            let spacing = '';
            if (tab_setting.page_spacing) {
              spacing = tab_setting.page_spacing + 'px';
            }

            let input = '';
            if (type == 'Int' || type == 'Decimal') {
              input = ` <input type="text" value="" style="width:${data.size_input ? data.size_input + 'px' : ''};color:${data.color_inner};" class="form-text " name="${data.db_column_name}" id="" placeholder="${data.is_show_placeholder == 1 ? data.placeholder_text : ''}" readonly>`;
            } else if (type == 'DateTime') {
              let size_input_data = data.size_input_date ?? data.size_input_date;
              let size_input_time = data.size_input_time ?? data.size_input_time;
              input = ` 
                    <div class="d-flex">
                      <div style="width:${size_input_data}px">
                      <?= TiwForm::normal('date', '', ['name' => '', 'readonly' => 'true', 'disabled' => 'true'], []); ?>
                      </div>
                      <div style="width:${size_input_time}px">
                        <?= TiwForm::normal('time', '', ['name' => '', 'readonly' => 'true', 'disabled' => 'true'], []); ?>
                      </div>
                    </div>
                      `;
            } else if (type == 'Time') {
              let size_input_time = data.size_input_time ?? data.size_input_time;
              input = ` 
                    <div class="d-flex">
                      <div style="width:${size_input_time}px">
                        <?= TiwForm::normal('time', '', ['readonly' => 'true', 'disabled' => 'true'], []); ?>
                      </div>
                    </div>
                      `;
            } else if (type == 'Date') {
              let size_input_date = data.size_input_date ?? data.size_input_date;
              input = ` 
                    <div class="d-flex">
                      <div style="width:${size_input_date}px">
                        <?= TiwForm::normal('date', '', ['readonly' => 'true', 'disabled' => 'true'], []); ?>
                      </div>
                    </div>
                      `;
            } else if (type == 'Upload') {
              let size_input = data.size_input ?? data.size_input;
              input = ` 
                      <div style="width:${size_input}px">
                        <?= TiwForm::normal('file', '', ['disabled' => 'true'], []); ?>
                      </div>`;
              if (data.is_show_advice == 1) {
                input += `<div class="font-15px">${data.advice}</div>`;
              }
            } else {
              input = ` <input type="number" min="${data.min}" max="${data.max}" value="" style="width:${data.size_input ?? data.size_input +'%'};color:${data.color_inner};" class="form-text " name="${data.db_column_name}" id="" placeholder="${data.is_show_placeholder == 1 ? data.placeholder_text : ''}" readonly>`;
            }

            html += `
              <div id="scope_list_${inx}" class="card-sortable ${alignment}" data-fill="${data.fill_id}" style="width:${size};">
                <input type="hidden" class="event_get_dataset" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
                <div class="handle">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
                </div>
                <div class="body-fill">
                  ${data.type_display == 2 ? '<div class="input-reverse">'   : ''} 
                    <div class="__field-name" style="width:${spacing};">
                     <div class="${data.font_bold ?? data.font_bold == 1 ? 'fw-bold' : ''}" style="color:${data.color};"> ${data.input_title}  ${is_required}</div>
                    </div>
                    <div class="__field-input">
                      ${input}
                    </div>
                  ${data.type_display == 2 ? '</div>'   : ''} 
                  <div class="__field-action">
                    <button class="btn-action __btn--delete js-remove"></button>
                    <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_${inx}','${inx}')"></button>
                  </div>
                </div>
              </div>
              `;
          }
        })
        scope_list_fill.insertAdjacentHTML('beforeend', html);
        let json_data_form = w.serializeJSON(form_json);

      }
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
    if (document.forms["form_option"]["input_title"].value != '') {
      let myModal = document.getElementById('modal_add_option_fill');
      let modal = bootstrap.Modal.getInstance(myModal)
      modal.hide();
      templateFormFill(form_data_string);
    }
  }

  const confirmAddLine = async () => {
    const myForm = document.getElementById('form_option_line');
    const formData = new FormData(myForm);
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    let form_data_string = JSON.parse(JSON.stringify(form_data));
    templateLine(form_data_string);
  }

  const confirmAddText = async () => {
    const myForm = document.getElementById('form_option_text');
    const formData = new FormData(myForm);
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    let form_data_string = JSON.parse(JSON.stringify(form_data));
    templateText(form_data_string);
  }


  const confirmImage = async () => {
    const myForm = document.getElementById('form_set_image');
    let formData = new FormData(myForm);
    let myFileInput = document.getElementById('event_upload_file');
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    formData.append("image", myFileInput.files[0]);
    let form_data_string = JSON.stringify(form_data);
    let url = 'ajax/ajax_upload_image.php?c=<?= $code ?>';
    wx.formData(url, 'form_set_image', function(res) {
      let data_json = JSON.parse(res);
      templateImage(data_json);
    });
  }

  const confirmEditImage = async (old_img, inx) => {
    const myForm = document.getElementById('form_edit_set_image');
    let formData = new FormData(myForm);
    let myFileInput = document.getElementById('event_edit_file');
    let url = 'ajax/ajax_upload_image.php?c=<?= $code ?>&is_edit=1&unique_name=' + old_img + '&index=' + inx + '&menu_name=<?= $menu_name ?>';
    wx.formData(url, 'form_edit_set_image', function(res) {
      let json_data_form = w.serializeJSON(form_json);
      saveEditPage(json_data_form, res, inx);
    });
  }

  const editImage = (ele, inx) => {
    let scope = document.getElementById(ele);
    let event_get_dataset = scope.querySelector('.event_get_dataset');
    let data = event_get_dataset.value;
    let json_data = JSON.parse(data);
    let scope_modal_edit_image = document.getElementById('scope_modal_edit_image');
    let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + data + '&index=' + inx;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/html_edit_image.php?c=<?= $code ?>', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      if (this.status == 200) {
        scope_modal_edit_image.innerHTML = `${this.response}`;
        let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_set_image'));
        modal_edit_option_fill.show();
        let scope_edit_file = document.getElementById('scope_edit_file');
        let btn_delete_file = scope_edit_file.querySelector('.delete_normal_file_event');
        let myFileInput = document.getElementById('event_edit_file');
        btn_delete_file.addEventListener('click', function() {
          scope_edit_file.querySelector('.form-file').classList.remove('active');
          document.getElementById('event_show_preview_img').querySelector('img').src = '';
        });
      }
    };
    xhr.send(params);
  }



  // Event Edit Option Fill Form
  const confirmEditFill = async (inx) => {
    const myForm = document.getElementById('form_edit_option');
    const formData = new FormData(myForm);
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    let edit_data = JSON.stringify(form_data);
    let json_data_form = w.serializeJSON(form_json);
    saveEditPage(json_data_form, edit_data, inx);
  }

  const editFill = (ele, inx) => {
    let scope = document.getElementById(ele);
    let event_get_dataset = scope.querySelector('.event_get_dataset');
    let data = event_get_dataset.value;
    let json_data = JSON.parse(data);
    let scope_modal_edit_option = document.getElementById('scope_modal_edit_option');

    let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + data + '&index=' + inx;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/html_edit_option_fill.php?c=<?= $code ?>', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      if (this.status == 200) {
        scope_modal_edit_option.innerHTML = `${this.response}`;
        let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_option_fill'));
        modal_edit_option_fill.show();
      }
    };
    xhr.send(params);
  }


  // Event Edit Option Text
  const confirmEditText = async (inx) => {
    const myForm = document.getElementById('form_edit_option_text');
    const formData = new FormData(myForm);
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    let edit_data = JSON.stringify(form_data);
    let json_data_form = w.serializeJSON(form_json);
    saveEditPage(json_data_form, edit_data, inx);
  }

  const editText = (ele, inx) => {
    let scope = document.getElementById(ele);
    let event_get_dataset = scope.querySelector('.event_get_dataset');
    let data = event_get_dataset.value;
    let json_data = JSON.parse(data);
    let scope_modal_edit_text = document.getElementById('scope_modal_edit_text');
    let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + data + '&index=' + inx;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/html_edit_option_text.php?c=<?= $code ?>', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      if (this.status == 200) {
        scope_modal_edit_text.innerHTML = `${this.response}`;
        let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_option_text'));
        modal_edit_option_fill.show();
        previewEditText();
      }
    };
    xhr.send(params);
  }


  // Event Edit Option line
  const confirmEditLine = async (inx) => {
    const myForm = document.getElementById('form_edit_option_line');
    const formData = new FormData(myForm);
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    let edit_data = JSON.stringify(form_data);
    let json_data_form = w.serializeJSON(form_json);
    saveEditPage(json_data_form, edit_data, inx);
  }

  const editLine = (ele, inx) => {
    let scope = document.getElementById(ele);
    let event_get_dataset = scope.querySelector('.event_get_dataset');
    let data = event_get_dataset.value;
    let json_data = JSON.parse(data);
    let scope_modal_edit_line = document.getElementById('scope_modal_edit_line');
    let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + data + '&index=' + inx;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/html_edit_option_line.php?c=<?= $code ?>', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      if (this.status == 200) {
        scope_modal_edit_line.innerHTML = `${this.response}`;
        let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_option_line'));
        modal_edit_option_fill.show();
      }
    };
    xhr.send(params);
  }

  // Template
  function templateFormFill(data) {
    let id = w.tiwform.randomName(10);
    let alignment = data.alignment ?? data.alignment;
    if (alignment == 'left') {
      alignment = 'me-auto';
    } else if (alignment == 'right') {
      alignment = 'ms-auto';
    } else {
      alignment = '';
    }
    let html = `
      <div class="card-sortable ${alignment}" data-fill="${data.fill_id}" style="width:${data.size_display ?? data.size_display}%;">
        <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          ${data.type_display == 2 ? '<div class="input-reverse">'   : ''} 
            <div class="__field-name">
              <div ${data.font_bold ?? data.font_bold == 1 ? 'fw-bold' : ''} style="color:${data.color};">${data.input_title} ${data.is_required_fill ? '<span class="text-danger">*</span>' : ''}</div>
            </div>
            <div class="__field-input">
              <input type="text" style="width:${data.size_input ?? data.size_input+'%'};color:${data.color_inner};" value="" class="form-text " name="list[${id}][input]" id="" placeholder="${data.is_show_placeholder == 1 ? data.placeholder_text : ''}">
            </div>
            ${data.type_display == 2 ? '</div>'   : ''} 
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit"></button>
          </div>
        </ฝdiv>
      </div>
      `;
    scope_list_fill.insertAdjacentHTML('beforeend', html);
    let json_data_form = w.serializeJSON(form_json);
    savePage(json_data_form);
  }

  function templateLine(data) {
    let id = w.tiwform.randomName(10);
    let html = `
      <div class="card-sortable">
        <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            <div style="width:${data.size_display_line}%;background-color:${data.color_line};height:${data.size_line}px;"></div>
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit"></button>
          </div>
        </div>
      </div>
      `;
    scope_list_fill.insertAdjacentHTML('beforeend', html);
    let json_data_form = w.serializeJSON(form_json);
    savePage(json_data_form);
  }

  function templateText(data) {
    let id = w.tiwform.randomName(10);
    let align_text = data.align_text ?? data.align_text;
    let color = data.custom_color_text ?? data.custom_color_text;
    let font_size = data.font_size ?? data.font_size;
    let font_family = data.font_family ?? data.font_family;
    let size_display_text = data.size_display_text ?? data.size_display_text;
    let alignment_display = data.alignment_display ?? data.alignment_display;
    if (alignment_display == 'left') {
      alignment_display = 'me-auto';
    } else if (alignment_display == 'center') {
      alignment_display = 'mx-auto';
    } else if (alignment_display == 'right') {
      alignment_display = 'ms-auto';
    } else {
      alignment_display = '';
    }

    let html = `
      <div class="card-sortable ${alignment_display}" style="width:${size_display_text}%">
        <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/resources/module/assets/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            <div style="color:${color};text-align:${align_text};font-size:${font_size}px;font-family:${font_family};" class="${data.font_bold && data.font_bold == 1 ? 'fw-bold' : ''} ${data.font_italic && data.font_italic == 1 ? 'fst-italic' : ''}">${data.title_text}</div>
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit"></button>
          </div>
        </div>
      </div>
      `;
    scope_list_fill.insertAdjacentHTML('beforeend', html);
    let json_data_form = w.serializeJSON(form_json);
    savePage(json_data_form);
  }

  function templateImage(data) {
    console.log(data.option_file);
    let params = 'file=' + data.file;

    sendHttpRequest('POST', 'ajax/ajax_get_file.php?c=<?= $code ?>&tab=<?= $tab_id ?>', params).then(responseData => {
      let id = w.tiwform.randomName(10);
      let alignment_img = data.option_file.alignment_img ?? data.option_file.alignment_img;
      if (alignment_img == 'left') {
        alignment_img = 'me-auto';
      } else if (alignment_img == 'center') {
        alignment_img = 'mx-auto';
      } else if (alignment_img == 'right') {
        alignment_img = 'ms-auto';
      } else {
        alignment_img = '';
      }
      let html = `
        <div style="width:${data.option_file.size_display_image}%;">
          <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
          <img src="${responseData.unique_name}" style="max-width:100%;width:${data.option_file.img_width}px;height:${data.option_file.img_height}px;">
        </div>
        `;
      scope_list_fill.insertAdjacentHTML('beforeend', html);
      let json_data_form = w.serializeJSON(form_json);
      savePage(json_data_form);
    }).catch(err => {
      console.log(err);
    })
  }


  function cloneItem(ele, type) {
    let scope = document.getElementById(ele);
    let data = scope.querySelector('.event_get_dataset').value;
    let json_data = JSON.parse(data);
    if (type == 'text') {
      templateText(json_data);
    } else if (type == 'line') {
      templateLine(json_data);
    } else if (type == 'image') {
      templateImage(json_data);
    }
  }

  const savePage = async (dataset) => {
    let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + dataset;
    console.log(params);
    sendHttpRequest('POST', 'ajax/ajax_save_page.php?c=<?= $code ?>&tab=<?= $tab_id ?>&menu_name=<?= $menu_name ?>', params).then(responseData => {
      setTimeout(() => {
        getListFill();
      }, 1000);
    }).catch(err => {
      console.log(err);
    })
  }

  const saveEditPage = async (dataset, edit_data, inx) => {
    let params = 'sys_function_id=<?= $sys_function_id ?>&meta_data=' + dataset + '&index=' + inx + '&edit_data=' + edit_data;
    sendHttpRequest('POST', 'ajax/ajax_save_page.php?c=<?= $code ?>&tab=<?= $tab_id ?>&is_edit=1&menu_name=<?= $menu_name ?>', params).then(responseData => {
      getListFill();
    }).catch(err => {
      console.log(err);
    })
  }


  function confirmSettingPage() {
    const myForm = document.getElementById('form_setting_page');
    const formData = new FormData(myForm);
    let form_data = {};
    for (const [key, value] of formData) {
      form_data[key] = value;
    }
    let data_form_setting = JSON.stringify(form_data);
    let json_data_form = w.serializeJSON(form_json);
    saveSettingPage(data_form_setting, json_data_form);
  }
  const saveSettingPage = async (data, dataset) => {
    let params = 'sys_function_id=<?= $sys_function_id ?>&tab_setting=' + data + '&meta_data=' + dataset;
    sendHttpRequest('POST', 'ajax/ajax_page_setting.php?c=<?= $code ?>&tab=<?= $tab_id ?>&menu_name=<?= $menu_name ?>', params).then(responseData => {
      setTimeout(() => {
        getListFill();
      }, 1000);
      console.log(responseData);
    }).catch(err => {
      console.log(err);
    })
  }

  // Sortable
  sortList();

  function sortList() {
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
          item.parentNode.removeChild(item);
          let json_data_form = w.serializeJSON(form_json);
          savePage(json_data_form);
        }
      },
      onEnd: function( /**Event*/ evt) {
        let json_data_form = w.serializeJSON(form_json);
        savePage(json_data_form);
      },
    });
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
              ${data.type}
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
        // if (opt.value != data) {
        //   opt.selected = true
        // }
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
              <input type="hidden" name="fill_name" value="${data.name}">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">DB Column name</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-primary fw-semibold" id="event_db_column_name" data-db_column_name="${data.db_column_name}">${data.db_column_name}</div>
              <input type="hidden" name="db_column_name" value="${data.db_column_name}">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-dark fw-semibold" id="event_type" data-type="${data.type}">${data.type}</div>
              <input type="hidden" name="type" value="${data.type}">
            </div>
          </div>
          <input type="hidden" name="fill_id" value="${data.id}">
          `;
      scope_show.insertAdjacentHTML('beforeend', html);
      let scope_template = document.getElementById('scope_show_template');
      scope_template.innerHTML = ''
      if (data.type == 'Varchar' || data.type == 'Longtext') {
        scope_template.innerHTML = `<?= template_form_text() ?>`;
      } else if (data.type == 'Int') {
        scope_template.innerHTML = `<?= template_form_number() ?>`;
      } else if (data.type == 'Decimal') {

        scope_template.innerHTML = `<?= template_form_decimal() ?>`;
        let digit = document.getElementById('event_show_digit').innerHTML = data.digit;
        let decimal = document.getElementById('event_show_decimal').innerHTML = data.decimal;

      } else if (data.type == 'Date') {
        scope_template.innerHTML = `<?= template_form_date() ?>`;
      } else if (data.type == 'Time') {
        scope_template.innerHTML = `<?= template_form_time() ?>`;
      } else if (data.type == 'DateTime') {
        scope_template.innerHTML = `<?= template_form_datetime() ?>`;
      } else if (data.type == 'Upload') {
        scope_template.innerHTML = `<?= template_form_upload() ?>`;
      } else {
        scope_template.innerHTML = `<?= template_form_text() ?>`;
      }
    }).catch(err => {
      console.log(err);
    })
  }

  function previewText() {
    let input_text_preview = document.getElementById('event_preview_text');
    let bold = document.getElementById('checkbox-title-bold');
    let italic = document.getElementById('checkbox-title-italic');
    let color = document.getElementById('custom_color_text');

    var checkedValue = null;
    var inputElements = document.getElementsByName('align_text');
    for (var i = 0; inputElements[i]; ++i) {
      if (inputElements[i].checked) {
        checkedValue = inputElements[i].value;
        if (checkedValue == 'left') {
          input_text_preview.classList.add('text-left');
          input_text_preview.classList.remove('text-center');
          input_text_preview.classList.remove('text-end');
        } else if (checkedValue == 'center') {
          input_text_preview.classList.add('text-center');
          input_text_preview.classList.remove('text-left');
          input_text_preview.classList.remove('text-end');
        } else if (checkedValue == 'right') {
          input_text_preview.classList.add('text-end');
          input_text_preview.classList.remove('text-left');
          input_text_preview.classList.remove('text-center');
        } else {
          input_text_preview.classList.add('text-left');
          input_text_preview.classList.remove('text-center');
          input_text_preview.classList.remove('text-end');
        }
        break;
      }
    }

    if (bold.checked == true) {
      input_text_preview.classList.add('fw-bold');
    } else {
      input_text_preview.classList.remove('fw-bold');
    }
    if (italic.checked == true) {
      input_text_preview.classList.add('fst-italic');
    } else {
      input_text_preview.classList.remove('fst-italic');
    }
    input_text_preview.style.color = color.value;
  }

  function previewEditText() {
    let input_text_preview = document.getElementById('event_preview_text_edit');
    let bold = document.getElementById('checkbox-title-bold_edit');
    let italic = document.getElementById('checkbox-title-italic_edit');
    let color = document.getElementById('custom_color_text_edit');

    let checkedValue = null;
    let inputElements = document.getElementsByName('align_text');
    for (let i = 0; inputElements[i]; ++i) {
      if (inputElements[i].checked) {
        checkedValue = inputElements[i].value;
        if (checkedValue == 'left') {
          input_text_preview.classList.add('text-left');
          input_text_preview.classList.remove('text-center');
          input_text_preview.classList.remove('text-end');
        } else if (checkedValue == 'center') {
          input_text_preview.classList.add('text-center');
          input_text_preview.classList.remove('text-left');
          input_text_preview.classList.remove('text-end');
        } else if (checkedValue == 'right') {
          input_text_preview.classList.add('text-end');
          input_text_preview.classList.remove('text-left');
          input_text_preview.classList.remove('text-center');
        } else {
          input_text_preview.classList.add('text-left');
          input_text_preview.classList.remove('text-center');
          input_text_preview.classList.remove('text-end');
        }
        break;
      }
    }

    if (bold.checked == true) {
      input_text_preview.classList.add('fw-bold');
    } else {
      input_text_preview.classList.remove('fw-bold');
    }
    if (italic.checked == true) {
      input_text_preview.classList.add('fst-italic');
    } else {
      input_text_preview.classList.remove('fst-italic');
    }
    input_text_preview.style.color = color.value;
  }

  function clearPageAll() {
    scope_list_fill.innerHTML = '';
    let json_data_form = w.serializeJSON(form_json);
    savePage(json_data_form);
  }

  function selectTypeImport(t) {
    let ele = t.getAttribute('data-type');
    let items = document.querySelectorAll('.card-list-select');
    let scope_show_form_import = document.getElementById('scope_show_form_import');
    scope_show_form_import.innerHTML = '';
    items.forEach((e) => {
      e.classList.remove('active');
    });
    if (ele == 'import_all') {
      scope_show_form_import.innerHTML = `<?= template_form_import_all() ?>`;
    } else if (ele == 'import') {
      scope_show_form_import.innerHTML = `<?= template_form_import() ?>`;
    } else {
      scope_show_form_import.innerHTML = `<?= template_form_import_all() ?>`;
    }

    t.classList.add('active');
  }

  function uploadImage(e, id) {
    const file = e.target.files[0];
    let scope_preview = document.getElementById(id);
    console.log(scope_preview);
    let img_preview = scope_preview.querySelector('img');
    if (file) {
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function() {
        img_preview.src = reader.result
      }
    }
  }
</script>
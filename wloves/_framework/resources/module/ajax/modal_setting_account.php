<?php
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
$data_tab_account_info = $_POST['json_tab_account_info'];
$font_size_options = [];
for ($i = 16; $i <= 20; $i++) {
  $font_size_options['list'][] = [
    'value' => $i,
    'name' => $i . ' px',
  ];
}

// ถ้าไม่มีข้อมูลให้ใช้ค่าเริ่มต้น
if (!$data_tab_account_info) {
  $data_tab_account_info  = [
    'is_title' =>  0,
    'title_name' => '',
    'title_font_size' => '',
    'title_font_bold' => 0,
    'title_font_color' => '',
    'username_font_size' => '',
    'username_font_bold' => 0,
    'username_font_color' => '',
    'password_type' => '',
    'is_edit_password' => 0,
    'is_verify_password' => 0,
    'is_update_date_time' => 0,
    'alignment' => '',
    'type_display' => '',
  ];
}
?>
<form method="post" action="view/module/controller_setting.php?c=<?= $_POST['code'] ?>&page=page_setting">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-18px font-Bold text-primary">ปรับแต่งข้อมูลบัญชีผู้ใช้</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="submit" name="submit_setting_account" class="btn-edit save min-w-150px">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div id="scope_customize_topic">
        <div class="row mt-20px mb-10px">
          <div class="col-sm-12 ">
            <div class="font-15px fw-bold">การปรับแต่งหัวเรื่อง</div>
            <?php TiwForm::normal('hidden', $_POST['id'], ['name' =>  'id']); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 mb-15px">
            <?= TiwForm::normal('checkbox', '1', ['name' => 'is_title', 'checked' => $data_tab_account_info['is_title'] ? true : false], ['style' => '1', 'label' => 'เปิดใช้งานหัวเรื่อง (Section title)', 'is_on_off' => true]); ?>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4 d-flex align-items-center">
            <div class="font-16px text-secondary fw-semibold ">หัวเรื่อง<span class="text-danger">*</span></div>
          </div>
          <div class="col-sm-8">
            <?= TiwForm::normal('text', $data_tab_account_info['title_name'], ['name' => 'title_name', 'required' => true], []); ?>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
          </div>
          <div class="col-sm-8">
            <div class="d-flex">
              <div class="max-w-100px w-100">
                <?php TiwForm::normal('select', $data_tab_account_info['title_font_size'], ['name' => 'title_font_size', 'class' => "input-bordered"], $font_size_options);  ?>
              </div>
              <div class="mx-10px pr-10px border-right">
                <div class="checkbox-bold">
                  <input type="checkbox" value="1" name="title_font_bold" id="checkbox-bold" <?= $data_tab_account_info['title_font_bold'] ? 'checked' : '' ?>>
                  <label for="checkbox-bold">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/bold.svg') ?>
                    </div>
                  </label>
                </div>
              </div>
              <div class="d-flex align-items-center scope_color_checkbox">
                <?php
                $title_color = $data_tab_account_info['title_font_color'];
                $array_color = ['var(--v2-color-info)', 'var(--v2-color-secondary)', 'var(--v2-color-primary)'];
                if (!in_array($title_color, $array_color)) {
                  $data_tab_account_info['title_font_color'] = '';
                }
                ?>
                <div class="radio-custom mr-5px">
                  <input type="radio" value="var(--v2-color-info)" name="title_font_color" id="radio-info" <?= $data_tab_account_info['title_font_color'] == 'var(--v2-color-info)' ? 'checked' : '' ?>>
                  <label for="radio-info" class=" bg-info">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="radio-custom mr-5px">
                  <input type="radio" value="var(--v2-color-secondary)" name="title_font_color" id="radio-secondary" <?= $data_tab_account_info['title_font_color'] == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
                  <label for="radio-secondary" class=" bg-secondary">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="radio-custom pr-5px mr-5px border-right">
                  <input type="radio" value="var(--v2-color-primary)" name="title_font_color" id="radio-primary" <?= $data_tab_account_info['title_font_color'] == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
                  <label for="radio-primary" class=" bg-primary">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <label class="btn-action-icon" for="event_custom_color">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                  <input id="event_custom_color" value="<?= $title_color ?>" type="color" name="title_font_color_custom" class="input-color-sm" onchange="customInputTitleColor(this);">
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr class="border-color-bg-1px">
      <div class="row">
        <div class="col-sm-12 mb-20px">
          <div class="font-15px fw-bold">การแสดงผลข้อมูลบัญชี และรหัสผ่าน</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold ">การแสดงผลชื่อบัญชี (Username)</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex">
            <div class="max-w-100px w-100">
              <?php TiwForm::normal('select', $data_tab_account_info['username_font_size'], ['name' => 'username_font_size', 'class' => "input-bordered"], $font_size_options);  ?>
            </div>
            <div class="mx-10px pr-10px border-right">
              <div class="checkbox-bold">
                <input type="checkbox" value="1" name="username_font_bold" id="checkbox-bold" <?= $data_tab_account_info['username_font_bold'] ? 'checked' : '' ?>>
                <label for="checkbox-bold">
                  <div class="checkbox-img ">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/bold.svg') ?>
                  </div>
                </label>
              </div>
            </div>
            <div class="d-flex align-items-center scope_color_checkbox">
              <?php
              $username_color = $data_tab_account_info['username_font_color'];
              $array_color = ['var(--v2-color-info)', 'var(--v2-color-secondary)', 'var(--v2-color-primary)'];
              if (!in_array($username_color, $array_color)) {
                $data_tab_account_info['username_font_color'] = '';
              }
              ?>
              <div class="radio-custom mr-5px">
                <input type="radio" value="var(--v2-color-info)" name="username_font_color" id="radio-username-info" <?= $data_tab_account_info['username_font_color'] == 'var(--v2-color-info)' ? 'checked' : '' ?>>
                <label for="radio-username-info" class=" bg-info">
                  <div class="radio-img ">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                  </div>
                </label>
              </div>
              <div class="radio-custom mr-5px">
                <input type="radio" value="var(--v2-color-secondary)" name="username_font_color" id="radio-username-secondary" <?= $data_tab_account_info['username_font_color'] == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
                <label for="radio-username-secondary" class=" bg-secondary">
                  <div class="radio-img ">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                  </div>
                </label>
              </div>
              <div class="radio-custom pr-5px mr-5px border-right">
                <input type="radio" value="var(--v2-color-primary)" name="username_font_color" id="radio-username-primary" <?= $data_tab_account_info['username_font_color'] == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
                <label for="radio-username-primary" class=" bg-primary">
                  <div class="radio-img ">
                    <?= W_Structure::getfile('_framework/resources/module/assets/icon/checking-mark.svg') ?>
                  </div>
                </label>
              </div>
              <label class="btn-action-icon" for="event_username_custom_color">
                <?= W_Structure::getfile('_framework/resources/module/assets/icon/color.svg') ?>
                <input id="event_username_custom_color" value="<?= $username_color ?>" type="color" name="username_font_color_custom" class="input-color-sm" onchange="customInputTitleColor(this);">
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4 d-flex align-items-center">
          <div class="font-16px text-secondary fw-semibold ">รูปแบบการแสดงผลรหัสผ่าน</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <?php
            $type_options = [
              'list' => [
                [
                  'value' => 'circle',
                  'name' => 'แสดงผลในรูปแบบวงกลม (••••••)',
                ],
                [
                  'value' => 'star',
                  'name' => 'แสดงผลข้่อมูลรหัสผ่าน (******)',
                ],
              ],
            ];
            TiwForm::normal('select', $data_tab_account_info['password_type'], ['name' => 'password_type'], $type_options);
            ?>
          </div>
        </div>
      </div>
      <hr class="border-color-bg-1px">
      <div class="row mb-20px">
        <div class="col-sm-12">
          <div class="font-15px fw-bold">การใช้งาน</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-12">
          <div class="d-flex align-items-center">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
            <span class="font-16px text-primary font-Medium ml-5px">บังคับให้มีในหน้า User Details (ภายใน Module)</span>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-12">
          <div class="d-flex align-items-center">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
            <span class="font-16px text-primary font-Medium ml-5px">บังคับให้มีในหน้าเพิ่มข้อมูล (Add)</span>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-12">
          <div class="d-flex align-items-center">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
            <span class="font-16px text-primary font-Medium ml-5px">บังคับให้มีในหน้าโปรไฟล์</span>
          </div>
        </div>
      </div>
      <hr class="border-color-bg-1px">
      <div class="row mb-20px">
        <div class="col-sm-12">
          <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-12">
          <div class="d-flex align-items-center">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
            <span class="font-16px text-primary font-Medium ml-5px">บังคับให้มีในหน้า User Details (ภายใน Module)</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_password', 'checked' => $data_tab_account_info['is_edit_password'] ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขรหัสผ่านได้ หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_verify_password', 'checked' => $data_tab_account_info['is_verify_password'] ? true : false], ['style' => '1', 'label' => 'ต้องกรอกช่อง ยืนยันรหัสผ่านอีกครั้งเมื่อทำการแก้ไข', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_update_date_time', 'checked' => $data_tab_account_info['is_update_date_time'] ? true : false], ['style' => '1', 'label' => 'แสดงวันที่, เวลา และผู้แก้ไขล่าสุดใต้รหัสผ่าน', 'is_on_off' => true]); ?>
        </div>
      </div>
      <hr class="border-color-bg-1px">
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
          100 % ของพื้นที่
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex">
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $data_tab_account_info['alignment'] == 'left' ? 'checked' : '' ?>>
              <label for="checkbox-align-left">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold">
              <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $data_tab_account_info['alignment'] == 'right' ? 'checked' : '' ?>>
              <label for="checkbox-align-right">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 mb-15px">
          <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
        </div>
        <div class="col-sm-12 mb-15px">
          <?php
          $type_display_row = $data_tab_account_info['type_display'] == 'row' ? 'checked' : '';
          $type_display_column = $data_tab_account_info['type_display'] == 'column' ? 'checked' : '';
          ?>
          <div class="d-flex">
            <div class="mr-10px">
              <?= TiwForm::normal('radio', 'row', ['name' => 'type_display',  $type_display_row => true], ['style' => '2']); ?>
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
              <?= TiwForm::normal('radio', 'column', ['name' => 'type_display', $type_display_column => true], ['style' => '2']); ?>
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
      <hr class="border-color-bg-5px">
    </div>
  </div>
</form>
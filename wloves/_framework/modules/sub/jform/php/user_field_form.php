<?php

// Menu ข้อมูลและตัวเลข
function f_template_data_and_number()
{

  $menus = [
    [
      'title' => 'ข้อความสั้นๆ',
      'des' => 'สำหรับกรอกข้อความสั้นๆ ไม่เกิน 1 บรรทัด',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/text.svg'),
      'action' => 'short_text',

    ],
    [
      'title' => 'ย่อหน้า',
      'des' => 'สำหรับกรอกข้อความแบบยาว ประมาณย่อหน้า',
      'icon' =>    W_Structure::getfile('_framework/modules/sub/jform/icon/text.svg'),
      'action' => 'paragrahp',

    ],
  ];

  ob_start();
?>
  <div class="container-field-option">
    <?php foreach ($menus as $inx => $menu) : ?>
      <div class="card-option <?= $inx == 0 ? 'selected' : '' ?>" data-title="<?= $menu['title'] ?>" data-type="<?= $menu['action'] ?>" onclick="selectOption(event);">
        <div class="__icon">
          <?= $menu['icon'] ?>
        </div>
        <div class="__title"><?= $menu['title'] ?></div>
        <div class="__description">
          <?= $menu['des'] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
}

// Menu Number
function f_template_number()
{
  $menus = [
    [
      'title' => 'ตัวเลข',
      'des' => 'สำหรับกรอกข้อมูลตัวเลข ประเภทจำนวนเต็มเท่านั้น',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/number.svg'),

      'action' => 'number',
    ],
    [
      'title' => 'ทศนิยม',
      'des' => 'สามารถกรอกข้อมูลตัวเลข ที่มีทศนิยมได้',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/number.svg'),
      'action' => 'decimal',
    ],
  ];

  ob_start();
?>
  <div class="container-field-option">
    <?php foreach ($menus as $inx => $menu) : ?>
      <div class="card-option <?= $inx == 0 ? 'selected' : '' ?>" data-title="<?= $menu['title'] ?>" data-type="<?= $menu['action'] ?>" onclick="selectOption(event);">
        <div class="__icon">
          <?= $menu['icon'] ?>
        </div>
        <div class="__title"><?= $menu['title'] ?></div>
        <div class="__description">
          <?= $menu['des'] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
}


// Menu Date Time
function f_template_date_and_time()
{
  $menus = [
    [
      'title' => 'วันที่',
      'des' => 'สำหรับกรอกข้อมูลตัวเลข ประเภทจำนวนเต็มเท่านั้น',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/date.svg'),
      'action' => 'date',
    ],
    [
      'title' => 'เวลา',
      'des' => 'สำหรับการบันทึกข้อมูลประเภท เวลา เท่านั้น',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/time.svg'),
      'action' => 'time',
    ],
    [
      'title' => 'วันที่และเวลา',
      'des' => 'สำหรับการบันทึกข้อมูลที่ประกอบด้วย วันที่และเวลา',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/datetime.svg'),
      'action' => 'datetime',
    ],
  ];

  ob_start();
?>
  <div class="container-field-option">
    <?php foreach ($menus as $inx => $menu) : ?>
      <div class="card-option <?= $inx == 0 ? 'selected' : '' ?>" data-title="<?= $menu['title'] ?>" data-type="<?= $menu['action'] ?>" onclick="selectOption(event);">
        <div class="__icon">
          <?= $menu['icon'] ?>
        </div>
        <div class="__title"><?= $menu['title'] ?></div>
        <div class="__description">
          <?= $menu['des'] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
}

// Menu Date and Time
function f_template_upload()
{
  $menus = [
    [
      'title' => 'ปุ่มอัปโหลดข้อมูล',
      'des' => 'ปุ่มสำหรับการอัปโหลด ไฟล์ รูปแบบต่าง ๆ (1 ปุ่ม)',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/text.svg'),
      'action' => 'upload',
    ],
  ];

  ob_start();
?>
  <div class="container-field-option">
    <?php foreach ($menus as $inx => $menu) : ?>
      <div class="card-option <?= $inx == 0 ? 'selected' : '' ?>" data-title="<?= $menu['title'] ?>" data-type="<?= $menu['action'] ?>" onclick="selectOption(event);">
        <div class="__icon">
          <?= $menu['icon'] ?>
        </div>
        <div class="__title"><?= $menu['title'] ?></div>
        <div class="__description">
          <?= $menu['des'] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
}

function f_template_form_short_text($is_edit = false, $data = [])
{
  $options_limit = [
    'list' => [
      [
        'value' => '0',
        'name' => 'ไม่จำกัด',
      ],
      [
        'value' => '1',
        'name' => 'จำกัด',
      ],
    ],
  ];
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">ข้อมูลและตัวเลข | ข้อความสั้นๆ</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' =>  'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
      <input type="hidden" name="category" value="String">
      <input type="hidden" name="type" value="Varchar">
    </div>
  </div>
<?php return ob_get_clean();
}

function f_template_form_paragraph($is_edit = false, $data = [])
{
  $options_limit = [
    'list' => [
      [
        'value' => '0',
        'name' => 'ไม่จำกัด',
      ],
      [
        'value' => '1',
        'name' => 'จำกัด',
      ],
    ],
  ];
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">ข้อมูลและตัวเลข | ย่อหน้า</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="String">
    <input type="hidden" name="type" value="Longtext">
  </div>
<?php return ob_get_clean();
}


function f_template_form_number($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">ข้อมูลและตัวเลข | ตัวเลข</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto', 'placeholder' => 'กรอกหากมี'], []); ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="Number">
    <input type="hidden" name="type" value="Int">
  </div>
<?php return ob_get_clean();
}


function f_template_form_decimal($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div>
        ช่องกรอกตัวเลข | ทศนิยม
      </div>
      <div class="font-15px text-secondary mt-10px fw-normal">
        **หากต้องการสร้างละติจูด, หรือลองจิจูด ต้องใช้ช่องกรอกรูปแบบข้อความ
      </div>
    </div>
    <div class="__body">

      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หลักตัวเลข<span class="text-danger">*</span></div>
        </div>
        <div class="col-4">
          <?= TiwForm::normal('number', $is_edit ? $data['digit'] : '', ['name' => 'digit', 'required' => 'true', 'placeholder' => ''], []); ?>
          <div class="text-secondary font-15px mt-5px">จำนวนหลักทั้งหมด</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">ตำแหน่งทศนิยม<span class="text-danger">*</span></div>
        </div>
        <div class="col-4">
          <?= TiwForm::normal('number', $is_edit ? $data['decimal'] : '', ['name' => 'decimal', 'required' => 'true', 'placeholder' => ''], []); ?>
        </div>
        <div class="col-5">
          <div class="text-danger font-15px">จำนวนหลักทั้งหมด</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto', 'placeholder' => 'กรอกหากมี'], []); ?>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-12">
          <div class="font-15px fw-bold">ตัวอย่างข้อมูล</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">ตำแหน่งทศนิยม</div>
        </div>
        <div class="col-9">
          <div class="font-16px fw-medium">0.00</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">ค่าสูงสุด</div>
        </div>
        <div class="col-9">
          <div class="font-16px fw-medium">999,999,999,999.99</div>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="Number">
    <input type="hidden" name="type" value="Decimal">
  </div>
<?php return ob_get_clean();
}


function f_template_form_date($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">วันที่และเวลา | วันที่</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="DateTime">
    <input type="hidden" name="type" value="Date">
  </div>
<?php return ob_get_clean();
}

function f_template_form_time($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">วันที่และเวลา | เวลา</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="DateTime">
    <input type="hidden" name="type" value="Time">
  </div>
<?php return ob_get_clean();
}


function f_template_form_date_time($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div>
        วันที่และเวลา | วันที่และเวลา
      </div>
      <div class="text-secondary font-15px mt-10px">การบันทึกข้อมูลเวลาจะสามารถบันทึกได้สูงสุด 24 ชั่วโมง (00:00:00 - 23:59:59)</div>
    </div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="DateTime">
    <input type="hidden" name="type" value="DateTime">
  </div>
<?php return ob_get_clean();
}

function f_template_form_upload($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">อัปโหลด | ปุ่มอัปโหลดข้อมูล</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="Upload">
    <input type="hidden" name="type" value="Upload">
  </div>
<?php return ob_get_clean();
}


function f_template_form_dropdown($is_edit = false, $data = [])
{
  ob_start();
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">สร้างตัวเลือก | Dropdown</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">ข้อความตัวอย่าง</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['placeholder'] : '', ['name' =>  'placeholder', 'placeholder' => 'Enter name…'], []); ?>
          <div class="text-secondary font-15px mt-5px">ข้อความเริ่มต้นในช่องเลือกข้อมูล</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto', 'placeholder' => 'กรอกหากมี'], []); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="font-15px fw-bold">กำหนดตัวเลือก</div>
        </div>
        <div class="col-12 my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_all_option', 'checked' => ($is_edit && $data['is_all_option'] == 1 ? true : '')], ['style' => '3', 'label' => 'มีตัวเลือก “ทั้งหมด”']); ?>
        </div>
      </div>

      <div id="scope_row_input" class="row">
        <?php if ($is_edit) : ?>
          <?php $json_encode = json_decode($data['json_data'], true); ?>
          <?php foreach ($json_encode as $key => $option_data) : ?>
            <div class="col-12 mb-10px ">
              <div class="input-group-custom scope_input_option">
                <div class="append-group-icon event_input_text_option" onchange="eventKeyPressOption(event);">
                  <?= TiwForm::normal('text', $option_data, ['name' => 'json_data[]'], []); ?>
                </div>
                <div class="append-input">
                  <div class="__icon-delete" onclick="eventDeleteInput(event);">
                    <img src="assets/icons/bin.svg" alt="">
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="col-12 mb-10px scope_input_option">
            <div class="input-group-custom">
              <div class="append-group-icon event_input_text_option" onchange="eventKeyPressOption(event);">
                <?= TiwForm::normal('text', '', ['name' => 'json_data[]'], []); ?>
              </div>
              <div class="append-input">
                <div class="__icon-delete" onclick="eventDeleteInput(event);">
                  <img src="assets/icons/bin.svg" alt="">
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="row">
        <div class="col-12">
          <button type="button" class="btn bg-light-blue" onclick="eventAddInputOption();">
            <img src="assets/icons/add_primary.svg" alt="" class="mr-5px w-20px h-20px">
            <div class="font-16px fw-semibold">เพิ่มตัวเลือก</div>
          </button>
        </div>
      </div>
    </div>
    <input type="hidden" name="category" value="Selectoption">
    <input type="hidden" name="type" value="Dropdown">
  </div>
<?php return ob_get_clean();
}



// Menu option
function f_template_option()
{
  $menus = [
    [
      'title' => 'Dropdown',
      'des' => 'สร้างตัวเลือกข้อมูลประเภท Dropdown',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/icon-dropdown.svg'),
      'action' => 'select_dropdown',
    ],
    [
      'title' => 'Radio',
      'des' => 'สร้างตัวเลือกข้อมูลประเภท Radio (เลือกได้ 1 ตัวเลือกจากทั้งหมด)',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/icon-radio.svg'),
      'action' => 'radio',
    ],
    [
      'title' => 'Checkbox',
      'des' => 'สร้างตัวเลือกประเภท Checkbox เลือกได้หลายตัวเลือกตามที่กำหนด',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/icon-checkbox.svg'),
      'action' => 'checkbox',
    ],
    [
      'title' => 'Tag',
      'des' => 'สร้างตัวเลือกข้อมูลประเภท Tag เลือกได้หลายตัวเลือก, แสดงผลเป็น Tag',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/text.svg'),
      'action' => 'select_tag',
    ],
    [
      'title' => 'Switch',
      'des' => 'สร้างตัวเลือกประเภทค่า True / False ในรูปแบบสวิตซ์สองทางเลือก',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/icon-switch.svg'),
      'action' => 'switch',
    ],
  ];

  ob_start();
?>
  <div class="container-field-option">
    <?php foreach ($menus as $inx => $menu) : ?>
      <div class="card-option <?= $inx == 0 ? 'selected' : '' ?>" data-title="<?= $menu['title'] ?>" data-type="<?= $menu['action'] ?>" onclick="selectOption(event);">
        <div class="__icon">
          <?= $menu['icon'] ?>
        </div>
        <div class="__title"><?= $menu['title'] ?></div>
        <div class="__description">
          <?= $menu['des'] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
}


function f_template_form_select_tag($is_edit = false, $data = [])
{
  ob_start();
  $id_form = $is_edit ? 'scope_list_fill_edit' : 'scope_list_fill';
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill pb-0  border-0">
    <div class="__header">สร้างตัวเลือก | Tag</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <hr class="border-bg">
    <div>
      <?php
      $count = 0;
      if (isset($data['json_data'])) {
        $count = count($data['json_data']);
      }
      ?>
      <span class=" font-Bold mr-10px"> กำหนดตัวเลือก</span> <span class="scope_count_options"><?= $count ?></span> ตัวเลือก
    </div>
    <hr class="border-bg mb-0">
    <div class="container-dropzone bg-main-color  mx--20px p-10px w-auto">
      <div id="<?= $id_form ?>" class="list-fill">
        <?php if (isset($data['json_data']) && $data['json_data']) { ?>
          <?php foreach ($data['json_data'] as $key => $data_option) { ?>
            <div class="card-sortable">
              <div class="handle">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
              </div>
              <div class="body-fill">
                <div class="__field-input">
                  <?= TiwForm::normal('text', $data_option, ['name' => 'option[]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?>
                </div>
                <div class="__field-action">
                  <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'<?= $id_form ?>')"></button>
                  <button type="button" class="btn-action __btn--delete js-remove"></button>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="bg-main-color mx--20px p-10px w-auto">
      <button type="button" class="btn btn-clean m-auto" onclick="addOption('<?= $id_form ?>')">
        <div class="font-16px fw-semibold">Add option</div>
      </button>
    </div>
    <input type="hidden" name="category" value="Tag">
    <input type="hidden" name="type" value="Varchar">
  </div>

<?php
  return ob_get_clean();
}


function f_template_form_select_dropdown($is_edit = false, $data = [])
{
  ob_start();
  $id_form = $is_edit ? 'scope_list_fill_edit' : 'scope_list_fill';
  $is_readonly = $is_edit ? 'readonly' : '';
?>
  <div id="scope_show_form" class="container-form-fill pb-0  border-0">
    <div class="__header">สร้างตัวเลือก | Dropdown</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name', $is_readonly => 'true'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <hr class="border-bg">
    <div>
      <?php
      $count = 0;
      if (isset($data['json_data'])) {
        $count = count($data['json_data']);
      }
      ?>
      <span class=" font-Bold mr-10px"> กำหนดตัวเลือก</span> <span class="scope_count_options"><?= $count ?></span> ตัวเลือก
    </div>
    <hr class="border-bg mb-0">
    <div class="container-dropzone bg-main-color  mx--20px p-10px w-auto">
      <div id="<?= $id_form ?>" class="list-fill">
        <?php if (isset($data['json_data']) && $data['json_data']) { ?>
          <?php foreach ($data['json_data'] as $key => $data_option) { ?>
            <div class="card-sortable">
              <div class="handle">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
              </div>
              <div class="body-fill">
                <div class="__field-input">
                  <?= TiwForm::normal('text', $data_option, ['name' => 'option[]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?>
                </div>
                <div class="__field-action">
                  <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'<?= $id_form ?>')"></button>
                  <button type="button" class="btn-action __btn--delete js-remove"></button>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="bg-main-color mx--20px p-10px w-auto">
      <button type="button" class="btn btn-clean m-auto" onclick="addOption('<?= $id_form ?>')">
        <div class="font-16px fw-semibold">Add option</div>
      </button>
    </div>
    <input type="hidden" name="category" value="Dropdown">
    <input type="hidden" name="type" value="Varchar">
  </div>

<?php
  return ob_get_clean();
}



// Menu option
function f_template_other()
{
  $menus = [
    [
      'title' => 'Auto address',
      'des' => 'สร้างชุดกรอกข้อมูล ที่อยู่อาศัยซึ่งสามารถเชื่อมโยงระหว่างกันได้',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/auto_address.svg'),
      'action' => 'auto_address',
    ],
    [
      'title' => 'Google map link',
      'des' => 'สร้างช่องกรอกสำหรับเชื่อมต่อกับ แผนที่ Google map',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/map.svg'),
      'action' => 'google_map',
    ],
    [
      'title' => 'Latitude & Longitude',
      'des' => 'สร้างช่องกรอกสำหรับละติจูดและลองจิจูดโดยเฉพาะ',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/lat_long.svg'),
      'action' => 'latitude_longitude',
    ],
    [
      'title' => 'Link',
      'des' => 'สร้างช่องกรอกที่เชื่อมโยงกับลิงก์ที่ระบุได้',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/link.svg'),
      'action' => 'link',
    ],
    [
      'title' => 'Youtube Embed',
      'des' => 'สร้างช่องกรอกที่สามารถแปลง Youtube embed เป็นวิดีโอได้',
      'icon' => W_Structure::getfile('_framework/modules/sub/jform/icon/youtube_embed.svg'),
      'action' => 'youtube_embed',
    ],
  ];

  ob_start();
?>
  <div class="container-field-option">
    <?php foreach ($menus as $inx => $menu) : ?>
      <div class="card-option <?= $inx == 0 ? 'selected' : '' ?>" data-title="<?= $menu['title'] ?>" data-type="<?= $menu['action'] ?>" onclick="selectOption(event);">
        <div class="__icon">
          <?= $menu['icon'] ?>
        </div>
        <div class="__title"><?= $menu['title'] ?></div>
        <div class="__description">
          <?= $menu['des'] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
}


function f_template_form_auto_address($is_edit = false, $data = [])
{
  ob_start();
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div> สร้างตัวเลือก | Auto address</div>
      <div class="font-15px text-secondary fw-medium">สร้างช่องกรอกข้อมูล 5 ช่องประกอบด้วย ที่อยู่, ตำบล/แขวง, อำเภอ/เขต, จังหวัด, รหัสไปรษณีย์</div>
    </div>

    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' =>  'name', 'required' => 'true', 'placeholder' => 'Address'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
      <input type="hidden" name="category" value="Auto address">
      <input type="hidden" name="type" value="Varchar">
    </div>
  </div>
<?php return ob_get_clean();
}


function f_template_form_google_map($is_edit = false, $data = [])
{
  ob_start();
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div>สร้างตัวเลือก | Google map link</div>
    </div>

    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' =>  'name', 'required' => 'true', 'placeholder' => 'Address'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
      <input type="hidden" name="category" value="Google map link">
      <input type="hidden" name="type" value="Varchar">
    </div>
  </div>
<?php return ob_get_clean();
}

function f_template_form_lat_long($is_edit = false, $data = [])
{
  ob_start();
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div>สร้างตัวเลือก | Latitude & Longitude</div>
    </div>

    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' =>  'name', 'required' => 'true', 'placeholder' => 'Address'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
      <input type="hidden" name="category" value="Latitude_Longtitude">
      <input type="hidden" name="type" value="Varchar">
    </div>
  </div>
<?php return ob_get_clean();
}

function f_template_form_link($is_edit = false, $data = [])
{
  ob_start();
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div>สร้างตัวเลือก | Link</div>
    </div>

    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' =>  'name', 'required' => 'true', 'placeholder' => 'Address'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
      <input type="hidden" name="category" value="Link">
      <input type="hidden" name="type" value="Varchar">
    </div>
  </div>
<?php return ob_get_clean();
}


function f_template_form_youtude_embed($is_edit = false, $data = [])
{
  ob_start();
?>
  <div id="scope_show_form" class="container-form-fill">
    <div class="__header">
      <div>สร้างตัวเลือก | Youtube Embed</div>
    </div>

    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' =>  'name', 'required' => 'true', 'placeholder' => 'Address'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
      <input type="hidden" name="category" value="Youtube_embed">
      <input type="hidden" name="type" value="Varchar">
    </div>
  </div>
<?php return ob_get_clean();
}

function f_template_form_radio($is_edit = false, $data = [])
{
  ob_start();
  $id_form = $is_edit ? 'scope_list_fill_edit' : 'scope_list_fill';
?>
  <div id="scope_show_form" class="container-form-fill pb-0  border-0">
    <div class="__header">สร้างตัวเลือก | Radio</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <hr class="border-bg">
    <div>
      <?php
      $count = 0;
      if (isset($data['json_data'])) {
        $count = count($data['json_data']);
      }
      ?>
      <span class=" font-Bold mr-10px"> กำหนดตัวเลือก</span> <span class="scope_count_options"><?= $count ?></span> ตัวเลือก
    </div>
    <hr class="border-bg mb-0">
    <div class="container-dropzone bg-main-color  mx--20px p-10px w-auto">
      <div id="<?= $id_form ?>" class="list-fill">
        <?php $is_other = false; ?>
        <?php if (isset($data['json_data']) && $data['json_data']) { ?>
          <?php foreach ($data['json_data'] as $key => $data_option) { ?>
            <?php
            if (isset($data_option['is_other']) && $data_option['is_other'] == '1') {
              $is_other = true;
            }
            ?>
            <div class="card-sortable">
              <div class="handle">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
              </div>
              <div class="body-fill">
                <?php if (isset($data_option['is_other'])) { ?>
                  <div class="__field-input d-flex justify-content-between">
                    <?= TiwForm::normal('text', $data_option['value'], ['name' => 'option[' . $key . '][value]', 'class' => 'max-w-300px border-0 ', 'readonly' => 'true', 'placeholder' => 'เพิ่มตัวเลือก']); ?>
                    <?php
                    $checked = $data_option['is_text_other'] == '1' ? 'checked' : '';
                    ?>
                    <?= TiwForm::normal('checkbox', '1', ['name' => 'option[' . $key . '][is_text_other]', 'main_class' => 'w-200px', $checked => true], ['style' => '3', 'label' => 'พร้อมช่องกรอก']); ?>
                    <?= TiwForm::normal('hidden', '1', ['name' => 'option[' . $key . '][is_other]']); ?>
                  </div>
                <?php } else { ?>
                  <div class="__field-input">
                    <?= TiwForm::normal('text', $data_option['value'], ['name' => 'option[' . $key . '][value]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?>
                  </div>
                <?php } ?>
                <div class="__field-action d-flex justify-content-end">
                  <?php if (!isset($data_option['is_other'])) { ?>
                    <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'<?= $id_form ?>')"></button>
                  <?php } ?>
                  <button type="button" class="btn-action __btn--delete js-remove" <?= isset($data_option['is_other']) ? 'data-remove="other"' : '' ?>></button>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="bg-main-color mx--20px p-10px w-auto d-flex align-items-center justify-content-center">
      <button type="button" class="btn btn-clean " onclick="addOptionRadio('<?= $id_form ?>')">
        <div class="font-16px fw-semibold">เพิ่มตัวเลือก</div>
      </button>
      <?php if (!$is_other) { ?>
        <span class="font-16px font-Regular mx-5px hide_has_add_other">หรือ</span>
        <button type="button" class="btn btn-clean primary hide_has_add_other" onclick="addOptionOtherRadio('<?= $id_form ?>')">
          <div class="font-16px fw-semibold">เพิ่มอื่น ๆ</div>
        </button>
      <?php } ?>
    </div>
    <input type="hidden" name="category" value="Radio">
    <input type="hidden" name="type" value="Varchar">
  </div>

<?php
  return ob_get_clean();
}


function f_template_form_checkbox($is_edit = false, $data = [])
{
  ob_start();
  $id_form = $is_edit ? 'scope_list_fill_edit' : 'scope_list_fill';
?>
  <div id="scope_show_form" class="container-form-fill pb-0  border-0">
    <div class="__header">สร้างตัวเลือก | Checkbox</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">จำกัดการเลือก</div>
        </div>
        <div class="col-9">
          <?php
          $limit_length = '';
          $checked_unlimit = 'checked';
          $checked_limit = '';
          if (isset($data['json_data']) && $data['json_data']) {
            $checked_unlimit = isset($data['json_data']['limit_type']) && $data['json_data']['limit_type'] == 'unlimit' ? 'checked' : '';
            $checked_limit = isset($data['json_data']['limit_type']) && $data['json_data']['limit_type'] == 'limit' ? 'checked' : '';
            $limit_length = isset($data['json_data']['limit_length']) ? $data['json_data']['limit_length'] : '';
            if ($checked_unlimit == 'checked') {
              $limit_length = '';
            }
          }
          ?>
          <?= TiwForm::normal('radio', 'unlimit', ['name' => 'option[limit_type]', 'main_class' => 'w-300px',  $checked_unlimit => true], ['style' => '2', 'label' => 'เลือกได้ไม่จำกัดจำนวน']); ?>
          <div class="d-flex align-items-center">
            <?= TiwForm::normal('radio', 'limit', ['name' => 'option[limit_type]', 'main_class' => 'w-150px', $checked_limit => true], ['style' => '2', 'label' => 'เลือกได้ไม่เกิน']); ?>
            <?= TiwForm::normal('number', $is_edit ? $limit_length : '', ['name' => 'option[limit_length]', 'class' => 'w-100px']); ?>
            <span>ตัวเลือก</span>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <hr class="border-bg">
    <div>
      <?php
      $count = 0;
      if (isset($data['json_data']['list'])) {
        $count = count($data['json_data']['list']);
      }
      ?>
      <span class=" font-Bold mr-10px"> กำหนดตัวเลือก</span> <span class="scope_count_options"><?= $count ?></span> ตัวเลือก
    </div>
    <hr class="border-bg mb-0">
    <div class="container-dropzone bg-main-color  mx--20px p-10px w-auto">
      <div id="<?= $id_form ?>" class="list-fill">
        <?php $is_other = false; ?>
        <?php if (isset($data['json_data']) && $data['json_data']) { ?>
          <?php foreach ($data['json_data']['list'] as $key => $data_option) { ?>
            <?php
            if (isset($data_option['is_other']) && $data_option['is_other'] == '1') {
              $is_other = true;
            }
            ?>
            <div class="card-sortable">
              <div class="handle">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
              </div>
              <div class="body-fill">
                <?php if (isset($data_option['is_other'])) { ?>
                  <div class="__field-input d-flex justify-content-between">
                    <?= TiwForm::normal('text', $data_option['value'], ['name' => 'option[list][' . $key . '][value]', 'class' => 'max-w-300px border-0 ', 'readonly' => 'true', 'placeholder' => 'เพิ่มตัวเลือก']); ?>
                    <?php
                    $checked = $data_option['is_text_other'] == '1' ? 'checked' : '';
                    ?>
                    <?= TiwForm::normal('checkbox', '1', ['name' => 'option[list][' . $key . '][is_text_other]', 'main_class' => 'w-200px', $checked => true], ['style' => '3', 'label' => 'พร้อมช่องกรอก']); ?>
                    <?= TiwForm::normal('hidden', '1', ['name' => 'option[list][' . $key . '][is_other]']); ?>
                  </div>
                <?php } else { ?>
                  <div class="__field-input">
                    <?= TiwForm::normal('text', $data_option['value'], ['name' => 'option[list][' . $key . '][value]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?>
                  </div>
                <?php } ?>
                <div class="__field-action d-flex justify-content-end">
                  <?php if (!isset($data_option['is_other'])) { ?>
                    <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'<?= $id_form ?>')"></button>
                  <?php } ?>
                  <button type="button" class="btn-action __btn--delete js-remove" <?= isset($data_option['is_other']) ? 'data-remove="other"' : '' ?>></button>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="bg-main-color mx--20px p-10px w-auto d-flex align-items-center justify-content-center">
      <button type="button" class="btn btn-clean " onclick="addOptionCheckbox('<?= $id_form ?>')">
        <div class="font-16px fw-semibold">เพิ่มตัวเลือก</div>
      </button>
      <?php if (!$is_other) { ?>
        <span class="font-16px font-Regular mx-5px hide_has_add_other">หรือ</span>
        <button type="button" class="btn btn-clean primary hide_has_add_other" onclick="addOptionOtherCheckbox('<?= $id_form ?>')">
          <div class="font-16px fw-semibold">เพิ่มอื่น ๆ</div>
        </button>
      <?php } ?>
    </div>
    <input type="hidden" name="category" value="Checkbox">
    <input type="hidden" name="type" value="Varchar">
  </div>

<?php
  return ob_get_clean();
}


function f_template_form_switch($is_edit = false, $data = [])
{
  ob_start();
?>
  <div id="scope_show_form" class="container-form-fill pb-0  border-0">
    <div class="__header">สร้างตัวเลือก | Switch</div>
    <div class="__body">
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('text', $is_edit ? $data['name'] : '', ['name' => 'name', 'required' => 'true', 'placeholder' => 'Name'], []); ?>
          <div class="text-secondary font-15px mt-5px">**ภาษาอังกฤษเท่านั้น และห้ามซ้ำกับ Field อื่น</div>
        </div>
      </div>
      <?php
      $type = '';
      $true_text = '';
      $false_text = '';
      if (isset($data['json_data']) && $data['json_data']) {
        $type = isset($data['json_data']['type']) ? $data['json_data']['type'] : '';
        $true_text = isset($data['json_data']['true_text']) ? $data['json_data']['true_text'] : '';
        $false_text = isset($data['json_data']['false_text']) ? $data['json_data']['false_text'] : '';
      }
      ?>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">กำหนดรูปแบบ<span class="text-danger">*</span></div>
        </div>
        <div class="col-9">
          <?php
          $options = [
            'list' => [
              [
                'value' => '',
                'name' => 'please select',
              ],
              [
                'value' => 'default',
                'name' => 'กำหนดเอง'
              ],
              [
                'value' => 'YesorNo',
                'name' => 'YesorNo'
              ],
              [
                'value' => 'TrueorFalse',
                'name' => 'TrueorFalse'
              ],
            ]
          ];
          TiwForm::normal('select', $is_edit ? $type : '', ['name' => 'option[type]', 'required' => 'true', 'onchange' => "eventSelectTypeSwite(event)"], $options);
          ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">การแสดงผล <span class="text-danger">*</span></div>
        </div>
        <div class="col-9 d-flex ">
          <div class="mr-10px">
            <?= TiwForm::normal('text', $is_edit ? $true_text : '', ['name' => 'option[true_text]', 'class' => 'max-w-150px scope_true_text', 'required' => 'true'], []); ?>
            <div class="text-secondary font-15px mt-5px">ค่าเป็น True</div>
          </div>
          <div>
            <?= TiwForm::normal('text', $is_edit ? $false_text : '', ['name' => 'option[false_text]', 'class' => 'max-w-150px scope_false_text', 'required' => 'true'], []); ?>
            <div class="text-secondary font-15px mt-5px">ค่าเป็น False</div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-3">
          <div class="font-16px fw-semibold">หมายเหตุ</div>
        </div>
        <div class="col-9">
          <?= TiwForm::normal('textarea', $is_edit ? $data['remark'] : '', ['name' => 'remark', 'class' => 'h-auto'], []); ?>
        </div>
      </div>
    </div>
    <hr class="border-bg">
    <input type="hidden" name="category" value="Switch">
    <input type="hidden" name="type" value="Varchar">
  </div>

<?php
  return ob_get_clean();
}

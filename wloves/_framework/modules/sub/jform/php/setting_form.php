<?php

function template_form_text($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $size_input = '100';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $type_input_text = 'text';
  $is_show_title = 1;
  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $type_input_text = isset($data['type_input_text']) ? $data['type_input_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
        <div class="mt-15px">
          <?= TiwForm::normal('radio', 'text', ['name' => 'type_input_text', 'checked' => $type_input_text == 'text' ? 1 : 0], ['style' => '2', 'label' => 'ช่องกรอกบรรทัดเดียว', 'is_on_off' => true]); ?>
        </div>
        <div class="mt-15px">
          <?= TiwForm::normal('radio', 'textarea', ['name' => 'type_input_text', 'checked' => $type_input_text == 'textarea' ? 1 : 0], ['style' => '2', 'label' => 'ช่องกรอกหลายบรรทัด', 'is_on_off' => true]); ?>
        </div>
        <?php if ((isset($field_setting['type']) && $field_setting['type']) && $field_setting['type'] == 'Longtext') { ?>
          <div class="mt-15px">
            <?= TiwForm::normal('radio', 'text_editor', ['name' => 'type_input_text', 'checked' => $type_input_text == 'text_editor' ? 1 : 0], ['style' => '2', 'label' => 'ช่องกรอกแบบ Text Editor', 'is_on_off' => true]); ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_list); ?>
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
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? true : false], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('textarea', $placeholder_text, ['id' => 'placeholder_text', 'name' => 'placeholder_text'], []); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm" value="<?= $color_placeholder ?>">
        </label>
      </div>
    </div>

  </div>

<?php return ob_get_clean();
}


function template_form_date($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];

  $option_format_time = [
    'list' => [
      [
        'value' => 'H:i:s',
        'name' => 'O’Clock (00:00 - 23:59)'
      ],
    ]
  ];

  $option_format_datetime = [
    'list' => [
      [
        'value' => 'D M Y, H:i:s',
        'name' => '01 Jan 20XX, Time'
      ],
    ]
  ];

  $option_format_date = [
    'list' => [

      [
        'value' => 'd/m/Y',
        'name' => 'DD / MM / YYYY'
      ],
    ]
  ];

  $option_format_init_time = [
    'list' => [
      [
        'value' => 'current',
        'name' => 'เวลาปัจจุบัน'
      ],
    ]
  ];
  $option_format_init_date = [
    'list' => [
      [
        'value' => 'current',
        'name' => 'วันที่ปัจจุบัน'
      ],
    ]
  ];
  $option_example = [
    'list' => [
      [
        'value' => 'example',
        'name' => 'ข้อความตัวอย่าง'
      ],
    ]
  ];

  $array_value = [
    'input_title',
    'type_format_date',
    'type_format_init_date',
    'placeholder_text_date',
    'color_placeholder_date',
    'custom_color_date',
    'input_inner_size_date',
    'size_input_date'
  ];

  $array_checkbox = [
    'font_bold',
    'is_show_title',
  ];

  foreach ($array_checkbox as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '0';
  }
  foreach ($array_value as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '';
  }
  $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : 16;
  $input_inner_size_date = isset($data['input_inner_size_date']) ? $data['input_inner_size_date'] : 16;
  $color = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
  $color_date = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
?>
  <!-- การปรับแต่งหัวข้อช่องกรอก -->
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $is_show_title == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>

    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold">
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input_date, ['id' => 'event_input_size', 'name' => 'size_input_date'], $size_display_input); ?>
          </div>
          <div class="fonf-16px fw-medium">: 410 pixel</div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_date, ['id' => '', 'name' => 'type_format_date'], $option_format_date); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $input_inner_size_date, ['name' => 'input_inner_size_date', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px ml-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_date" id="radio-date--info" <?= $color_date == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-date--info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_date" id="radio-date--secondary" <?= $color_date == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-date--secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_date" id="radio-date--primary" <?= $color_date == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-date--primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_date">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_date" type="color" name="custom_color_date" class="input-color-sm" value="<?= $custom_color_date ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบข้อมูลตั้งต้น*</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', '', ['id' => '', 'name' => 'type_format_init_date'], $option_example); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', 'ระบุ วันเดือน ปีเกิด', ['id' => 'placeholder_text', 'name' => 'placeholder_text'], []); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm">
        </label>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_form_time($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $option_format_time = [
    'list' => [
      [
        'value' => 'H:i:s',
        'name' => 'O’Clock (00:00 - 23:59)'
      ],
    ]
  ];

  $option_format_datetime = [
    'list' => [
      [
        'value' => 'D M Y, H:i:s',
        'name' => '01 Jan 20XX, Time'
      ],
    ]
  ];

  $option_format_date = [
    'list' => [

      [
        'value' => 'DD/MM/ YYYY',
        'name' => 'DD / MM / YYYY'
      ],
    ]
  ];

  $option_format_init_time = [
    'list' => [
      [
        'value' => 'current',
        'name' => 'เวลาปัจจุบัน'
      ],
    ]
  ];
  $option_format_init_date = [
    'list' => [
      [
        'value' => 'current',
        'name' => 'วันที่ปัจจุบัน'
      ],
    ]
  ];
  $option_example = [
    'list' => [
      [
        'value' => 'example',
        'name' => 'ข้อความตัวอย่าง'
      ],
    ]
  ];


  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];

  $array_value = [
    'input_title',
    'font_size_title',
    'custom_color',
    'size_input_time',
    'type_format_time',
    'type_format_init_time',
    'placeholder_text_time',
    'color_placeholder_time',
    'custom_color_time',
    'input_inner_size_time',
    'color_inner_time',
  ];

  $array_checkbox = [
    'font_bold',
    'is_show_title',
  ];

  foreach ($array_checkbox as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '0';
  }
  foreach ($array_value as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '';
  }

  $color = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
  $color_time = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
?>
  <!-- การปรับแต่งหัวข้อช่องกรอก -->
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $is_show_title == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input_time, ['id' => 'event_input_size', 'name' => 'size_input_time'], $size_display_input); ?>
          </div>
          <div class="fonf-16px fw-medium">: 100 pixel</div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_time, ['id' => '', 'name' => 'type_format_time'], $option_format_time); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $input_inner_size_time, ['name' => 'input_inner_size_time', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner_time" id="radio-info" <?= $color_inner_time == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner_time" id="radio-secondary" <?= $color_inner_time == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner_time" id="radio-primary" <?= $color_inner_time == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_time">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_time" type="color" name="custom_color_time" class="input-color-sm" value="<?= $custom_color_time ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold required">รูปแบบข้อมูลตั้งต้น</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_init_time, ['id' => '', 'name' => 'type_format_init_time'], $option_format_init_time); ?>
        </div>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_form_datetime($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];

  $option_format_time = [
    'list' => [
      [
        'value' => 'H:i:s',
        'name' => 'O’Clock (00:00 - 23:59)'
      ],
    ]
  ];

  $option_format_datetime = [
    'list' => [
      [
        'value' => 'd M Y, H:i:s',
        'name' => '01 Jan 20XX, Time'
      ],
    ]
  ];

  $option_format_date = [
    'list' => [

      [
        'value' => 'd/m/Y',
        'name' => 'DD / MM / YYYY'
      ],
    ]
  ];

  $option_format_init_time = [
    'list' => [
      [
        'value' => 'current',
        'name' => 'เวลาปัจจุบัน'
      ],
    ]
  ];
  $option_format_init_date = [
    'list' => [
      [
        'value' => 'current',
        'name' => 'วันที่ปัจจุบัน'
      ],
    ]
  ];
  $option_example = [
    'list' => [
      [
        'value' => 'example',
        'name' => 'ข้อความตัวอย่าง'
      ],
    ]
  ];



  $array_value = [
    'input_title',
    'font_size_title',
    'font_bold',
    'custom_color',
    'size_input_date',
    'type_format_datetime',
    'placeholder_text_date',
    'input_inner_size_date',
    'color_date',
    'custom_color_date',
    'type_format_init_date',
    'size_input_time',
    'type_format_time',
    'color_time',
    'custom_color_time',
    'type_format_init_time',
    'input_inner_size_time',
  ];

  $array_checkbox = [
    'font_bold',
    'is_show_title',
  ];

  foreach ($array_checkbox as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '0';
  }
  foreach ($array_value as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '';
  }

  $color = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';

?>
  <!-- การปรับแต่งหัวข้อช่องกรอก -->
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $is_show_title ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : ''  ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div id="scope_customize_date">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งช่องกรอกข้อมูลวันที่ (Date)</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ขนาดช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select', $size_input_date, ['id' => 'event_input_size', 'name' => 'size_input_date'], $size_display_input); ?>
          </div>
          <div class="fonf-16px fw-medium">: 200 pixel</div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_datetime, ['id' => '', 'name' => 'type_format_datetime'], $option_format_datetime); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $input_inner_size_date, ['name' => 'input_inner_size_date', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_date" id="radio-date-info" <?= $color_date == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-date-info" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_date" id="radio-date-secondary" <?= $color_date == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-date-secondary" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_date" id="radio-date-primary" <?= $color_date == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-date-primary" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_date">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_date" type="color" name="custom_color_date" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_date ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold required">รูปแบบข้อมูลตั้งต้น</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_init_date, ['id' => '', 'name' => 'type_format_init_date'], $option_format_init_date); ?>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div id="scope_customize_time">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งช่องกรอกข้อมูลเวลา (Time)</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ขนาดช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100 ">
            <?= TiwForm::normal('select', $size_input_time, ['id' => 'event_input_size', 'name' => 'size_input_time'], $size_display_input); ?>
          </div>
          <div class="fonf-16px fw-medium">: 200 pixel</div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_time, ['id' => '', 'name' => 'type_format_time'], $option_format_time); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', '16', ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_time" id="radio-time-info" <?= $color_time == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-time-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_time" id="radio-time-secondary" <?= $color_time == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-time-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_time" id="radio-time-primary" <?= $color_time == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-time-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_time">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_time" type="color" name="custom_color_time" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color_time ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold required">รูปแบบข้อมูลตั้งต้น</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('select', $type_format_init_time, ['id' => '', 'name' => 'type_format_init_time'], $option_format_init_time); ?>
        </div>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_form_number($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '100',
        'name' => '25%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '410',
        'name' => '100%'
      ],
    ]
  ];

  $array_value = [
    'input_title',
    'font_size_title',
    'font_bold',
    'custom_color',
    'custom_color_inner',
    'size_input',
    'is_set_number',
    'placeholder_text',
    'is_save_minus',
    'is_min_max',
    'min',
    'max',
    'input_inner_size',
    'is_show_placeholder',
    'placeholder_text',
    'color_placeholder',
    'digit_min',
    'digit_max',
  ];

  $array_checkbox = [
    'font_bold',
    'is_show_title',
  ];

  foreach ($array_checkbox as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '0';
  }
  foreach ($array_value as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '';
  }

  $color = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
  $color_inner = isset($data['color_inner']) ? $data['color_inner'] : 'var(--v2-color-info)';
?>
  <!-- การปรับแต่งหัวข้อช่องกรอก -->
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $is_show_title ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div id="scope_customize_number">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_input); ?>
          </div>
          <div class="fonf-16px fw-medium">: 200 pixel</div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อกำหนดการกรอกข้อมูล</div>
      </div>
      <div class="col-sm-8">
        <div class="mb-10px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_set_number', 'checked' => $is_set_number == 1 ? 'true' : 'false'], ['style' => '3', 'label' => 'กำหนดหลักตัวเลข']); ?>
        </div>
        <div class="row mb-10px">
          <div class="col-sm-6">
            <div class="mb-5px">
              <?= TiwForm::normal('number', '', ['name' => 'digit_min', 'checked' => $digit_min == 1 ? 'true' : 'false'], []); ?>
            </div>
            <div class="font-15px text-secondary"> หลักต่ำสุด </div>
          </div>
          <div class="col-sm-6">
            <div class="mb-5px">
              <?= TiwForm::normal('number', '', ['name' => 'digit_max', 'checked' => $digit_max == 1 ? 'true' : 'false'], []); ?>
            </div>
            <div class="font-15px text-secondary"> หลักสูงสุด (ไม่เกิน 19) </div>
          </div>
        </div>
        <div class="mb-10px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_save_minus', 'checked' => $is_save_minus == 1 ? 'true' : 'false'], ['style' => '3', 'label' => 'สามารถบันทึกข้อมูลเป็นค่าติดลบได้']); ?>
        </div>
        <div class="mb-10px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_min_max', 'checked' => $is_min_max == 1 ? 'true' : 'false'], ['style' => '3', 'label' => 'กำหนดค่าต่ำสุดและค่าสูงสุด']); ?>
        </div>
        <div class="row mb-10px">
          <div class="col-sm-6">
            <div class="mb-5px">
              <?= TiwForm::normal('number', $min, ['name' => 'min'], []); ?>
            </div>
            <div class="font-15px text-secondary"> ค่าต่ำสุด </div>
          </div>
          <div class="col-sm-6">
            <div class="mb-5px">
              <?= TiwForm::normal('number', $max, ['name' => 'max'], []); ?>
            </div>
            <div class="font-15px text-secondary"> ค่าสูงสุด </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', '16', ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color_inner" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_inner ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $placeholder_text, ['id' => 'placeholder_text', 'name' => 'placeholder_text'], []); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm">
        </label>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_form_decimal($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '100',
        'name' => '25%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '410',
        'name' => '100%'
      ],
    ]
  ];

  $array_value = [
    'input_title',
    'font_size_title',
    'font_bold',
    'custom_color',
    'custom_color_inner',
    'size_input',
    'is_set_number',
    'placeholder_text',
    'is_save_minus',
    'is_min_max',
    'min',
    'max',
    'input_inner_size',
    'is_show_placeholder',
    'color_placeholder',
    'digit_min',
    'digit_max',
  ];

  $array_checkbox = [
    'font_bold',
    'is_show_title',
  ];

  foreach ($array_checkbox as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '0';
  }
  foreach ($array_value as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '';
  }

  $color = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
  $color_inner = isset($data['color_inner']) ? $data['color_inner'] : 'var(--v2-color-info)';
?>
  <!-- การปรับแต่งหัวข้อช่องกรอก -->
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $is_show_title == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : ''  ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div id="scope_customize_number">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_input); ?>
          </div>
          <div class="fonf-16px fw-medium">: 200 pixel</div>
        </div>
      </div>
    </div>

    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">จำนวนหลักทั้งหมด</div>
      </div>
      <div class="col-sm-8">
        <div class="font-15px text-secondary fw-semibold">
          <span id="event_show_digit"><?= $field_setting['digit'] ?></span> หลัก <span class="text-secondary">จำนวนหลักที่บันทึกได้</span>
          <input type="hidden" name="digit" id="__val__digit" value="<?= $field_setting['digit'] ?>">
          <input type="hidden" name="decimal" id="__val__decimal" value="<?= $field_setting['decimal'] ?>">
        </div>
      </div>
    </div>

    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">จำนวนหลักทศนิยม</div>
      </div>
      <div class="col-sm-8">
        <div class="font-15px text-secondary fw-semibold"><span id="event_show_decimal"><?= $field_setting['decimal'] ?></span> หลัก</div>
      </div>
    </div>

    <div class="row  mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อกำหนดการกรอกข้อมูล</div>
      </div>
      <div class="col-sm-8">
        <div class="mb-10px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_set_number', 'checked' => $is_set_number == 1 ? 'true' : 'false'], ['style' => '3', 'label' => 'กำหนดหลักตัวเลข']); ?>
        </div>
        <div class="mb-10px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_save_minus', 'checked' => $is_save_minus == 1 ? 'true' : 'false'], ['style' => '3', 'label' => 'สามารถบันทึกข้อมูลเป็นค่าติดลบได้']); ?>
        </div>
        <div class="row">
          <?php  ?>
          <div class="col-sm-6">
            <div>
              <?= TiwForm::normal('number', $min, ['name' => 'min'], []); ?>
            </div>
            <div class="font-15px text-secondary"> ค่าต่ำสุด </div>
          </div>
          <div class="col-sm-6">
            <div>
              <?= TiwForm::normal('number', $max, ['name' => 'max'], []); ?>
            </div>
            <div class="font-15px text-secondary"> ค่าสูงสุด </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร (ในช่องกรอก)</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_inner">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_inner" type="color" name="custom_color_inner" class="input-color-sm" value="<?= $custom_color_inner ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $placeholder_text, ['id' => 'placeholder_text', 'name' => 'placeholder_text'], []); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm">
        </label>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}



function template_form_upload($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $array_value = [
    'advice',
    'input_title',
  ];

  $array_checkbox = [
    'font_bold',
    'is_preview_img',
    'is_show_advice',
    'type_select_all',
    'type_img_all',
    'pdf',
    'png',
    'jpeg',
    'jpg',
    'type_video_all',
    'mp3',
    'mp4',
    'wav',
    'type_file_all',
    'svg',
    'json',
    'csv',
    'xlsx',
    'xlm',
    'rar',
    'zip',
    'db',
    'txt',
    'text',
    'doc',
    'xls',
    'ptt',
    'prg',
    'is_edit_fill',
    'is_required_fill',
    'is_show_list_fill',
    'is_same_data',
    'size_display',
    'alignment',
    'type_display'
  ];

  foreach ($array_checkbox as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '0';
  }
  foreach ($array_value as $name) {
    ${$name} = (isset($data[$name]) && $is_edit == true) ? $data[$name] : '';
  }

  $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : 16;
  $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : 1;
  $color = isset($data['color']) ? $data['color'] : 'var(--v2-color-info)';
?>
  <!-- การปรับแต่งหัวข้อช่องกรอก -->
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $is_show_title == 1 ? 1 : 0], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : ''  ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : ''  ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : ''  ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" value="<?= $custom_color ?>">
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
        <div class="font-15px fw-bold">การปรับแต่งรูปแบบการบันทึกข้อมูล</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="font-16px fw-medium">ปุ่มอัปโหลด</div>
        <div class="d-flex align-items-center my-10px">
          <div class="mr-10px">
            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/btn_upload.svg') ?>
          </div>
          <div class="font-16px fw-medium text-secondary">**ตัวอย่างปุ่ม</div>
        </div>
        <div>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_preview_img', 'checked' => $is_preview_img == 1 ? 'true' : 'false'], ['style' => '3', 'label' => 'พรีวิวไฟล์เมื่ออัปโหลดสำเร็จ (เฉพาะชนิดรูปภาพ)']); ?>
        </div>
      </div>
    </div>

    <div class="row mb-15px">
      <div class="col-12">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_advice', 'checked' => $is_show_advice == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'คำแนะนำการอัปโหลด (ใต้ปุ่มอัปโหลด)', 'is_on_off' => true]); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">คำแนะนำ</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('textarea', $advice, ['id' => '', 'name' => 'advice']); ?>
        </div>
      </div>
    </div>
  </div>

  <div id="scope_condition_upload">
    <div class="row">
      <div class="col-12">
        <div class="mb-10px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'type_select_all', 'id' => 'select_all_type', 'checked' => $type_select_all == 1 ? 1 : 0, 'onclick' => 'selectOptionAllType();'], ['style' => '3', 'label' => 'เลือกทั้งหมด']); ?>
        </div>
        <div class="scope_event_checked_all">
          <div class="mb-10px">
            <?= TiwForm::normal('checkbox', '1', ['name' => 'type_img_all', 'id' => 'select_all_img', 'checked' => $type_img_all == 1 ? 1 : 0, 'onclick' => "selectOptionTypeUpload('img');"], ['style' => '3', 'label' => 'ไฟล์ประเภทรูปภาพทั้งหมด']); ?>
          </div>
          <div class="d-flex flex-wrap pl-25px event_check_box event_check_img_all">
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'png', 'checked' => $png == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeImage();'], ['style' => '3', 'label' => '.png']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'jpeg', 'checked' => $jpeg == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeImage();'], ['style' => '3', 'label' => '.jpeg']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'jpg', 'checked' => $jpg == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeImage();'], ['style' => '3', 'label' => '.jpg']); ?>
            </div>
          </div>
          <div class="mb-10px">
            <?= TiwForm::normal('checkbox', '1', ['name' => 'type_video_all', 'id' => 'select_all_video', 'checked' => $type_video_all == 1 ? 1 : 0, 'onclick' => "selectOptionTypeUpload('video');"], ['style' => '3', 'label' => 'ไฟล์ประเภทวิดีโอทั้งหมด']); ?>
          </div>
          <div class="d-flex flex-wrap pl-25px event_check_box event_check_video_all">
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'mp3', 'checked' => $mp3 == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeVideo();'], ['style' => '3', 'label' => '.mp3']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'mp4', 'checked' => $mp4 == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeVideo();'], ['style' => '3', 'label' => '.mp4']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'wav', 'checked' => $wav == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeVideo();'], ['style' => '3', 'label' => '.wav']); ?>
            </div>
          </div>
          <div class="w-100px mb-10px">
            <?= TiwForm::normal('checkbox', '1', ['name' => 'type_file_all', 'id' => 'select_all_file', 'checked' => $type_file_all == 1 ? 1 : 0, 'onclick' => "selectOptionTypeUpload('file');"], ['style' => '3', 'label' => 'ไฟล์อื่น ๆ']); ?>
          </div>
          <div class="d-flex flex-wrap pl-25px event_check_box event_check_file_all">
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'pdf', 'checked' => $pdf == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.pdf']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'svg', 'checked' => $svg == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.svg']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'json', 'checked' => $json == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.json']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'csv', 'checked' => $csv == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.csv']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'xlsx', 'checked' => $xlsx == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.xlsx']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'xlm', 'checked' => $xlm == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.xlm']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'rar', 'checked' => $rar == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.rar']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'zip', 'checked' => $zip == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.zip']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'db', 'checked' => $db == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.db']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'txt', 'checked' => $txt == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.txt']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'text', 'checked' => $text == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.text']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'doc', 'checked' => $doc == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.doc']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'xls', 'checked' => $xls == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.xls']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'ptt', 'checked' => $ptt == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.ptt']); ?>
            </div>
            <div class="w-100px mb-10px">
              <?= TiwForm::normal('checkbox', '1', ['name' => 'prg', 'checked' => $prg == 1 ? 1 : 0, 'onclick' => 'selectOptionTypeFile();'], ['style' => '3', 'label' => '.prg']); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_setting_page($is_edit = false, $data = [], $type = '')
{
  $page_col = isset($data['page_col']) ? $data['page_col'] : 1;
  $page_spacing = isset($data['page_spacing']) ? $data['page_spacing'] : '10';
  $is_edit = isset($data['is_edit']) ? $data['is_edit'] : 0;
  $is_delete = isset($data['is_delete']) ? $data['is_delete'] : 0;
  ob_start();
?>
  <div class="container-fluid px-20px">
    <div class="row">
      <div class="col-sm-12 mt-20px mb-20px">
        <div class="font-15px fw-bold text-primary">รูปแบบจอแสดงผลปัจจุบัน</div>
        <div class="font-16px fw-semibold text-dark mt-15px">50 : 50</div>
        <div class="font-15px fw-medium text-secondary mt-5px">แสดงข้อมูลในหน้าเดียว แบ่งเป็นรายการผู้ใช้งานครึ่งหน้า และ รายละเอียดผู้ใช้ครึ่งหน้า</div>
        <div class="w-400px mt-10px">
          <img src=" <?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/type-01-border.png"') ?>" alt="" class="w-100">
        </div>
      </div>
    </div>
    <hr>

    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">สัดส่วนหน้ากระดาษ<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex mb-15px">
          <?= TiwForm::normal('radio', '1', ['name' => 'page_col', 'checked' => $page_col == 1 ? 'true' : 'false'], ['style' => '2']); ?>
          <div class="ml-10px">
            <div class="font-16px fw-medium">1 ส่วน</div>
            <div class="text-secondary font-15px fw-medium">บังคับสร้างเนื้อหาบนพื้นที่แสดงผล 100%</div>
          </div>
        </div>
        <div class="d-flex mb-15px">
          <?= TiwForm::normal('radio', '2', ['name' => 'page_col', 'checked' => $page_col == 2 ? 'true' : 'false'], ['style' => '2']); ?>
          <div class="ml-10px">
            <div class="font-16px fw-medium">2 ส่วน</div>
            <div class="text-secondary font-15px fw-medium">สามารถสร้างเนื้อหาบนพื้นที่ 50 % ได้ <br> (ใน 1 แถวสามารถสร้างเนื้อหาได้สองส่วน)</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-15px align-items-center">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ระยะห่างระหว่างเนื้อหา<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('number', $page_spacing, ['id' => '', 'name' => 'page_spacing']); ?>
          </div>
          <div class="fonf-16px fw-medium">Pixel</div>
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อกำหนดและเงื่อนไขเฉพาะของหน้า</div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit', 'checked' => $is_edit == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'สามารถแก้ไขข้อมูลภายในหน้าได้ (ปุ่มแก้ไข) *ไม่เกี่ยวกับข้อมูลบัญชีผู้ใช้', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_delete', 'checked' => $is_delete == 1 ? 'true' : 'false'], ['style' => '1', 'label' => 'สามารถลบข้อมูลได้ (ลบข้อมูล)', 'is_on_off' => true]); ?>
      </div>
    </div>
    <hr>
  </div>
<?php return ob_get_clean();
}


function template_setting_page_addpage($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $page_spacing = isset($data['page_spacing']) ? $data['page_spacing'] : '10';
?>
  <div class="container-fluid px-20px">
    <div class="row">
      <div class="col-sm-12 mt-20px mb-20px">
        <div class="font-15px fw-bold text-primary">รูปแบบจอแสดงผลปัจจุบัน</div>
        <div class="font-16px fw-semibold text-dark mt-15px">Modal สไลด์ด้านข้าง</div>
        <div class="font-15px fw-medium text-secondary mt-5px">จอสไลด์จากด้านข้าง ข้อมูลต่าง ๆ ภายในหน้านี้จะถูกบังคับกว้าง 100%</div>
        <div class="w-400px mt-20px">
          <img src=" <?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/page-slider.png"') ?>" alt="" class="w-100">
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-sm-12">
        <div class="font-16px fw-semibold text-dark mt-15px">การปรับแต่งโครงสร้างข้อมูลภายในหน้าแสดงผล</div>
      </div>
    </div>
    <?= TiwForm::normal('hidden', '1', ['name' => 'page_col']); ?>
    <div class="row mb-15px align-items-center">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ระยะห่างระหว่างเนื้อหา<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('number', $page_spacing, ['id' => '', 'name' => 'page_spacing']); ?>
          </div>
          <div class="fonf-16px fw-medium">Pixel</div>
        </div>
      </div>
    </div>
    <hr>
  </div>
<?php return ob_get_clean();
}


function template_form_import_all($code)
{
  ob_start();
  $where  = [
    'code' => $code
  ];
  $extra_template = Extra::selectExtraTemplate($where);
  $key = [
    'value' => 'id',
    'name' => 'template',
  ];
  $options = TiwForm::generateSelectData($extra_template, $key, []);
  // $list_meta_data = [];
  // $count_tab = [];
  // $count_fill = [];
  // foreach ($extra_template as $key_data => $meta_data) {
  //   $list_meta_data[$key_data]['meta_data'] = json_decode($meta_data['meta_data'], true);
  // }
  // foreach ($list_meta_data as $key_meta => $meta_data) {
  //   $count_tab[$key_data]['meta_data'] = json_decode($meta_data['meta_data'], true);
  // }
  // W_Main::display($extra_template);
?>
  <div class="px-20px mt-20px">
    <div class="row mb-15px">
      <div class="col-12">
        <div class="fw-bold font-15px">เลือกข้อมูล</div>
      </div>
    </div>
    <div class="row align-items-center mb-15px">
      <div class="col-sm-4">
        <div class="required font-16px text-secondary fw-semibold">หน้าที่ต้องการ</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('select', '', ['id' => 'event_select_page', 'name' => 'page'], $options); ?>
      </div>
    </div>
    <div class="row align-items-center mb-20px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">จำนวนข้อมูล</div>
      </div>
      <div class="col-sm-8">
        <span class="font-16px text-secondary">โปรดเลือกหน้าที่ต้องการนำเข้าข้อมูลก่อน</span>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}

function template_form_import($code)
{
  ob_start();
  $where  = [
    'code' => $code
  ];
  $extra_template = Extra::selectExtraTemplate($where);
  $key = [
    'value' => 'id',
    'name' => 'template',
  ];
  $options = TiwForm::generateSelectData($extra_template, $key, []);

?>
  <div class="px-20px mt-20px">
    <div class="row mb-15px">
      <div class="col-12">
        <div class="fw-bold font-15px">เลือกข้อมูล</div>
      </div>
    </div>
    <div class="row align-items-center mb-15px">
      <div class="col-sm-4">
        <div class="required font-16px text-secondary fw-semibold">หน้าที่ต้องการ</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('select', '', ['id' => 'event_select_page', 'name' => 'page', 'onchange' => 'selectFieldImport();'],  $options); ?>
      </div>
    </div>
    <div id="scope_show_detail_import"></div>
  </div>


<?php return ob_get_clean();
}


function template_form_condition($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $alignment =  'left';
  $type_display =  1;
  $is_click_link = 1;
  $is_copy_link = 1;
  $is_click_video = 1;
  $video_type_preview = 1;
  if ($is_edit == true) {
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $is_click_link = isset($data['is_click_link']) ? $data['is_click_link'] : '';
    $is_copy_link = isset($data['is_copy_link']) ? $data['is_copy_link'] : '';
    $is_click_video = isset($data['is_click_video']) ? $data['is_click_video'] : '';
    $video_type_preview = isset($data['video_type_preview']) ? $data['video_type_preview'] : '';
  }
?>
  <div id="scope_condition">
    <div class="row mb-20px mt-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
      </div>
    </div>

    <?php if ($type == 'Upload') : ?>
      <div class="row">
        <div class="col-sm-12  mb-15px">
          <div class="d-flex align-items-center">
            <div class="mr-5px">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/check_icon.svg') ?>
            </div>
            <div class="text-primary font-16px">จำกัดการอัปโหลดครั้งละ 1 ไฟล์</div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill', 'checked' => $is_edit_fill == 1 ? 1 : 0], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill', 'checked' => $is_required_fill == 1 ? 1 : 0], ['style' => '1', 'label' => 'ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ <br>ไม่สามารถบันทึกแบบฟอร์มได้', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill', 'checked' => $is_show_list_fill == 1 ? 1 : 0], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
      </div>
      <?php if ($type != 'Auto address') : ?>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_same_data', 'checked' => $is_same_data == 1 ? 1 : 0], ['style' => '1', 'label' => 'ข้อมูลที่บันทึกสามารถซ้ำกันได้', 'is_on_off' => true]); ?>
        </div>
      <?php endif; ?>
      <?php if ($type == 'Google map link' || $type == 'Link') : ?>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_click_link', 'checked' => $is_click_link == 1 ? 1 : 0], ['style' => '1', 'label' => 'กดลิงก์ไปยังปลายทางได้', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_copy_link', 'checked' => $is_copy_link == 1 ? 1 : 0], ['style' => '1', 'label' => 'ปุ่มคัดลอกลิงก์', 'is_on_off' => true]); ?>
        </div>
      <?php endif; ?>
      <?php if ($type == 'Youtube_embed') : ?>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_click_video', 'checked' => $is_click_video == 1 ? 1 : 0], ['style' => '1', 'label' => 'กดวิดีโอไปยังปลายทางได้', 'is_on_off' => true]); ?>
        </div>
      <?php endif; ?>
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
            <?= TiwForm::normal('select', $size_display, ['name' => 'size_display'], $size_display_list); ?>
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
            <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $alignment == 'left' ? 'checked' : '' ?>>
            <label for="checkbox-align-left">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
              </div>
            </label>
          </div>
          <div class="checkbox-bold">
            <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $alignment == 'right' ? 'checked' : '' ?>>
            <label for="checkbox-align-right">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>
    <?php if ($type == 'Youtube_embed') : ?>
      <div class="row">
        <div class="col-sm-4 mb-15px">
          <div class="font-16px text-secondary fw-semibold">การแสดงผลหลังบันทึกข้อมูล</div>
        </div>
        <div class="col-sm-8 mb-15px">
          <div class="d-flex mb-10px">
            <div class="mr-10px">
              <?= TiwForm::normal('radio', '1', ['name' => 'video_type_preview', 'checked' => $video_type_preview == 1 ? true : false], ['style' => '2']); ?>
            </div>
            <div>
              <div class="font-16px fw-medium">
                Youtube Embed
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div class="mr-10px">
              <?= TiwForm::normal('radio', '2', ['name' => 'video_type_preview', 'checked' => $video_type_preview == 2 ? true : false], ['style' => '2']); ?>
            </div>
            <div>
              <div class="font-16px fw-medium">
                พรีวิว วิดีโอ
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="row">
      <div class="col-sm-12 mb-15px">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-12 mb-15px">

        <div class="d-flex">
          <div class="mr-10px">
            <?= TiwForm::normal('radio', '1', ['name' => 'type_display', 'checked' => $type_display == 1 ? 1 : 0], ['style' => '2']); ?>
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
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/info.svg') ?>
              </span>
            </div>
            <div class="text-secondary">หัวข้อช่องกรอกอยู่ด้านข้าง</div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 mb-15px">
        <div class="d-flex">
          <div class="mr-10px">
            <?= TiwForm::normal('radio', '2', ['name' => 'type_display', 'checked' => $type_display == 2 ? 1 : 0], ['style' => '2']); ?>
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
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/info.svg') ?>
              </span>
            </div>
            <div class="text-secondary">หัวข้อช่องกรอกอยู่ด้านบน และช่องกรอกอยู่ด้านล่าง</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
<?php return ob_get_clean();
}

function template_form_select_tag($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $color_select_tag =  'var(--v2-color-optional-1)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $is_show_title = 1;
  $is_add_more_option = 0;
  $custom_color_placeholder = '#000000';
  $option = [];
  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $is_add_more_option = isset($data['is_add_more_option']) ? $data['is_add_more_option'] : '';
    $option = isset($data['option']) ? $data['option'] : [];
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
        <div class="font-15px fw-bold">การปรับแต่งช่องกรอกข้อมูล</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">พื้นหลังแท็ก</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-optional-1)" name="color_select_tag" id="radio-primary" <?= $color_select_tag == 'var(--v2-color-optional-1)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-color-optional-1">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_select_tag">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_select_tag" type="color" name="custom_color_select_tag" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ฟังก์ชั่นช่องแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_add_more_option', 'checked' => $is_add_more_option == 1 ? true : false], ['style' => '1', 'label' => 'สามารถสร้างตัวเลือกเพิ่มได้', 'is_on_off' => true]); ?>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">กำหนดการใช้งานตัวเลือก <span class=" font-Regular">2 รายการ</span></div>
      </div>
      <div class="col-sm-12">
        <table class="table jform-table-custom">
          <thead>
            <tr>
              <th class=" min-w-150px thin-cell">การใช้งาน</th>
              <th>ตัวเลือก</th>
            </tr>
          </thead>
          <tbody class="scope_option_tag">
            <?php if ($data) { ?>
              <?php if ($option) { ?>
                <?php
                foreach ($option as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = isset($option_list['is_open']) ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list['name'] ?>">
                    </td>
                    <td><?= $option_list['name'] ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php if ($field_setting) { ?>
                <?php
                foreach ($field_setting['json_data'] as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = true ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list ?>">
                    </td>
                    <td><?= $option_list ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_form_setting_condition_tag($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $alignment =  'left';
  $type_display =  1;

  if ($is_edit == true) {
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
  }
?>
  <div id="scope_condition">
    <div class="row mb-20px mt-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill', 'checked' => $is_edit_fill == 1 ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px d-flex align-items-start">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill', 'checked' => $is_required_fill == 1 ? true : false], ['style' => '1', 'is_on_off' => true]); ?>
        <label class="ml-5px" for="__p5odqvabhx">
          ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้ได้รวมถึงจำเป็นต้องมีในหน้าเพิ่มข้อมูล (Add data)
        </label>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill', 'checked' => $is_show_list_fill == 1 ? true : false], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
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
        <?= TiwForm::normal('hidden', $type_display, ['name' => 'type_display']); ?>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select', $size_display, ['name' => 'size_display'], $size_display_list); ?>
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
            <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $alignment == 'left' ? 'checked' : '' ?>>
            <label for="checkbox-align-left">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
              </div>
            </label>
          </div>
          <div class="checkbox-bold">
            <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $alignment == 'right' ? 'checked' : '' ?>>
            <label for="checkbox-align-right">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
<?php return ob_get_clean();
}

function template_form_select_dropdown($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $color_select_tag =  'var(--v2-color-optional-1)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $is_show_title = 1;
  $is_add_more_option = 0;
  $custom_color_placeholder = '#000000';
  $option = [];
  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $is_add_more_option = isset($data['is_add_more_option']) ? $data['is_add_more_option'] : '';
    $option = isset($data['option']) ? $data['option'] : [];
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
        <div class="font-15px fw-bold">การปรับแต่งช่องกรอกข้อมูล</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">พื้นหลังแท็ก</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-optional-1)" name="color_select_tag" id="radio-primary" <?= $color_select_tag == 'var(--v2-color-optional-1)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-color-optional-1">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_select_tag">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_select_tag" type="color" name="custom_color_select_tag" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ฟังก์ชั่นช่องแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_add_more_option', 'checked' => $is_add_more_option == 1 ? true : false], ['style' => '1', 'label' => 'สามารถสร้างตัวเลือกเพิ่มได้', 'is_on_off' => true]); ?>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">กำหนดการใช้งานตัวเลือก <span class=" font-Regular">2 รายการ</span></div>
      </div>
      <div class="col-sm-12">
        <table class="table jform-table-custom">
          <thead>
            <tr>
              <th class=" min-w-150px thin-cell">การใช้งาน</th>
              <th>ตัวเลือก</th>
            </tr>
          </thead>
          <tbody class="scope_option_dropdown">
            <?php if ($data) { ?>
              <?php if ($option) { ?>
                <?php
                foreach ($option as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = isset($option_list['is_open']) ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list['name'] ?>">
                    </td>
                    <td><?= $option_list['name'] ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php if ($field_setting) { ?>
                <?php
                foreach ($field_setting['json_data'] as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = true ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list ?>">
                    </td>
                    <td><?= $option_list ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}


function template_form_setting_condition_dropdown($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $alignment =  'left';
  $type_display =  1;

  if ($is_edit == true) {
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
  }
?>
  <div id="scope_condition">
    <div class="row mb-20px mt-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill', 'checked' => $is_edit_fill == 1 ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px d-flex align-items-start">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill', 'checked' => $is_required_fill == 1 ? true : false], ['style' => '1', 'is_on_off' => true]); ?>
        <label class="ml-5px" for="__p5odqvabhx">
          ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้ได้รวมถึงจำเป็นต้องมีในหน้าเพิ่มข้อมูล (Add data)
        </label>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill', 'checked' => $is_show_list_fill == 1 ? true : false], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
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
        <?= TiwForm::normal('hidden', $type_display, ['name' => 'type_display']); ?>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select', $size_display, ['name' => 'size_display'], $size_display_list); ?>
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
            <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $alignment == 'left' ? 'checked' : '' ?>>
            <label for="checkbox-align-left">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
              </div>
            </label>
          </div>
          <div class="checkbox-bold">
            <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $alignment == 'right' ? 'checked' : '' ?>>
            <label for="checkbox-align-right">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
<?php return ob_get_clean();
}



function template_form_auto_address($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $size_input = '100';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $type_input_text = 'text';
  $is_show_title = 1;

  $address =  '';
  $sub_district =  '';
  $district = '';
  $city = '';
  $zip = '';

  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $address = isset($data['address']) ? $data['address'] : '';
    $sub_district = isset($data['sub_district']) ? $data['sub_district'] : '';
    $district = isset($data['district']) ? $data['district'] : '';
    $city = isset($data['city']) ? $data['city'] : '';
    $zip = isset($data['zip']) ? $data['zip'] : '';

    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $type_input_text = isset($data['type_input_text']) ? $data['type_input_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_list); ?>
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
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? true : false], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-12">
            <?= TiwForm::normal('text', $address, ['id' => 'address', 'name' => 'address', 'placeholder' => 'ที่อยู่'], []); ?>
          </div>
          <div class="col-6">
            <?= TiwForm::normal('text', $sub_district, ['id' => 'sub_district', 'name' => 'sub_district', 'placeholder' => 'ตำบล / เขต'], []); ?>
          </div>
          <div class="col-6">
            <?= TiwForm::normal('text', $district, ['id' => 'district', 'name' => 'district', 'placeholder' => 'อำเภอ / แขวง'], []); ?>
          </div>
          <div class="col-6">
            <?= TiwForm::normal('text', $city, ['id' => 'city', 'name' => 'city', 'placeholder' => 'จังหวัด'], []); ?>
          </div>
          <div class="col-6">
            <?= TiwForm::normal('text', $zip, ['id' => 'zip', 'name' => 'zip', 'placeholder' => 'รหัสไปรษณีย์'], []); ?>
          </div>
        </div>

      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm" value="<?= $color_placeholder ?>">
        </label>
      </div>
    </div>

  </div>

<?php return ob_get_clean();
}


function template_form_google_map($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $size_input = '100';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $type_input_text = 'text';
  $is_show_title = 1;
  $link =  '';
  $is_show_advice =  0;
  $advice = '';

  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $link = isset($data['link']) ? $data['link'] : '';
    $is_show_advice = isset($data['is_show_advice']) ? 1 : 0;
    $advice = isset($data['advice']) ? $data['advice'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $type_input_text = isset($data['type_input_text']) ? $data['type_input_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_list); ?>
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
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? true : false], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-12">
            <?= TiwForm::normal('text', $link, ['id' => 'link', 'name' => 'link', 'placeholder' => 'กรอกหรือวางลิงก์จาก Google map'], []); ?>
          </div>
        </div>

      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm" value="<?= $color_placeholder ?>">
        </label>
      </div>
    </div>

    <div class="row mb-15px">
      <div class="col-12">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_advice', 'checked' => $is_show_advice == 1 ? 1 : 0], ['style' => '1', 'label' => 'คำแนะนำ (ใต้ช่องกรอก แสดงเฉพาะโหมดแก้ไข)', 'is_on_off' => true]); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">คำแนะนำ</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('textarea', $advice, ['id' => '', 'name' => 'advice']); ?>
        </div>
      </div>
    </div>
  </div>

<?php return ob_get_clean();
}


function template_form_latitude_longitude($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $size_input = '100';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $type_input_text = 'text';
  $is_show_title = 1;

  $latitude =  '';
  $longitude =  '';

  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $latitude = isset($data['latitude']) ? $data['latitude'] : '';
    $longitude = isset($data['longitude']) ? $data['longitude'] : '';
    $district = isset($data['district']) ? $data['district'] : '';
    $city = isset($data['city']) ? $data['city'] : '';
    $zip = isset($data['zip']) ? $data['zip'] : '';

    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $type_input_text = isset($data['type_input_text']) ? $data['type_input_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_list); ?>
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
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? true : false], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-6">
            <?= TiwForm::normal('text', $latitude, ['id' => 'latitude', 'name' => 'latitude', 'placeholder' => 'ละติจูด'], []); ?>
          </div>
          <div class="col-6">
            <?= TiwForm::normal('text', $longitude, ['id' => 'longitude', 'name' => 'longitude', 'placeholder' => 'ลองจิจูด'], []); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm" value="<?= $color_placeholder ?>">
        </label>
      </div>
    </div>

  </div>

<?php return ob_get_clean();
}


function template_form_link($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $size_input = '100';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $type_input_text = 'text';
  $is_show_title = 1;
  $is_show_advice =  1;

  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $is_show_advice = isset($data['is_show_advice']) ? 1 : 0;
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $type_input_text = isset($data['type_input_text']) ? $data['type_input_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_list); ?>
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
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? true : false], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-12">
            <?= TiwForm::normal('text', $placeholder_text, ['id' => 'placeholder_text', 'name' => 'placeholder_text', 'placeholder' => 'วางลิงก์'], []); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm" value="<?= $color_placeholder ?>">
        </label>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-12">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_advice', 'checked' => $is_show_advice == 1 ? true : false], ['style' => '1', 'label' => 'คำแนะนำ (ใต้ช่องกรอก แสดงเฉพาะโหมดแก้ไข)', 'is_on_off' => true]); ?>
      </div>
    </div>

  </div>

<?php return ob_get_clean();
}


function template_form_youtube_embed($is_edit = false, $data = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $size_input = '100';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $advice = '';
  $type_input_text = 'text';
  $is_show_title = 1;

  $is_show_advice =  1;

  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $is_show_advice = isset($data['is_show_advice']) ? 1 : 0;
    $advice = isset($data['advice']) ? $data['advice'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $type_input_text = isset($data['type_input_text']) ? $data['type_input_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
            <?= TiwForm::normal('select', $size_input, ['id' => 'event_input_size', 'name' => 'size_input'], $size_display_list); ?>
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
          <div class="max-w-100px w-100 mr-5px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class=" bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
        <div class="my-15px">
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_placeholder', 'checked' => $is_show_placeholder == 1 ? true : false], ['style' => '1', 'label' => 'แสดงข้อความตัวอย่าง ในช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-12">
            <?= TiwForm::normal('text', $placeholder_text, ['id' => 'placeholder_text', 'name' => 'placeholder_text', 'placeholder' => 'วาง YouTube embed'], []); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">กำหนดสีข้อความตัวอย่าง</div>
      </div>
      <div class="col-sm-8">
        <label class="btn-action-icon" for="event_color_placeholder">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
          <input id="event_color_placeholder" type="color" name="color_placeholder" class="input-color-sm" value="<?= $color_placeholder ?>">
        </label>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-12">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_advice', 'checked' => $is_show_advice == 1 ? true : false], ['style' => '1', 'label' => 'คำแนะนำ (ใต้ช่องกรอก แสดงเฉพาะโหมดแก้ไข)', 'is_on_off' => true]); ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">คำแนะนำ</div>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-12">
            <?= TiwForm::normal('textarea', $advice, ['id' => 'advice', 'name' => 'advice', 'placeholder' => 'ตย. รูปแบบ Embed ที่ถูกต้องwidth=”320” height=”180” YouTube URL [/embed]'], []); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php return ob_get_clean();
}

function template_form_radio($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $color_select_tag =  'var(--v2-color-optional-1)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $is_show_title = 1;
  $is_add_more_option = 0;
  $custom_color_placeholder = '#000000';
  $option = [];
  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $is_add_more_option = isset($data['is_add_more_option']) ? $data['is_add_more_option'] : '';
    $option = isset($data['option']) ? $data['option'] : [];
    $select_first_checkbox = isset($data['select_first_checkbox']) ? $data['select_first_checkbox'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
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
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
        <div class="font-15px fw-bold">การปรับแต่งช่องแสดงผลข้อมูล</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ตัวเลือกเริ่มต้น</div>
      </div>
      <div class="col-sm-8">
        <?php
        $options = [
          'list' => [
            [
              'value' => '',
              'name' => 'เลือกตัวเลือกเริ่มต้น',
            ],
          ]
        ];
        if ($data) {
          if ($option) {
            foreach ($option as $key => $option_list) {
              $options['list'][] = [
                'value' => $option_list['name'],
                'name' => $option_list['name'],
              ];
            }
            TiwForm::normal('select', $select_first_checkbox, ['name' => 'select_first_checkbox', 'required' => 'true'], $options);
          }
        } else {
          if ($field_setting) {
            if ($field_setting['json_data']) {
              foreach ($field_setting['json_data'] as $key => $option_list) {
                $options['list'][] = [
                  'value' => $option_list['value'],
                  'name' => $option_list['value'],
                ];
              }
            }
            TiwForm::normal('select', '', ['name' => 'select_first_checkbox', 'required' => 'true'], $options);
          }
        }
        ?>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">กำหนดการใช้งานตัวเลือก <span class=" font-Regular">2 รายการ</span></div>
      </div>
      <div class="col-sm-12">
        <table class="table jform-table-custom">
          <thead>
            <tr>
              <th class=" min-w-150px thin-cell">การใช้งาน</th>
              <th>ตัวเลือก</th>
            </tr>
          </thead>
          <tbody class="scope_option_dropdown">
            <?php if ($data) { ?>
              <?php if ($option) { ?>
                <?php
                foreach ($option as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = isset($option_list['is_open']) ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list['name'] ?>">
                      <?php
                      $is_other = isset($option_list['is_other']) ? $option_list['is_other'] : '';
                      if ($is_other) {
                      ?>
                        <input type="hidden" name="option[<?= $rand_id ?>][is_other]" value="<?= $is_other ?>">
                      <?php } ?>
                    </td>
                    <td><?= $option_list['name'] ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php if ($field_setting) { ?>

                <?php
                foreach ($field_setting['json_data'] as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = true ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list['value'] ?>">
                      <?php
                      $is_other = isset($option_list['is_other']) ? $option_list['is_other'] : '';
                      if ($is_other) {
                      ?>
                        <input type="hidden" name="option[<?= $rand_id ?>][is_other]" value="<?= $is_other ?>">
                      <?php } ?>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <?= $option_list['value']  ?>
                        <?php if (isset($option_list['is_text_other']) &&  $option_list['is_text_other']) {
                          TiwForm::normal('text', '', ['readonly' => 'true', 'class' => 'max-w-150px']);
                        }
                        ?>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}
?>

<?php
function template_form_setting_condition_radio($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $alignment =  'left';
  $type_display =  1;
  $format_display = 'toptobottom';
  if ($is_edit == true) {
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $format_display = isset($data['format_display']) ? $data['format_display'] : 'toptobottom';
  }
?>
  <div id="scope_condition">
    <div class="row mb-20px mt-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill', 'checked' => $is_edit_fill == 1 ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px d-flex align-items-start">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill', 'checked' => $is_required_fill == 1 ? true : false], ['style' => '1', 'is_on_off' => true]); ?>
        <label class="ml-5px" for="__p5odqvabhx">
          ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้ได้รวมถึงจำเป็นต้องมีในหน้าเพิ่มข้อมูล (Add data)
        </label>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill', 'checked' => $is_show_list_fill == 1 ? true : false], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
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
        <?= TiwForm::normal('hidden', $type_display, ['name' => 'type_display']); ?>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select', $size_display, ['name' => 'size_display'], $size_display_list); ?>
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
            <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $alignment == 'left' ? 'checked' : '' ?>>
            <label for="checkbox-align-left">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
              </div>
            </label>
          </div>
          <div class="checkbox-bold">
            <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $alignment == 'right' ? 'checked' : '' ?>>
            <label for="checkbox-align-right">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?php
          $option_format_display = [
            'list' => [
              [
                'value' => 'toptobottom',
                'name' => 'เรียงจากบนลงล่าง',
              ],
              [
                'value' => 'lefttoright',
                'name' => 'เรียงจากซ้ายไปขวา',
              ],
            ]
          ];
          ?>
          <?= TiwForm::normal('select', $format_display, ['name' => 'format_display'], $option_format_display); ?>
        </div>
      </div>
    </div>
  </div>
  <hr>
<?php return ob_get_clean();
}

function template_form_checkbox($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $color_select_tag =  'var(--v2-color-optional-1)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $is_show_title = 1;
  $is_add_more_option = 0;
  $custom_color_placeholder = '#000000';
  $option = [];
  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $is_add_more_option = isset($data['is_add_more_option']) ? $data['is_add_more_option'] : '';
    $option = isset($data['option']) ? $data['option'] : [];
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
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
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
        <div class="font-15px fw-bold">การปรับแต่งช่องแสดงผลข้อมูล</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">จำกัดการเลือก</div>
      </div>
      <div class="col-sm-8">
        <?php
        $limit_type = isset($field_setting['limit_type']) && $field_setting['limit_type']  ? $field_setting['limit_type'] : '';
        $limil_count = '';
        if ($limit_type == 'limit') {
          $limil_count = $field_setting['limit_length'];
          echo 'เลือกได้ไม่เกิน' . $limil_count . 'ตัวเลือก';
        } else {
          echo 'ไม่จำกัดจำนวนตัวเลือก';
        }
        TiwForm::normal('hidden',  $limil_count, ['name' => 'limil_count']);
        ?>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">กำหนดการใช้งานตัวเลือก <span class=" font-Regular">2 รายการ</span></div>
      </div>
      <div class="col-sm-12">
        <table class="table jform-table-custom">
          <thead>
            <tr>
              <th class=" min-w-150px thin-cell">การใช้งาน</th>
              <th>ตัวเลือก</th>
            </tr>
          </thead>
          <tbody class="scope_option_dropdown">
            <?php if ($data) { ?>
              <?php if ($option) { ?>
                <?php
                foreach ($option as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = isset($option_list['is_open']) ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list['name'] ?>">
                      <?php
                      $is_other = isset($option_list['is_other']) ? $option_list['is_other'] : '';
                      if ($is_other) {
                      ?>
                        <input type="hidden" name="option[<?= $rand_id ?>][is_other]" value="<?= $is_other ?>">
                      <?php } ?>
                    </td>
                    <td><?= $option_list['name'] ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php if ($field_setting['json_data']['list']) { ?>

                <?php
                foreach ($field_setting['json_data']['list'] as $key => $option_list) {
                  $rand_id = TiwForm::randomName(5);
                ?>
                  <tr>
                    <td>
                      <div class="scope_box_event d-flex align-items-center ">
                        <div class="checkbox-group">
                          <?php
                          $checked = true ? 'checked' : '';
                          ?>
                          <input type="checkbox" name="option[<?= $rand_id ?>][is_open]" value="1" id="<?= $rand_id ?>" <?= $checked ?>>
                          <label class="mb-0" for="<?= $rand_id ?>"></label>
                        </div>
                        <label for="<?= $rand_id ?>" class="label_text ml-5px mb-0 cursor-pointer">ใช้งาน</label>
                      </div>
                      <input type="hidden" name="option[<?= $rand_id ?>][name]" value="<?= $option_list['value'] ?>">
                      <?php
                      $is_other = isset($option_list['is_other']) ? $option_list['is_other'] : '';
                      if ($is_other) {
                      ?>
                        <input type="hidden" name="option[<?= $rand_id ?>][is_other]" value="<?= $is_other ?>">
                      <?php } ?>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <?= $option_list['value']  ?>
                        <?php if (isset($option_list['is_text_other']) &&  $option_list['is_text_other']) {
                          TiwForm::normal('text', '', ['readonly' => 'true', 'class' => 'max-w-150px']);
                        }
                        ?>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php return ob_get_clean();
}
?>

<?php
function template_form_setting_condition_checkbox($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $alignment =  'left';
  $type_display =  1;
  $format_display = 'toptobottom';
  if ($is_edit == true) {
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $format_display = isset($data['format_display']) ? $data['format_display'] : 'toptobottom';
  }
?>
  <div id="scope_condition">
    <div class="row mb-20px mt-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill', 'checked' => $is_edit_fill == 1 ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px d-flex align-items-start">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill', 'checked' => $is_required_fill == 1 ? true : false], ['style' => '1', 'is_on_off' => true]); ?>
        <label class="ml-5px" for="__p5odqvabhx">
          ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้ได้รวมถึงจำเป็นต้องมีในหน้าเพิ่มข้อมูล (Add data)
        </label>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill', 'checked' => $is_show_list_fill == 1 ? true : false], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
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
        <?= TiwForm::normal('hidden', $type_display, ['name' => 'type_display']); ?>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select', $size_display, ['name' => 'size_display'], $size_display_list); ?>
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
            <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $alignment == 'left' ? 'checked' : '' ?>>
            <label for="checkbox-align-left">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
              </div>
            </label>
          </div>
          <div class="checkbox-bold">
            <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $alignment == 'right' ? 'checked' : '' ?>>
            <label for="checkbox-align-right">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบการแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <?php
          $option_format_display = [
            'list' => [
              [
                'value' => 'toptobottom',
                'name' => 'เรียงจากบนลงล่าง',
              ],
              [
                'value' => 'lefttoright',
                'name' => 'เรียงจากซ้ายไปขวา',
              ],
            ]
          ];
          ?>
          <?= TiwForm::normal('select', $format_display, ['name' => 'format_display'], $option_format_display); ?>
        </div>
      </div>
    </div>
  </div>
  <hr>
<?php return ob_get_clean();
}

function template_form_switch($is_edit = false, $data = [], $field_setting = [])
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $size_display_input = [
    'list' => [
      [
        'value' => '410',
        'name' => '100%'
      ],
      [
        'value' => '200',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '25%'
      ],
    ]
  ];


  $field_name = '';
  $db_column_name = '';
  $type = '';
  $field_id = '';
  $input_title = '';
  $font_size_title =  '16';
  $input_inner_size = '16';
  $color = 'var(--v2-color-info)';
  $color_inner =  'var(--v2-color-info)';
  $color_select_tag =  'var(--v2-color-optional-1)';
  $size_display =  '100';
  $font_bold =  '';
  $custom_color = '';
  $is_show_placeholder = '';
  $placeholder_text = '';
  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $type_display =  1;
  $color_placeholder = '';
  $is_show_title = 1;
  $is_add_more_option = 0;
  $is_show_switch_text = 1;
  $custom_color_placeholder = '#000000';
  $option = [];
  if ($is_edit == true) {
    $field_name = isset($data['field_name']) ? $data['field_name'] : '';
    $db_column_name = isset($data['db_column_name']) ? $data['db_column_name'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $field_id = isset($data['field_id']) ? $data['field_id'] : '';
    $input_title = isset($data['input_title']) ? $data['input_title'] : '';
    $font_size_title = isset($data['font_size_title']) ? $data['font_size_title'] : '';
    $color = isset($data['color']) ? $data['color'] : '';
    $input_inner_size = isset($data['input_inner_size']) ? $data['input_inner_size'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $font_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $is_show_placeholder = isset($data['is_show_placeholder']) ? $data['is_show_placeholder'] : '';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $color_placeholder = isset($data['color_placeholder']) ? $data['color_placeholder'] : '';
    $is_show_title = isset($data['is_show_title']) ? $data['is_show_title'] : '';
    $is_add_more_option = isset($data['is_add_more_option']) ? $data['is_add_more_option'] : '';
    $option = isset($data['option']) ? $data['option'] : [];
    $is_show_switch_text = isset($data['is_show_switch_text']) ? $data['is_show_switch_text'] : '';
  }
?>
  <div id="scope_customize_topic">
    <div class="row">
      <div class="col-sm-12 mb-20px">
        <div class="font-15px fw-bold">การปรับแต่งหัวข้อช่องกรอก</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_title == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_title', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงหัวข้อช่องกรอก', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', $input_title, ['id' => 'event_input_name', 'name' => 'input_title', 'required' => 'true'], []); ?>
        <div class="font-15px mt-5px text-secondary">การเปลี่ยนแปลงไม่ส่งผลต่อ Field ใน Database</div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-10px">
            <?= TiwForm::normal('select', $font_size_title, ['name' => 'font_size_title', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="mx-10px pr-10px border-right">
            <div class="checkbox-bold">
              <input type="checkbox" value="1" name="font_bold" id="checkbox-bold" <?= $font_bold == 1 ? 'checked' : '' ?>>
              <label for="checkbox-bold">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                </div>
              </label>
            </div>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color" id="radio-info--input" <?= $color == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info--input" class="bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color" id="radio-secondary--input" <?= $color == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary--input" class="bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color" id="radio-primary--input" <?= $color == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary--input" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color" type="color" name="custom_color" class="input-color-sm" onchange="customInputTitleColor(this)" value="<?= $custom_color ?>">
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
        <div class="font-15px fw-bold">การปรับแต่งช่องแสดงผลข้อมูล</div>
        <div class="mt-15px">
          <?php $check_show_title =  $is_show_switch_text == 1 ? 1 : 0 ?>
          <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_switch_text', 'checked' => $check_show_title], ['style' => '1', 'label' => 'แสดงค่าที่เลือกหลังสวิตซ์', 'is_on_off' => true]); ?>
        </div>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบค่า</div>
      </div>
      <div class="col-sm-8">
        <?php
        $swite_type = '';
        $swite_type_text = '';
        if (isset($field_setting['json_data'])) {
          $swite_type = $field_setting['json_data']['type'];
        }
        if (isset($data) && $data) {
          $swite_type = $data['swite_type'];
        }
        if ($swite_type == 'default') {
          $swite_type_text = 'กำหนดเอง';
        } else if ($swite_type == 'YesorNo') {
          $swite_type_text = 'Yes or No';
        } else if ($swite_type == 'TrueorFalse') {
          $swite_type_text = 'True or False';
        }
        TiwForm::normal('hidden',  $swite_type, ['name' => 'swite_type']);
        ?>
        <?= $swite_type_text ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">ค่าแสดงผล</div>
      </div>
      <div class="col-sm-8">
        <?php
        $true_text = '';
        $false_text = '';
        if (isset($field_setting['json_data'])) {
          $true_text = $field_setting['json_data']['true_text'];
          $false_text = $field_setting['json_data']['false_text'];
        }
        if (isset($data) && $data) {
          $true_text = $data['true_text'];
          $false_text = $data['false_text'];
        }
        TiwForm::normal('hidden',  $true_text, ['name' => 'true_text']);
        TiwForm::normal('hidden',  $false_text, ['name' => 'false_text']);
        ?>
        <?= $true_text . '/' . $false_text ?>
      </div>
    </div>
    <div class="row mb-15px">
      <div class="col-sm-4">
        <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
      </div>
      <div class="col-sm-8">
        <div class="d-flex">
          <div class="max-w-100px w-100 mr-10px">
            <?= TiwForm::normal('select',  $input_inner_size, ['name' => 'input_inner_size', 'class' => "input-bordered"], $font_size); ?>
          </div>
          <div class="d-flex align-items-center scope_color_checkbox">
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-info)" name="color_inner" id="radio-info" <?= $color_inner == 'var(--v2-color-info)' ? 'checked' : '' ?>>
              <label for="radio-info" class=" bg-info">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom mr-5px">
              <input type="radio" value="var(--v2-color-secondary)" name="color_inner" id="radio-secondary" <?= $color_inner == 'var(--v2-color-secondary)' ? 'checked' : '' ?>>
              <label for="radio-secondary" class=" bg-secondary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <div class="radio-custom pr-5px mr-5px border-right">
              <input type="radio" value="var(--v2-color-primary)" name="color_inner" id="radio-primary" <?= $color_inner == 'var(--v2-color-primary)' ? 'checked' : '' ?>>
              <label for="radio-primary" class="bg-primary">
                <div class="radio-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                </div>
              </label>
            </div>
            <label class="btn-action-icon" for="event_custom_color_placeholder">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
              <input id="event_custom_color_placeholder" type="color" name="custom_color_placeholder" onchange="customInputTitleColor(this)" class="input-color-sm" value="<?= $custom_color_placeholder ?>">
            </label>
          </div>
        </div>
      </div>
    </div>

  </div>
<?php return ob_get_clean();
}
?>

<?php
function template_form_setting_condition_switch($is_edit = false, $data = [], $type = null)
{
  ob_start();
  $list_font_size = [];
  foreach (range(10, 28) as $number) {
    $list_font_size[] = [
      'value' => $number,
      'name' => $number . 'px'
    ];
  }

  $font_size =
    [
      'list' => $list_font_size,
    ];

  $size_display_list = [
    'list' => [
      [
        'value' => '49',
        'name' => '50%'
      ],
      [
        'value' => '100',
        'name' => '100%'
      ],
    ]
  ];

  $is_edit_fill =  '1';
  $is_required_fill =  '1';
  $is_show_list_fill =  '1';
  $is_same_data = '1';
  $size_display =  '100';
  $alignment =  'left';
  $type_display =  1;
  $format_display = 'toptobottom';
  if ($is_edit == true) {
    $is_edit_fill = isset($data['is_edit_fill']) ? $data['is_edit_fill'] : '';
    $is_required_fill = isset($data['is_required_fill']) ? $data['is_required_fill'] : '';
    $is_show_list_fill = isset($data['is_show_list_fill']) ? $data['is_show_list_fill'] : '';
    $is_same_data = isset($data['is_same_data']) ? $data['is_same_data'] : '';
    $size_display = isset($data['size_display']) ? $data['size_display'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $type_display = isset($data['type_display']) ? $data['type_display'] : '';
    $format_display = isset($data['format_display']) ? $data['format_display'] : 'toptobottom';
  }
?>
  <div id="scope_condition">
    <div class="row mb-20px mt-20px">
      <div class="col-sm-12">
        <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_edit_fill', 'checked' => $is_edit_fill == 1 ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล', 'is_on_off' => true]); ?>
      </div>
      <div class="col-sm-12 mb-15px d-flex align-items-start">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_required_fill', 'checked' => $is_required_fill == 1 ? true : false], ['style' => '1', 'is_on_off' => true]); ?>
        <label class="ml-5px" for="__p5odqvabhx">
          ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้ได้รวมถึงจำเป็นต้องมีในหน้าเพิ่มข้อมูล (Add data)
        </label>
      </div>
      <div class="col-sm-12 mb-15px">
        <?= TiwForm::normal('checkbox', '1', ['name' => 'is_show_list_fill', 'checked' => $is_show_list_fill == 1 ? true : false], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
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
        <?= TiwForm::normal('hidden', $type_display, ['name' => 'type_display']); ?>
      </div>
      <div class="col-sm-8">
        <div class="d-flex align-items-center">
          <div class="max-w-100px w-100">
            <?= TiwForm::normal('select', $size_display, ['name' => 'size_display'], $size_display_list); ?>
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
            <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $alignment == 'left' ? 'checked' : '' ?>>
            <label for="checkbox-align-left">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
              </div>
            </label>
          </div>
          <div class="checkbox-bold">
            <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $alignment == 'right' ? 'checked' : '' ?>>
            <label for="checkbox-align-right">
              <div class="checkbox-img ">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
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

        <div class="d-flex">
          <div class="mr-10px">
            <?= TiwForm::normal('radio', '1', ['name' => 'type_display', 'checked' => $type_display == 1 ? true : false], ['style' => '2']); ?>
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
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/info.svg') ?>
              </span>
            </div>
            <div class="text-secondary">หัวข้อช่องกรอกอยู่ด้านข้าง</div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 mb-15px">
        <div class="d-flex">
          <div class="mr-10px">
            <?= TiwForm::normal('radio', '2', ['name' => 'type_display', 'checked' => $type_display == 2 ? true : false], ['style' => '2']); ?>
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
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/info.svg') ?>
              </span>
            </div>
            <div class="text-secondary">หัวข้อช่องกรอกอยู่ด้านบน และช่องกรอกอยู่ด้านล่าง</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
<?php return ob_get_clean();
}

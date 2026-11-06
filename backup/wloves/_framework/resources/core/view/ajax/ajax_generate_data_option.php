<?php
require_once '../../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$type = (isset($_POST['type'])) ? $_POST['type'] : '';

$arr = [
  'name' => [
    'value' => 'name',
    'name' => 'name',
    'input' => '<input type="text" placeholder="" name="select_value[name]" value="">',
    'selected' => true,
  ],
  'value' => [
    'value' => 'value',
    'name' => 'value',
    'input' => '<input type="text" name="select_value[value]" value="" placeholder="">',
  ],
  'class' => [
    'value' => 'class',
    'name' => 'class',
    'input' => '<input type="text" placeholder="ex: class1 class2" name="select_value[class]" value="">'
  ],
  'id' => [
    'value' => 'id',
    'name' => 'id',
    'input' => '<input type="text" placeholder="ex: id1 id2" name="select_value[id]" value="">',
  ],
  'placeholder' => [
    'value' => 'placeholder',
    'name' => 'placeholder',
    'input' => '<input type="text" name="select_value[placeholder]" placeholder="" value="">',
  ],
  'required' => [
    'value' => 'required',
    'name' => 'required',
    'input' => '<input type="hidden" name="select_value[required]" value="true">',
  ],
  'disabled' => [
    'value' => 'disabled',
    'name' => 'disabled',
    'input' => '<input type="hidden" name="select_value[disabled]" value="true">',
  ],
  'readonly' => [
    'value' => 'readonly',
    'name' => 'readonly',
    'input' => '<input type="hidden" name="select_value[readonly]" value="true">',
  ],
  'min' => [
    'value' => 'min',
    'name' => 'min',
    'input' => '<input type="number" name="select_value[min]" value="">',
  ],
  'max' => [
    'value' => 'max',
    'name' => 'max',
    'input' => '<input type="number" name="select_value[max]" value="">',
  ],
  'step' => [
    'value' => 'step',
    'name' => 'step',
    'input' => '<input type="text" name="select_value[step]" value="">',
  ],
  'tooltip' => [
    'value' => 'tooltip',
    'name' => 'tooltip',
    'input' => '<input type="text" name="select_value[tooltip]" value="" placeholder="Ex. Edit, Delete">',
  ],
  'checked' => [
    'value' => 'checked',
    'name' => 'checked',
    'input' => '<input type="hidden" name="select_value[checked]" value="true">',
  ],
  'type' => [
    'value' => 'type',
    'name' => 'type',
    'input' => '<select name="select_value[type]">
                  <option value="button">button</option>
                  <option value="submit">submit</option>
                </select>',
    'selected' => true,
  ],
  'brandnote_id' => [
    'value' => 'id',
    'name' => 'id',
    'input' => '<input type="text" name="select_value[id]" value="" placeholder="Ex. #brandnote_01">',
    'selected' => true,
  ],
  'brandnote_name' => [
    'value' => 'name',
    'name' => 'name',
    'input' => '<input type="text" name="select_value[name]" value="">',
    'selected' => true,
  ],
  'brandnote_value' => [
    'value' => 'value',
    'name' => 'value',
    'input' => '<input type="text" name="select_value[value]" value="" placeholder="">',
    'selected' => true,
  ],
  'brandnote_height' => [
    'value' => 'height',
    'name' => 'height',
    'input' => '<input type="number" name="select_value[height]" value="" placeholder="Enter height editor (Default 400)">',
    'selected' => true,
  ],
  'brandnote_class' => [
    'value' => 'class',
    'name' => 'class',
    'input' => '<input type="text" name="select_value[class]" value="" placeholder="Class for custom">',
    'selected' => true,
  ],
];

$options_arr = [
  'is_return' => [
    'value' => 'is_return',
    'name' => 'is_return <div class="tootip-gen-form" title="ใช้กรณีที่ต้องการ echo เอง">' . file_get_contents('../../../../../_framework/modules/main/tiwform/icon/lightbulb.svg') . '</div>',
    'input' => '<input type="hidden" name="select_options[is_return]" value="true">',
  ],
  'is_scan' => [
    'value' => 'is_scan',
    'name' => 'is_scan <div class="tootip-gen-form" title="ใช้กรณีที่ไม่ต้องการใช้ scan">' . file_get_contents('../../../../../_framework/modules/main/tiwform/icon/lightbulb.svg') . '</div>',
    'input' => '<select name="select_options[is_scan]">
                  <option value="0">false</option>
                </select>',
  ],
  'text' => [
    'value' => 'text',
    'name' => 'text',
    'input' => '<input type="text" placeholder="text in button" name="select_options[text]" value="">',
    'selected' => true,
  ],
  'type' => [
    'value' => 'type',
    'name' => 'type',
    'input' => '<select name="select_options[type]">
                  <option value="">Normal</option>
                  <option value="edit">Edit</option>
                  <option value="delete">Delete</option>
                </select>',
    'selected' => true,
  ],
  'prefix' => [
    'value' => 'prefix',
    'name' => 'prefix <div class="tootip-gen-form" title="ใช้กรณีที่ไฟล์งานไม่ได้อยู่ใน module หรือย้อนกลับไป ../../ ไม่เจอไฟล์ icon">' . file_get_contents('../../../../../_framework/modules/main/tiwform/icon/lightbulb.svg') . '</div>',
    'input' => '<input type="text" placeholder="Ex. ../../../../" name="select_options[prefix]" value="">',
  ],
  'is_search' => [
    'value' => 'is_search',
    'name' => 'is_search',
    'input' => '<input type="hidden" name="select_options[is_search]" value="true">',
  ],
  'style' => [
    'value' => 'style',
    'name' => 'style',
    'input' => '<select name="select_options[style]">
                  <option value="1">slide</option>
                  <option value="2">radio</option>
                  <option value="3">checkbox</option>
                </select>',
    'selected' => true,
  ],
  'label' => [
    'value' => 'label',
    'name' => 'label',
    'input' => '<input type="text" placeholder="" name="select_options[label]" value="">',
  ],
  'is_on_off' => [
    'value' => 'is_on_off',
    'name' => 'is_on_off',
    'input' => '<input type="hidden" name="select_options[is_on_off]" value="true">',
  ],
  'text_on' => [
    'value' => 'text_on',
    'name' => 'text_on',
    'input' => '<input type="text" placeholder="Text on" name="select_options[text_on]" value="Yes">',
  ],
  'text_off' => [
    'value' => 'text_off',
    'name' => 'text_off',
    'input' => '<input type="text_off" placeholder="Text off" name="select_options[text_off]" value="No">',
  ],
  'url' => [
    'value' => 'url',
    'name' => 'url',
    'input' => '<input type="text" placeholder="" name="select_options[url]" value="">',
  ],

  'modal_id' => [
    'value' => 'modal_id',
    'name' => 'modal_id',
    'input' => '<input type="text" placeholder="Modal ID" name="select_options[modal_id]" value="">',
  ],
  'modal_is_ajax' => [
    'value' => 'modal_is_ajax',
    'name' => 'Ajax Modal',
    'input' => '<input type="hidden" name="select_options[is_ajax]" value="true">',
  ],
  'is_edit' => [
    'value' => 'is_edit',
    'name' => 'is_edit <div class="tootip-gen-form" title="ถ้า is_edit เป็น false form จะเป็นเปลี่ยนเป็นโหมดไม่ให้แก้ไข">' . file_get_contents('../../../../../_framework/modules/main/tiwform/icon/lightbulb.svg') . '</div>',
    'input' => '<select name="select_options[is_edit]">
                  <option value="0">false</option>
                </select>',
  ],
];

//type ที่เลือกมาให้แสดง attribute อะไรบ้าง
$data_of_type = [
  'text' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //text
  'number' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['min'], $arr['max'], $arr['step']], //number
  'password' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //password
  'textarea' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //textarea
  'date' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['min'], $arr['max'], $arr['step']], //date
  'time' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['min'], $arr['max'], $arr['step']], //time
  'datetime' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['min'], $arr['max'], $arr['step']], //datetime
  'month' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['min'], $arr['max'], $arr['step']], //month
  'week' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['min'], $arr['max'], $arr['step']], //week
  'daterange' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //daterange
  'color' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly']], //color
  'file' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly']], //file
  'btn' => [$arr['name'], $arr['value'], $arr['type'], $arr['tooltip'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //btn
  'tel-flag' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //disabled
  'select-language' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //select-language
  'select-title' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['placeholder'], $arr['required'], $arr['disabled'], $arr['readonly']], //select-title
  'checkbox' => [$arr['name'], $arr['value'], $arr['checked'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly']], //checkbox
  'radio' => [$arr['name'], $arr['value'], $arr['checked'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly']], //radio
  'select' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['placeholder']], //select
  'select-img' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['placeholder']], //select-img
  'select-color' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['placeholder']], //select-color
  'select-tag' => [$arr['name'], $arr['value'], $arr['class'], $arr['id'], $arr['required'], $arr['disabled'], $arr['readonly'], $arr['placeholder']], //select-tag
  'brandnote' => [$arr['brandnote_id'], $arr['brandnote_name'], $arr['brandnote_value'], $arr['brandnote_height'], $arr['brandnote_class']], //brandnote
  'drag-drop-file' => [$arr['name'], $arr['class'], $arr['required']], //drag and drop file
  'add-scan-tag' => [$arr['name'], $arr['placeholder'], $arr['required']], //Add and scan tag
];

//type ที่เลือกมาให้แสดง attribute อะไรบ้าง
$options_of_type = [
  'text' => [$options_arr['is_return'], $options_arr['is_edit']],
  'number' => [$options_arr['is_return'], $options_arr['is_edit']],
  'textarea' => [$options_arr['is_return'], $options_arr['is_edit']],
  'date' => [$options_arr['is_return'], $options_arr['is_edit']],
  'time' => [$options_arr['is_return'], $options_arr['is_edit']],
  'datetime' => [$options_arr['is_return'], $options_arr['is_edit']],
  'month' => [$options_arr['is_return'], $options_arr['is_edit']],
  'week' => [$options_arr['is_return'], $options_arr['is_edit']],
  'daterange' => [$options_arr['is_return'], $options_arr['is_edit']],
  'color' => [$options_arr['is_return'], $options_arr['is_edit']],
  'file' => [$options_arr['is_return'], $options_arr['prefix'], $options_arr['url']], //file
  'btn' => [$options_arr['is_return'], $options_arr['text'], $options_arr['type'], $options_arr['modal_id'], $options_arr['modal_is_ajax'], $options_arr['prefix']], //btn
  'select-language' => [$options_arr['is_return'], $options_arr['is_search'], $options_arr['prefix'], $options_arr['is_edit']], //select-language
  'select-title' => [$options_arr['is_return'], $options_arr['is_search'], $options_arr['prefix'], $options_arr['is_edit']],
  'checkbox' => [$options_arr['is_return'], $options_arr['style'], $options_arr['label'], $options_arr['is_on_off'], $options_arr['text_on'], $options_arr['text_off']], //select-title
  'radio' => [$options_arr['is_return'], $options_arr['style'], $options_arr['label'], $options_arr['is_on_off'], $options_arr['text_on'], $options_arr['text_off']], //radio
  'select' => [$options_arr['is_return'], $options_arr['is_search'], $options_arr['prefix'], $options_arr['is_edit']], //select
  'select-img' => [$options_arr['is_return'], $options_arr['is_search'], $options_arr['prefix'], $options_arr['is_edit']], //select-img
  'select-color' => [$options_arr['is_return'], $options_arr['is_search'], $options_arr['prefix'], $options_arr['is_edit']], //select-color
  'select-tag' => [$options_arr['is_return'], $options_arr['prefix'], $options_arr['is_edit']], //select-tag
  'drag-drop-file' => [$options_arr['is_return'], $options_arr['is_edit']],
  'add-scan-tag' => [$options_arr['is_return'], $options_arr['is_scan'], $options_arr['prefix']], //Add and scan tag
];

if (isset($data_of_type[$type])) {
  $attribute_options = [
    'prefix' => '../../../../../',
    'list' => [
      [
        'name' => 'Attribute',
        'list' => $data_of_type[$type]
      ],
    ]
  ];
} else {
  $attribute_options = [
    'prefix' => '../../../../../',
    'list' => []
  ];
}

if (isset($options_of_type[$type])) {
  $options = [
    'prefix' => '../../../../../',
    'list' => [
      [
        'name' => 'Options',
        'list' => $options_of_type[$type]
      ],
    ]
  ];
} else {
  $options = [
    'prefix' => '../../../../../',
    'list' => []
  ];
}
?>

<div class="mb-15px">
  <div class="">เลือก Attribute เบี้องต้นที่ต้องการ</div>
  <?= TiwForm::normal('select-tag', '', ['name' => 'select_attribute', 'class' => 'tag_with_input_event', 'placeholder' => 'Filter Data'], $attribute_options); ?>
</div>

<?php if (array_key_exists($type, $options_of_type)) { ?>
  <div class="mb-15px">
    <div class="">เลือก Options</div>
    <?= TiwForm::normal('select-tag', '', ['name' => 'select_type_options', 'class' => 'tag_with_input_event', 'placeholder' => 'Filter Options'], $options); ?>
  </div>
<?php } ?>

<?php if ($type == 'select' || $type == 'select-tag' || $type == 'select-color') { ?>
  <div class="w-100 mb-10px ">หรือหากต้องการวาด list เอง</div>
  <div class="w-100 d-flex align-items-end">
    <div class="row w-100">
      <div class="col-sm-6">
        <div class="text-primary mb-5px"><?= ($type == 'select-color') ? 'Color Code' : 'Value' ?></div>
        <?= TiwForm::normal('text', '', ['name' => 'list_value[]', 'placeholder' => ($type == 'select-color') ? 'Ex. #232B32' : 'value']); ?>
      </div>
      <div class="col-sm-6">
        <div class="text-primary mb-5px">Name</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_name[]', 'placeholder' => 'name']); ?>
      </div>
    </div>
    <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
  </div>
  <div class="w-100 align-items-end d-none list_template_event">
    <div class="row w-100">
      <div class="col-sm-6">
        <?= TiwForm::normal('text', '', ['class' => 'demo_value_event', 'placeholder' => ($type == 'select-color') ? 'Ex. #232B32' : 'value']); ?>
      </div>
      <div class="col-sm-6">
        <?= TiwForm::normal('text', '', ['class' => 'demo_name_event', 'placeholder' => 'name']); ?>
      </div>
    </div>
    <?= TiwForm::normal('btn', '', ['tooltip' => 'Delete', 'class' => 'mb-10px ml-10px delete_list_event', 'type' => 'button'], ['type' => 'delete', 'prefix' => '../../../../../']); ?>
  </div>
  <div class="list_area_event w-100"></div>
<?php } else if ($type == 'select-img') { ?>
  <div class="w-100 mb-10px ">หรือหากต้องการวาด list เอง</div>
  <div class="w-100 d-flex align-items-end">
    <div class="row w-100">
      <div class="col-sm-6 col-md-3">
        <div class="text-primary mb-5px">Value</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_value[]', 'placeholder' => 'value']); ?>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="text-primary mb-5px">Name</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_name[]', 'placeholder' => 'name']); ?>
      </div>
      <div class="col-md-6">
        <div class="text-primary mb-5px">Image</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_img[]', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
  </div>
  <div class="w-100 align-items-end d-none list_template_event">
    <div class="row w-100">
      <div class="col-sm-6 col-md-3">
        <?= TiwForm::normal('text', '', ['class' => 'demo_value_event', 'placeholder' => 'value']); ?>
      </div>
      <div class="col-sm-6 col-md-3">
        <?= TiwForm::normal('text', '', ['class' => 'demo_name_event', 'placeholder' => 'name']); ?>
      </div>
      <div class="col-md-6">
        <?= TiwForm::normal('text', '', ['class' => 'demo_image_event', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <?= TiwForm::normal('btn', '', ['tooltip' => 'Delete', 'class' => 'mb-10px ml-10px delete_list_event', 'type' => 'button'], ['type' => 'delete', 'prefix' => '../../../../../']); ?>
  </div>
  <div class="list_area_event w-100"></div>
<?php } ?>
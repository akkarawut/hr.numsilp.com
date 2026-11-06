<?php
require_once '../../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
W_Structure::loadModulesForAjax(['brandnote']);

$type = isset($_POST['type']) ? $_POST['type'] : '';

$data_list = isset($_POST['select_value']) ? $_POST['select_value'] : [];
$name = isset($data_list['name']) ? $data_list['name'] : '';
$select_options = isset($_POST['select_options']) ? $_POST['select_options'] : [];

$is_ajax = (isset($select_options['is_ajax']) && $select_options['is_ajax']) ? true : false;
unset($select_options['is_ajax']);

//attr
$value = isset($data_list['value']) ? $data_list['value'] : '';
unset($data_list['value']);

if ($data_list) {
  $dot = '';
  $html_data = '[';
  foreach ($data_list as $key => $list) {
    $html_data .= $dot . "'" . $key . "' => '" . $list . "'";
    $dot = ', ';
  }
  $html_data .= ']';
} else {
  $html_data = '';
}

if ($select_options) {
  $dot = '';
  $html_options = '';
  foreach ($select_options as $key => $list) {
    if ($list == 'true') {
      $html_options .= $dot . "'" . $key . "' => true";
    } else if ($key == 'is_edit' && $list == 0) {
      $html_options .= $dot . "'" . $key . "' => false";
    } else {
      $html_options .= $dot . "'" . $key . "' => '" . $list . "'";
    }
    $dot = ', ';
    if ($key == 'modal_id') {
      $html_options .= $dot . "'modal_data' => []";
    }
  }
} else {
  $html_options = '';
}

if ($type == 'select' || $type == 'select-color' || $type == 'select-img' || $type == 'select-tag') {
  $list = [];
  $options_list = [];
  if (isset($_POST['list_value']) && $_POST['list_name']) {
    foreach ($_POST['list_value'] as $key => $list_value) {
      $list[$key] = [
        ($type == 'select-color') ? 'color' : 'value' => $list_value,
        'name' => $_POST['list_name'][$key],
      ];

      $options_list[$key] = [
        'value' => $list_value,
        'name' => $_POST['list_name'][$key],
      ];
    }
  }
  if (isset($_POST['list_img'])) {
    foreach ($_POST['list_img'] as $key => $image) {
      $list[$key]['img'] = $image;
      $options_list[$key]['img'] = $image;
    }
  }

  if ($type == 'select' || $type == 'select-img') {
    if (!$list) {
      $list = [
        [
          'id' => 1,
          'title' => 'option1',
          'path' => 'https://wolves.bar/test/system/resource/placeholder/placeholder_user_square.jpg',
        ],
        [
          'id' => 2,
          'title' => 'option2',
          'path' => 'https://wolves.bar/test/system/resource/placeholder/placeholder_user_square.jpg',
        ],
      ];
      $key = [
        'value' => 'id',
        'name' => 'title',
        'img' => 'path',
      ];
    } else {
      $key = [
        'value' => 'value',
        'name' => 'name',
        'img' => 'img',
      ];
    }

    $options = [];
    if (isset($select_options['is_search']) && $select_options['is_search']) {
      $options['is_search'] = true;
    }
    $options['prefix'] = '../../../../';

    $select_options = TiwForm::generateSelectData($list, $key, $options);
  } else if ($type == 'select-color') {
    if (!$list) {
      $list = [
        [
          'color' => '#fff',
          'title' => 'option1',
        ],
        [
          'color' => '#000',
          'title' => 'option2',
        ],
      ];
      $key = [
        'color' => 'color',
        'name' => 'title',
      ];
    } else {
      $key = [
        'color' => 'color',
        'name' => 'name',
      ];
    }

    $options = [];
    if (isset($select_options['is_search']) && $select_options['is_search']) {
      $options['is_search'] = true;
    }
    $options['prefix'] = '../../../../';

    $select_options = TiwForm::generateSelectData($list, $key, $options);
  } else if ($type == 'select-tag') {
    if (!$list) {
      $list = [
        [
          'id' => 1,
          'title' => 'option1',
        ],
        [
          'id' => 2,
          'title' => 'option2',
        ],
      ];
      $key = [
        'value' => 'id',
        'name' => 'title',
      ];
    } else {
      $key = [
        'value' => 'value',
        'name' => 'name',
      ];
    }

    $options = [];
    if (isset($select_options['is_search']) && $select_options['is_search']) {
      $options['is_search'] = true;
    }
    $options['prefix'] = '../../../../';

    $select_options = TiwForm::generateSelectData($list, $key, $options);
  }
} else if ($type == 'add-scan-tag') {
  $select_options['prefix'] = '../../../../';
}
?>

<div class="mt-20px mb-10px">Type <?= $type ?> demo.</div>
<?php
if ($type == 'brandnote') {
  $id     = isset($data_list['id']) ? $data_list['id'] : '';
  $value  = isset($data_list['value']) ? $data_list['value'] : '';
  $height = (isset($data_list['height']) && $data_list['height']) ? $data_list['height'] : '400';
  $class  = isset($data_list['class']) ? $data_list['class'] : '';
  if (!$id) {
    echo '<span class="text-danger">กรุณากรอกไอดี</span>';
  } else {
    Brandnote::startNote($id, $name, $value, $height, $class);
  }
} else {
  if (isset($select_options['modal_id']) && $type == 'btn') {
    $select_options['modal_data'] = [];
  }

  TiwForm::normal($type, $value, $data_list, $select_options);
}

$ref_url = ($type == 'brandnote') ? 'school.php?topic=500' : '#' . $type;
$ref_blank = ($type == 'brandnote') ? 'target="_blank"' : '';
?>

<div class="mt-20px">Code generated <a href="<?= $ref_url ?>" <?= $ref_blank ?>><span class='text-primary'>[คลิกที่นี่เพื่อดูข้อมูลเพิ่มเติม]</span></a></div>
<div class='code-container mt-10px'>
  <?php
  if ($type == 'select' || $type == 'select-color' || $type == 'select-img' || $type == 'select-tag') {
    $html_select_options = $select_options ? $html_options : '';
    if ($type == 'select' || $type == 'select-tag') {
      $key_html = "\$key = [
  'value' => 'ตัวแปรใน database',
  'name' => 'ตัวแปรใน database',
];
";
    } else if ($type == 'select-color') {
      $key_html = "\$key = [
  'color' => 'ตัวแปรใน database',
  'name' => 'ตัวแปรใน database',
];
";
    } else if ($type == 'select-img') {
      $key_html = "\$key = [
  'value' => 'ตัวแปรใน database',
  'name' => 'ตัวแปรใน database',
  'img' => 'ตัวแปรใน database',
];
";
    }
  ?>
    <div class="text-primary mb-10px">แบบดึง list จาก database</div>
    <pre><code data-language='php'>//$list = list ข้อมูลใน database
<?= $key_html ?>
$options = TiwForm::generateSelectData($list, $key, [<?= $html_select_options ?>]);
TiwForm::normal('<?= $type ?>', '<?= $value ?>', <?= $html_data ?>, $options);</code></pre>
  <?php } else if ($type == 'brandnote') { ?>
    <pre><code data-language='php'>W_Structure::loadModules(['brandnote']); //เรียกใช้ Modules brandnote

Brandnote::startNote('<?= $id ?>', '<?= $name ?>', '<?= $value ?>', '<?= $height ?>', '<?= $class ?>');</code></pre>
  <?php } else { ?>
    <pre><code data-language='php'>TiwForm::normal('<?= $type ?>', '<?= $value ?>', <?= $html_data . ', [' . $html_options . ']' ?>);</code></pre>

    <?php if ($type == 'btn') { ?>
      <?php if ($is_ajax) { ?>
        <pre><code data-language="php">&lt;?php Tiwdal::ajaxModal('<?= $select_options['modal_id'] ?>', $size, $options); ?&gt;</code></pre>
      <?php } else if (isset($select_options['modal_id'])) { ?>
        <pre><code data-language="php">&lt;?php Tiwdal::startModal('<?= $select_options['modal_id'] ?>', $size, $options);?&gt;
  <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
  <div class="modal-header">
    <h5 class="modal-title">Ex. Edit Modal</h5>
  </div>
  <div class="modal-body">
    <label>ID :</label>
    &lt;?= TiwForm::normal('text', '', ['name' => 'id'], []); ?&gt;
    <label>Name :</label>
    &lt;?= TiwForm::normal('text', '', ['name' => 'name'], []); ?&gt;
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
    &lt;?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'CANCEL']); ?&gt;
    &lt;?= TiwForm::normal('btn', '', ['name' => '', 'type' => 'submit'], ['text' => 'SAVE']); ?&gt;
  </div>
&lt;?php Tiwdal::endModal()?&gt;</code></pre>
      <?php } ?>
    <?php } ?>
  <?php } ?>
</div>

<!-- code select -->
<?php if ($type == 'select' || $type == 'select-color' || $type == 'select-img' || $type == 'select-tag') { ?>
  <div class="text-primary">แบบย่อ</div>
  <div class='code-container mt-10px'>
    <pre><code data-language='php'>$options = <?= '[\'list_encode\' => \'' . json_encode($options_list) . '\', ' . $html_options . ']' ?>;
TiwForm::normal('<?= $type ?>', '<?= $value ?>', <?= $html_data ?>, $options);</code></pre>
  </div>

  <div class="text-primary">แบบเต็ม</div>
  <div class='code-container mt-10px'>
    <pre><code data-language='php'>$<?= $name ? $name . '_' : ''; ?>options = [
<?php
  $options_html = "";
  if (isset($select_options['is_search']) && $select_options['is_search']) {
    $options_html .= "  'is_search' => true,\n";
  }
  if (isset($_POST['select_options']['is_edit']) && !$_POST['select_options']['is_edit']) {
    $options_html .= "  'is_edit' => false,\n";
  }
  if (isset($_POST['select_options']['prefix'])) {
    $options_html .= "  'prefix' => '" . $_POST['select_options']['prefix'] . "',\n";
  }

  $options_html .= "  'list' => [\n";
  foreach ($options_list as $list) {
    $options_html .= "    [\n";
    if ($type == 'select-color') {
      $options_html .= "     'color' => '" . $list['value'] . "',\n";
    } else {
      $options_html .= "     'value' => '" . $list['value'] . "',\n";
    }
    $options_html .= "     'name' => '" . $list['name'] . "',\n";
    if ($type == 'select-img') {
      $options_html .= "     'img' => '" . $list['img'] . "',\n";
    }
    $options_html .= "    ],\n";
  }
  $options_html .= "  ],\n";
  echo $options_html;
?>
];
TiwForm::normal('<?= $type ?>', '<?= $value ?>', <?= $html_data ?>, $<?= $name ? $name . '_' : ''; ?>options);</code></pre>
  </div>
<?php } ?>

<?php
//modal 
if (isset($select_options['modal_id']) && $type == 'btn') {
?>
  <?php Tiwdal::startModal($select_options['modal_id']); ?>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
  <div class="modal-header">
    <h5 class="modal-title">Ex. Edit Modal</h5>
  </div>
  <div class="modal-body">
    <label>ID :</label>
    <?= TiwForm::normal('text', '', ['name' => 'id'], []); ?>
    <label>Name :</label>
    <?= TiwForm::normal('text', '', ['name' => 'name'], []); ?>
  </div>
  <div class="modal-footer d-flex justify-content-between">
    <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-100px', 'data-bs-dismiss' => 'modal'], ['text' => 'CANCEL']); ?>
    <?= TiwForm::normal('btn', '', ['name' => '', 'type' => 'submit'], ['text' => 'SAVE']); ?>
  </div>
  <?php Tiwdal::endModal() ?>
<?php } ?>


<script>
  Rainbow.color();
  startCopyToClipboard();
  checkDragDropLengthEvent();
  <?php if ($type == 'tel-flag') { ?>
    var intlt = $('.telephone-form-group .form-flags');
    var $intlt = intlt.intlTelInput({
      preferredCountries: ['th'],
      separateDialCode: true
    });
  <?php } else if ($type == 'brandnote') { ?>
    startBrandnoteEvent();
  <?php } ?>
</script>
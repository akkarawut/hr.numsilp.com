<?php
$_PAGE['permission']  = ['landing', '', ''];
$prefix = '../../../';
require $prefix . '_framework/import.php';


if (isset($_POST['submit_tel'])) {

  W_Main::display($_POST);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta($prefix);
  W_Main::loadAsset('../../structure/css/animate.css');
  ?>
</head>

<body>
  <?php include '../layout/header-default.php'; ?>

  <div class="bg-card p-20px min-h-100">

    <!-- upload sound -->
    <form action="" method="post">
      <?= TiwForm::normal('upload-audio', '', ['name' => 'type_upload_sound', 'accept' => '.mp3, .m4r', 'required' => true]) ?>
      <button type="submit">submit</button>
    </form>

    <!-- text -->
    <div class="_scope_type_text">
      <?= TiwForm::normal('text', '', ['class' => '_type_text', 'placeholder' => 'Text', 'text_value' => '']); ?>
    </div>

    <!-- datetime -->
    <?= TiwForm::normal('datetime', '', ['class' => '_type_datetime']); ?>

    <!-- daterange -->
    <?= TiwForm::normal('daterange', '', ['class' => '_type_daterange']); ?>

    <!-- textarea -->
    <?= TiwForm::normal('textarea', '', ['class' => '_type_textarea']); ?>

    <!-- select -->
    <?php
    $list = [
      [
        'id' => 1,
        'name' => 'test1',
        'detail' => 'test1',
      ],
      [
        'id' => 2,
        'name' => 'test2',
        'detail' => 'test2',
      ]
    ];

    $key = [
      'value' => 'id',
      'name' => 'name',
    ];
    $options = TiwForm::generateSelectData($list, $key, ['is_search' => true, 'is_data' => true]);
    TiwForm::normal('select', '', ['class' => '_type_select', 'placeholder' => 'Select'], $options);

    $options = TiwForm::generateSelectData($list, $key, ['is_data' => true]);

    TiwForm::normal('select', '', ['class' => '_type_select', 'placeholder' => 'Select'], $options);
    ?>

    <!-- color -->
    <div>
      <?= TiwForm::normal('color', '', ['class' => '_type_color']); ?>
    </div>

    <!-- file -->
    <?php
    $file = 'http://128.199.207.248:8412/system/resource/placeholder/placeholder_user_square.jpg';
    TiwForm::normal('file', $file, ['class' => '_type_file', 'name' => 'test_upload_file']);
    ?>

    <!-- select tag -->
    <?php
    $options = [
      'list' => [
        [
          'name' => 'Employee Remark',
          'list' => [
            [
              'value' => 1,
              'name' => 'มีความเป็นตัวเองสูง',
            ],
            [
              'value' => 2,
              'name' => 'ชอบขาดประชุม',
            ],
          ],
        ],
        [
          'name' => 'Employee Remark 2',
          'list' => [
            [
              'value' => 3,
              'name' => 'มีความเป็นตัวเองสูง 2',
            ],
          ],
        ],
      ]
    ];
    TiwForm::normal('select-tag', [1, 2], ['name' => 'select_tag', 'placeholder' => 'Filter Data'], $options);

    $data = [
      'id'   => 1,
      'name' => 'Edit Name',
      'description' => 'Edit Description',
    ];
    TiwForm::normal('btn', '', ['type' => 'button'], ['text' => 'Click for open edit modal.', 'modal_id' => 'modal_test_select_tag', 'modal_data' => ['select_tag' => [1, 2], 'modal_upload_audio' => 'https://www.w3schools.com/html/horse.ogg']]);
    ?>

    <?php Tiwdal::startModal('modal_test_select_tag', 'modal-md'); ?>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
    <div class="modal-header">
      <h5 class="modal-title">Ex. Edit Modal</h5>
    </div>
    <div class="modal-body">
      <?php
      $options = [
        'list' => [
          [
            'name' => 'Employee Remark',
            'list' => [
              [
                'value' => 1,
                'name' => 'มีความเป็นตัวเองสูง',
              ],
              [
                'value' => 2,
                'name' => 'ชอบขาดประชุม',
              ],
            ],
          ],
          [
            'name' => 'Employee Remark 2',
            'list' => [
              [
                'value' => 3,
                'name' => 'มีความเป็นตัวเองสูง 2',
              ],
            ],
          ],
        ]
      ];
      TiwForm::normal('select-tag', '', ['name' => '{select_tag}', 'placeholder' => 'Filter Data'], $options);
      ?>

      <?= TiwForm::normal('upload-sound', '', ['name' => '{modal_upload_audio}', 'accept' => '.mp3, .m4r', 'required' => true]) ?>
    </div>
    <div class="modal-footer">
      <?php
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'ADD', 'type' => '']);
      ?>
    </div>
    <?php Tiwdal::endModal() ?>

    <!-- select image -->
    <?php
    $options = [
      'list' => [
        [
          'value' => 1,
          'name' => 'number1',
          'img' => 'http://128.199.207.248:8412/system/resource/placeholder/placeholder_user_square.jpg',
        ],
        [
          'value' => 2,
          'name' => 'number2',
          'img' => 'http://128.199.207.248:8412/system/resource/placeholder/placeholder_user_square.jpg',
        ],
        [
          'value' => 3,
          'name' => 'number3',
          'img' => '',
        ],
      ]
    ];
    TiwForm::normal('select-img', 2, ['class' => '_type_select_img', 'placeholder' => 'Please Select', 'disabled' => true], $options);
    ?>

    <!-- select bank -->
    <?= TiwForm::normal('select-bank', '', ['class' => '_type_select_bank', 'placeholder' => 'Please Select Bank']); ?>

    <!-- select color -->
    <?php
    $options = [
      'list' => [
        [
          'color' => '#f44336',
          'name' => 'แดง',
        ],
        [
          'color' => '#9c27b0',
          'name' => 'ม่วง',
        ],
        [
          'color' => '#2196f3',
          'name' => 'ฟ้า',
        ],
      ]
    ];
    TiwForm::normal('select-color', '#9c27b0', ['name' => 'text', 'placeholder' => 'Please Select'], $options);
    ?>

    <!-- select language -->
    <?= TiwForm::normal('select-language', '', ['class' => '_type_select_language', 'placeholder' => 'Please Select language'], ['is_search' => true]); ?>

    <!-- select select-title -->
    <?= TiwForm::normal('select-title', '', ['class' => '_type_select_title', 'placeholder' => 'Please Select title']); ?>

    <!-- upload-img -->
    <?php
    $options = [
      'width' => '200px',
      'height' => '100%',
      // 'bg-img' => '../../modules/main/tiwform/img/bg1.png',
    ];
    TiwForm::normal('upload-img', '', ['name' => 'file_img', 'class' => '_type_upload_img'], $options);
    ?>

    <!-- tel-flag -->
    <form action="" method="post">
      <?= TiwForm::normal('tel-flag', '+66918521221', ['name' => 'tel', 'class' => '_type_tel_flag', 'placeholder' => 'Enter']); ?>
      <?= TiwForm::normal('btn', '', ['name' => 'submit_tel']) ?>
    </form>

    <!-- event change input -->
    <button class="_add_input">add input</button>
    <div class="_scope_add_input"></div>

  </div>

  <?php
  include '../layout/footer.php';
  W_Structure::loadFooter($prefix);
  ?>
  <script>
    w.on('keyup', '._type_text', function(e) {
      console.log(w(this).val());
    });

    w.on('change', '._type_color', function(e) {
      console.log(w(this).val());
    });

    w.on('change', '._type_select_bank input[select_value]', function(e) {
      console.log(w(this).val());
      console.log(w.tiwform.getSelectData('._type_select_img'));
    });

    w.on('change', '._type_select_img input[select_value]', function(e) {
      console.log(w(this).val());
    });

    w.on('keyup', '._event_change_input', function() {
      console.log(w(this).val());
    });

    w.on('click', '._add_input', function(e) {
      var len = w('._scope_add_input input').length;
      w('._scope_add_input').append('<input class="_event_change_input" value="' + len + '">');
    });

    w.on('change', '._type_select input[select_value]', function(e) {
      var data = w.tiwform.getSelectData('._type_select');
      console.log(data);
    });
  </script>
</body>

</html>
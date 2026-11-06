<?php
$type_options = [
  'is_search' => true,
  'list' => [
    [
      'value' => 'text',
      'name' => 'Text'
    ],
    [
      'value' => 'number',
      'name' => 'Number'
    ],
    [
      'value' => 'textarea',
      'name' => 'Textarea'
    ],
    [
      'value' => 'brandnote',
      'name' => 'Brandnote'
    ],
    [
      'value' => 'checkbox',
      'name' => 'Checkbox'
    ],
    [
      'value' => 'select',
      'name' => 'Select'
    ],
    [
      'value' => 'select-img',
      'name' => 'Select Img'
    ],
    [
      'value' => 'select-tag',
      'name' => 'Select tag'
    ],
    [
      'value' => 'radio',
      'name' => 'Radio'
    ],
    [
      'value' => 'date',
      'name' => 'Date'
    ],
    [
      'value' => 'datetime',
      'name' => 'Date Time'
    ],
    [
      'value' => 'btn',
      'name' => 'Button'
    ],
    [
      'value' => 'password',
      'name' => 'Password'
    ],
    [
      'value' => 'time',
      'name' => 'Time'
    ],
    [
      'value' => 'month',
      'name' => 'Month'
    ],
    [
      'value' => 'week',
      'name' => 'Week'
    ],
    [
      'value' => 'daterange',
      'name' => 'Date range'
    ],
    [
      'value' => 'color',
      'name' => 'Color'
    ],
    [
      'value' => 'file',
      'name' => 'Upload file'
    ],
    [
      'value' => 'tel-flag',
      'name' => 'Telephone'
    ],
    [
      'value' => 'select-language',
      'name' => 'Language'
    ],
    [
      'value' => 'select-title',
      'name' => 'Title'
    ],
    [
      'value' => 'select-color',
      'name' => 'Select color'
    ],
    [
      'value' => 'drag-drop-file',
      'name' => 'Drag and drop file'
    ],
    [
      'value' => 'add-scan-tag',
      'name' => 'Add and scan tag'
    ],
  ]
];
?>

</div> <!-- ปิด div w-love-content-container-wrap จากหน้า main -->

<div class="w-love-content-container-wrap mt--10px overflow-inherit">
  <div class="content-header-container">
    <h3 class="header-title text-primary">Generate Form</h3>
  </div>

  <div class="content-body-container">
    <div class="row">
      <!-- generate form -->
      <div class="col-12">
        <form method="post" id="generate_form_event" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12 mb-15px">
              <div class="text">ประเภท Form</div>
              <?= TiwForm::normal('select', '', ['name' => 'type', 'class' => 'gen_type_event', 'placeholder' => 'เลือกประเภท'], $type_options); ?>
            </div>
            <div class="col-12 gen_area_option_event"></div>

            <div class="col-12 mt-40px">
              <?php
              TiwForm::normal('btn', '', ['class' => 'w-100 btn-primary generate_event', 'type' => 'button'], ['text' => 'Generate']);
              ?>
            </div>
          </div>
        </form>
        <div class="gen_area_event"></div>
      </div>
    </div>
  </div>
</div>

<div class="w-love-content-container-wrap mt-50px">
  <div class="content-header-container">
    <h3 class="header-title">Normal Form</h3>
  </div>
  <div class="content-body-container">
    <div class="row">

      <div class="col-12">
        <div class="code-container mt-10px">
          <pre><code data-language="php">Question 1 : ทำไมใช้ tiwform ในไฟล์ Ajax, Ajax Table, Ajax Modal ไม่ได้ ?
ตอบ : ต้อง include ไฟล์ tiwform เข้าไปในไฟล์ Ajax ด้วย
W_Structure::loadMetaForAjax('../../');</code></pre>
        </div>
      </div>

      <div class="col-12">
        Navigator :
        <a href="#text">Text</a>,
        <a href="#text">Email</a>,
        <a href="#text">Password</a>,
        <a href="#text">Textarea</a>,
        <a href="#date">Date</a>,
        <a href="#daterange">Date range</a>,
        <a href="#month">Month</a>,
        <a href="#week">Week</a>,
        <a href="#datetime">Datetime</a>,
        <a href="#time">Time</a>,
        <a href="#select">Select Option</a>,
        <a href="#select-img">Select Img</a>,
        <a href="#select-tag">Select Tag</a>,
        <a href="#color">Color</a>,
        <a href="#file">File</a>,
        <a href="#button">Button</a>,
        <a href="#upload-img">Upload img</a>,
        <a href="#telephone">telephone</a>,
        <a href="#language">select language</a>,
        <a href="#title_name">select title name</a>
        <a href="#radio">checkbox, radio</a>
        <a href="#drag-drop-file">Drag and drop file</a>
        <a href="#add-tag">Add Tag</a>

        <div class="code-container mt-10px">
          <pre><code data-language="php">param 1 = type ได้แก่
'text', 'email', 'password', 'textarea', 'date', 'date-range', 'month', 'week', 'datetime', 'time', 
'select', 'select-img', 'select-tag', 'color', 'file', 'btn'
param 2 = value
param 3 = attribute เช่น ['name' => 'id']
param 4 = options ใช้เฉพาะ type ที่กำหนดไว้เท่านั้นสามารถดูได้ใน code ของ type นั้น ๆ</code></pre>
        </div>
      </div>
      <!----------------------------input---------------------------->
      <div class="col-12 pt-20px">
        <h5 id="text" class="text-primary">text, number, hidden, email, password, textarea</h5>
        <div id="textarea"></div>
        <div id="number"></div>
        <div id="hidden"></div>
        <div id="email"></div>
        <div id="password"></div>
        <div id="textarea"></div>
        <div clas="my-20px">
          <?php
          TiwForm::normal('text', 'text', ['name' => 'text', 'placeholder' => 'กรุณากรอกข้อความด้วย']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">param 1 = text, number, hidden, email, password, textarea
TiwForm::normal('text', 'value', ['name' => 'name', 'placeholder' => 'กรุณากรอกข้อความด้วย']);

TiwForm::normal('number', 'value', ['name' => 'name', 'placeholder' => 'กรุณากรอกข้อความด้วย']);

TiwForm::normal('hidden', 'value', ['name' => 'name']);

TiwForm::normal('email', 'value', ['name' => 'name', 'placeholder' => 'กรุณากรอกข้อความด้วย']);

TiwForm::normal('password', 'value', ['name' => 'name', 'placeholder' => 'กรุณากรอกข้อความด้วย']);

TiwForm::normal('textarea', 'value', ['name' => 'name', 'placeholder' => 'กรุณากรอกข้อความด้วย']);</code></pre>
        </div>
      </div>
      <!-------------------------date------------------------->
      <div class="col-12 pt-20px">
        <h5 id="date" class="text-primary">input:date</h5>
        <div class="my-20px">
          <?php
          TiwForm::normal('date', '2021-01-01', ['name' => 'name']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('date', '2021-01-01', ['name' => 'name']);</code></pre>
        </div>
      </div>
      <!--------------------------month------------------------>
      <div class="col-12 pt-20px">
        <h5 id="month" class="text-primary">input:month</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('month', '2021-01', ['name' => 'name']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('month', '2021-01', ['name' => 'name']);</code></pre>
        </div>
      </div>
      <!-------------------------week------------------------>
      <div class="col-12 pt-20px">
        <h5 id="week" class="text-primary">input:week</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('week', '2021-W01', ['name' => 'name']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('week', '2021-W01', ['name' => 'name']);</code></pre>
        </div>
      </div>
      <!------------------------datetime----------------------->
      <div class="col-12 pt-20px">
        <h5 id="datetime" class="text-primary">input:datetime</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('datetime', '2021-01-01T10:59', ['name' => 'name']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('datetime', '2021-01-01T10:59', ['name' => 'name']);</code></pre>
        </div>
      </div>
      <!-------------------------time---------------------------->
      <div id="time" class="col-12 pt-20px">
        <h5 class="text-primary">input:time</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('time', '10:59', ['name' => 'name']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('time', '10:59', ['name' => 'name']);</code></pre>
        </div>
      </div>
      <!--------------------------select------------------------->
      <div class="col-12 pt-20px">
        <h5 id="select" class="text-primary">select option</h5>
        <div clas="my-20px">
          <?php
          $options = [
            'list' => [
              [
                'value' => 1,
                'name' => 'number1'
              ],
              [
                'value' => 2,
                'name' => 'number2'
              ],
            ]
          ];
          TiwForm::normal('select', '', ['name' => 'text', 'placeholder' => 'Please Select'], $options);
          $options['is_search'] = true;
          TiwForm::normal('select', '', ['name' => 'text', 'placeholder' => 'Please Select'], $options);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">$options = [
  'is_search' => true, //หากต้องการใช้ select แบบค้นหาได้ให้ใส่มา
  'list' => [
    [
      'value' => '',
      'name' => 'please select',
      'disabled' => true
    ],
    [
      'value' => 1,
      'name' => 'number1'
    ],
    [
      'value' => 2,
      'name' => 'number2'
    ],
  ]
];
TiwForm::normal('select', 2, ['name' => 'text'], $options);</code></pre>
        </div>
      </div>
      <!------------------------select img----------------------->
      <div class="col-12 pt-20px">
        <h5 id="select-img" class="text-primary">select with img</h5>
        <div clas="my-20px">
          <?php
          $options = [
            'list' => [
              [
                'value' => 1,
                'name' => 'number1',
                'img' => '../../structure/image/placeholder/user.png',
              ],
              [
                'value' => 2,
                'name' => 'number2',
                'img' => '../../structure/image/placeholder/user.png',
              ],
              [
                'value' => 3,
                'name' => 'number3',
                'img' => '',
              ],
            ]
          ];
          //param 2 = value ถ้า value ตรงกับไอดีใน list จะทำการ selected ให้อัตโนมัติ
          TiwForm::normal('select-img', 2, ['name' => 'text', 'placeholder' => 'Please Select'], $options);

          $options['is_search'] = true;
          TiwForm::normal('select-img', 2, ['name' => 'text', 'placeholder' => 'Please Select'], $options);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">$options = [
  'list' => [
    [
      'value' => 1,
      'name' => 'number1',
      'img' => 'structure/image/placeholder/user.png',
    ],
    [
      'value' => 2,
      'name' => 'number2',
      'img' => 'structure/image/placeholder/user.png',
    ],
    [
      'value' => 3,
      'name' => 'number3',
      'img' => '',
    ],
  ]
];
TiwForm::normal('select-img', 2, ['name' => 'text', 'placeholder' => 'Please Select'], $options);</code></pre>
        </div>
      </div>
      <!------------------------color------------------------->
      <div class="col-12 pt-20px">
        <h5 id="color" class="text-primary">input:color</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('color', '#a14f4f', ['name' => 'color']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('color', '#a14f4f', ['name' => 'color']);</code></pre>
        </div>
      </div>

      <div class="col-12 pt-20px">
        <h5 id="select-img" class="text-primary">select with color</h5>
        <div clas="my-20px">
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
          //param 2 = value ถ้า value ตรงกับไอดีใน list จะทำการ selected ให้อัตโนมัติ
          TiwForm::normal('select-color', '#9c27b0', ['name' => 'text', 'placeholder' => 'Please Select'], $options);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">$options = [
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
//param 2 = value ถ้า value ตรงกับไอดีใน list จะทำการ selected ให้อัตโนมัติ
TiwForm::normal('select-color', '#9c27b0', ['name' => 'text', 'placeholder' => 'Please Select'], $options);</code></pre>
        </div>
      </div>
      <!-------------------------file------------------------->
      <div class="col-12 pt-20px">
        <h5 id="file" class="text-primary">input:file</h5>
        <div class="mb-10px">
          <?= TiwForm::normal('file', '', ['name' => 'file']); ?>
        </div>
        <div class="mb-10px">
          <?= TiwForm::normal('file', 'http://128.199.207.248:8412/system/resource/placeholder/placeholder_user_square.jpg', ['name' => 'file']); ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">ใส่ไฟล์ที่อัพโหลดไปแล้ว
$value = 'http://128.199.207.248:8412/system/resource/placeholder/placeholder_user_square.jpg';

TiwForm::normal('file', $value, ['name' => 'file']);</code></pre>
        </div>
      </div>
      <!--------------------------button------------------------>
      <div class="col-12 pt-20px">
        <h5 id="button" class="text-primary">button</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('btn', '', ['class' => 'btn-primary'], ['text' => '.btn-primary']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('btn', '', ['class' => 'btn-primary'], ['text' => 'Add']);</code></pre>
        </div>
        <div clas="my-20px">
          <?php
          TiwForm::normal('btn', '', ['class' => 'btn-danger'], ['text' => '.btn-danger']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('btn', '', ['class' => 'btn-danger'], ['text' => 'Add']);</code></pre>
        </div>
        <div clas="my-20px">
          <?php
          TiwForm::normal('btn', '', ['class' => 'btn-light'], ['text' => '.btn-light']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('btn', '', ['class' => 'btn-light'], ['text' => 'Add']);</code></pre>
        </div>
        <span class="text-danger">หมายเหตุ:</span> หรือหากต้องการสีอื่น ๆ ให้เอา Class จาก <a href="school.php?topic=800" target="_blank">BOAT COLOR</a> มาใส่
      </div>
      <!--------------------------edit------------------------>
      <div class="col-12 pt-20px">
        <h5 id="button" class="text-primary">button:edit</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('btn', '', ['tooltip' => 'Edit'], ['type' => 'edit']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('btn', '', ['tooltip' => 'Edit'], ['type' => 'edit']);</code></pre>
        </div>
      </div>
      <!--------------------------delete------------------------>
      <div class="col-12 pt-20px">
        <h5 id="button" class="text-primary">button:delete</h5>
        <div clas="my-20px">
          <?php
          TiwForm::normal('btn', '', ['tooltip' => 'Delete'], ['type' => 'delete']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('btn', '', ['tooltip' => 'Delete'], ['type' => 'delete']);</code></pre>
        </div>
      </div>
      <!-------------------date range--------------------->
      <div class="col-12 pt-20px">
        <h5 id="daterange" class="text-primary">date range</h5>
        <div clas="my-20px">

          <?php
          TiwForm::normal('daterange', '2021-03-09 to 2021-03-13', ['name' => 'date_filter', 'placeholder' => 'Filter Billing Date']);
          ?>

        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">$data = [
  'name' => 'date_filter', //แล้วแต่จะตั้ง
  'placeholder' => 'Filter Billing Date'
];
//param 2 = หากต้องการเซ็ตวันที่ต้องใช้ format นี้ ตย. 2021-03-09 to 2021-03-13
TiwForm::normal('daterange', '', $data);</code></pre>
        </div>
      </div>
      <!----------------------select tag---------------------->
      <div class="col-12 pt-20px">
        <h5 id="select-tag" class="text-primary">select and tag</h5>
        <div clas="my-20px">

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
                    'selected' => '',
                  ],
                  [
                    'value' => 2,
                    'name' => 'ชอบขาดประชุม',
                    'selected' => '',
                  ],
                  [
                    'value' => 3,
                    'name' => 'ทำงานเกินเวลาบ่อย',
                  ],
                ],
              ],
              [
                'name' => 'Employee Remark 2',
                'list' => [
                  [
                    'value' => 4,
                    'name' => 'มีความเป็นตัวเองสูง 2',
                  ],
                  [
                    'value' => 5,
                    'name' => 'ชอบขาดประชุม 2',
                    'selected' => '',
                  ],
                  [
                    'value' => 6,
                    'name' => 'ทำงานเกินเวลาบ่อย 2',
                  ],
                ],
              ],
            ]
          ];
          TiwForm::normal('select-tag', '', ['name' => 'select_tag', 'placeholder' => 'Filter Data', 'id' => 'select_tag'], $options);
          ?>

        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">แบบมี title
$options = [
  'list' => [
    [
      'name' => 'Employee Remark',
      'list' => [
        [
          'value' => 1,
          'name' => 'มีความเป็นตัวเองสูง',
          'selected' => '',
        ],
        [
          'value' => 2,
          'name' => 'ชอบขาดประชุม',
          'selected' => '',
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

//หรือ แบบไม่มี title
$options = [
  'list' => [
    [
        'value' => 'timestamp',
        'name' => 'timestamp',
    ],
    [
        'value' => 'Type',
        'name' => 'Type',
    ]
  ]
];

TiwForm::normal('select-tag', '', ['name' => 'select_tag', 'placeholder' => 'Filter Data'], $options);


//หากต้องการเซ็ตค่าแบบ script
var select_ids = [2,3]; //ไอดีที่ต้องการให้ select
w.tiwform.setTagData('#ไอดีของ Tag', select_ids);</code></pre>
        </div>
      </div>
      <!-----------------------upload img---------------------->
      <div class="col-12 pt-20px">
        <h5 id="upload-img" class="text-primary">upload img</h5>
        <div class="my-20px">
          <?php
          $options = [
            'width' => '200px',
            'height' => '100%',
            'bg-img' => '../../modules/main/tiwform/img/bg1.png',
          ];
          ?>
          <div class="mb-10px">
            <?= TiwForm::normal('upload-img', '', ['name' => 'img_1'], $options); ?>
          </div>

          <?php
          $options = [
            'width' => '150px',
            'height' => '100%',
            'bg-img' => '../../modules/main/tiwform/img/bg1.png',
          ];
          ?>
          <div class="">
            <?= TiwForm::normal('upload-img', '', ['name' => 'img_2', 'title' => 'ไม่ใส่ title ให้เอาออก'], $options); ?>
          </div>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">//param 2 = url image
$options = [
  'width' => '200px',
  'height' => '100%',
  'bg-img' => '../../modules/main/tiwform/img/bg1.png',
  'title' => 'ทดสอบ title', //title จะไปแสดงด้านล่างของรูป
  'is_btn' => 0, //ไม่เอาปุ่มทั้งหมด
  'is_view' => 1, //ไม่เอาปุ่ม view
  'is_delete' => 1, //ไม่เอาปุ่ม ลบ
  'preview_name' => '{name}', //ใช้กรณีที่ใช้ Auto form ใส่ชื่อให้ตรงกับ data ที่หลังบ้านส่งมา
];
TiwForm::normal('upload-img', 'src image', ['name' => 'text', 'title' => 'ไม่ใส่ title ให้เอาออก'], $options);</code></pre>
        </div>
      </div>
      <!--------------------input telephone--------------------->
      <div class="col-12 pt-20px">
        <h5 id="telephone" class="text-primary">input:telephone</h5>
        <div clas="my-20px">
          <?= TiwForm::normal('tel-flag', '+66 19876543', ['name' => 'tel', 'placeholder' => 'กรอกเบอร์โทร']); ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">//value = +XX
TiwForm::normal('tel-flag', '+66 19876543', ['name' => 'tel', 'placeholder' => 'กรอกเบอร์โทร']);</code></pre>
        </div>
      </div>
      <!------------------select Language--------------------->
      <div class="col-12 pt-20px">
        <h5 id="language" class="text-primary">Select Language</h5>
        <?= TiwForm::normal('select-language', '', ['name' => 'text'], ['is_search' => true]); ?>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('select-language', $value, ['name' => 'language']);</code></pre>
        </div>
      </div>
      <!--------------------select title------------------------>
      <div class="col-12 pt-20px">
        <h5 id="title_name" class="text-primary">Select Title Name</h5>
        <?= TiwForm::normal('select-title', '', ['name' => 'text']); ?>
        <div class="code-container mt-10px">
          <pre><code data-language="php">TiwForm::normal('select-title', $value, ['name' => 'title_name']);</code></pre>
        </div>
      </div>
    </div>
  </div>

  <div class="content-header-container" id="radio">
    <h3 class="header-title text-primary" id="checkbox">CHECKBOX & RADIO</h3>
  </div>
  <div class="content-body-container">
    <div class="row">
      <div class="col pt-20px">
        <label class="font-weight-bold text-primary">[Type 1]</label>
        <div class="d-flex justify-content-around">
          <?php
          TiwForm::normal('checkbox', 1, ['name' => 'language'], ['style' => 1]);
          TiwForm::normal('checkbox', 1, ['name' => 'language'], ['style' => 1, 'is_on_off' => true]);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">//Param 1 = 'checkbox, radio';
$data = [
  'name' => 'ex_checkbox',
  'checked' => true, //ต้องการให้เช็ค
  'required' => true, //หากต้องการบังคับให้เช็ค
  'class' => '',
  //สามารถเพิ่ม attribute อื่นๆ ได้ตามต้องการ
];

$options = [
  'style' => 1, //รูปแบบของ Checkbox, Radio | style = 1,2,3
  'is_on_off' => true, //เปิดปิดการแสดงผลตัวหนังสือ ON, OFF
  'text_on' => 'Yes', //หากไม่ใส่ค่าพื้นฐานคือ ON
  'text_off' => 'No', //หากไม่ใส่ค่าพื้นฐานคือ OFF
];

TiwForm::normal('checkbox', $value, $data, $options);</code></pre>
        </div>
        <label>CSS CUSTOM</label>
        <div class="code-container mt-10px">
          <pre><code data-language="php">
@import "../../../../_framework/modules/tiwform/mixin";
.test-class {
  $data: (
    'color_on' : var(--color-primary),
    'color_off' : var(--color-secondary),
    'border_size' : 3px,
    'font_size' : 10px,
    'width' : 50px,
    'height' : 25px,
    'bg_color_on' : #fff,
    'bg_color_off' : #fff,
  );
  @include checkbox-slide($data);
}</code></pre>
        </div>
      </div>


      <div class="content-header-container w-100"></div>
      <div class="col-12 pt-20px">
        <label class="font-weight-bold text-primary">[Type 2]</label>
        <div class="d-flex justify-content-around">
          <?php
          TiwForm::normal('radio', 1, ['name' => 'option_type'], ['style' => 2, 'label' => 'Option 1']);
          TiwForm::normal('radio', 1, ['name' => 'option_type', 'class' => 'green'], ['style' => 2, 'label' => 'Class: green']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">//Param 1 = 'checkbox, radio';
$data = [
  'name' => 'ex_checkbox',
  'checked' => true, //ต้องการให้เช็ค
  'required' => true, //หากต้องการบังคับให้เช็ค
  'class' => 'green',
  //สามารถเพิ่ม attribute อื่นๆ ได้ตามต้องการ
];

$options = [
  'style' => 1, //รูปแบบของ Checkbox, Radio | style = 1,2,3
  'label' => 'Option 1', //text ที่แสดงข้าง ๆ
];

TiwForm::normal('checkbox', $value, $data, $options)</code></pre>
        </div>
        <label>CSS CUSTOM</label>
        <div class="code-container mt-10px">
          <pre><code data-language="php">@import "../../../../_framework/modules/tiwform/mixin";
.test-class {
  $data: (
    'bg_color' : transparent,
    'bg_color_active' : var(--color-primary),
    'border_color' : var(--color-primary),
    'border_color_active' : transparent,
    'box_size' : 24px,
    'icon_size' : 10px,
  );
  @include checkbox-sun($data);
}</code></pre>
        </div>
      </div>

      <div class="col-12">
        <label class="font-weight-bold text-primary">[Type 3]</label>
        <div class="d-flex justify-content-around">
          <?php
          TiwForm::normal('checkbox', 1, ['name' => 'check_type', 'class' => 'check-all'], ['style' => 3, 'label' => 'Check All']);
          TiwForm::normal('checkbox', 1, ['name' => 'check_type'], ['style' => 3, 'label' => 'Check Nomal']);
          TiwForm::normal('checkbox', 1, ['name' => 'check_type', 'class' => 'green'], ['style' => 3, 'label' => 'Class green']);
          ?>
        </div>
        <div class="code-container mt-10px">
          <pre><code data-language="php">//Param 1 = 'checkbox, radio';
$data = [
  'name' => 'ex_checkbox',
  'checked' => true, //ต้องการให้เช็ค
  'required' => true, //หากต้องการบังคับให้เช็ค
  'class' => '', //ใส่ Class check-all ถ้าต้องการใช้เช็คเป็นขีด ใช้กรณี check all
  //สามารถเพิ่ม attribute อื่นๆ ได้ตามต้องการ
];

$options = [
  'style' => 1, //รูปแบบของ Checkbox, Radio | style = 1,2,3
  'label' => 'Option 1', //text ที่แสดงข้าง ๆ
];

TiwForm::normal('checkbox', $value, $data, $options)</code></pre>
        </div>
        <label>CSS CUSTOM</label>
        <div class="code-container mt-10px">
          <pre><code data-language="php">@import "../../../../_framework/modules/tiwform/mixin";
.test-class {
  $data: (
    'icon_color' : var(--color-place-holder),
    'icon_color_active' : #FFF,
    'bg_color' : transparent,
    'bg_color_active' : var(--color-primary),
    'border_color' : var(--bg-card-navbar),
    'border_color_active' : var(--color-primary),
    'box_size' : 20px,
    'icon_size' : 13px,
    'icon' : 'icon/check.svg',
  );
  @include checkbox-group($data);
}</code></pre>
        </div>
      </div>

      <!------------------Drag and drop file--------------------->
      <div class="col-12 pt-20px">
        <h5 id="drag-drop-file" class="text-primary">Drag and drop file</h5>
        <?php
        $imgs = [
          [
            'code' => 'cdior_document_2',
            'file_name' => 'test1',
            'extension' => 'jpg',
            'link' => 'http://174.138.19.235:8125/system/resource/placeholder/placeholder_user_square.jpg',
          ],
          [
            'code' => 'cdior_document_3',
            'file_name' => 'test2',
            'extension' => 'jpg',
            'link' => 'http://174.138.19.235:8125/system/resource/placeholder/placeholder_user_square.jpg',
          ],
        ];
        TiwForm::normal('drag-drop-file', $imgs, ['name' => 'file[]', 'required' => true]);
        ?>

        <div class="code-container mt-10px">
          <pre><code data-language="php">$imgs = [
  [
    'code' => 'cdior_document_2',
    'file_name' => 'test1',
    'extension' => 'jpg',
    'link' => 'http://174.138.19.235:8125/system/resource/placeholder/placeholder_user_square.jpg',
  ],
  [
    'code' => 'cdior_document_3',
    'file_name' => 'test2',
    'extension' => 'jpg',
    'link' => 'http://174.138.19.235:8125/system/resource/placeholder/placeholder_user_square.jpg',
  ],
];
TiwForm::normal('drag-drop-file', $imgs, ['name' => 'file[]']);</code></pre>
        </div>
      </div>

      <!------------------Add Tag--------------------->
      <div class="col-12 pt-20px">
        <h5 id="add-tag" class="text-primary">Add Tag</h5>
        <?= TiwForm::normal('add-scan-tag', ['test1', 'test2', 'test3'], ['name' => 'tag[]', 'required' => true]); ?>

        <div class="code-container mt-10px">
          <pre><code data-language="php">$value = ['test1', 'test2', 'test3']
TiwForm::normal('add-scan-tag', $value, ['name' => 'tag[]']);</code></pre>
        </div>
      </div>

    </div>
  </div>

  <script>
    w.ready(function() {
      w(document).on('change', '.gen_type_event input[select_value]', function(e) {
        var url = 'view/ajax/ajax_generate_data_option.php';
        var params = {
          type: $(this).val()
        };
        $.post(url, params).done(function(data) {
          $('.gen_area_option_event').html(data);
          setTagAutoEvent();
        });
      });

      w(document).on('click', '.generate_event', function(e) {
        if (!$('.gen_type_event input[select_value]').val()) {
          w.notification('error', 'กรุณาเลือก ประเภท Form ก่อน');
          return false;
        }
        var url = 'view/ajax/ajax_generate_form.php';
        var params = $('#generate_form_event').serializeJSON();
        $.post(url, params).done(function(data) {
          $('.gen_area_event').html(data);
          startdateRangeEvent();
        });
      });

      w(document).on('click', '.add_list_event', function(e) {
        var clone = $('.list_template_event').html();
        var len = $('.list_template_event .other_list_event').length;
        $('.list_area_event').append('<div class="d-flex align-items-end other_list_event w-100 list_' + len + '">' + clone + '</div>');

        $('.list_' + len + ' .demo_value_event').attr('name', 'list_value[]');
        $('.list_' + len + ' .demo_name_event').attr('name', 'list_name[]');
        $('.list_' + len + ' .demo_image_event').attr('name', 'list_img[]');
      });

      w(document).on('click', '.delete_list_event', function(e) {
        var scope = $(this).parents('.other_list_event');
        scope.remove();
      });
    });
  </script>
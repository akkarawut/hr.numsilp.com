<div class="content-header-container">
  <h3 class="header-title">Live Form</h3>
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
    <div class="col-12 pt-20px">
      <?php
      $api = [
        'api' => 'test_class::testFunciton',
        'params' => [
          'code' => 'ovsdu',
          'id' => 1,
          'data' => [
            'name' => '{name}',
          ]
        ]
      ];
      ?>

      <h5 class="text-primary">คำอธิบาย (กรุณาอ่านก่อน)</h5>
      <div class="code-container mt-10px">
        <pre><code data-language="php">//API ที่จะยิงไปบันทึก
$api = [
  'api' => 'test_class::testFunciton', //class ที่จะยิง
  'params' => [
    //หมายเหตุ : ต้องเรียก Params ตาม API ที่จะยิงไป
    'code' => 'ovsdu',
    'id' => 1,
    'data' => [
      'name' => '{name}',
    ]
  ]
];

----------------------

ตย. API ที่ยิงไป
test_class::testFunciton($code, $id, $data);

----------------------

TiwForm::liveForm($type, $name, $value, $api, $options);
Param 1 = Type
Param 2 = Name
Param 3 = Value
Param 4 = $api จากตัวอย่างข้างบน
Param 5 = Option เสริมเฉพาะบาง Type
</code></pre>
      </div>

      <?php
      //input:text
      TiwForm::liveForm('text', 'name', 'value', $api);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">TiwForm::liveForm('date', 'name', 'value', $api);</code></pre>
      </div>

      <?php
      //input:date
      TiwForm::liveForm('date', 'name', '10/12/2012', $api);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">TiwForm::liveForm('time', 'name', '10/12/2012', $api);</code></pre>
      </div>

      <?php
      //input:time
      TiwForm::liveForm('time', 'name', '19:00', $api);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">TiwForm::liveForm('time', 'name', '19:00', $api);</code></pre>
      </div>

      <?php
      //input:datetime
      TiwForm::liveForm('datetime', 'name', '10/12/2012 19:00', $api);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">TiwForm::liveForm('datetime', 'name', '10/12/2012 19:00', $api);</code></pre>
      </div>

      <?php
      //textarea
      TiwForm::liveForm('textarea', 'name', 'textarea', $api);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">TiwForm::liveForm('textarea', 'name', 'textarea', $api);</code></pre>
      </div>
      <?php
      //input:number
      $rand = rand(111111, 99999) . '.' . rand(0, 99);
      $options = [
        'currency' => '฿',
        'detail' => number_format($rand, 2),
      ];
      TiwForm::liveForm('number', 'name', $rand, $api, $options);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">$rand = rand(111111, 99999).'.'.rand(0,99);
$options = [
  'currency' => '฿',
  'detail' => number_format($rand, 2),
];
TiwForm::liveForm('number', 'name', $rand, $api, $options);</code></pre>
      </div>
      <?php
      //select option
      $rand = rand(111111, 99999) . '.' . rand(0, 99);
      $options = [
        'list' => [
          [
            'name' => 'Please select',
            'value' => '',
            'disabled' => true,
          ],
          [
            'name' => 'select 1',
            'value' => 1,
          ],
          [
            'name' => 'select 2',
            'value' => 2,
          ],
        ],
        'detail' => 'select 1',
      ];
      TiwForm::liveForm('select', 'name', 1, $api, $options);
      ?>
      <div class="code-container mt-10px">
        <pre><code data-language="php">$rand = rand(111111, 99999).'.'.rand(0,99);
$options = [
  'list' => [
    [
      'name' => 'please select',
      'value' => '',
      'disabled' => true,
    ],
    [
      'name' => 'number1',
      'value' => 1,
    ],
    [
      'name' => 'number2',
      'value' => 2,
    ],
  ],
  'detail' => 'select 1',
];
TiwForm::liveForm('select', 'name', 'value', $api, $options);</code></pre>
      </div>

      <div class="d-flex flex-wrap justify-content-around">
        <?php
        TiwForm::liveForm('checkbox', 'name', 1, $api, ['style' => 1, 'label' => 'Option 1', 'is_on_off' => true]);
        TiwForm::liveForm('checkbox', 'name', 1, $api, ['style' => 1, 'label' => 'Option 1']);
        TiwForm::liveForm('checkbox', 'name', 1, $api, ['style' => 2, 'is_on_off' => 1, 'label' => 'Option 2']);
        TiwForm::liveForm('checkbox', 'name', 1, $api, ['style' => 3, 'label' => 'Option 3']);
        TiwForm::liveForm('checkbox', 'name', 1, $api, ['style' => 3, 'class' => 'check-all', 'label' => 'Option 3']);
        ?>
      </div>
      <div class="code-container mt-10px">
        <pre><code data-language="php">param 3 = 0 กับ 1 เท่านั้น ถ้าเป็น 1 จะทำการ checked ให้
param 4 = options = [
  'type' => 1 หรือ 2 หรือ 3;
  'is_on_off' => แสดงตัวหนังสือ on off (ใช้ได้เฉพาะ type 1 เท่านั้น)
];
//type 1 = checkbox radio ที่เป็นแบบเลื่อนเปิดปิด
//type 2 = checkbox radio ที่เป็นแบบกลม ๆ
//type 3 = checkbox radio ที่เป็นแบบสี่เหลี่ยม
TiwForm::liveForm('checkbox', 'name', 1, $api);</code></pre>
      </div>

      <h6 class="mt-20px">คำอธิบาย</h6>
      <div class="code-container mt-10px">
        <pre><code data-language="php">param 1 = type (text, textarea, number, select)
param 2 = name ชื่อที่ตรงกับ database
param 3 = value
param 4 = API ที่จะยิงไปเพื่อบันทึก
param 5 = options (ใส่หรือไม่ใส่ก็ได้)

ตัวอย่าง option เต็ม ๆ
$options = [
  'class' => 'class1 class2', //ถ้าต้องการใส่ class เพิ่ม

  'detail' => //ข้อความ, - ใส่กรณีที่ต้องการ value ใน input แสดงผลไม่เหมือนกับ ข้อความ ก่อนกดแก้ไข อย่างเช่นกรณีที่เป็นตัวเลข ต้องแสดง 999,999.00 แต่ใส่ value ต้องแสดง 999999.00 เป็นต้น

  'placeholder' => placeholder,

  'currency' => '$', //ข้อความ 1 ตัวอักษรที่จะนำไปแสดงข้างหน้า

  'list' => [
    [
      'name' => 'please select',
      'value' => '',
      'disabled' => true,
    ],
    [
      'name' => 'number1',
      'value' => 1,
    ],
    [
      'name' => 'number2',
      'value' => 2,
    ],
  ], //ใส่กรณีที่ใช้ type select
];</code></pre>
      </div>
    </div>
  </div>
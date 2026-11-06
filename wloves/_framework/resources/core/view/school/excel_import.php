<?php
$getmain  = isset($_GET['getmain']) ? $_GET['getmain'] : '';
$getsub   = isset($_GET['getsub']) ? $_GET['getsub'] : '';
$data_nav = [
  [
    'id'       => 1,
    'name'     => 'Test 01',
    'count'    => 14,
    'html_dot' => '',
    'sub'      => [
      [
        'id'       => 1,
        'name'     => 'sub test01',
        'count'    => 10,
        'html_dot' => '<div class="dot red"></div><div class="dot yellow"></div>'
      ],
      [
        'id'       => 2,
        'name'     => 'sub test02',
        'count'    => 4,
        'html_dot' => '<div class="dot red"></div>'
      ]

    ]
  ],
  [
    'id'       => 2,
    'name'     => 'Test 02',
    'count'    => 10,
    'html_dot' => '',
    'sub'      => [
      [
        'id'       => 3,
        'name'     => 'sub test01',
        'count'    => 5,
        'html_dot' => '<div class="dot red"></div>'
      ],
      [
        'id'       => 4,
        'name'     => 'sub test02',
        'count'    => 5,
        'html_dot' => '<div class="dot red"></div>'
      ]

    ]
  ]
];
?>
<div class="content-header-container">
  <h3 class="header-title">วิธีการใช้งาน IT EXCEL<p class="font-14px mt-5px">ฮั่นแน่</p>
  </h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <label>นำโค้ดส่วนนี้ไปใช้งาน</label>
      <div class="code-container mb-3">
        <pre><code data-language="php">
  W_Structure::loadModules(['itexcel']); // การประกาศ itexcel จาก Module ที่เก็บไว้

  Itexcel::import($file,$start_row);
        </code></pre>
      </div>
      <label>อธิบายเพิ่มเติม</label>
      <p class="mb-0">
        $file = ไฟล์ excel ที่ส่งมา<br>
        $start_row = ให้เริ่มอ่านข้อมูลตั้งแต่แถวไหน <br>
      </p>
    </div>
  </div>
</div>

<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <label>ตัวอย่าง การใช้งาน</label>
      <a href="view/school/file_example.xlsx" class="text-danger font-20px" download>( ไฟล์ตัวอย่าง กดสิ กดสิ !!! )</a>
      <div class="code-container">
        <pre><code data-language="php">
  data_excel_list = Itexcel::import($_FILES['excel_file']['tmp_name'], 1);

  // array ที่ได้จากไฟล์ตัวอย่าง ได้สิแบบนี้เลย

  [0] => Array
    (
        [name1] => ชื่อสินค้า 1
        [name2] => ชื่อสินค้า 2
        [name3] => ชื่อสินค้า 3
        [name4] => ชื่อสินค้า 4
        [name5] => ชื่อสินค้า 5
        [name6] => ชื่อสินค้า 6
        [name7] => ชื่อสินค้า 7
        [name8] => ชื่อสินค้า 8
        [name9] => ชื่อสินค้า 9
        [name10] => ชื่อสินค้า 10
        [weight] => 30
        [unit] => กิโลกรัม
    )

</code></pre>
      </div>
    </div>
  </div>
</div>
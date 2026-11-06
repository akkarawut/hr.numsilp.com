<?php

$data_th = [
  [
    'th' => '',
    'type' => ['checkbox'],
  ],
  [
    'th' => 'Name',
    'type' => ['text', true],
  ],
  [
    'th' => 'Country',
    'type' => ['text']
  ],
  [
    'th' => 'Currency',
    'type' => ['dropdown', false, ['THB', 'BITCOIN', 'DOLLAR']],
  ],
  [
    'th' => 'Level',
    'type' => ['text', true]
  ],
  [
    'th' => 'Date',
    'type' => ['date']
  ],
  [
    'th' => 'Units',
    'type' => ['numeric']
  ]
];

$data_td = [
  [
    'is_checked' => true,
    'id' => 'Lorem',
    'country' => 'ipsum',
    'currency' => 'THB',
    'level' => 'sit',
    'date' => '12/08/2015',
    'units' => 23
  ],
  [
    'is_checked' => true,
    'id' => 'Lorem2',
    'country' => 'ipsum2',
    'currency' => 'THB',
    'level' => 'sit2',
    'date' => '15/12/2015',
    'units' => 35
  ],
  [
    'is_checked' => true,
    'id' => 'Lorem',
    'country' => 'ipsum',
    'currency' => 'BITCOIN',
    'level' => 'sit',
    'date' => '12/08/2015',
    'units' => 12
  ],
  [
    'is_checked' => false,
    'id' => 'Lorem2',
    'country' => 'ipsum2',
    'currency' => 'DOLLAR',
    'level' => 'sit2',
    'date' => '15/12/2015',
    'units' => 5
  ],
  [
    'is_checked' => false,
    'id' => 'Lorem',
    'country' => 'ipsum',
    'currency' => 'DOLLAR',
    'level' => 'sit',
    'date' => '12/08/2015',
    'units' => 55
  ],
  [
    'is_checked' => true,
    'id' => 'Lorem2',
    'country' => 'ipsum2',
    'currency' => 'BITCOIN',
    'level' => 'sit2',
    'date' => '15/12/2015',
    'units' => 64
  ],
  // [
  //   'id' => 'Total',
  //   'country' => '',
  //   'currency' => '',
  //   'level' => '',
  //   'date' => '',
  //   'units' => 'SUM'
  // ],
  // [
  //   'id' => '=SUM(F:F)',
  //   'country' => '',
  //   'currency' => '',
  //   'level' => '',
  //   'date' => '',
  //   'units' => '=SUM(F1:F6)'
  // ],
];

$options = [
  'columnSorting' => true,
  'rowHeaders' => true,
  'autoInsertRow' => false,
  'manualColumnMove' => false,
  'manualRowMove' => false,
  'dropdownMenu' => false,
  'filters' => false,
  'height' => 'auto',
  'fixedRowsBottom' => false,
  'fixedColumnsLeft' => false,
];
?>
<div class="content-header-container">
  <h3 class="header-title">การใช้งาน Handsontable<p class="font-14px mt-5px">waiting . . .</p>
  </h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12 p-10px">
      <div class="d-flex align-items-center justify-content-between mb-10px">
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#general_modal">
          General Modal
        </button> -->
        <h5 class="mb-0">Handsontable</h5>
        <div class="d-flex">
          <button id="export-string" class="btn btn-outline-primary mx-5px">Export Data</button>
          <button id="export-file" class="btn btn-outline-primary mx-5px">Export file</button>
        </div>
      </div>
      <?php Brandontable::letMe('example1', ['data_th' => $data_th, 'data_td' => $data_td], $options); ?>
    </div>
    <div class="col-12 p-10px">
      <div class="code-container">
        <pre><code data-language="php">&lt;?php
  //เรียกใช้ Module brandontable ไว้ที่หัวของหน้านั้น ๆ
  W_Structure::loadModules(['brandontable']);

  //เรียกฟังก์ชัน
  Brandontable::letMe('example1', ['data_th' => $data_th, 'data_td' => $data_td], $options);
?&gt;
</code></pre>
      </div>
    </div>

    <div class="col-12">อธิบายเพิ่มเติม</div>
    <div class="col-12">*** data_th ***</div>
    <div class="col-3">th</div>
    <div class="col-9">= คือ ชื่อ th ตาราง <span class="text-danger">(ใส่ array ให้ตรงกับ key ของ $data_td)</span></div>
    <div class="col-3">type</div>
    <div class="col-9">= คือ type (array) <br>
      param1 คือ type ของ input ใน column นั้นๆ // text, date, numeric, dropdown, checkbox<br>
      param2 คือ readonly ของ input ใน column นั้นๆ // true, false<br><br>
      *** ถ้า type = dropdown (ส่ง param3 เป็น array มาด้วย) ***<br>
      param3 คือ array ของ dropdown // เช่น ['THB', 'BITCOIN', 'DOLLAR']
    </div>

    <div class="col-12"><br></div>
    <div class="col-12">*** data_td ***</div>
    <div class="col-3">data_td</div>
    <div class="col-9">// คือ data <span class="text-danger">(ใส่ key ใน array ให้ตรงกับ $data_th['th'])</span></div>

    <div class="col-12"><br></div>
    <div class="col-12">*** options ***</div>
    <div class="col-3">$columnSorting</div>
    <div class="col-9">= true //sort ข้อมูล</div>
    <div class="col-3">$rowHeaders </div>
    <div class="col-9">= true //ลำดับ ข้อมูล</div>
    <div class="col-3">$autoInsertRow </div>
    <div class="col-9">= false //เพิ่ม row ได้</div>
    <div class="col-3">$manualColumnMove </div>
    <div class="col-9">= false //ย้าย column ได้</div>
    <div class="col-3">$manualRowMove </div>
    <div class="col-9">= false //ย้าย row ได้</div>
    <div class="col-3">$dropdownMenu </div>
    <div class="col-9">= false //มี Menu dropdown ที่ th ให้</div>
    <div class="col-3">$filters </div>
    <div class="col-9">= false //เพิ่ม filter ที่ Menu dropdown</div>
    <div class="col-3">$height </div>
    <div class="col-9">= 'auto' //ความสูงของตาราง</div>
    <div class="col-3">$fixedRowsBottom </div>
    <div class="col-9">= false //freeze row ของตารางล่างสุด <br> (เช่น $fixedRowsBottom = 2 ก็จะ freeze 2 row ล่างสุดไว้)</div>
    <div class="col-3">$fixedColumnsLeft</div>
    <div class="col-9">= false //freeze column ของตารางซ้ายสุด <br> (เช่น $fixedColumnsLeft = 2 ก็จะ freeze 2 column ซ้ายสุด)</div>

    <!-- ปุ่ม -->
    <div class="col-12"><br></div>
    <div class="col-12">*** ปุ่มต่างๆ *** <span class="text-danger">(กำลังพัฒนา)</span></div>
    <div class="col-3">Export CSV</div>
    <div class="col-9">= ใส่ id ให้ปุ่ม id="export-file" </div>
    <div class="col-3">Export String</div>
    <div class="col-9">= ใส่ id ให้ปุ่ม id="export-string" (display in the console)</div>

    <!-- ตัวอย่าง -->
    <div class="col-12"><br></div>
    <div class="col-12 p-10px">*** ตัวอย่าง ***</div>
    <div class="col-12 p-10px">
      <div class="code-container">
        <pre><code data-language="php">&lt;?php
  //เรียกใช้ Module brandontable ไว้ที่หัวของหน้านั้น ๆ
  W_Structure::loadModules(['brandontable']);

  //ตัวอย่าง th (ใส่ array ให้ตรงกับ key ของ $data_td)
  $data_th = [
    [
      'th' => '',
      'type' => ['checkbox'],
    ],
    [
      'th' => 'Name',
      'type' => ['text', true],
    ],
    [
      'th' => 'Country',
      'type' => ['text']
    ],
    [
      'th' => 'Currency',
      'type' => ['dropdown', false, ['THB', 'BITCOIN', 'DOLLAR']],
    ],
    [
      'th' => 'Level',
      'type' => ['text', true]
    ],
    [
      'th' => 'Date',
      'type' => ['date']
    ],
    [
      'th' => 'Unit',
      'type' => ['numeric']
    ],
  ];

  //ตัวอย่าง data (ใส่ key ใน array ให้ตรงกับ $data_th['th'])
  $data_td = [
    [
      'is_checked' => true,
      'id' => 'Lorem',
      'country' => 'ipsum',
      'currency' => 'dolor',
      'level' => 'sit',
      'date' => '12/08/2015',
      'units' => 23
    ],
    [
      'is_checked' => false,
      'id' => 'Lorem2',
      'country' => 'ipsum2',
      'currency' => 'dolor2',
      'level' => 'sit2',
      'date' => '15/12/2015',
      'units' => 35
    ],
  ];

  //options Default (ไม่ใส่มา จะ Default ให้ตามนี้)
  $options = [
    'columnSorting' => true,
    'rowHeaders' => true,
    'autoInsertRow' => false,
    'manualColumnMove' => false,
    'manualRowMove' => false,
    'dropdownMenu' => false,
    'filters' => false,
    'height' => 'auto',
    'fixedRowsBottom' => false,
    'fixedColumnsLeft' => false,
  ];
?&gt;

//เรียกฟังก์ชัน
 &lt;?php Brandontable::letMe('example1', ['data_th' => $data_th, 'data_td' => $data_td], $options);?&gt;
</code></pre>
      </div>
    </div>
  </div>
</div>


<!-- <div class="modal fade" id="general_modal" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ex. General Modal</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <?php Brandontable::letMe('example2', ['data_th' => $data_th, 'data_td' => $data_td], []); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div> -->
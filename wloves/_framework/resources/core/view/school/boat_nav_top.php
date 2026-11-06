<?php
$data_nav = [
  'param_name'  => 'param_name',
  'class' => '',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'Module Details',
      'icon'   => 'structure/image/layout/icon-news.svg',
      'count'  => 10,
    ],
    [
      'id'  => 2,
      'name'  => 'User Type & User Setup',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 3,
      'name'  => 'Connected Module',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 4,
      'name'  => 'Module Details',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 5,
      'name'  => 'User Type & User Setup',
      'icon'   => '',
      'count'  => '',
    ],
    [
      'id'  => 6,
      'name'  => 'Connected Module',
      'icon'   => '',
      'count'  => '',
    ]
  ]
];
?>
<div class="content-header-container">
  <h3 class="header-title">วิธีการใช้งาน BOAT NAV แบบ DINNER</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <h5 class="col-12 text-danger">!! อ่านก่อนนำไปใช้งาน : NAV รูปแบบ DINNER ใช้แบบข้อมูลแท็บบน !!</h5>
    <div class="col-12">
      <h6>ตัวอย่างที่จะได้ เมื่อทำถูก</h6>

      <div class="row">
        <div class="col-12 mb-4">
          <?php Boatnav::dinner($data_nav); ?>
        </div>
      </div>

      <h6>นำโค้ดส่วนนี้ไปใช้งาน</h6>
      <div class="code-container mb-3">
        <pre><code data-language="php">
  W_Structure::loadModules(['boatnav']); // การประกาศ itnav จาก Module ที่เก็บไว้

  Boatnav::dinner($data_nav); // ประกาศการเรียกใช้งาน
        </code></pre>
      </div>
      <h6>อธิบายเพิ่มเติม</h6>
      <div class="row">
        <div class="col-3">$data_nav</div>
        <div class="col-9">= ข้อมูลที่ใส่ให้แสดงผล</div>
        <div class="col-3">$data_nav['param_name']</div>
        <div class="col-9">= ชื่อของค่า get ที่จะส่งขึ้นไป <span class="text-danger">!!ต้องส่งมา</span></div>
        <div class="col-3">$data_nav['class'] </div>
        <div class="col-9">= จะนำไปใส่ให้ต่อ class div นอกสุดเพื่อให้สามารถ custom css ได้ในงานอื่นๆ ไม่ custom ไม่ต้องส่งมาครับ</div>
        <div class="col-3">$data_nav['list']['id'] </div>
        <div class="col-9">= id สำหรับส่งค่า get เพื่อที่จะได้เอามา active ถูกเมนู <span class="text-danger">!!ต้องส่งมา</span></div>
        <div class="col-3">$data_nav['list']['name'] </div>
        <div class="col-9">= name แสดงชื่อเมนู <span class="text-danger">!!ต้องส่งมา</span></div>
        <div class="col-3">$data_nav['list']['icon'] </div>
        <div class="col-9">= icon แสดงรูปภาพ icon ถ้าไม่อยากให้แสดงให้ส่งสตริงเปล่ามา</div>
        <div class="col-3">$data_nav['list']['count'] </div>
        <div class="col-9">= count แสดงจำนวนข้อมูลในเมนู ถ้าไม่อยากให้แสดงให้ส่งสตริงเปล่ามา ถ้าส่งเป็น 0 จะยังแสดงให้อยู่</div>
      </div>
    </div>
  </div>
</div>

<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <h6>ตัวอย่าง การประกาศตัวแปรที่ใช้</h6>
      <div class="code-container">
        <pre><code data-language="php">
  $data_nav = [
    'param_name'  => 'param_name',
    'class' => '',
    'list' => [
      [
        'id'  => 1,
        'name'  => 'Module Details',
        'icon'   => 'structure/image/layout/icon-news.svg',
        'count'  => 10,
      ],
      [
        'id'  => 2,
        'name'  => 'User Type & User Setup',
        'icon'   => '',
        'count'  => '',
      ],
      [
        'id'  => 3,
        'name'  => 'Connected Module',
        'icon'   => '',
        'count'  => '',
      ],
      [
        'id'  => 4,
        'name'  => 'Module Details',
        'icon'   => '',
        'count'  => '',
      ],
      [
        'id'  => 5,
        'name'  => 'User Type & User Setup',
        'icon'   => '',
        'count'  => '',
      ],
      [
        'id'  => 6,
        'name'  => 'Connected Module',
        'icon'   => '',
        'count'  => '',
      ]
    ]
  ];
</code></pre>
      </div>
    </div>
  </div>
</div>
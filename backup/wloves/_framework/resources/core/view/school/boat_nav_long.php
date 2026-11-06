<?php
$data_nav = [
  'param_name'  => 'param_name',
  'class' => '',
  'list' => [
    [
      'name'  => 'User',
      'icon'   => '',
      'count'  => 10,
      'status' => '',
      'color_status' => '',
      'html' => '',
      'list'   => [
        [
          'title'  => 'Current User 01',
          'list'   => [
            [
              'id'  => 1,
              'name'  => 'Login',
              'icon'   => '',
              'count'  => '',
              'status' => '',
              'color_status' => '',
              'html' => '',
            ],
            [
              'id'  => 2,
              'name'  => 'Logout',
              'icon'   => '',
              'count'  => '',
              'status' => '',
              'color_status' => '',
              'html' => '',
            ]
          ]
        ],
        [
          'title'  => 'Current User 02',
          'list'   => []
        ]
      ]
    ],
    [
      'name'  => 'Lorem ipsum error',
      'icon'   => '',
      'count'  => 2,
      'status' => '',
      'color_status' => '',
      'html' => '',
      'list'   => [
        [
          'title'  => 'Current User 01',
          'list'   => []
        ],
        [
          'title'  => '',
          'list'   => [
            [
              'id'  => 3,
              'name'  => 'Login',
              'icon'   => '',
              'count'  => '',
              'status' => '',
              'color_status' => '',
              'html' => '',
            ],
            [
              'id'  => 4,
              'name'  => 'Logout',
              'icon'   => '',
              'count'  => '',
              'status' => '',
              'color_status' => '',
              'html' => '',
            ]
          ]
        ]
      ]
    ]
  ]
];
?>
<div class="content-header-container">
  <h3 class="header-title">วิธีการใช้งาน BOAT NAV แบบ MILKCLUB</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <h5 class="col-12 text-danger">!! อ่านก่อนนำไปใช้งาน : NAV รูปแบบ MILKCLUB ใช้กับจำนวนข้อมูลที่มีตั้งแต่ 2-3 ชั้น ข้อมูลย่อยเข้าไปอีก !!</h5>
    <div class="col-12">
      <h6>ตัวอย่างที่จะได้ เมื่อทำถูก</h6>

      <div class="row">
        <div class="col-4 mb-4">
          <?php Boatnav::milkclub($data_nav); ?>
        </div>
      </div>

      <h6>นำโค้ดส่วนนี้ไปใช้งาน</h6>
      <div class="code-container mb-3">
        <pre><code data-language="php">
  W_Structure::loadModules(['boatnav']); // การประกาศ itnav จาก Module ที่เก็บไว้

  Boatnav::milkclub($data_nav); // ประกาศการเรียกใช้งาน
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
        <div class="col-3">$data_nav['list']['name'] ขั้นที่ 1 กับ 3 </div>
        <div class="col-9">= name แสดงชื่อเมนู <span class="text-danger">!!ต้องส่งมา</span></div>
        <div class="col-3">$data_nav['list']['tilte'] ขั้นที่ 2 </div>
        <div class="col-9">= tilte แสดงชื่อหัวหมวดหมู่ ไม่ส่งมาจะไม่แสดงและถ้ามา list ในขั้นที่ 2 จะนำ list มาแสดงตามลำดับแทน</div>
        <div class="col-3">$data_nav['list']['icon'] </div>
        <div class="col-9">= icon แสดงรูปภาพ icon ถ้าไม่อยากให้แสดงให้ส่งสตริงเปล่ามา</div>
        <div class="col-3">$data_nav['list']['count'] </div>
        <div class="col-9">= count แสดงจำนวนข้อมูลในเมนู ถ้าไม่อยากให้แสดงให้ส่งสตริงเปล่ามา ถ้าส่งเป็น 0 จะยังแสดงให้อยู่</div>
        <div class="col-3">$data_nav['list']['status'] </div>
        <div class="col-9">= status แสดงคำสถานะที่ใส่มา ถ้าไม่อยากให้แสดงให้ส่งสตริงเปล่ามา</div>
        <div class="col-3">$data_nav['list']['count_status'] </div>
        <div class="col-9">= count_status นำไปใช้ใน class ของ count และ status เพื่อ custom css ต่อได้</div>
        <div class="col-3">$data_nav['list']['html'] </div>
        <div class="col-9">= html นำส่วนนี้ไปใสต่อ div สุดท้ายใน list นั้นเป็นตัวทำเพื่อการ custom มากเกินกำหนด เลยส่งมาเป็น html มาใส่เลย</div>
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
    'list' =>[
      [
        'name'  => 'User',
        'icon'   => '',
        'count'  => 10,
        'status' => '',
        'color_status' => '',
        'html' => '',
        'list'   => [
          [
            'title'  => 'Current User 01',
            'list'   => [
              [
                'id'  => 1,
                'name'  => 'Login',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ],
              [
                'id'  => 2,
                'name'  => 'Logout',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ]
            ]
          ],
          [
            'title'  => 'Current User 02',
            'list'   => []
          ]
        ]
      ],
      [
        'name'  => 'Lorem ipsum error',
        'icon'   => '',
        'count'  => 2,
        'status' => '',
        'color_status' => '',
        'html' => '',
        'list'   => [
          [
            'title'  => 'Current User 01',
            'list'   => []
          ],
          [
            'title'  => '',
            'list'   => [
              [
                'id'  => 3,
                'name'  => 'Login',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ],
              [
                'id'  => 4,
                'name'  => 'Logout',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ]
            ]
          ]
        ]
      ]
    ]
  ];
</code></pre>
      </div>
    </div>
  </div>
</div>
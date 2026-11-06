<div class="content-header-container">
  <h3 class="header-title text-info">Gantt Chart</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="i-chart-ex">
        <?php
        $ex_data = [
          '2022-10-03' => [
            [
              'fromDate' => '2023-04-01 10:30',
              'toDate' => ' 2023-04-01 13:15',
              'type' => 'normal',
              'detail' => 'Task : 0001'
            ],
            [
              'fromDate' => '2023-04-01 14:30',
              'toDate' => ' 2023-04-02 17:15',
              'type' => 'late_all',
              'detail' => 'Task : 0002'
            ],
          ],
          '2022-10-04' => [],
          '2022-10-06' => [
            [
              'fromDate' => '2023-04-03 10:30',
              'toDate' => ' 2023-04-03 13:15',
              'type' => 'late_due_date',
              'detail' => 'Task : 0003'
            ],
            [
              'fromDate' => '2023-04-03 14:30',
              'toDate' => ' 2023-04-03 17:15',
              'type' => 'late_due_date',
              'detail' => 'Task : 0004'
            ],
          ],
          '2022-10-07' => [
            [
              'fromDate' => '2023-04-05 12:00',
              'toDate' => ' 2023-04-05 12:35',
              'type' => 'normal',
              'detail' => 'Task : 0007'
            ],
            [
              'fromDate' => '2023-04-05 12:45',
              'toDate' => ' 2023-04-05 13:00',
              'type' => 'normal',
              'detail' => 'Task : 0008'
            ],
            [
              'fromDate' => '2023-04-05 13:05',
              'toDate' => ' 2023-04-05 17:15',
              'type' => 'late_from_estimate',
              'detail' => 'Task : 0009'
            ],
            [
              'fromDate' => '2023-04-05 17:20',
              'toDate' => ' 2023-04-05 19:15',
              'type' => 'late_from_estimate',
              'detail' => 'Task : 00010'
            ],
          ],
          '2022-10-08' => [],
        ];
        $options = [
          'color_template' => [
            [
              'type' => 'normal',
              'color' => '#3872E1'
            ],
            [
              'type' => 'late_from_estimate',
              'color' => '#FFAD00'
            ],
            [
              'type' => 'late_due_date',
              'color' => '#ED4234'
            ],
            [
              'type' => 'late_all',
              'color' => '#A738E1'
            ],
          ]
        ];
        ArtChart::scheduleChart($ex_data, $options);
        ?>
      </div>
    </div>
  </div>
  <div class="w-love-content-container">
    <h3 class="text-underline">คู่มือการใช้งาน</h3>
    <h5 class="text-primary">ข้อมูลเบื้องต้น</h5>
    <ul class="list-group mb-4">
      <li class="list-group-item"><span class="text-success">Structure::loadModules(['artchart'])</span> - ต้องโหลดโมดูล artchart เสมอหากต้องการใช้ฟังชันนี้</li>
      <li class="list-group-item">$options <span class="text-primary">['color_template']</span> - 1.สีที่เราจะเอาไปแสดงใน Schedule Chart</li>
      <li class="list-group-item">$options <span class="text-primary">['color_template']</span> - 2.ต้องส่งไปในรูปแบบ Array เท่านั้น <a href="#option_color">ตัวอย่าง Color template</a></li>
    </ul>
    <pre>
    <code data-language="php">
      //เรียกใช้ Module
      Structure::loadModules(['artchart']); 

      //เรียกใช้ Chart

      // กรณีงานตัดแบบที่ยังไม่เชื่อมข้อมูล สามารถนำโค๊ดนี้ไปใช้ได้เลย จะเป็นการสร้างข้อมูลตัวอย่างขึ้นมาแสดง
      ArtChart::scheduleChart();

      // กรณีที่จะเชื่อม API แล้ว
      // parameter 1 = List ข้อมูลจา่ก API
      // parameter 2 = options สำหรับการ customize
      ArtChart::scheduleChart($ex_data, $options);

    </code>
    </pre>
    <h4 class="text-danger text-underline" id="description_key">คำอธิบายแต่ละ Key *สำคัญมาก* (ไม่อ่านจะเชื่อมไม่ถูก)</h4>
    <pre>
      <span>
        <a href="#example_data" class="mt-2">จากข้อมูลตัวอย่าง</a>
      </span>
        1. key data = ชื่อของกลุ่มข้อมูล (แสดงด้านซ้ายของกราฟ) ต้องเป็น YYYY-mm-dd เท่านั้น
        2. fromDate = เวลาเริ่มต้นของ Progress นั้น (แสดงอยู่ในตารางอิงกับ key toDate)
        3. toDate   = เวลาสิ้นสุดต้นของ Progress นั้น (แสดงอยู่ในตารางอิงกับ key fromDate)
        4. type     = ประเภทของ Progress นั้น (ควรขอจาก Backend ก่อนว่ามี Type อะไรบ้างเพื่อจะได้สร้าง Color Template ให้ตรงกับ Type ที่มีอยู่)
        5. detail   = รายละเอียดของ Progress นั้น (แสดงเมื่อ Hover ที่กราฟช่องนั้นๆ)
  </pre>

    <h3 class="" id="option_color">ตัวอย่าง Option - color template</h3>
    <pre>
        <code data-language="php">
        ตัวอย่าง Option['color_template'] ที่ส่งไปใน Schedule Chart
        $options = [
          'color_template' => [
            [
            'type' => 'normal',
            'color' => '#3872E1'
            ],
            [
            'type' => 'late_from_estimate',
            'color' => '#FFAD00'
            ],
          ]
        ];
        </code>
      </pre>
    <h3 class="text-primary text-underline">Options</h3>
    <pre>

คำจำกัดความ
      <div class="row w-100">
        <div class="col-2"><p class="font-16px text-primary">ชื่อ KEY</p></div>
        <div class="col-4"><p class="font-16px text-primary">รายละเอียด</p></div>
        <div class="col-4"><p class="font-16px text-primary">ประเภทตัวแปร / ตัวอย่าง</p></div>
        <div class="col-2"><p class="font-16px text-primary">ค่า Defalut</p></div>

        <div class="col-2">color_template</div>
        <div class="col-4">สีที่ใช้แยกประเภทข้อมูล</div>
        <div class="col-4">Array /
$options = [
  'color_template' => [
    [
      'type' => 'normal',
      'color' => '#3872E1'
    ],
    [
      'type' => 'late_from_estimate',
      'color' => '#FFAD00'
    ],
    [
      'type' => 'late_due_date',
      'color' => '#ED4234'
    ],
    [
      'type' => 'late_all',
      'color' => '#A738E1'
    ],
  ]
];
      </div>
        <div class="col-2">สีฟ้าทุกประเภท</div>
      </div>
    </pre>
    <h4 class="text-underline" id="example_data">*ตัวอย่างข้อมูลจาก API* </h4>
    <a href="#description_key">กลับไปอ่าน คำอธิบาย KEY</a>
    <pre>
      <?php Aww::display($ex_data); ?>
    </pre>

    <h3 class="text-danger text-underline">*ข้อสังเกตุ*</h3>
    <pre>
      <p class="font-22px mb-0">ในกรณีที่ Chart ไม่แสดง</p>
      <p class="mb-0">กด F12 เช็คเมนู Console ดู Error ได้เลย</p>
      <p class="mb-0">จะมี ERROR MESSAGE แสดงบอกในนั้น </p>
    </pre>
  </div>
</div>
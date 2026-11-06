<style>
  .glowing-text {
    font-size: 48px;
    font-weight: bold;
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #e60073, 0 0 40px #e60073, 0 0 50px #e60073, 0 0 60px #e60073, 0 0 70px #e60073;
  }
</style>
<div class="content-header-container">
  <h3 class="header-title text-info">Line Chart</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="i-chart-ex">
        <?php
        // for ($i = 0; $i < 6; $i++) {
        //   $ex_data['line_1'][] = [
        //     'date' => date('Y-m-d', strtotime("+" . $i . " days")),
        //     'value' => strval(rand(1000, 2000)),
        //     'insert_date_time' => Aww::formatDate('', 'd/m/Y H:i:s')
        //   ];
        //   $ex_data['line_2'][] = [
        //     'date' => date('Y-m-d', strtotime("+" . $i . " days")),
        //     'value' => strval(rand(1000, 2000)),
        //     'insert_date_time' => Aww::formatDate('', 'd/m/Y H:i:s')
        //   ];
        // }
        $ex_data = [];
        for ($i = 0; $i <= 20; $i++) {
          $ex_data['line_1'][] = [
            'range_date' => $i . '.00 - ' . ($i + 1) . '.00',
            'value' => rand(0, 100)
          ];
        }
        $options = [
          'data_key' => [
            [
              'name' => 'Line 1',
              'key' => 'line_1'
            ],
            [
              'name' => 'Line 2',
              'key' => 'line_2'
            ],
          ],
          'unit' => 'บาท',
          'labels_is_string' => true
        ];
        ArtChart::Line($ex_data, 'range_date', 'value', $options);
        ?>
      </div>
    </div>
  </div>
  <div class="w-love-content-container">
    <h3 class="text-underline"> คู่มือการใช้งาน</h3>
    <h5 class="text-primary">ข้อมูลเบื้องต้น</h5>
    <ul class="list-group mb-4">
      <li class="list-group-item"><span class="text-success">Structure::loadModules(['artchart'])</span> - ต้องโหลดโมดูล artchart เสมอหากต้องการใช้ฟังชันนี้</li>
      <li class="list-group-item"><span class="text-primary">ArtChart::Line($p1, $p2, $p3, $p3,); มีทั้งหมด 4 พารามิเตอร์ </span> <small>($p = ย่อจากพารามิเตอร์ , ตัวเลขหลัง $p คือตำแหน่งพารามิเตอร์)</small></li>
      <li class="list-group-item">Parameter 1 - ข้อมูลทั้งหมดของ Array ที่จะส่งเข้าไปเพื่อวาด Line Chart</li>
      <li class="list-group-item">Parameter 2 - ข้อมูลวันที่ที่ต้องการเอาไปวาด ควรจะเป็นข้อมูล "วันที่/Date" โดยจะแสดงบน Chart <small>(แสดงวันที่ตรงแกน X และข้อมูลตอน Hover)</small></li>
      <li class="list-group-item">Parameter 3 - ข้อมูลค่าที่เป็นตัวเลขที่ต้องการแสดงผล ควรเป็น "ค่า/Value" เพื่อแสดงบน Chart <small>(แสดงค่าตรงแกน Y ,ข้อมูลตอน Hover และเส้นกราฟ)</small></li>
      <li class="list-group-item">Parameter 4 - Options - ใช้สำหรับการตั้งค่า Legend และ Mapping ข้อมูล <small class="text-danger">(สำคัญมาก!! ถ้าส่วนนี้ไม่ถูก Chart จะไม่แสดงผล)</small></li>
    </ul>
  </div>
  <div class="row mt-15px">
    <div class="code-container">
      <pre>
      <code data-language="php">
/// ตัวอย่าง
$data = [];

/// ข้อมูลชุดที่ 1
$data['ex_array_1'][] = [
  'date' => 'วันที่',
  'value' => 'จำนวน/ค่า',
];

/// ข้อมูลชุดที่ 2
$data['ex_array_2'][] = [
  'date' => 'วันที่',
  'value' => 'จำนวน/ค่า',
];

/// ตั้งค่าเงื่อนไขสำหรับการแสดงผล
$options = [
  'data_key' => [
    [
      'name' => 'Line 1',
      'key' => 'ex_array_1'
    ],
    [
      'name' => 'Line 2',
      'key' => 'ex_array_2'
    ],
  ],
  /// หน่วยที่แสดงผลในกราฟ (Que : แสดงตรงไหนอะ  Ans: ใน Chart ตอน Hover เลย)
  'unit' => 'บาท'
];
ArtChart::Line($data, 'date', 'value', $options);
      </code>
      </pre>
    </div>
    <ul class="list-group mb-4">
      <li class="list-group-item bg-primary text-white">อธิบายเพิ่มเติมจากโค้ดด้านบน</li>
      <li class="list-group-item">$data['ex_array_1'] - ข้อมูลตรงนี้อาจจะมีมากกว่าได้ 2 ตัวได้ แต่เราต้องเลือกส่งตัวที่ต้องการไปใน Parameter 2 และ Parameter 3</li>
      <li class="list-group-item">ตัวแปร <b>'name'</b> ใน array 'data_key' ของ $options จะถูกนำไปเป็น Legend ของ Chart</li>
      <li class="list-group-item">ตัวแปร <b>'key'</b> ใน array 'data_key' ของ $options จะต้องเป็นชื่อเดียวกับ Array key ของ ข้อมูลที่ที่ส่งไปใน Parameter 1
        <p class="text-warning mb-0">(ในตัวอย่างด้านบนจะชื่อ ex_array_1 และ ex_array_2)</p>
      </li>
      <li class="list-group-item bg-danger text-white">ข้อควรระวัง </li>
      <li class="list-group-item">หากข้อมูล $data (พามิเตอร์ที่ 1) และ ใน $options (พารามิเตอร์ที่ 4) ถูกส่งเข้าโดยที่ข้อมูล ('key') ไม่เท่ากัน หรือไม่ตรงกันจะทำให้ chart ไม่ถูกวาด</li>
      <li class="list-group-item">ข้อมูลที่จะส่งมาวาด Chart ใน พารามิเตอร์ที่ 2 จะต้องเป็นข้อมูลวันที่เท่านั้น หากเลือกตัวแปรผิดไม่ว่าจะทั้งจาก Data/ข้อมูล (พามิเตอร์ที่ 1) หรือจาก key,var/ตัวแปร (พามิเตอร์ที่ 2) ก็จะทำให้ Chart ไม่วาดเช่นกัน</li>
    </ul>


    <p class="text-primary">ตัวอย่างข้อมูลจาก API</p>
    <?= Aww::display($ex_data); ?>

    <h5 class="text-primary">ท้ายที่สุด</h5>
    <p class="font-18px text-success">ก็น่าจะครบครอบคลุมสำหรับทุก Chart ที่เราจะใช้ลุยงานกันต่อจากนี้แล้ว (สำหรับตอนนี้)
      ขอให้ Frontend ทุกคนสนุกกับการเขียนโค้ดและใช้งานกันอย่างราบรื่นครับ
      ขอบคุณครับ (ไอ้อาร์ตน่าจะอยากบอกแบบนี้)
    </p>
    <p class="font-16px glowing-text">
      ปล. หากมีข้อเสนอติเตียนสำหรับ Module : Artchart สามารถแจ้งกับน้องอาร์ตได้เลย ไม่ว่าจะทั้งชอบไม่ชอบ
      และสำหรับคู่มือถ้าเขียนผิดพลาดหรือไม่ครอบคลุม หรือเข้าใจยากไปก็สามารถแจ้งได้เลยนะครับ(เอิร์ธ ,2023)
    </p>



  </div>
</div>
<div class="content-header-container">
  <h3 class="header-title text-info">Bar Chart</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="i-chart-ex">
        <?php
        $ex_data = [];
        for ($i = 0; $i < 25; $i++) {
          $ex_data[] = [
            'shop' => 'shop ' . ($i + 1),
            'total_sale' => ($i == 5) ? '0' : strval(rand(100000, 200000)),
            'insert_date_time' => date('Y-m-d H:i:s'),
            'update_date_time' => date('Y-m-d H:i:s'),
          ];
        }
        $options = [
          'layout' => 'vertical',
          'unit' => 'บาท',
          'labels' => [
            'layout' => 'vertical',
          ],
          'bullet' => true
        ];
        ArtChart::Bar($ex_data, 'shop', 'total_sale', $options);
        // ArtChart::Bar();
        ?>
      </div>
    </div>
  </div>

  <div class="w-love-content-container">
    <h3 class="text-underline">คู่มือการใช้งาน</h3>
    <h5 class="text-primary">ข้อมูลเบื้องต้น</h5>
    <ul class="list-group mb-4">
      <li class="list-group-item"><span class="text-success">Structure::loadModules(['artchart'])</span> - ต้องโหลดโมดูล artchart เสมอหากต้องการใช้ฟังชันนี้</li>
      <li class="list-group-item">$options <span class="text-primary">['unit']</span> - หน่วยของค่าที่เราจะเอาไปโชว์ใน Chart สามารถใส่คำที่ต้องการได้เลย</li>
      <li class="list-group-item">$options <span class="text-primary">['layout']</span> - เลือกแกนของ Chart สามารถเลือกได้ว่าจะเอาแนวตั้งหรือแนวนอน vertical/horizontal</li>
    </ul>

    <h5 class="text-primary">Artchart Bar ประกอบด้วย 4 พารามิเตอร์</h5>
    <p class="mb-2">Example : ArtChart::Bar($para1, $para2, $para3, $para4);</p>
    <ul class="list-group mb-4">
      <li class="list-group-item">Parameter 1 - ข้อมูลจาก API (ต้องเป็น Array)</li>
      <li class="list-group-item">Parameter 2 - "ชื่อ" ที่แสดงบน Chart (Key Data จาก API)</li>
      <li class="list-group-item">Parameter 3 - "ค่า" ที่แสดงบน Chart (Key Data จาก API คนละตัวกับชื่อ)</li>
      <li class="list-group-item">Parameter 4 - Option สำหรับปรับแต่งข้อมูลของ Chart (สำหรับ Bar จะปรับได้เพียง หน่วยกับแกน Chart)</li>
    </ul>
    <pre>
    <code data-language="php">
      //เรียกใช้ Module
      Structure::loadModules(['artchart']); 

      //เรียกใช้ Chart

      // กรณีงานตัดแบบที่ยังไม่เชื่อมข้อมูล สามารถนำโค๊ดนี้ไปใช้ได้เลย จะเป็นการสร้างข้อมูลตัวอย่างขึ้นมาแสดง
      ArtChart::Bar(); 

      // กรณีงานตัดแบบที่ยังไม่เชื่อมข้อมูล แต่อยากใส่ options รอเพื่อต่อ API 
      $options = [
        'unit' => 'บาท',
        'layout' => 'vertical', //horizontal = แนวนอน, vertical = แนวตั้ง 
      ];
      ArtChart::Bar(null, '', '', $options);

      // กรณีที่จะเชื่อม API แล้ว
      // parameter 1 = List ข้อมูลจาก API
      // parameter 2 = ชื่อ KEY จาก API ที่จะให้เป็น 'หัวข้อ' ข้อมูล
      // parameter 3 = ชื่อ KEY จาก API ที่จะให้เป็น 'ค่า' ข้อมูล
      // parameter 4 = options สำหรับการ customize
      ArtChart::Bar($ex_data, 'shop', 'total_sale', $options);
    </code>
    </pre>
    <h3 class="text-primary text-underline">Options</h3>
    คำจำกัดความ
    Tooltips = กล่องข้อความจะแสดงตอน Hover
    <pre>
      <div class="row w-100">
        <div class="col-2"><p class="font-16px text-primary">ชื่อ KEY</p></div>
        <div class="col-4"><p class="font-16px text-primary">รายละเอียด</p></div>
        <div class="col-4"><p class="font-16px text-primary">ประเภทตัวแปร / ตัวอย่าง</p></div>
        <div class="col-2"><p class="font-16px text-primary">ค่า Defalut</p></div>

        <div class="col-2">unit</div>
        <div class="col-4">(Tooltips)ใช้แสดงหน่วยของข้อมูล</div>
        <div class="col-4">String / 'unit' => 'บาท'</div>
        <div class="col-2">ค่าว่าง</div>

        <div class="col-2">layout</div>
        <div class="col-4">แนวของ Chart <br>horizontal = แนวนอน, vertical = แนวตั้ง  </div>
        <div class="col-4">String /'layout' => 'vertical'</div>
        <div class="col-2">horizontal</div>
      </div>
    </pre>

    <h3 class="text-danger text-underline">*ตัวอย่างข้อมูลจาก API*</h3>
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
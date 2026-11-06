<div class="content-header-container">
  <h3 class="header-title text-info">Pie Chart</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="i-chart-ex">
        <?php
        $ex_data = [];
        for ($i = 0; $i < 5; $i++) {
          $ex_data[] = [
            'progress' => 'Progress ' . ($i + 1),
            'amount' => strval(rand(1000, 2000)),
            // ($i == 0) ? 'test' : 'progress' => 'Progress ' . ($i + 1),
            // ($i == 0) ? 'test' : 'amount' => strval(rand(1000, 2000)),
            'insert_date_time' => date('Y-m-d H:i:s'),
            'update_date_time' => date('Y-m-d H:i:s'),
          ];
        }
        $options = [
          'unit' => 'บาท',
          'show_percent' => true,
        ];
        // ArtChart::Pie($ex_data, 'progress', 'amount', $options);
        ArtChart::Pie(null, '', '', $options);
        ?>
      </div>
    </div>
  </div>

  <div class="w-love-content-container">
    <h3 class="text-primary text-underline">คู่มือการใช้งาน</h3>
    <pre>
    <code data-language="php">
      //เรียกใช้ Module
      W_Structure::loadModules(['artchart']); 

      //เรียกใช้ Chart

      // กรณีงานตัดแบบที่ยังไม่เชื่อมข้อมูล สามารถนำโค๊ดนี้ไปใช้ได้เลย จะเป็นการสร้างข้อมูลตัวอย่างขึ้นมาแสดง
      ArtChart::Pie(); 

      // กรณีงานตัดแบบที่ยังไม่เชื่อมข้อมูล แต่อยากใส่ options รอเพื่อต่อ API 
      $options = [
        'unit' => 'บาท',
        'show_percent' => true,
      ];
      ArtChart::Pie(null, '', '', $options);

      // กรณีที่จะเชื่อม API แล้ว
      // parameter 1 = List ข้อมูลจา่ก API
      // parameter 2 = ชื่อ KEY จาก API ที่จะให้เป็น 'หัวข้อ' ข้อมูล
      // parameter 3 = ชื่อ KEY จาก API ที่จะให้เป็น 'ค่า' ข้อมูล
      // parameter 4 = options สำหรับการ customize
      ArtChart::Pie($ex_data, 'progress', 'amount', $options);
    </code>
    </pre>
    <h3 class="text-primary text-underline">Options</h3>
    <pre>

คำจำกัดความ
Tooltips = กล่องข้อความจะแสดงตอน Hover

      <div class="row w-100">
        <div class="col-2"><p class="font-16px text-primary">ชื่อ KEY</p></div>
        <div class="col-4"><p class="font-16px text-primary">รายละเอียด</p></div>
        <div class="col-4"><p class="font-16px text-primary">ประเภทตัวแปร / ตัวอย่าง</p></div>
        <div class="col-2"><p class="font-16px text-primary">ค่า Defalut</p></div>

        <div class="col-2">unit</div>
        <div class="col-4">(Tooltips)ใช้แสดงหน่วยของข้อมูล</div>
        <div class="col-4">String / 'unit' => 'บาท'</div>
        <div class="col-2">ค่าว่าง</div>

        <div class="col-2">show_percent</div>
        <div class="col-4">(Tooltips)ใช้แสดง Percent ของข้อมูล</div>
        <div class="col-4">Boolean / 'show_percent' => true</div>
        <div class="col-2">false</div>
      </div>
    </pre>
    <h3 class="text-danger text-underline">*ข้อควรระวัง*</h3>
    <pre>
      <p class="font-22px">หาก Chart ไม่วาด ให้กด F12 เช็ค Console ดู Error ได้เลยครับ จะมี ERROR MESSAGE บอก</p>
    </pre>

    <h3 class="text-danger text-underline">*ตัวอย่างข้อมูลจาก API*</h3>
    <pre>
      <?php W_Main::display($ex_data); ?>
  </pre>
  </div>
</div>
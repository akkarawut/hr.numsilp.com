<?php
$commit_text = [
  [
    'text' => 'ADD',
    'detail' => 'ใช้ในกรณีเพิ่ม Function(สคริปต์ต่างๆ) ,ตัดแบบ(Design + UI) ,Connect API(เชื่อมหลังบ้าน) ,Third Party(เอาปลั๊กอินนอกมาเรียกใช้) ,CSS&SCSS(ใช้ตอนเพิ่มไฟล์ของตัวเอง)'
  ],
  [
    'text' => 'CUT',
    'detail' => 'ใช้เมื่อเรา Commit โค้ดที่เราต้องการจะลบออก,Comment Code ส่วนต่างๆ หรือลบไฟล์นั้นๆไป'
  ],
  [
    'text' => 'FIX',
    'detail' => 'ใช้สำหรับแก้ไขไฟล์ต่างๆ HTML SCSS Php Ajax Script รวมทั้งไฟล์ใน Third Party (ที่ไม่ใช่การเพิ่มไฟล์ใหม่)'
  ],
  [
    'text' => 'START',
    'detail' => 'Case-หน้าบ้าน จะใช้ตั้งแต่ตั้งโปรเจคและเริ่มเอาเฟรมเวิร์คไปติดตั้ง </br>Case-หลังบ้าน จะใช้ตั้งแต่ตั้งโมดูลใหม่ที่เราเริ่มทำ </br> Extra-กรณีพิเศษเวลาเริ่ม Phase หรือต่อ Phase (ถ้ารู้)'
  ],
  [
    'text' => 'STOP',
    'detail' => 'ใช้เมื่อ Commit โค้ดสุดท้ายในโปรเจคนั้นๆตอนจบงานหรือจบ Module </br>ถ้างานนั้นแบ่งเป็น Phase ควรปิด Phase นั้นๆด้วย Commit นี้'
  ],
  [
    'text' => 'REFACTOR',
    'detail' => 'ใช้ Commit Code ในกรณีที่เราทำการ Clean Code (จัดการโค้ด) ,Fixed log(แก้/เก็บ Log) ,Optimize&Performance(ปรับปรุงประสิทธิภาพเว็ปไซต์ ใช้ Lighthouse tool ช่วยเช็ค)'
  ],

]
?>
<div class="content-header-container">
  <h3 class="header-title">กฏการเขียน Commit code สำหรับ Frontend ใช้กันภายใน<p class="font-14px mt-5px">สไตล์การเขียน Commit code</p>
  </h3>
</div>
<div class="content-body-container">
  <div class="body-group-text">
    <h5 class="label-text">รายการ Commit ทั้งหมดที่เราจะใช้กัน</h5>
    <p class="font-14px mb-0">ใช้เท่านี้นะครับ ไม่ต้องแถม (เท่านี้ก็เยอะแล้ว)</p>
  </div>
  <div class="table-responsive">
    <table class="table w-100">
      <thead>
        <tr>
          <th class="text-nowrap text-info">Commit Word</th>
          <th class="text-info">หลักการใช้</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commit_text as $commit_data) { ?>
          <tr>
            <td scope="row" class="text-info"><?= $commit_data['text']; ?></td>
            <td scope="row" class="text-info"><?= $commit_data['detail']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <p>
      <hr>
      <b>
        ตัวอย่างการตั้งชื่อ Commit GIT<br />
      </b>
      <hr>
    <div class="mb-10px">
      โปรเจคที่เป็นหน้าบ้าน ตั้งชื่อ [COMMIT_CODE] Description<br />
      <span class="text-primary">EX [START] Install Framework</span><br />
    </div>
    <div>
      หลังบ้าน Wloves ตั้งชื่อ [MODULE_NAME][COMMIT_CODE] Description<br />
      <span class="text-primary">ตย. [LIGHTHOUSE][START] Setup module lighthouse</span><br />
    </div>
    </p>
  </div>
</div>
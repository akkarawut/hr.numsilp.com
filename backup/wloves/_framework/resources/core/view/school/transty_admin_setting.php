<style>
  .custom-width {
    width: 100px;
  }
</style>
<div class="content-header-container">
  <h3 class="header-title text-info">การตั้งค่าเริ่มต้นก่อนใช้งานเว็ปสองภาษา (TRANSLATY)</h3>
</div>
<div class="content-body-container pb-0" id="main">
  <div class="row">
    <div class="col-12">
      <?php
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
      ?>
      <h5 class="text-info">ตั้งค่าในเมนู <span style="color:#6f42c1;"> Program Setting </span></h5>
      <h6 class="font-16px mt-3 text-info">Path <span style="color:#6f42c1;"> (module_main/core/program_setting.php?c=&setting=language) </span></h6>
      หาไม่เจอคลิกลิงค์นี้จ้า <a href="<?= $actual_link . '/wloves-pro/module_main/core/program_setting.php?c=&setting=language'; ?>" target="_blank">Link</a>
      <hr>
      <p class="text-info mt-3 mb-1">
        การเซ็ตค่าเริ่มต้น (ตั้งค่าภาษาหลัก)
      </p>
      <p class="text-secondary mb-1">
        TRANSLATY คือ Tool ที่ทำให้เว็ปเราสามารถทำเว็ปสองภาษาได้ โดยเราจะต้องเซ็ตค่าเริ่มต้นก่อนใช้งาน โดยการเปิดเมนู <span class="text-warning"> Program Setting </span> แล้วเลือกเมนู <span class="text-warning"> Language </span> จากนั้นก็เลือกภาษาที่ต้องการใช้งาน แล้วก็กดปุ่ม <span class="text-warning"> Make Default </span> แล้วรอรีเฟรชจากนั้นจะมี
      <div class="">
        <div class="badge-primary custom-width d-flex justify-content-center">Default</div>
      </div>
      อยู่หลังภาษาที่เราเลือก
      </p>
      <hr>
      <p class="text-info mt-3">
        การเพิ่มภาษา
      </p>
      <p class=" text-secondary mt-3">
      <p class="mb-1">กดที่ปุ่ม + เพิ่มภาษาเพื่อเพิ่มภาษาอื่นๆ</p>
      <img src="assets/image/btn-add-lang.png" class="my-1">
      <p class="mb-1">Modal เพิ่มภาษาจะเปิดมาให้เลือกภาษาสำหรับแปลเพิ่ม <span class="text-danger font-SemiBold">ตอนนี้หลังบ้านจะมี 3 ภาษา (อังกฤษ, ไทย, จีน)</span></p>
      <img src="assets/image/select_lang.png" class="my-1">
      <p class="mb-1">กด ADD เพื่อยืนยันการเพิ่มภาษา เป็นอันเสร็จสำหรับการเพิ่มภาษา</p>
      </p>
    </div>
    <hr>
    <div class="col-12">
      <p class="mb-1">การเปลี่ยนภาษาหลัก</p>
      <img src="assets/image/make_default.png" class="mb-3">
      <p class="mb-1">กดที่ไอคอน 3 dots (ท้ายสุดของตารางภาษา)</p>
      <ul class="list-group">
        <li class="list-group-item">Make default - เลือกตรงนี้เพื่อให้ภาษานี้เป็นภาษาหลักของ WLOVES</li>
        <li class="list-group-item text-danger">Cancel Language - ยกเลิกภาษานี้ออกจาก WLOVES</li>
      </ul>
      <?php foreach (range(1, 3) as $heart_top) { ?>
        <div class="d-flex justify-content-between text-warning">
          <p>♥</p>
          <p>♥</p>
          <p>♥</p>
        </div>
      <?php } ?>
      <div class="d-flex justify-content-center">
        <h4 class="">ถ้าทำถึงตรงนี้แล้วก็เป็นอันเสร็จขั้นตอนการตั้งค่าหลายภาษาเบื้องต้น</h4>
      </div>
      <?php foreach (range(1, 3) as $heart_btm) { ?>
        <div class="d-flex justify-content-between text-danger">
          <p>♥</p>
          <p>♥</p>
          <p>♥</p>
        </div>
      <?php } ?>
    </div>
    <hr>
  </div>
</div>
<div class="content-body-container" id="remark">
  <div class="body-group-text mb-30px">
    <h5 class="text-info">หมายเหตุเพิ่มเติม <span class=" text-warning"> (31/JAN/2023)</span></h5>
    <p class=" text-secondary mb-0 font-14px">ถ้ามีเพิ่มเติม จะแจ้งหลังจากนี้</p>
  </div>
  <p class="text-info">
    เมนูการเพิ่มคำในแต่ละภาษาจะแสดงให้กด หากอยู่ในเมนูของ Core WLOVES
  </p>
  <img src="assets/image/core-img.png" class="mb-3">
</div>
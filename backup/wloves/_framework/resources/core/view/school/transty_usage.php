<div class="content-header-container">
  <h3 class="header-title text-info">วิธีใช้งาน (TRANSLATY)</h3>
</div>
<div class="content-body-container pb-0" id="main">
  <div class="row">
    <h5 class="text-info">การใช้งาน <span style="color:#6f42c1;"> TRANSLATY </span></h5>
    <div class="col-12">
      <p class="mb-1">กดที่เมนูที่สองจาก Right sidebar menu</p>
      <img src="assets/image/side-menu-lang.png" class="mb-3">
      <p class="mb-1">
        <?php
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        ?>
        จะพามาที่หน้านี้
      </p>
      <img src="assets/image/trans-start.png" class="mb-3 img-responsive">
      <p class="mb-1">จากนั้นเลือกภาษาที่ต้องการเพิ่มละกด Install Module (สีเขียว)</p>
      <img src="assets/image/select-lang-modal.png" class="mb-3">
      <p class="mb-1">หลังจาก Install ภาษาเสร็จจะได้ลิสต์ภาษาเบื้องต้นมาชุดหนึ่ง หน้าตาลิสต์จะขึ้นแบบนี้</p>
      <img src="assets/image/step_done.png" class="mb-3 img-responsive">
      <div class="d-flex align-items-center">
        <button class="form-btn event_btn_addlg btn-warning min-w-180px  h-45px mr-2px" type="button" value="">Add New Language</button>
        - กดปุ่มนี้เพื่อเพิ่มภาษาอื่นๆ
      </div>
      <div class="d-flex align-items-center mt-1">
        <button class="form-btn btn-danger min-w-180px  h-45px mr-2px" type="button" value="">Delete Language</button>
        - กดปุ่มนี้เพื่อลบภาษาที่เพิ่มมา
      </div>
      <div class="d-flex align-items-center mt-1 mb-2">
        <button class="form-btn btn-success min-w-180px  h-45px mr-2px" type="button" value="">Add New Word</button>
        - กดปุ่มนี้เพื่อเพิ่มคำใหม่ (แล้วจะเจอ Modal ตามรูป 1.1 ด้านล่าง)
      </div>
      <div class="row">
        <div class="col-2">
          <label class="container ml-3">Show Framework
            <input type="checkbox" id="check_framework" checked="checked">
            <span class="checkmark"></span>
          </label>
        </div>
        <div class="col">
          <span class="ml-1">
            - เช็คเครื่องหมายถูก เพื่อแสดงคำ Default ของ WLOVES
          </span>
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <img src="assets/image/modal_add_word.png" class="my-1">
      </div>
      <div class="d-flex justify-content-center">
        <p class="mb-1 font-14px">(รูป 1.1 Modal เมื่อกดปุ่ม Add New Word)</p>
      </div>
      <?php /* 
      <ul class="list-group">
        <li class="list-group-item">
          - เลือกตรงนี้เพื่อให้ภาษานี้เป็นภาษาหลักของ WLOVES
        </li>
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
  */ ?>
    </div>
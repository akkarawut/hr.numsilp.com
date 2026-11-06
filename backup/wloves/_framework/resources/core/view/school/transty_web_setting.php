<style>
  .custom-width {
    width: 100px;
  }

  .custom-a-link {
    padding: 15px 25px;
    font-size: 24px;
    text-align: center;
    cursor: pointer;
    outline: none;
    color: #fff;
    background-color: #04AA6D;
    border: none;
    border-radius: 15px;
    box-shadow: 0 9px #999;
    width: 250px;
  }

  .custom-a-link:hover {
    background-color: #3e8e41;
    color: #fff;
  }

  .custom-a-link:active {
    background-color: #3e8e41 !important;
    box-shadow: 0 5px #666;
    color: #fff;
    transform: translateY(4px);
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
        TRANSLATY คือ Tool ที่ทำให้เว็ปเราสามารถทำเว็ปสองภาษาได้ โดยเราจะต้องเซ็ตค่าเริ่มต้นก่อนใช้งาน สำหรับโปรเจคหน้าบ้าน ถ้าเราได้เอาไฟล์ Framework มาลงแล้วสามารถข้ามไปที่เมนูวิธีใช้งานได้เลย
      </p>
      <hr>
      <p class="text-info mb-0">
        <a href="school.php?topic=translaty&sub=translaty_start" class="btn font-17px custom-a-link"><u>วิธีใช้งาน</u></a>
      </p>
    </div>
  </div>
  <hr>
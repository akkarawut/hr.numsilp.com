<?php
$setting_basic = phr_setting_basic::getSettingBasic(API_CODE);
?>
<div class="card rounded-20px">
  <div class="card-body px-0">
    <div class="row py-7px px-20px">
      <div class="col-12">
        <div class="font-15px fw-semibold">ตั้งค่าข้อมูลตั้งต้นที่เกี่ยวข้องกับเงินเดือนและคอมมิชชั่น</div>
      </div>
    </div>
    <hr>
    <div class="row py-5px px-20px align-items-center">
      <div class="col">
        <div class="fw-semibold">หักจ่ายประกันสังคมจากฐานเงินเดือน :
          <?php if ($setting_basic['is_security']) { ?>
            <span class="text-primary"><?= number_format($setting_basic['security_price'], 2) ?> <?= $setting_basic['security_unit'] == 'percent' ? 'เปอร์เซนต์' : 'บาท' ?> / ต่อเดือน </span>
          <?php } else { ?>
            <span class="text-secondary">ไม่หักประกันสังคม</span>
          <?php } ?>
        </div>
      </div>
      <div class="col-auto">
        <div class="icon-action">
          <div class="d-flex">
            <a href="setting_basic.php?page=setting_basic_security_deductions" class="btn btn-c-icon circle btn-c-primary">
              <?= file_get_contents('assets/icons/pencil.svg') ?>
            </a>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="row py-5px px-20px align-items-center">
      <div class="col">
        <div class="fw-semibold">ภาษีหัก ณ ที่จ่ายคอมมิชชั่นตั้งต้น : <span class="text-primary"><?= $setting_basic['tax'] ?>%</span></div>
      </div>
      <div class="col-auto">
        <div class="icon-action">
          <div class="d-flex">
            <a href="setting_basic.php?page=setting_basic_tax" class="btn btn-c-icon circle btn-c-primary">
              <?= file_get_contents('assets/icons/pencil.svg') ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
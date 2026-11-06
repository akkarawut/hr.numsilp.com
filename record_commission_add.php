<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ลงบันทึกยอดขายและค่าคอมมิชชั่น';

$dropdown_emp = phr_helper::dropdownEmployee(API_CODE);
if ($_POST) {
  if (isset($_POST['submit_add_commission'])) {
    unset($_POST['submit_add_commission']);
    $data = [
      'insert_user_id' => $user_info['id'],
      'user_id' => $_POST['user_id'],
      'sale_price' => $_POST['sale_price'],
      'commission_price' => $_POST['commission_price'],
      'remark' => $_POST['remark'],
    ];

    $file = isset($_FILES['file_others']) ? $_FILES['file_others'] : '';
    $file_others = [];
    if ($file) {
      foreach ($file['name'] as $key => $value) {
        $file_others[] = [
          'name' => $value,
          'type' => $file['type'][$key],
          'tmp_name' => $file['tmp_name'][$key],
          'error' => $file['error'][$key],
          'size' => $file['size'][$key],
        ];
      }
    }
    $result = phr_commission::addNewCommission(API_CODE, $data, $file_others);
    if ($result['response_status']) {
      $redirect = 'record_commission_detail.php?id=' . $result['response_data']['insert_id'];
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

function templateInputUpload()
{
  ob_start(); ?>
  <div class="col-12 mb-15px __event_row">
    <div class="d-flex align-items-center">
      <div class="input-file-w-100">
        <?= TiwForm::normal('file', '', ['name' => 'file_others[]'], []) ?>
      </div>
      <div class="min-50px">
        <button type="button" class="btn-icon fill-danger" onclick="deleteRow(this);">
          <?= file_get_contents('assets/icons/remove.svg') ?>
        </button>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}
?>
<!-- MENU::ลงบันทึกยอดขายและค่าคอมมิชชั่น -->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $og_data = [
    'url'         => '',
    'title'       => 'HR',
    'image'       => '',
    'description' => '',
  ];
  W_Structure::loadMetaForWebsite('', $og_data);
  W_Main::loadAsset('assets/css/main.css');
  ?>
  <?php include_once 'layout/favicon.php' ?>
</head>

<body>
  <?php include_once 'layout/navbar.php' ?>
  <div id="hr-body">
    <?php include_once 'layout/sidebar.php' ?>
    <div id="hr-content" class="bg-white">
      <div class="container-fluid mt-5">
        <div class="max-w-500px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="record_commission.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">ลงบันทึกค่าคอมมิชชั่น</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="max-w-500px mx-auto">
            <p class="sub-title my-25px">
              หลังจากบันทึกแล้ว รายการค่าคอมมิชชั่นจะอยู่ในสถานะ รอชำระ ยอดจะถูก
              นำไปรวมกับรายได้ที่พนักงานจะได้รับ
            </p>

            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียดบันทึก</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">พนักงาน</div>
                        <select name="user_id" class="input" required>
                          <option value="" selected disabled>เลือก</option>
                          <?php foreach ($dropdown_emp as $val) { ?>
                            <option value="<?= $val['id'] ?>"><?= $val['full_name'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ยอดขาย</div>
                        <input type="number" step="0.01" class="input" name="sale_price" required>
                        <div class="input-title-abs">บาท</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">คอมมิชชั่นที่ควรได้รับ (บาท)</div>
                        <input type="number" step="0.01" class="input" name="commission_price" required>
                        <div class="input-title-abs">บาท</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title ">หมายเหตุ</div>
                        <textarea name="remark" class="input" value=""></textarea>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">หลักฐานประกอบ</div>
                    <div class="font-15px">
                      ไฟล์ประกอบการพิจารณาความถูกต้อง โปรดตั้งชื่อไฟล์ให้เรียบร้อย
                      ก่อนการอัปโหลด
                    </div>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="d-flex align-items-center">
                      <div class="input-file-w-100">
                        <?= TiwForm::normal('file', '', ['name' => 'file_others[]'], []) ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="scope_row_file" class="row"></div>

                <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_file');">+ เพิ่มไฟล์</button>


                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="record_commission.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_commission" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
                        <div class="fw-semibold">บันทึก</div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>

  </div>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>

  <script>
    function addRow(t) {
      let scope = document.getElementById(t);
      w('#' + t).append(`<?= templateInputUpload() ?>`);
    }

    function deleteRow(t) {
      t.closest('.__event_row').remove();
    }
  </script>
</body>

</html>
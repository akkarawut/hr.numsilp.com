<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลพนักงาน';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$mode = isset($_GET['mode']) && $_GET['mode'] ? $_GET['mode'] : 'employee';
$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 'emp_detail';
if ($_POST) {
  if (isset($_POST['submit_cost_add'])) {
    unset($_POST['submit_cost_add']);
    $_POST['user_id'] = $id;
    $result  = phr_employee::addNewEmployeeCost(API_CODE, $_POST);
    if ($result['response_status']) {
      $redirect = 'employee_detail.php?id=' . $id . '&mode=' . $mode . '&page=' . $page;
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

function templatePayment()
{
  ob_start(); ?>

  <div class="col-lg-6 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">จำนวนงวดชำระ</div>
        <input type="number" class="input" name="period" required>
        <div class="input-title-abs">งวด</div>
      </div>
    </div>
  </div>
  <div class="col-lg-6 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">ยอดชำระต่องวด</div>
        <input type="number" step="0.01" class="input" name="price_per_period" required>
        <div class="input-title-abs">บาท</div>
      </div>
    </div>
  </div>

<?php
  return ob_get_clean();
}


?>
<!-- MENU::ออกใบเตือน -->
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
        <div class="max-w-750px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="employee_detail.php?id=<?= $id ?>&mode=<?= $mode ?>&page=<?= $page ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">เพิ่มค่าใช้จ่าย</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">
              ค่าใช้จ่ายนี้จะถูกคำนวณทุกครั้งที่คำนวณเงินเดือน จนกว่าจะครบงวด หรือ ถูกลบ
            </p>

            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียดค่าใช้จ่าย</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ชื่อค่าใช้จ่าย</div>
                        <input type="text" class="input" name="name" required value="">
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ยอดเต็ม (บาท)</div>
                        <input type="number" step="0.01" class="input" name="total_price" required>
                        <div class="input-title-abs">บาท</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">รูปแบบการชำระ</div>
                        <select name="type_pay" class="input" required onchange="changeTypePayment(this);">
                          <option value="one" selected>ชำระครั้งเดียว</option>
                          <option value="multiple">ชำระหลายงวด</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="scope_template_payment row"></div>
                </div>

                <div class="row">
                  <div class="col-lg-12 text-danger mb-15px">*โปรดตรวจสอบให้แน่ใจว่าสอดคล้องกับยอดเต็มและจำนวนงวดชำระหรือไม่</div>
                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">หมายเหตุ</div>
                        <textarea name="remark" class="input" value=""></textarea>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="employee_detail.php?id=<?= $id ?>'&mode=<?= $mode ?>&page=<?= $page ?>" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_cost_add" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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
    function changeTypePayment(t) {
      if (t.value == 'one') {
        w('.scope_template_payment').html('');
      } else {
        w('.scope_template_payment').html(`<?= templatePayment(); ?>`);
      }
    }
  </script>
</body>

</html>
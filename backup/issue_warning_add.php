<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ออกใบเตือน';
if ($_POST) {
  if (isset($_POST['submit_issue_warning'])) {
    unset($_POST['submit_issue_warning']);
    $result  = phr_document_warning::addNewDocumentWarning(API_CODE, $_POST);
    if ($result['response_status']) {
      $redirect = 'issue_warning_detail.php?id=' . $result['response_data']['insert_id'];
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$dropdown_emp = phr_helper::dropdownEmployee(API_CODE);

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
                <a href="issue_warning.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">ออกใบเตือน</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">
              กรอกรายละเอียดใบเตือน
            </p>

            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียดใบเตือน</div>
                  </div>
                </div>
                <div class="row">


                  <div class="col-12 mb-15px">
                    <span class="fw-semibold">เลขที่เอกสาร : </span>
                    <span class="text-primary fw-semibold">หมายเลขเอกสารจะถูกสร้างอัตโนมัติหลังจากบันทึก</span>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">พนักงาน</div>
                        <select name="user_id" class="input" required onchange="changeEmployeeWarning(this);">
                          <option value="" selected disabled>เลือก</option>
                          <?php foreach ($dropdown_emp as $val) { ?>
                            <option value="<?= $val['id'] ?>"><?= $val['full_name'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="mb-15px">
                    <span class="text-primary fw-semibold">ใบเตือนครั้งที่ : </span>
                    <span class="count_warning">โปรดเลือกพนักงานก่อน</span>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">สาเหตุ</div>
                        <textarea name="detail" class="input" value="" placeholder="กรอก" required></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title ">รายละเอียดเพิ่มเติม</div>
                        <textarea name="description" class="input" value="" placeholder="กรอก"></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="issue_warning.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_issue_warning" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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
    function changeEmployeeWarning(t) {
      let v = t.value;
      let url = `ajax/ajax_change_employee_warning.php?id=${v}`;
      wx.get(url, function(res) {
        let count_warning = document.querySelector('.count_warning');
        count_warning.innerHTML = res;
      });
    }
  </script>
</body>

</html>
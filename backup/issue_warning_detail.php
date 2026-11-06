<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once  'assets/plugin/exportpdf/vendor/autoload.php';
$name_page = 'ออกใบเตือน';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$noti = isset($_GET['noti']) && $_GET['noti'] ? $_GET['noti'] : 0;
$issue = phr_document_warning::getDocumentWarningByID(API_CODE, $id);
if ($_POST) {
  if (isset($_POST['submit_delete_issue'])) {
    $result = phr_document_warning::deleteDocumentWarning(API_CODE, $issue['id']);
    if ($result['response_status']) {
      $redirect = 'issue_warning.php';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

?>
<!-- MENU::ออกใบเตื่อน -->
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
              <?php if (!$noti) { ?>
                <div class="icon-back mr-10px">
                  <a href="issue_warning.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                </div>
              <?php } ?>
              <h1 class="title-header m-0">รายละเอียดใบเตือน</h1>
            </div>
            <div class="icon-action">
              <div class="d-flex">
                <button type="button" onclick="printPDF();" class="btn btn-c-icon circle btn-c-primary">
                  <?= file_get_contents('assets/icons/printer.svg') ?>
                </button>
                <?php if (!$noti) { ?>
                  <button type="button" class="btn btn-c-icon circle btn-c-danger" <?php Tiwdal::register('delete_deduct', $issue); ?>>
                    <?= file_get_contents('assets/icons/delete.svg') ?>
                  </button>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <div class="container">
        <div class="max-w-750px mx-auto">
          <p class="sub-title my-25px">
            รายละเอียดใบเตือนของพนักงาน
          </p>

          <div id="print_pdf" class="card rounded-20px ">
            <div class="card-body py-25px px-30px">
              <div class="row py-7px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียด</div>
                </div>
              </div>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">เลขที่เอกสาร</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $issue['doc_no'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">พนักงาน</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $issue['user_fullname'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ใบเตือนครั้งที่</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $issue['number'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สาเหตุ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $issue['detail'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">รายละเอียดเพิ่มเติม</div>
                </div>
                <div class="col-md-7">
                  <?= $issue['description'] ? $issue['description'] : '-' ?>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันเวลาที่ทำรายการ</div>
                </div>
                <div class="col-md-7">
                  <?= W_Main::formatDate($issue['update_date_time'], 'd/m/Y, H:i') ?>
                  <div class="text-secondary font-14px">
                    โดย <?= $issue['insert_user_fullname'] ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <?php Tiwdal::startModal('delete_deduct', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบใบเตือน', '']) ?></div>
        <div class="text-center">
          <div>
            คุณแน่ใจหรือไม่ที่จะลบใบเตือนนี้ออกจากระบบ <br>
            ข้อมูลนี้ไม่มีผลต่อข้อมูลอื่น ๆ ของระบบ
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
        TiwForm::normal('btn', '', ['name' => 'submit_delete_issue', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal(); ?>
    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    ?>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
    <script>
      function printPDF() {
        html2canvas(document.querySelector("#print_pdf")).then(canvas => {
          var imgData = canvas.toDataURL('image/png');
          var pdf = new jsPDF();
          pdf.setProperties({
            title: 'เอกสารใบเตือน <?= $issue['doc_no'] ?>'
          });
          pdf.addImage(imgData, 'PNG', 6, 6);
          pdf.output('dataurlnewwindow');
        });
      }
    </script>
</body>


</html>
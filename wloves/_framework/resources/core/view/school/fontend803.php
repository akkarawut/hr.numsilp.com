<?php
if ($_POST) {
  if (isset($_POST['text_submit'])) {
    $result = Excel::importMappingExcel($_FILES['file_1'], $_FILES['file_2'], $_FILES['file_3'], $_FILES['file_4']);
    echo json_encode($result);
  }
}

?>

<div class="content-body-container p-15">
  <form method="post" enctype="multipart/form-data">
    <div class="row mb-15px">
      <?php
      TiwForm::normal('file', '', ['name' => 'file_1'], []);
      ?>
    </div>
    <div class="row mb-15px">
      <?php
      TiwForm::normal('file', '', ['name' => 'file_2'], []);
      ?>
    </div>
    <div class="row mb-15px">
      <?php
      TiwForm::normal('file', '', ['name' => 'file_3'], []);
      ?>
    </div>
    <div class="row mb-15px">
      <?php
      TiwForm::normal('file', '', ['name' => 'file_4'], []);
      ?>
    </div>
    <button type="submit" name="text_submit" class="btn btn-primary mt-15px">Submit</button>
  </form>
</div>
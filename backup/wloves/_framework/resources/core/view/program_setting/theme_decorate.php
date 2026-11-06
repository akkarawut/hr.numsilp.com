<?php
if ($_POST) {
  if (isset($_POST['submit_theme_image'])) {
    unset($_POST['submit_theme_image']);
    $result = File::add('logo_image', $_FILES['logo_image']);
    $result = File::add('signin_image', $_FILES['signin_image']);
    $result = File::add('favicon', $_FILES['favicon']);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}
?>
<form method="post" enctype="multipart/form-data" id="form_theme_program">
  <input type="hidden" name="submit_theme_image">
  <div class="row">
    <div class="col-12 px-0">
      <div class="title-detail px-15px">
        <h3 class="text-uppercase">Program THEME</h3>
        <p class="font-14px">Setting your program theme.</p>
      </div>
      <hr class="">
    </div>
    <div class="col-12 font-14px pb-20px">
      Decorate
    </div>
  </div>
  <div class="row form-group ">
    <div class="col-sm-2 mb-0">
      <label class="text-secondary">Program Logo</label>
    </div>
    <div class="col-sm-10 font-18px font-Medium text_progress">
      <div class="d-md-flex">
        <?php
        $options = [
          'width' => '400px',
          'height' => '80px',
          'bg-img' => '../../_framework/modules/tiwform/img/bg1.png',
          'is_btn' => 1,
          'is_view' => 1,
          'is_delete' => 0,
        ];
        TiwForm::normal('upload-img', $list_info['logo_image'], ['name' => 'logo_image', 'class' => 'change_theme_submit program-logo'], $options);
        ?>
      </div>
      <p class="mt-10px mb-5px font-14px">Recommend Logo Size </p>
      <p class="mb-5px font-14px font-Medium">
        File .jpg or .png size 200 x 40 to 400 x 80 px | This logo will be show when Responsive screen, Open Menu and Log In page
      </p>
    </div>
  </div>
  <div class="row form-group ">
    <div class="col-sm-2 mb-0">
      <label class="text-secondary">Favicon</label>
    </div>
    <div class="col-sm-10 font-18px font-Medium text_progress">
      <div class="d-md-flex">
        <?php
        $options = [
          'width' => '50px',
          'height' => '50px',
          'bg-img' => 'structure/image/placeholder/favicon.svg',
          'is_btn' => 1,
          'is_view' => 1,
          'is_delete' => 0,
        ];
        TiwForm::normal('upload-img', $list_info['favicon'], ['name' => 'favicon', 'class' => 'change_theme_submit program-logo'], $options);
        ?>
      </div>
      <p class="mt-10px mb-5px font-14px">Recommend Favicon Size</p>
      <p class="mb-5px font-14px font-Medium">
        File .jpg or .png size 50 x 50
      </p>
    </div>
  </div>
  <div class="row form-group">
    <div class="col-sm-2 mb-0">
      <label class="text-secondary">Login Background</label>
    </div>
    <div class="col-sm-10 font-18px font-Medium text_progress">
      <div class="d-md-flex">
        <?php
        $options = [
          'width' => '100%',
          'height' => '55.53%',
          'bg-img' => 'structure/image/placeholder/upload.svg',
          'is_btn' => 1,
          'is_view' => 1,
          'is_delete' => 0,
        ];
        TiwForm::normal('upload-img', $list_info['signin_image'], ['name' => 'signin_image', 'class' => ' mt-3 mt-md-0 program-logo change_theme_submit'], $options);
        ?>
      </div>
      <p class="mt-10px mb-5px font-14px">Recommend Background Image Size</p>
      <p class="mb-5px font-14px font-Medium">
        File .jpg or .png size 1,280 x 800 to 1,920 x 1,080 px
      </p>
    </div>
  </div>
</form>

<script>
  $(function() {
    //highlight and customize side nav menu color
    $('.change_theme_submit').change(function(e) {
      $('#form_theme_program').submit();
    });
  });
</script>
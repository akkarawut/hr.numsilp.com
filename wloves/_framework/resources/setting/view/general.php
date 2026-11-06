<?php
$program = W_WLoves::$initial_data['program'];
if ($_POST) {
  if (isset($_POST['submit_info_program'])) {
    unset($_POST['submit_info_program']);
    $result = WLoves::setProgramSetup($_POST);
    $redirect = '?page=' . $page;
  }

  W_Main::responseAPI();
}
?>
<div class="col-xl-6 col-lg-12">
  <div class="w-card h-md-100">
    <form method="post" enctype="multipart/form-data" class="form-loading">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <h3 class="title">Program general information</h3>
        </div>
        <div class="btn-edit-container">
          <?php if ($status != 'edit') { ?>
            <a href="?page=<?= $page ?>&status=edit" class="btn-edit edit"> <?= Ty::get('edit_data') ?></a>
          <?php } else { ?>
            <a href="?page=<?= $page ?>" class="btn-edit cancel"> <?= Ty::get('cancel') ?></a>
            <button type="submit" class="btn-edit save" name="submit_info_program"> <?= Ty::get('save', [], ["case" => "ucfirst"]) ?></button>
          <?php } ?>
        </div>
      </div>
      <div class="card-scroll p-20px">
        <p class=" font-Bold"> <?= Ty::get('general_info') ?></p>
        <div class="input-group ">
          <div class="label">
            <span> <?= Ty::get('program_name') ?></span>
          </div>
          <div class="input">
            <?php if ($status == 'edit') { ?>
              <?php TiwForm::normal('text', $program['program_name'], ['name' => 'program_name', 'placeholder' => Ty::get('enter')]); ?>
            <?php } else { ?>
              <span class=" font-18px font-SemiBold text-primary"><?= $program['program_name'] ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span> Explanation</span>
          </div>
          <div class="input">
            <?php if ($status == 'edit') { ?>
              <?php TiwForm::normal('textarea', $program['description'], ['name' => 'description', 'placeholder' => Ty::get('enter'), 'class' => 'min-h-60px']); ?>
            <?php } else { ?>
              <span class=" font-Medium"><?= $program['description'] ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span><?= Ty::get('program_owner') ?> </span>
          </div>
          <div class="input">
            <?php if ($status == 'edit') { ?>
              <?php TiwForm::normal('text', $program['customer'], ['name' => 'customer', 'placeholder' => Ty::get('enter')]); ?>
            <?php } else { ?>
              <span class=" font-Medium"><?= $program['customer'] ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span><?= Ty::get('program_start') ?> </span>
          </div>
          <div class="input">
            <?php if ($status == 'edit') { ?>
              <?php TiwForm::normal('date', $program['start_date'], ['name' => 'start_date', 'placeholder' => Ty::get('enter')]); ?>
            <?php } else { ?>
              <span class=" font-Medium"><?= $program['start_date'] ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span> <?= Ty::get('program_timeout') ?> </span>
          </div>
          <div class="input flex-column align-items-start">
            <?php if ($status == 'edit') { ?>
              <div class=" my-10px">
                <?= TiwForm::normal('radio', 0, ['name' => 'is_end_time', 'checked' => 'true', 'onchange' => "changeInputRequired(event,'.scope_input')"], ['style' => '2', 'label' => 'None']); ?>
              </div>
              <div>
                <?= TiwForm::normal('radio', 1, ['name' => 'is_end_time', 'onchange' => "changeInputRequired(event,'.scope_input')"], ['style' => '2', 'label' => 'Configure time out date']); ?>
              </div>
              <div class="mb-10px w-100 scope_input" style="display:none">
                <?php TiwForm::normal('time', $program['end_time'], ['name' => 'end_time', 'placeholder' => Ty::get('enter')]); ?>
              </div>
            <?php } else { ?>
              <span class="font-Medium"><?= true ?  'None' : 'Configure time out date' ?></span>
              <p class="mb-0"><?= true ?  '' : $program['end_time'] ?></p>
            <?php } ?>
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span>Program age </span>
          </div>
          <div class="input">
            1 Day(s)
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span><?= Ty::get('default_curren') ?> </span>
          </div>
          <div class="input">
            <?php if ($status == 'edit') { ?>
              <?php
              $options = [
                'is_search' => true, //หากต้องการใช้ select แบบค้นหาได้ให้ใส่มา
                'list' => [
                  [
                    'value' => '',
                    'name' => 'please select',
                    'disabled' => true
                  ],
                  [
                    'value' => 'thb',
                    'name' => 'Thai Baht'
                  ],
                  [
                    'value' => 'dhk',
                    'name' => 'Dollar hk'
                  ],
                ]
              ];
              TiwForm::normal('select', $program['default_currency'], ['name' => 'default_currency', 'class' => 'max-w-300px'], $options);
              ?>
            <?php } else { ?>
              <span class=" font-Medium"><?= $program['default_currency'] ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="input-group ">
          <div class="label">
            <span><?= Ty::get('service_email') ?> </span>
          </div>
          <div class="input">
            <?php if ($status == 'edit') { ?>
              <?php TiwForm::normal('email', $program['support_email'], ['name' => 'support_email', 'placeholder' => Ty::get('enter')]); ?>
            <?php } else { ?>
              <span class=" font-Medium"><?= $program['support_email'] ?></span>
            <?php } ?>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  function changeInputRequired(e, class_name) {
    var is_check = e.target.value;
    var input = document.querySelector(class_name).querySelector('input');
    console.log(is_check);
    console.log(input);
    if (is_check == 1) {
      input.required = true;
      document.querySelector(class_name).style.display = 'block';
    } else {
      input.required = false;
      document.querySelector(class_name).style.display = 'none';
    }
  }
</script>
<?php
function templateInputDate($date = '') {
  ob_start(); ?>
  <div class="hr-form-control">
    <div class="input-group">
      <div class="input-title">วันที่สิ้นสุดทดลองงาน</div>
      <input type="date" class="input" name="working_end_date" required value="<?= $date ? $date : '' ?>">
    </div>
  </div>
<?php
  return ob_get_clean();
}

function templateCalPrice($setting_basic) {
  ob_start(); ?>
  <div class="col-lg-6 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">ประกันสังคม</div>
        <select name="is_security" class="input event_change_secure" required>
          <option value="" selected disabled>เลือก</option>
          <option value="1" <?= $setting_basic['is_security'] ? 'selected' : ''  ?>>หักประกันสังคม</option>
          <option value="0" <?= !$setting_basic['is_security'] ? 'selected' : ''  ?>>ไม่หักประกันสังคม</option>
        </select>
      </div>
    </div>
  </div>
  <div class="col-lg-3 event_display_box_secure mb-15px <?= !$setting_basic['is_security'] ? 'd-none' : '' ?>">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">ยอดหัก</div>
        <input type="number" min="0" max="9999999" step="0.01" class="input" name="security_price" value="<?= $setting_basic['security_price'] ?>" required>
      </div>
    </div>
  </div>
  <div class="col-lg-3 event_display_box_secure mb-15px <?= !$setting_basic['is_security'] ? 'd-none' : '' ?>">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">หน่วย</div>
        <select name="security_unit" class="input" required>
          <option value="" selected disabled>เลือก</option>
          <option value="bath" <?= $setting_basic['security_unit'] == 'bath' ? 'selected' : ''  ?>>บาท</option>
          <option value="percent" <?= $setting_basic['security_unit'] == 'percent' ? 'selected' : ''  ?>>เปอร์เซ็นต์</option>
        </select>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}

function templateSalary($type_work) {
  ob_start(); ?>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required"><?= $type_work == 'month' ? 'เงินเดือน' : 'ค่าจ้างรายวัน' ?> (บาท)</div>
        <input type="number" class="input" name="salary" value="" required>
      </div>
    </div>
  </div>

  <?php if ($type_work == 'month') { ?>
    <div class="col-6 mb-15px">
      <div class="d-flex align-items-center">
        <div class="pt-15px">คำนวณเงินเดือนทุกวันที่</div>
        <div class="hr-form-control max-w-100px mx-2">
          <div class="input-group">
            <div class="input-title required">วันที่</div>
            <input type="number" step="1" min="1" max="31" class="input" name="day_cal_salary" value="" required>
          </div>
        </div>
        <div class="pt-15px">ของเดือน</div>
      </div>
    </div>

    <div class="col-6 mb-15px">
      <div class="d-flex align-items-center">
        <div class="pt-15px">และวันที่</div>
        <div class="hr-form-control max-w-100px mx-2">
          <div class="input-group">
            <div class="input-title required">วันที่</div>
            <input type="number" step="1" min="1" max="31" class="input" name="day_cal_salary2" value="" required>
          </div>
        </div>
        <div class="pt-15px">ของเดือน</div>
      </div>
    </div>
  <?php } else { ?>
    <div class="col-12 mb-15px">
      <span class="text-danger">** คำนวณค่าจ้างรายวันหลังจากเสร็จสิ้นเวลาทำงานของวัน</span>
    </div>
  <?php } ?>
<?php
  return ob_get_clean();
}


function templateInputUpload() {
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

<script>
  function addRow(t, type) {
    let scope = document.getElementById(t);
    let html = ``;
    if (type == 'email') {
      html =
        `
    <div class="col-12 mb-15px __event_row">
      <div class="hr-form-control">
        <div class="input-group">
          <input type="email" class="input" name="email[]" required value="">
        </div>
        <div class="input-delete">
          <button type="button" class="btn-delete-text" onclick="deleteRow(this);">
            <?= file_get_contents('assets/icons/remove.svg') ?>
          </button>
        </div>
      </div>
    </div>
    `;
      scope.insertAdjacentHTML('beforeend', html);
    } else if (type == 'tel') {
      html =
        `
    <div class="col-12 mb-15px __event_row">
      <div class="hr-form-control">
        <div class="input-group">
          <input type="number" class="input" name="tel[]" required value="">
        </div>
        <div class="input-delete">
          <button type="button" class="btn-delete-text" onclick="deleteRow(this);">
            <?= file_get_contents('assets/icons/remove.svg') ?>
          </button>
        </div>
      </div>
    </div>
    `;
      scope.insertAdjacentHTML('beforeend', html);
    } else if (type == 'verify') {
      w('#' + t).append(`<?= templateInputUpload() ?>`);
    }
  }

  function deleteRow(t) {
    t.closest('.__event_row').remove();
  }

  w(document).on('change', '.event_change_secure', function() {
    let v = w(this).val();
    if (v == 1) {
      w('.event_display_box_secure').removeClass('d-none');
    } else {
      w('.event_display_box_secure').addClass('d-none');
    }

  });

  w(document).on('change', '.event_change_status_emp', function() {
    let v = w(this).val();
    if (v == 'trial_work') {
      w('#scope_row_date_end').html(`<?= templateInputDate() ?>`);
      w('#scope_salary').html(`<?= templateSalary('month') ?>`);
      w('#scope_price_box').html(``);
      w('.scope_chage_col').addClass('col-6');
      w('.scope_chage_col').removeClass('col-12');
    } else if (v == 'month') {
      w('#scope_price_box').html(`<?= templateCalPrice($setting_basic) ?>`);
      w('#scope_salary').html(`<?= templateSalary('month') ?>`);
      w('.scope_chage_col').addClass('col-12');
      w('.scope_chage_col').removeClass('col-6');
      w('#scope_row_date_end').html('');
    } else {
      w('#scope_row_date_end').html('');
      w('#scope_salary').html(`<?= templateSalary('day') ?>`);
      w('#scope_price_box').html('');
      w('.scope_chage_col').addClass('col-12');
      w('.scope_chage_col').removeClass('col-6');
    }
  });

  var code_file = [];

  function deleteFile(t, input_arr) {
    let val = t.getAttribute('data-file');
    let input_code_file = document.querySelector('.event_delete_file');
    code_file.push(val);
    input_code_file.value = code_file;
    t.closest('.__event_row').remove();
  }

  $('.delete_normal_file_event').on('click', function() {
    let scope = $(this).closest('.scope_event_file');
    let val = scope.attr('data-file');
    code_file.push(val);
    $('.event_delete_file').val(code_file);
  });
</script>
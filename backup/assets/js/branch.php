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
          <input type="text" class="input" name="email[]" required value="">
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
          <input type="text" class="input" name="tel[]" required value="">
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
    }
  }

  function deleteRow(t) {
    t.closest('.__event_row').remove();
  }
</script>
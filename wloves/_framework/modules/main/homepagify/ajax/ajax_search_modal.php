<?php
$_WLOVES['no_check_permission'] = 1;
require_once  '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../../../../');
?>
<form method="post" class="homepage-form" data-homepagify="<?= $_POST['this_table'] ?>" data-send_get="0">
  <div class="modal-body">
    <div class="form-row align-items-center">
      <?php
      foreach ($_POST['filter_list'] as $key =>  $input) {
        if ($input['type'] == 'number_between') {
      ?>
          <div class="col-md-4">
            <?= $input['title'] ?>
          </div>
          <div class="col-md-8">
            <?php if (is_array($input['name'])) { ?>
              <div class="form-row d-flex align-items-center">
                <div class="col">
                  <?= TiwForm::normal('number', '', ['name' => $input['name'][0], 'class' => 'filter-param', 'placeholder' => '']); ?>
                </div>
                <div class="col-auto">-</div>
                <div class="col">
                  <?= TiwForm::normal('number', '', ['name' => $input['name'][1], 'class' => 'filter-param', 'placeholder' => '']); ?>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } else if ($input['type'] == 'select') { ?>
          <div class="col-md-4">
            <?= $input['title'] ?>
          </div>
          <div class="col-md-8">
            <select class="form-select filter-param" name="<?= $input['name'] ?>">
              <option value="all">All</option>
              <?php foreach ($input['option'] as $option) {
                echo '<option value="' . $option['value'] . '">' . (isset($option['text']) ? $option['text'] : $option['value']) . '</option>';
              } ?>
            </select>
          </div>
        <?php } else { ?>
          <div class="col-md-4">
            <?= $input['title'] ?>
          </div>
          <div class="col-md-8">
            <?= TiwForm::normal($input['type'], '', ['name' => $input['name'], 'class' => 'filter-param', 'placeholder' => 'Enter']); ?>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
  <div class="modal-footer d-flex justify-content-between">
    <button type="button" class="btn btn-filter-clear">Clear all</button>
    <button type="submit" class="btn btn-filter-search mr-5px">Search</button>
  </div>
</form>
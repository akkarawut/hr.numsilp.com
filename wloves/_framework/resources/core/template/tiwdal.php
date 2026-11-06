<?php
$_PAGE['permission'] = ['core', 'core_template', 'tiwdal'];
require_once '../../_framework/import.php';
W_Structure::loadModules(['tiwdal']);

?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php include_once 'structure/layout/header-default.php'; ?>

  <div class="row">
    <div class="col-12">
      <div class="topic-page">
        <div class="topic-page-name">
          <h3>Modal </h3>
          <span>Ex. Modal</span>
        </div>
      </div>

      <div class="w-loves-card">
        <div class="w-loves-card-header">
          <h3>Edit Modal</h3>
        </div>
        <div>
          <?php
          $edit_data = [
            'id'   => 1,
            'name' => 'Edit Modal'
          ];
          ?>
          <button class="btn btn-secondary" <?php Tiwdal::register('edit_modal', $edit_data); ?>>Click for open edit modal.</button>
          <hr>

          <div class="w-loves-card-header">
            <h3>Ajax Modal</h3>
          </div>
          <?php
          $options = [
            'data-url' => 'view/tiwdal/ajax.tiwdal.php'
          ];
          ?>
          <button class="btn btn-secondary" <?php Tiwdal::register('ajax_modal', $edit_data, $options); ?>>Click for open ajax modal.</button>

        </div>
      </div>
    </div>
  </div>

  <!------------Edit Modal-------------->
  <?php Tiwdal::startModal('edit_modal'); ?>
  <div class="modal-header">
    <h5 class="modal-title">Ex. Edit Modal</h5>
    <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <label>ID :</label>
    <input type="text" class="form-control" name="id">
    <label>Name :</label>
    <input type="text" class="form-control" name="name">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-success">Save</button>
  </div>
  <?php Tiwdal::endModal() ?>

  <!------------Ajax Modal-------------->
  <?php Tiwdal::ajaxModal('ajax_modal'); ?>


  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
  Launch
</button>
<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_program_setting_api_key'];
require_once '../../../_framework/import.php';


if ($_POST) {
  $redirect = '';

  if (isset($_POST['submit_add_api_key'])) {
    $api_result = API::insert($_POST['description']);
  } else if (isset($_POST['submit_edit_api_key'])) {
    $api_result = API::update($_POST['id'], $_POST['description']);
  } else if (isset($_POST['submit_delete_api_key'])) {
    $api_result = API::delete($_POST['id']);
  }

  W_Main::notification(isset($api_result) ? 'success' : 'error', isset($api_result) ? 'success' : 'error');
  W_Main::redirect($redirect);
}


$api_keys = API::select();
// W_Main::display($api_keys);
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta(); ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>

  <div class="p-20px">
    <div class="w-love-table-header-container mb-10px">
      <div class="w-love-table-header-wrap">
        <div class="table-header-detail-group d-block">
          <h3 class="font-16px mb-0">Manage API Key</h3>
          <span class="font-14px">Manage API Key in this Program</span>
        </div>
        <div class="table-header-btn-group">
          <button type="button" class="btn btn-table-custom btn-secondary" <?= Tiwdal::register('add-api-key-modal', []); ?>>Add API Key</button>
        </div>
      </div>
    </div>
    <div class="w-data-table-container">
      <div id="api_key_list" class="container-pagination" <?= Homepagify::createHomepagify('api_key_list', '?c=', '', '') ?>>
        <div class="table-responsive">
          <table class="table table-sort">
            <thead>
              <tr>
                <th nowrap>API Key</th>
                <th nowrap>Description</th>
                <th nowrap></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="add-api-key-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
      <form method="post" class="aww-regex-form" novalidate>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">ADD API KEY</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 form-group">
                <label>Description</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Enter Description..."></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="submit_add_api_key">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php Tiwdal::startModal('edit-api-key-modal'); ?>
  <form method="post">
    <div class="modal-header">
      <h5 class="modal-title">Edit API KEY</h5>
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-12 form-group">
          <label>Description</label>
          <textarea name="{description}" rows="3" class="form-control" placeholder="Enter Description..."></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <input type="hidden" name="submit_edit_api_key">
      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-primary">Edit</button>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('delete-key-modal'); ?>
  <form method="post">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    <div class="modal-body">
      <h3 class="text-center font-16px font-SemiBold text-uppercase">Delete Api key</h3>
      <p class="mb-0 text-center">
        Are you sure to delete <span class="text-danger text-uppercase"> “Api key ”</span> form this system.
      </p>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <input type="hidden" name="submit_delete_api_key">
      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-danger">Delete</button>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php include_once '../layout/footer.php'; ?>
  <?php W_Structure::loadFooter(); ?>
</body>

</html>
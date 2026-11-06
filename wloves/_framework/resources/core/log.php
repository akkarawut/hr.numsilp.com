<?php
$_PAGE['permission'] = ['core', 'core_log', ''];

require_once '../../../_framework/import.php';

W_Structure::loadModules(['datatables', 'boatnav']);

$log_type = isset($_GET['log_type']) ? $_GET['log_type'] : 'sql';

// POST handler
if (isset($_POST['clear_log_by_id'])) {
  $response = Log::deleteLog(['same_id' => $_POST['id']]);
  if ($response) {
    W_Main::notification('Success', 'success');
  } else {
    W_Main::notification('Error', 'error');
  }
  W_Main::redirect('');
} else if (isset($_POST['clear_log_by_type'])) {
  $response = Log::deleteLog(['type' => $_POST['log_type']]);
  if ($response) {
    W_Main::notification('Success', 'success');
  } else {
    W_Main::notification('Error', 'error');
  }
  W_Main::redirect('');
} else if (isset($_POST['clear_log_all'])) {
  $response = Log::deleteLog();
  if ($response) {
    W_Main::notification('Success', 'success');
  } else {
    W_Main::notification('Error', 'error');
  }
  W_Main::redirect('');
}

$link = '?c=';

$log_list = Log::selectByType($log_type);
$log_types = Log::selectCategory([], ['it_nav_wolves' => 1]);
$log_types['class'] = 'nav-log';
$log_types['param_name'] = 'log_type';

foreach ($log_types['list'] as $key => $list) {
  $log_types['list'][$key]['icon'] = str_replace('../../', '', $list['icon']);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php
  include_once 'structure/layout/header.php';
  include_once 'structure/layout/sidenav.php';
  ?>
  <!-- Sub menu -->

  <div class="setting-menu-container p-20px">
    <div class="row">
      <div class="col-12 col-lg-3">
        <div class="setting-menu-header">
          <div class="setting-menu-content">
            <p class="setting-menu-title">LOG ERROR</p>
            <p class="setting-menu-info">Check Them All!</p>
          </div>
          <button type="submit" class="btn btn-danger d-flex align-items-center  toggle-modal" <?= Tiwdal::register('delete-log-all-modal', []); ?> data-original-title="ลบทั้งหมด">
            <?= W_Structure::getfile('_framework/structure/image/icon/general/golden-bin.svg'); ?>
            <p class="mb-0 ml-10px">Clear All</p>
          </button>
        </div>
        <hr class="log">

        <?php Boatnav::wolves($log_types, $link); ?>
      </div>
      <!-- List -->
      <div class="col-12 col-lg-9">
        <div class="setting-menu-header">
          <div class="setting-menu-content">
            <p class="setting-menu-title text-uppercase"> <?= $log_type; ?> LOG</p>
            <p class="setting-menu-info">Manage your logs.</p>
          </div>
          <button type="submit" class="btn btn-danger d-flex align-items-center toggle-modal" <?= Tiwdal::register('delete-log-type-modal', []); ?> data-toggle="tooltip" data-original-title="ลบทั้งหมด">
            <?= W_Structure::getfile('_framework/structure/image/icon/general/golden-bin.svg'); ?>
            <p class="mb-0 ml-10px">Clear <?= $log_type ?> LOG</p>
          </button>
        </div>
        <div class="w-data-table-container mt-3">
          <table class="table custom-table">
            <thead>
              <tr>
                <th nowrap>Info</th>
                <th nowrap>Log</th>
                <th nowrap class="thin-cell no-sort no-colvis"></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($log_list as $idx => $log) {
                W_Main::registerModel('log_' . $log['id'], $log);
                $json_text = '';
                $start_key = 'problem : <b>{';
                $st_pos = strpos($log['topic'], $start_key);
                // && strlen($log['topic']) > strlen($start_key)
                if ($st_pos > 0) {
                  $json_text = substr($log['topic'], $st_pos + strlen($start_key) - 1);
                  $json_text = str_replace('</b>', '', $json_text);
                } else {
                  $json_text = '';
                  $start_key = 'problem : <b>[';
                  $st_pos = strpos($log['topic'], $start_key);
                  // && strlen($log['topic']) > strlen($start_key)
                  if ($st_pos > 0) {
                    $json_text = substr($log['topic'], $st_pos + strlen($start_key) - 1);
                    $json_text = str_replace('</b>', '', $json_text);
                  } else {
                    $json_text = $st_pos;
                  }
                }
              ?>
                <tr>
                  <td nowrap class="align-top" width="1%">
                    <div class="body-group-text">
                      <span class="label-text">ID :</span>
                      <span> <?= $log['id']; ?></span>
                    </div>
                    <div class="body-group-text">
                      <span class="label-text">User :</span>
                      <span> <?= $log['insert_user_id']; ?></span>
                    </div>
                    <div class="body-group-text">
                      <span class="label-text">Code :</span>
                      <span> <?= $log['code']; ?></span>
                    </div>
                    <hr>
                    <div class="body-group-text">
                      <span> <?= W_Main::formatDate($log['insert_date_time'], 'd/m/Y'); ?></span>
                    </div>
                    <div class="body-group-text">
                      <span> <?= W_Main::formatDate($log['insert_date_time'], 'H:i:s'); ?></span>
                    </div>

                  </td>
                  <td class="align-top">
                    <div class="w-love-label-container mb-25px">
                      <label class="w-love-label-title">เจอประเด็น</label>
                      <p class="w-love-label-text" style="white-space: pre-wrap; word-break: break-all;"><?= $log['topic']; ?></p>
                    </div>
                    <p class="">
                      <button class="btn btn-secondary btn-log-detail d-inline" type="button" data-bs-toggle="collapse" data-parent="#myGroup" data-bs-target="#description_<?= $log['id']; ?>" aria-expanded="false" aria-controls="description_<?= $log['id']; ?>">
                        View Details
                      </button>
                      <?php if ($json_text) { ?>
                        <button class="btn btn-secondary btn-log-detail d-inline" type="button" data-bs-toggle="collapse" data-parent="#myGroup" data-bs-target="#json_<?= $log['id']; ?>" aria-expanded="false" aria-controls="json_<?= $log['id']; ?>">
                          View JSON
                        </button>
                      <?php } ?>
                    </p>
                    <div id="myGroup" class="accordion-group">
                      <div class="collapse" id="description_<?= $log['id']; ?>">
                        <div class="w-love-label-container mb-25px">
                          <label class="w-love-label-title">Link</label>
                          <p class="w-love-label-text"><?= $log['link']; ?></p>
                        </div>
                        <div class="w-love-label-container">
                          <label class="w-love-label-title">Source</label>
                          <p class="w-love-label-text"><?= $log['file_source']; ?></p>
                        </div>
                        <div class="w-love-label-container">
                          <label class="w-love-label-title">Description</label>
                          <p class="w-love-label-text"><?= $log['description']; ?></p>
                        </div>
                      </div>
                      <?php if ($json_text) { ?>
                        <div class="collapse" id="json_<?= $log['id']; ?>">
                          <div class="w-love-label-container mb-25px">
                            <label class="w-love-label-title">SHOW JSON</label>
                            <pre class="w-love-label-text" style="white-space: pre-wrap; word-break: break-all;"><?php print_r(json_decode($json_text, true)); ?></pre>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                  </td>
                  <td class="align-top">
                    <div class="btn-table-wrap">
                      <button type="submit" class="btn btn-danger d-flex align-items-center toggle-modal" data-target="#delete-log-modal" data-toggle="tooltip" title="" data-original-title="ลบ" data-model="<?= 'log_' . $log['id']; ?>">
                        <?= W_Structure::getfile('_framework/structure/image/icon/general/golden-bin.svg'); ?>
                        <p class="mb-0 ml-10px">Clear</p>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal section -->
  <div class="modal fade" id="delete-log-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
      <form method="post" class="ww-regex-form" novalidate>
        <div class="modal-content">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 form-group text-center">
                <p class="mb-5px mt-20px">
                  <span class="text-danger">Delete LOG</span> that looks like this LOG
                </p>
                <p>Are you sure?</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Maybe</button>
            <form method="POST">
              <input type="hidden" name="clear_log_by_id" value="1">
              <input type="hidden" name="id" value=""><!-- need to insert value by js here -->
              <button type="submit" class="btn btn-danger width-100px">
                <p class="mb-0 px-25px">Yes I’m Sure</p>
              </button>
            </form>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="delete-log-type-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
      <form method="post" class="ww-regex-form" novalidate>
        <div class="modal-content">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 form-group text-center">
                <p>ARE YOU SURE</p>
                <p class="mb-5px mt-20px">
                  Are your sure to <span class="text-danger"> “Clear all <?= $log_type; ?> LOG” </span> in this system.
                </p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Maybe</button>
            <form method="POST">
              <input type="hidden" name="clear_log_by_type" value="1">
              <input type="hidden" name="log_type" value="<?= $log_type; ?>">
              <button type="submit" class="btn btn-danger width-100px">
                <p class="mb-0 px-25px">Yes I’m Sure</p>
              </button>
            </form>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="delete-log-all-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
      <form method="post" class="ww-regex-form" novalidate>
        <div class="modal-content">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 form-group text-center">
                <p>ARE YOU SURE</p>
                <p class="mb-5px mt-20px">
                  Are your sure to <span class="text-danger"> “Clear all LOG” </span> in this system.
                </p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Maybe</button>
            <form method="POST">
              <input type="hidden" name="clear_log_all" value="1">
              <button type="submit" class="btn btn-danger width-100px">
                <p class="mb-0 px-25px">Yes I’m Sure</p>
              </button>
            </form>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter("../../"); ?>
</body>

</html>
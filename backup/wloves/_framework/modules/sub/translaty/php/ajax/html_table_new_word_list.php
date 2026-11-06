<?php
$_WLOVES['no_check_permission'] = 1;
if (isset($_GET['is_ajax']) && $_GET['is_ajax']) {
  $path_arr = explode('/', $_SERVER['REQUEST_URI']);
  $inx_framwork = array_search('_framework', $path_arr);
  foreach ($path_arr as $i => $item) {
    if ($i < $inx_framwork && $i != 0) {
      $path_add .= $item . '/';
    }
  }
  $path_wloves = $_SERVER['DOCUMENT_ROOT'] . '/' . $path_add;
  require_once $path_wloves . '/_framework/import.php';
  W_Structure::loadMetaForAjax('../../../../');
}
$curr_language_list = Translaty::getCurrentLanguage();
$data =  Translaty::exportTableList('');
?>

<?php
foreach ($data['list'] as $key => $item) {
?>
  <tr class="<?= isset($item['is_framework']) && $item['is_framework'] ? 'is_framework_scope' : '' ?> <?= (int)$key % 2 == 0 ? ' even-tr' : '' ?>">
    <td nowrap class="thin-cell vertical-align-center">
      <div class="px-10px d-flex">
        <?php
        $edit_data = [
          'key' => $item['key'],
          'old_key' => $item['key'],
        ];
        foreach ($curr_language_list as $lg) {
          $edit_data[$lg . '_word'] =   $item[$lg . '_word'];
        }
        // $del_data = [
        //   'key' => $item['key'],
        // ];
        if (isLocalhost() && !isset($item['is_framework'])) {
        ?>
          <button type="button" class="btn-icon event-change-keyword" <?= Tiwdal::register('edit_modal', $edit_data); ?> data-toggle="tooltip" data-placement="right" title="Edit Data">
            <?= W_Structure::getfile('_framework/modules/sub/translaty/icon/icon-edit.svg') ?>
          </button>
        <?php } ?>
        <button type="button" class="btn-icon event-copy-keyword <?= isset($item['is_value']) ? 'event-is-value' : '' ?> ml-5px" data-toggle="tooltip" data-placement="right" title="Copy Key" value='<?= $item['key'] ?>'>
          <?= W_Structure::getfile('_framework/modules/sub/translaty/icon/icon-copy.svg') ?>
        </button>
        <button type="button" class="btn-icon event-copy-keyword-html <?= isset($item['is_value']) ? 'event-is-value' : '' ?> ml-5px pb-4px" data-toggle="tooltip" data-placement="right" title="Copy Key" value='<?= $item['key'] ?>'>
          <span class="color-white">&lt;/&gt;</span>
        </button>
      </div>
    </td>
    <!-- # -->
    <td class=" vertical-align-center min-w-300px">
      <div style="display: flex; justify-content:space-between;">
        <div>
          <?= $item['key'] ?>

        </div>
        <div>
          <?= isset($item['is_framework']) ? '<span class="text-primary-ty">(' . $item['filename'] . ')</span>' : '' ?>
        </div>
      </div>
    </td>
    <?php foreach ($curr_language_list as $lg) {
    ?> <td class=" vertical-align-center max-w-200px overflow-hidden ">
        <?= $item[$lg . '_word'] ?>
      </td>
    <?php
    }
    ?>
  </tr>
<?php
}
?>
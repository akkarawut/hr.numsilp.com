<?php
$_PAGE['permission'] = ['core', 'core_program_setting', 'core_program_setting_api_key'];
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$list =  API::select();
?>
<tbody data-total_count="<?= count($list) ?>">
  <?php foreach ($list as $data_list) { ?>
    <tr>
      <td><?= $data_list['api_key'] ?></td>
      <td><?= $data_list['description'] ?></td>
      <td class="thin-cell">
        <div class="d-flex align-items-center">
          <?php
          TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'mr-10px'], ['text' => '', 'type' => 'edit', 'prefix' => '../../../../', 'modal_id' => 'edit-api-key-modal', 'modal_data' => $data_list]);
          TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'delete', 'prefix' => '../../../../', 'modal_id' => 'delete-key-modal', 'modal_data' => $data_list]);
          ?>
        </div>
      </td>
    </tr>
  <?php } ?>
</tbody>
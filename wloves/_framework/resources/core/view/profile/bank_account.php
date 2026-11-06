<?php
if ($_POST) {
  if (isset($_POST['submit_add_bank'])) {
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    unset($_POST['id']);
    unset($_POST['submit_add_bank']);
    $_POST['user_id'] = $user_id;
    $_POST['is_default_account'] = (isset($_POST['is_default_account']) && $_POST['is_default_account']) ? 1 : 0;
    $result = User_Bank_Account::addNewAccount($_GET['c'], $_POST);
  } else if (isset($_POST['submit_edit_bank'])) {
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    unset($_POST['id']);
    unset($_POST['submit_edit_bank']);
    $_POST['is_default_account'] = (isset($_POST['is_default_account']) && $_POST['is_default_account']) ? 1 : 0;
    $result = User_Bank_Account::updateAccount($_GET['c'], $id, $_POST);
  } else if (isset($_POST['submit_delete_bank'])) {
    $result = User_Bank_Account::deleteAccount($_GET['c'], $_POST['id']);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

$account_list = User_Bank_Account::selectAccount($_GET['c'], ['user_id' => $user_id], ['bank_img_path' => true]);

?>
<div class="bg-card br-bottom-10px py-15px">
  <div class="d-flex align-items-center justify-content-between flex-wrap px-15px">
    <div class="mb-15px">
      <div class="text-uppercase font-Medium text-info">Bank Account | <span class="text-primary"><?= $user_info['title'] . ' ' . $user_info['name'] . ' ' . $user_info['surname'] ?></span></div>
      <div class="font-14px text-secondary">User log in account for used this system</div>
    </div>
    <div class="mb-10px d-flex">
      <?= TiwForm::normal('btn', '', ['class' => 'text-uppercase'], ['text' => '+ Add Bank Account', 'modal_id' => 'add_bank_modal', 'modal_data' => []]); ?>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table-bg-card-back">
      <thead>
        <tr>
          <th nowrap>Bank</th>
          <th nowrap>Account No.</th>
          <th nowrap>Account Name</th>
          <th nowrap></th>
          <th nowrap></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($account_list) {
          foreach ($account_list as $data) {
        ?>
            <tr>
              <td><?= $data['bank_abb'] ?></td>
              <td><?= $data['account_no'] ?></td>
              <td><?= $data['account_name'] ?></td>
              <td>
                <?php
                $text = '';
                $color = '';
                if ($data['is_default_account']) {
                  $text = 'Receiving Account';
                  $color = 'text-primary';
                }
                ?>
                <p class="mb-0 <?= $color ?>"><?= $text ?></p>
              </td>
              <td nowrap class="thin-cell py-5px">
                <div class="d-flex align-items-center">
                  <?php
                  TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'edit', 'prefix' => '../../', 'modal_id' => 'edit_bank_modal', 'modal_data' => $data]);
                  TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'delete', 'prefix' => '../../', 'modal_id' => 'delete_bank_modal', 'modal_data' => $data]);
                  ?>
                </div>
              </td>
            </tr>
        <?php }
        } else {
          echo '<tr><td colspan="5" class="text-center font-14px text-secondary">NO DATA</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php Tiwdal::startModal('add_bank_modal', 'modal-md'); ?>
<div class="modal-header">
  <h5 class="modal-title">ADD BANK ACCOUNT</h5>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0">Bank<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?= TiwForm::normal('select-bank', '', ['name' => 'bank_abb', 'placeholder' => 'Please Select'], ['is_search' => true]); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0">Account No.<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('number', '', ['name' => 'account_no', 'placeholder' => 'Enter', 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0">Account Name<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => 'account_name', 'placeholder' => 'Enter', 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px"></div>
      <div class="col-md-9">
        <?= TiwForm::normal('checkbox', 1, ['name' => 'is_default_account', 'class' => ''], ['style' => '3', 'label' => 'Set to receiving account']); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_add_bank', 'type' => 'submit', 'class' => 'min-w-100px btn_check_pin_event'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('edit_bank_modal', 'modal-md'); ?>
<div class="modal-header">
  <h5 class="modal-title">EDIT BANK ACCOUNT</h5>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0">Bank<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?= TiwForm::normal('select-bank', '', ['name' => '{bank_abb}', 'placeholder' => 'Please Select'], ['is_search' => true]); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0">Account No.<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('number', '', ['name' => '{account_no}', 'placeholder' => 'Enter', 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0">Account Name<span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => '{account_name}', 'placeholder' => 'Enter', 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px"></div>
      <div class="col-md-9">
        <div class="d-flex align-items-center">
          <?= TiwForm::normal('checkbox', 1, ['name' => '{is_default_account}', 'class' => ''], ['style' => '3', 'label' => 'Set to receiving account']); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_edit_bank', 'type' => 'submit', 'class' => 'min-w-100px btn_check_pin_event'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('delete_bank_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<form method="post" class="">
  <div class="modal-body">
    <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">DELETE BANK ACCOUNT</div>
    <div class="text-secondary text-center">Are you sure to delete <span class="text-danger text-uppercase">“User’s Bank Account”</span><br>if you delete this data recent history isn’t effective.</div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'No…']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_bank', 'type' => 'submit', 'class' => 'm-5px min-w-120px btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>
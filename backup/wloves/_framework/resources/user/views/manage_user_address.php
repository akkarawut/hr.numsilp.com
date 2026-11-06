<?php
if ($_POST) {
  if (isset($_POST['submit_add_address'])) {
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    $_POST['user_id'] = $user_id;

    unset($_POST['id']);
    unset($_POST['submit_add_address']);
    unset($_POST['country_name']);

    $result = User_address::addNewAddress($_POST);
  } else if (isset($_POST['submit_edit_address'])) {
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    $_POST['tel'] = $_POST['tel_full'];
    $_POST['user_id'] = $user_id;

    unset($_POST['id']);
    unset($_POST['submit_edit_address']);
    unset($_POST['country_name']);
    unset($_POST['tel_full']);

    $result = User_address::updateAddress($id, $_POST);
  } else if (isset($_POST['submit_delete_address'])) {
    $result = User_address::deleteAddress($_POST['id']);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

$where = [
  'user_id' => $user_id,
  'type_name' => 'user_address'
];
$address_list = User_address::selectAddress($where);
?>

<div class="container-detail py-15px px-0 mb-10px">
  <div class="d-flex align-items-center justify-content-between flex-wrap px-15px">
    <div class="mb-10px">
      <div class="text-uppercase font-16px font-SemiBold">Multiple Address</div>
      <div class="font-14px text-secondary">Customer address for billing, Shipping and export document.</div>
    </div>
    <div class="mb-10px d-flex">
      <?= TiwForm::normal('btn', '', ['class' => 'text-uppercase'], ['text' => '+ ADD NEW ADDRESS', 'modal_id' => 'add_address_modal', 'modal_data' => []]); ?>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table-bg-card-back">
      <thead>
        <tr>
          <th nowrap>Name</th>
          <th nowrap>Address</th>
          <th nowrap>Telephone</th>
          <th nowrap></th>
          <th nowrap></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($address_list) {
          foreach ($address_list as $data) {
            $data['tel_full'] = '+' . $data['tel_country_code'] . ' ' . $data['tel_no'];
        ?>
            <tr>
              <td><?= $data['full_name'] ?></td>
              <td><?= $data['address'] . ' ' . $data['sub_district'] . ' ' . $data['district'] . ' ' . $data['province'] . ' ' . $data['country'] . ' ' . $data['zipcode'] ?></td>
              <td><?= ($data['tel_country_code'] && $data['tel_no']) ? '+' . $data['tel_country_code'] . ' ' . $data['tel_no'] : '-'; ?></td>
              <td>
                <?php
                if ($data['is_default']) {
                  echo '<span class="text-primary">Default Address</span>';
                }
                ?>
              </td>
              <td nowrap class="thin-cell py-5px">
                <div class="d-flex align-items-center">
                  <?php
                  TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'edit', 'prefix' => '../../', 'modal_id' => 'edit_address_modal', 'modal_data' => $data]);
                  TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'delete', 'prefix' => '../../', 'modal_id' => 'delete_address_modal', 'modal_data' => $data]);
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

<?php Tiwdal::startModal('add_address_modal'); ?>
<div class="modal-header">
  <h5 class="modal-title">Add Address</h5>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-lg-12">
        <label class="mb-0">Consignee</label>
        <?php TiwForm::normal('text', '', ['name' => 'full_name', 'placeholder' => 'Enter', 'required' => 'required']); ?>
        <label class="mb-0">Address</label>
        <?php TiwForm::normal('textarea', '', ['name' => 'address', 'placeholder' => 'Enter', 'required' => 'required']); ?>
        <div class="row">
          <div class="col-6">
            <label class="mb-0">Sub District</label>
            <?php TiwForm::normal('text', '', ['name' => 'sub_district', 'placeholder' => 'Enter', 'class' => 'sub_district mb-0', 'required' => '']); ?>
          </div>
          <div class="col-6 mb-10px">
            <label class="mb-0">District</label>
            <?php TiwForm::normal('text', '', ['name' => 'district', 'placeholder' => 'Enter', 'class' => 'district mb-0', 'required' => '']); ?>
          </div>
          <div class="col-6 mb-10px">
            <label class="mb-0">Province</label>
            <?php TiwForm::normal('text', '', ['name' => 'province', 'placeholder' => 'Enter', 'class' => 'province mb-0', 'required' => '']); ?>
          </div>
          <div class="col-6 mb-10px">
            <label class="mb-0">Zip Code</label>
            <?php TiwForm::normal('text', '', ['name' => 'zipcode', 'placeholder' => 'Enter', 'class' => 'zipcode mb-0', 'required' => '']); ?>
          </div>
          <div class="col-12 mb-10px">
            <label class="mb-0">Phone Number</label>
            <?php TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no', 'placeholder' => 'Enter'], ['country_code' => 'tel_country_code']); ?>
          </div>
        </div>
        <div class="d-flex align-items-center mb-10px">
          <?= TiwForm::normal('checkbox', 1, ['name' => 'is_default', 'class' => ''], ['style' => '3', 'label' => 'Set to default Address']); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_add_address', 'type' => 'submit', 'class' => 'min-w-100px'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('edit_address_modal'); ?>
<div class="modal-header">
  <h5 class="modal-title">Edit Address</h5>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-lg-12">
        <label class="mb-0">Consignee</label>
        <?php TiwForm::normal('text', '', ['name' => '{full_name}', 'placeholder' => 'Enter', 'required' => 'required']); ?>
        <label class="mb-0">Address</label>
        <?php TiwForm::normal('textarea', '', ['name' => '{address}', 'placeholder' => 'Enter', 'required' => 'required']); ?>
        <div class="row">
          <div class="col-6">
            <label class="mb-0 mb-10px">Sub District</label>
            <?php TiwForm::normal('text', '', ['name' => '{sub_district}', 'placeholder' => 'Enter', 'class' => 'sub_district mb-0', 'required' => '']); ?>
          </div>
          <div class="col-6">
            <label class="mb-0 mb-10px">District</label>
            <?php TiwForm::normal('text', '', ['name' => '{district}', 'placeholder' => 'Enter', 'class' => 'district mb-0', 'required' => '']); ?>
          </div>
          <div class="col-6">
            <label class="mb-0 mb-10px">Province</label>
            <?php TiwForm::normal('text', '', ['name' => '{province}', 'placeholder' => 'Enter', 'class' => 'province mb-0', 'required' => '']); ?>
          </div>
          <div class="col-6">
            <label class="mb-0 mb-10px">Zip Code</label>
            <?php TiwForm::normal('text', '', ['name' => '{zipcode}', 'placeholder' => 'Enter', 'class' => 'zipcode mb-0', 'required' => '']); ?>
          </div>
          <div class="col-12">
            <label class="mb-0">Phone Number</label>
            <?php TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no', 'placeholder' => 'Enter'], ['country_code' => 'tel_country_code', 'main_class' => 'edit_tel_scope']); ?>
          </div>
        </div>
        <div class="d-flex align-items-center mb-10px">
          <?= TiwForm::normal('checkbox', 1, ['name' => '{is_default}', 'class' => ''], ['style' => '3', 'label' => 'Set to default Address']); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_edit_address', 'type' => 'submit', 'class' => 'min-w-100px'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('delete_address_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<form method="post" class="">
  <div class="modal-body">
    <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">Delete Address</div>
    <div class="text-secondary text-center">Are you sure to delete <span class="text-danger text-uppercase">“ADDRESS”</span><br>if you delete this data recent history isn’t effective.</div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'No…']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_address', 'type' => 'submit', 'class' => 'm-5px min-w-120px btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php
W_Main::loadAsset('../../structure/plugin/jquery.Thailand.js/dependencies/JQL.min.js');
W_Main::loadAsset('../../structure/plugin/jquery.Thailand.js/dependencies/typeahead.bundle.js');
W_Main::loadAsset('../../structure/plugin/jquery.Thailand.js/dists/jquery.Thailand.min.js');
W_Main::loadAsset('../../structure/plugin/jquery.Thailand.js/dists/jquery.Thailand.min.js');
W_Main::loadAsset('../../structure/plugin/jquery.Thailand.js/dists/jquery.Thailand.min.css');
?>
<script>
  $(function() {
    $.Thailand({
      database: '../../structure/plugin/jquery.Thailand.js/database/db.json',
      $district: $('.sub_district'),
      $amphoe: $('.district'),
      $province: $('.province'),
      $zipcode: $('.zipcode'),
    });

    $('#edit_address_modal').on('shown.bs.modal', function() {
      var result = w.tiwdal.getRegisterData(this);
      setTelEvent('.edit_tel_scope', result.tel_full);
    });
  });
</script>
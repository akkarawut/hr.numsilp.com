<?php
if ($_POST) {
  if (isset($_POST['submit_add_contact_person'])) {
    unset($_POST['submit_add_contact_person']);
    $_POST['user_id'] = $user_id;
    $result = User_Contact_Person::addNewContact($_POST);
  } else if (isset($_POST['submit_edit_contact_person'])) {
    $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : 0;
    unset($_POST['id']);
    unset($_POST['submit_edit_contact_person']);
    $result = User_Contact_Person::updateContact($id, $_POST);
  } else if (isset($_POST['submit_delete_contact_person'])) {
    $result = User_Contact_Person::deleteContact($_POST['id']);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

$contact_list = User_Contact_Person::selectContact(['user_id' => $user_id]);
?>

<div class="container-detail py-15px px-0 mb-10px">
  <div class="d-flex align-items-center justify-content-between flex-wrap px-15px">
    <div class="mb-10px">
      <div class="text-uppercase font-16px font-SemiBold">CONTACT PERSON</div>
      <div class="font-14px text-secondary">User’s Contact Person.</div>
    </div>
    <div class="mb-10px d-flex">
      <?= TiwForm::normal('btn', '', ['class' => 'text-uppercase'], ['text' => '+ ADD CONTACT PERSON', 'modal_id' => 'add_contact_modal', 'modal_data' => []]); ?>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table-bg-card-back">
      <thead>
        <tr>
          <th nowrap>Name</th>
          <th nowrap>Relationship info</th>
          <th nowrap>Telephone Number</th>
          <th nowrap></th>
          <th nowrap></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($contact_list) {
          foreach ($contact_list as $data) {
            $data['tel_full'] = '+' . $data['tel_country_code'] . ' ' . $data['tel_no'];
        ?>
            <tr>
              <td><?= $data['name'] ?></td>
              <td><?= $data['relationship'] ?></td>
              <td><?= $data['tel_no'] ?></td>
              <td></td>
              <td class="thin-cell">
                <div class="d-flex align-items-center">
                  <?php
                  TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'edit', 'prefix' => '../../', 'modal_id' => 'edit_contact_modal', 'modal_data' => $data]);
                  TiwForm::normal('btn', '', ['type' => 'button', 'class' => ''], ['text' => '', 'type' => 'delete', 'prefix' => '../../', 'modal_id' => 'delete_contact_modal', 'modal_data' => $data]);
                  ?>
                </div>
              </td>
            </tr>
        <?php }
        } else {
          echo '<tr><td colspan="4" class="text-center font-14px text-secondary">NO DATA</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php Tiwdal::startModal('add_contact_modal', 'modal-md'); ?>
<div class="modal-header">
  <h5 class="modal-title"><?= Itlanguage::translate('ADD CONTACT PERSON'); ?></h5>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0"><?= Itlanguage::translate('Name'); ?><span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0"><?= Itlanguage::translate('Relationship info'); ?><span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => 'relationship', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0"><?= Itlanguage::translate('Telephone Number'); ?><span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no', 'placeholder' => 'Enter'], ['country_code' => 'tel_country_code']); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_add_contact_person', 'type' => 'submit', 'class' => 'min-w-100px'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('edit_contact_modal', 'modal-md'); ?>

<div class="modal-header">
  <h5 class="modal-title"><?= Itlanguage::translate('EDIT CONTACT PERSON'); ?></h5>
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0"><?= Itlanguage::translate('Name'); ?><span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => '{name}', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0"><?= Itlanguage::translate('Relationship info'); ?><span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('text', '', ['name' => '{relationship}', 'placeholder' => Itlanguage::translate('Enter'), 'required' => 'required']); ?>
      </div>
      <div class="col-md-3 font-14px text-secondary pt-10px">
        <label class="mb-0"><?= Itlanguage::translate('Telephone Number'); ?><span class="text-danger">*</span></label>
      </div>
      <div class="col-md-9">
        <?php TiwForm::normal('tel-flag', '+66', ['name' => 'tel_no', 'placeholder' => 'Enter'], ['country_code' => 'tel_country_code', 'main_class' => 'edit_tel_scope']); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel']);
    TiwForm::normal('btn', '', ['name' => 'submit_edit_contact_person', 'type' => 'submit', 'class' => 'min-w-100px'], ['text' => 'SAVE']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('delete_contact_modal', 'modal-md'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
<form method="post" class="">
  <div class="modal-body">
    <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">DELETE CONTACT PERSON</div>
    <div class="text-secondary text-center">Are you sure to Delete <span class="text-danger text-uppercase">“User’s Contact Person”</span><br>if you delete this data recent history isn’t effective.</div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('hidden', '', ['name' => '{id}']);
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => 'No…']);
    TiwForm::normal('btn', '', ['name' => 'submit_delete_contact_person', 'type' => 'submit', 'class' => 'm-5px min-w-120px btn-danger'], ['text' => 'Yes!! I’m Sure']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<script>
  $(function() {
    $('#edit_contact_modal').on('shown.bs.modal', function() {
      var result = w.tiwdal.getRegisterData(this);
      setTelEvent('.edit_tel_scope', result.tel_full);
    });
  });
</script>
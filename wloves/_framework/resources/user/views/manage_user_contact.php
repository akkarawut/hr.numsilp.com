<?php
$api = [
  'api'    => 'User::updateUser',
  'params' => [
    'id'   => $user_id,
    'data' => []
  ]
];
$where = [
  'user_id' => $user_id
];
$options = [];
$select_contact = User_Contact_Person::selectContact($_GET['c'], $where, $options);
?>
<div class="row">
  <div class="col-lg-12">
    <div class="title-detail">
      <div>
        <h3><?= Itlanguage::translate('CONTACT INFORMATION'); ?> </h3>
        <p><?= Itlanguage::translate('Customer contact information'); ?></p>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Email'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'email' => '{email}'
          ];
          TiwForm::liveForm('text', 'email', $user_data['email'], $api); ?>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Telephone No.'); ?></p>
        </div>
        <div class="col-md-8">
          <div class="flex-between-center">
            <?php if ($user_data['tel']) {
              TiwForm::normal('tel-flag', '+' . $user_data['tel_country_code'] . ' ' . $user_data['tel'], ['name' => '', 'placeholder' => '-', 'readonly' => 'readonly', 'class' => 'border-0'], ['main_class' => 'readonly']);
            } else {
              echo '-';
            }
            ?>
            <button class="icon-card" <?= Tiwdal::register('edit_tel_modal', $user_data); ?>>
              <?= file_get_contents('assets/image/icon/icon-edit.svg'); ?>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Facebook'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'facebook_id' => '{facebook_id}'
          ];
          TiwForm::liveForm('text', 'facebook_id', $user_data['facebook_id'], $api); ?>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Line ID'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'line_id' => '{line_id}'
          ];
          TiwForm::liveForm('text', 'line_id', $user_data['line_id'], $api); ?>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Twiter'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'twitter_url' => '{twitter_url}'
          ];
          TiwForm::liveForm('text', 'twitter_url', $user_data['twitter_url'], $api); ?>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('We Chat'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'we_chat_url' => '{we_chat_url}'
          ];
          TiwForm::liveForm('text', 'we_chat_url', $user_data['we_chat_url'], $api); ?>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Shopee ID'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'shopee_id' => '{shopee_id}'
          ];
          TiwForm::liveForm('text', 'shopee_id', isset($user_data['shopee_id']) ? $user_data['shopee_id'] : '', $api); ?>
        </div>
      </div>
    </div>
    <div class="card-detail">
      <div class="row">
        <div class="col-md-4 align-items-center d-flex">
          <p class="label"><?= Itlanguage::translate('Lazada ID'); ?></p>
        </div>
        <div class="col-md-8">
          <?php
          $api['params']['data'] = [
            'lazada_id' => '{lazada_id}'
          ];
          TiwForm::liveForm('text', 'lazada_id', isset($user_data['lazada_id']) ? $user_data['lazada_id'] : '', $api); ?>
        </div>
      </div>
    </div>

    <div class="title-detail mt-20px">
      <div>
        <h3><?= Itlanguage::translate('CONTACT PERSON'); ?> </h3>
        <p><?= Itlanguage::translate('Customer contact person.'); ?></p>
      </div>
      <button class="form-btn px-10px" <?= Tiwdal::register('add_contact_modal'); ?>>ADD NEW CONTACT PERSON</button>
    </div>
    <?php foreach ($select_contact as $key => $data) {
    ?>
      <div class="card-detail">
        <div class="row py-10px">
          <div class="col-md-4 align-items-center d-flex">
            <p class="label"><?= Itlanguage::translate($data['name']); ?></p>
          </div>
          <div class="col-md-8">
            <div class="flex-between-center">
              <div>
                <p class="mb-0"><?= $data['relationship'] ?></p>
                <p class="label"><?= Itlanguage::translate('Tel : ' . $data['tel']); ?></p>
                <p class="label"><?= Itlanguage::translate('Line ID : ' . $data['line_id']); ?></p>
                <p class="label"><?= Itlanguage::translate('Email : ' . $data['email']); ?></p>
              </div>
              <div class="d-flex">
                <button class="icon-card" <?= Tiwdal::register('edit_contact_modal', $data); ?>>
                  <?= file_get_contents('assets/image/icon/icon-edit.svg'); ?>
                </button>
                <button class="icon-card" <?= Tiwdal::register('delete_contact_modal', $data); ?>>
                  <?= file_get_contents('assets/image/icon/icon-bin.svg'); ?>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<?php Tiwdal::startModal('edit_tel_modal', 'modal-sm'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title"><?= Itlanguage::translate('แก้ไขเบอร์โทรศัพท์'); ?></h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-12">
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Phone Number'); ?></label>
        <?php
        $tel_country_code = ($user_data['tel_country_code']) ? $user_data['tel_country_code'] : '+66';
        TiwForm::normal('tel-flag', '+' . $tel_country_code . ' ' . $user_data['tel'], ['name' => '{tel}', 'placeholder' => 'เบอร์โทรศัพท์']);
        ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <input type="hidden" name="submit_update_tel_user">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><?= Itlanguage::translate('ยกเลิก'); ?></button>
    <?= TiwForm::normal('btn', '', ['type' => 'submit'], ['text' => Itlanguage::translate('ยืนยัน')]); ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('add_contact_modal', 'modal-sm'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title"><?= Itlanguage::translate('Add Customer Contact Person'); ?></h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-12">
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Name'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter']); ?>
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Relationship'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => 'relationship', 'placeholder' => 'Enter']); ?>
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Phone Number'); ?></label>
        <?php
        // $tel_country_code = ($user_data['tel_country_code']) ? $user_data['tel_country_code'] : '+66';
        TiwForm::normal('tel-flag', '+66', ['name' => 'tel', 'placeholder' => 'Enter']);
        ?>
        <label class="mb-0 text-secondary mt-10px"><?= Itlanguage::translate('Line ID'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => 'line_id', 'placeholder' => 'Enter']); ?>
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Email'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => 'email', 'placeholder' => 'Enter']); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="submit_add_contact">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><?= Itlanguage::translate('Cancel'); ?></button>
    <?= TiwForm::normal('btn', '', ['type' => 'submit'], ['text' => Itlanguage::translate('Confirm')]); ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('edit_contact_modal', 'modal-sm'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-header">
  <h5 class="modal-title"><?= Itlanguage::translate('Edit Customer Contact Person'); ?></h5>
</div>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-12">
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Name'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => '{name}', 'placeholder' => 'Enter']); ?>
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Relationship'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => '{relationship}', 'placeholder' => 'Enter']); ?>
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Phone Number'); ?></label>
        <?php
        // $tel_country_code = ($user_data['tel_country_code']) ? $user_data['tel_country_code'] : '+66';
        TiwForm::normal('tel-flag', '+66', ['name' => '{tel}', 'placeholder' => 'Enter']);
        ?>
        <label class="mb-0 text-secondary mt-10px"><?= Itlanguage::translate('Line ID'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => '{line_id}', 'placeholder' => 'Enter']); ?>
        <label class="mb-0 text-secondary"><?= Itlanguage::translate('Email'); ?></label>
        <?php TiwForm::normal('text', '', ['name' => '{email}', 'placeholder' => 'Enter']); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <input type="hidden" name="submit_update_contact">
    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><?= Itlanguage::translate('Cancel'); ?></button>
    <?= TiwForm::normal('btn', '', ['type' => 'submit'], ['text' => Itlanguage::translate('Confirm')]); ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>

<?php Tiwdal::startModal('delete_contact_modal'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
<form method="post">
  <div class="modal-body">
    <div class="row">
      <div class="col-12 form-group text-center">
        <h5 class="font-16px mb-5px mt-20px"><?= Itlanguage::translate('Delete Contact Person'); ?></h5>
        <p class="text-secondary"><?= Itlanguage::translate('Are you sure to delete this Contact Person.'); ?></p>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="{id}">
    <input type="hidden" name="submit_delete_contact">
    <button type="button" class="btn btn-modal-cancel px-1" data-bs-dismiss="modal"><?= Itlanguage::translate('Cancel'); ?></button>
    <button type="submit" class="btn btn-danger width-100px">
      <p class="mb-0 px-25px"><?= Itlanguage::translate('YES!!'); ?></p>
    </button>
  </div>
</form>
<?php Tiwdal::endModal() ?>
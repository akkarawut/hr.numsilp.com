<?php
if ($_POST) {
  $api_to_module = Translaty::getLanguageApitoModule();
  if (isset($_POST['submit_add_language'])) {
    unset($_POST['submit_add_language']);
    Translaty::addLanguage($api_to_module[$_POST['locale_name']]);
    $result = Language::addNewSystemLanguage($_POST['id']);
  } else if (isset($_POST['submit_cancel_language'])) {
    unset($_POST['submit_cancel_language']);
    Translaty::delLanguage($api_to_module[$_POST['locale_name']]);
    $result = Language::deleteSystemLanguage($_POST['id']);
  } else if (isset($_POST['submit_make_default_language'])) {
    unset($_POST['submit_make_default_language']);
    Translaty::setDefaultLg($api_to_module[$_POST['locale_name']]);
    $result = Language::updateSystemLanguage($_POST['id'], ['is_system_default' => 1]);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    // W_Main::redirect($response_redirect);
  }
}
$languages = Language::selectSystemLanguage();
$select_language = Language::selectLanguage(['not_exists' => true]);
$language_options = [
  'list' => []
];
foreach ($select_language as $language) {
  $language_options['list'][] = [
    'value' => $language['locale_name'],
    'name' => $language['title'],
    'img' => $language['img_path'],
  ];
}
?>



<div class="w-card">
  <div class="w-card-title-bar">
    <div class="d-flex flex-wrap align-items-center">
      <h3 class="title"><?= Ty::get('lang_setting') ?></h3>
      <p class="detail">
        <?= Ty::get('config_lang') ?>, <?= Ty::get('defualt_cancel') ?>, <?= Ty::get('change_personal') ?>
      </p>
    </div>
    <div class="btn-edit-container">
      <button class="btn-edit edit" <?= Tiwdal::register('add_language_modal', []); ?>>+ <?= Ty::get('add_lang') ?></button>
    </div>
  </div>
  <div class="py-15px px-20px">
    <div class="">
      <table class="table-bg-card-back">
        <thead>
          <tr>
            <th><?= Ty::get('Language') ?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($languages) {
            foreach ($languages as $language) {
          ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flag-image mr-10px">
                      <img src="<?= $language['img_path'] ?>">
                    </div>
                    <div class="mr-10px text-uppercase"><?= $language['display_name'] ?></div>
                    <?= ($language['is_system_default']) ? '<div class="badge-primary">Default</div>' : ''; ?>
                  </div>
                </td>
                <td class="thin-cell">
                  <?php if (!$language['is_system_default']) { ?>
                    <div class="btn-group dot3">
                      <button type="button" class="form-btn-icon" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= file_get_contents('../../structure/image/icon/general/more.svg'); ?>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm">
                        <form action="" method="post">
                          <?= TiwForm::normal('hidden', $language['locale_name'], ['name' => 'locale_name']) ?>
                          <?= TiwForm::normal('hidden', $language['id'], ['name' => 'id']) ?>
                          <button type="submit" class="btn dropdown-item justify-content-start" name="submit_make_default_language">
                            <span class="ml-5px"><?= Ty::get('make_defualt') ?></span>
                          </button>
                        </form>
                        <form action="" method="post">
                          <?= TiwForm::normal('hidden', $language['locale_name'], ['name' => 'id']) ?>
                          <button type="submit" class="btn dropdown-item justify-content-start" name="submit_cancel_language">
                            <span class="ml-5px text-danger"><?= Ty::get('cancel_language') ?></span>
                          </button>
                        </form>
                      </div>
                    </div>
                  <?php } ?>
                </td>
              </tr>
          <?php
            }
          } else {
            echo '<span class="font-14px text-secondary text-center">NO DATA</span>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('add_language_modal', 'modal-md'); ?>
<form action="" method="post">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title"><?= Ty::get('add_lang_prog', [], ["case" => "strtoupper"]) ?></h5>
  </div>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-3 pt-5px">
        <?= Ty::get('Language', [], ["case" => "strtoupper"]) ?><span class="text-danger">*</span>
      </div>
      <div class="col-md-9">
        <?php
        TiwForm::normal('select-img', '', ['name' => 'locale_name', 'placeholder' => Ty::get('please_select')], $language_options);
        ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-light min-w-80px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get('cancel')]);
    TiwForm::normal('btn', '', ['type' => 'submit', 'class' => 'min-w-100px', 'name' => 'submit_add_language'], ['text' => Ty::get('add')]);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>
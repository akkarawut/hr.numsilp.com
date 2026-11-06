  <?php
  $update_options = [
    'permission' => $get_user['permission'],
    'setting_permission' => $module_setup['setting_permission'],
  ];
  W_Permission::updateUserPermission($id, $update_options);
  $options = [
    'permission' => $get_user['permission'],
    'hidden_permission' => $module_setup['setting_permission'],
  ];
  $permission = W_Permission::templateConfigUserPermission($options);
  $count_function = 0;
  $count_have_function = 0;
  foreach ($permission as $key_code => $list_page) {
    $count_function += $list_page['count_function'];
    $count_have_function += $list_page['count_have_function'];
  }
  ?>
  <hr class=" border-color-bg-5px m-0">
  <form method="POST" id="form_permission">
    <?php TiwForm::normal('hidden', $id, ['name' =>  'id']); ?>
    <div class="w-card-title-bar border-bottom">
      <div class="d-flex flex-wrap align-items-center">
        <h3 class="title"><?= Ty::get('account_rights_access_system') ?></h3>
      </div>

      <div class="d-flex">
        <div class="funtion-count me-0">
          <span class=" font-SemiBold font-14px"><?= Ty::get('permission') ?></span>
          <span class="count"><span class="text-success"> <?= $count_have_function ?> </span>/ <?= $count_function ?></span>
        </div>
        <?php if (!$is_edit) { ?>
          <div class="btn-edit-container">
            <?php if (!$is_edit_permission) { ?>
              <a href="?c=<?= $code ?>&id=<?= $id ?>&page=<?= $page ?>&is_edit_permission=1" class="btn-edit edit"> <?= Ty::get('edit_data', [], ["case" => "ucfirst"]) ?></a>
            <?php } else { ?>
              <a href="?c=<?= $code ?>&id=<?= $id ?>&page=<?= $page ?>" class="btn-edit cancel"> <?= Ty::get('cancel', [], ["case" => "ucfirst"]) ?></a>
              <button type="submit" name="submit_update_user_permission" class="btn-edit save"> <?= Ty::get(['Save record', 'save_record'], [], ["case" => "ucfirst"]) ?></button>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <?php foreach ($permission as $key_code => $module) { ?>
      <?php if ($module['module'] != 'User') { ?>
        <div class="permission-list">
          <div class="permission-list-title">
            <div class="arrow active" onclick="eventToggleShow(event)">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/double_arrow_down.svg') ?>
            </div>
            <div class="line-separator"></div>
            <div class="icon">
              <?php
              $theme = W_Structure::getThemeClass();
              $data_file = W_Main::datafile($module[$theme . '_icon']);
              $icon = W_Structure::getLinkURL('_framework/structure/image/icon/icon-core.svg');
              if (!$data_file['is_placeholder']) {
                $icon = $module[$theme . '_icon'];
              }
              ?>
              <img src=" <?= $icon ?>" alt="">
            </div>
            <div class="name">
              <?= $module['name'] ?>
            </div>
            <?php if ((!$is_edit) && ($is_edit_permission)) { ?>
              <div class="module-check-all-box">
                <p class="check-all text-danger" onclick="checkAllPermission(this,false)"><?= Ty::get('clear_per', [], ["case" => "ucfirst"]) ?></p>
                <p class="check-all text-primary" onclick="checkAllPermission(this,true)"><?= Ty::get('check_per', [], ["case" => "ucfirst"]) ?></p>
              </div>
            <?php } ?>
            <div class="line-separator"></div>
            <div class="count">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/web_icon.svg') ?>
              <span class="text-count-function"> <span class="text-success"><?= $module['count_have_function'] ?> </span>/ <?= $module['count_function'] ?></span>
            </div>
          </div>
          <div class="permission-list-table active">
            <table class="table table-custom mb-0">
              <thead>
                <tr>
                  <th class="thin-cell min-w-300px"><?= Ty::get('page_name', [], ["case" => "ucfirst"]) ?></th>
                  <th colspan="2"><?= Ty::get('default_permission', [], ["case" => "ucfirst"]) ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($module['list'] as $key => $module_list) { ?>
                  <?php foreach ($module_list['list'] as $page) { ?>
                    <?php if (isset($page['is_permission']) && $page['is_permission']) { ?>
                      <tr>
                        <td class=" font-SemiBold text-capitalize align-top"><?= $page['title'] ?></td>
                        <td class="scope_default_permission align-top">
                          <?php if ($is_edit_permission) { ?>
                            <div class="flex-wrap d-flex">
                              <div class="mr-40px">
                                <?php TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $page['key']  . ']', 'checked' => $page['checked'], 'onchange' => 'togglePermissionFn(this)', ($is_edit_permission) ? '' : 'disabled' => true], ['style' => '3', 'label' => Ty::get('view')]); ?>
                                <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $page['key']  . ']']); ?>
                              </div>
                            </div>
                          <?php } else { ?>
                            <div class="d-flex justify-content-start">
                              <?php if ($page['checked']) { ?>
                                <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                                <span class=" font-SemiBold text-capitalize ml-5px"><?= Ty::get('view') ?></span>
                              <?php } else { ?>
                                <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                                <span class=" font-SemiBold text-capitalize ml-5px text-muted"><?= Ty::get('view') ?></span>
                              <?php } ?>
                            </div>
                          <?php } ?>
                        </td>
                        <td class="scope_fn_permission">
                          <?php if (isset($page['function'])) { ?>
                            <?php foreach ($page['function'] as $function) { ?>
                              <?php
                              $label = str_replace('_', ' ', $function['name']);
                              $label = ucwords($label);
                              ?>
                              <?php if (isset($function['is_permission']) && $function['is_permission']) { ?>
                                <?php $is_disabled = ($page['checked']) ? '' : 'd-none'; ?>
                                <?php if ($is_edit_permission) { ?>
                                  <div class="mr-10px mb-10px <?= $is_disabled ?>">
                                    <?php
                                    TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $function['key']  . ']', 'checked' => $function['checked'], 'class' => 'green', 'onchange' => "sendDataAutoPermission(event)", ($is_edit_permission) ? '' : 'disabled' => true], ['style' => '3', 'label' =>  $label]);
                                    ?>
                                  </div>
                                <?php } else { ?>
                                  <div class="mr-10px mb-10px d-flex">
                                    <?php if ($function['checked']) { ?>
                                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                                      <span class=" font-SemiBold text-capitalize ml-5px"><?= $label ?></span>
                                    <?php } else { ?>
                                      <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                                      <span class=" font-SemiBold text-capitalize ml-5px text-muted"><?= $label ?></span>
                                    <?php } ?>
                                  </div>
                                <?php } ?>
                              <?php } ?>
                              <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $function['key']  . ']']); ?>
                            <?php } ?>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php } else { ?>
        <div class="permission-list">
          <div class="permission-list-title">
            <div class="arrow active" onclick="eventToggleShow(event)">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/double_arrow_down.svg') ?>
            </div>
            <div class="line-separator"></div>
            <div class="icon">
              <img src=" <?= W_Structure::getLinkURL('_framework/structure/image/icon/general/user.svg') ?>" alt="">
            </div>
            <div class="name text-capitalize">
              <?= $module['name'] ?>
            </div>
            <div class="line-separator"></div>
            <div class="count">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/web_icon.svg') ?>
              <span class="text-count-function"> <span class="text-success"><?= $module['count_have_function'] ?> </span>/ <?= $module['count_function'] ?></span>
            </div>
          </div>
          <div class="permission-list-table active">
            <table class="table table-custom mb-0">
              <thead>
                <tr>
                  <th class="thin-cell min-w-300px"><?= Ty::get('page_name') ?></th>
                  <th><?= Ty::get('default_permission') ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class=" font-SemiBold text-capitalize"><?= $module['list']['name'] ?></td>
                  <td>
                    <?php if ($is_edit_permission) { ?>
                      <?php if (isset($module['list']['is_permission']) &&  $module['list']['is_permission']) { ?>
                        <div class="flex-wrap d-flex">
                          <div class="mr-40px">
                            <?php TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $module['list']['key']  . ']', 'checked' => $module['list']['checked'], ($is_edit_permission) ? '' : 'disabled' => true], ['style' => '3', 'label' => Ty::get('view')]); ?>
                            <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $module['list']['key']  . ']']); ?>
                          </div>
                        </div>
                      <?php } ?>
                    <?php } else { ?>
                      <div class="d-flex justify-content-start">
                        <?php if ($module['list']['checked']) { ?>
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                          <span class=" font-SemiBold text-capitalize ml-5px"><?= Ty::get('view') ?></span>
                        <?php } else { ?>
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                          <span class=" font-SemiBold text-capitalize ml-5px text-muted"><?= Ty::get('view') ?></span>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      <?php } ?>
      <hr class="border-bg-5px m-0">
    <?php } ?>
  </form>
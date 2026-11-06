<?php
$options = [
  'permission' => $get_module['hidden_permission'],
  'auto_permission'  => $get_module['setting_permission'],
];
$permission = W_Permission::templateConfigModulePermission($options);
$count_module = count($permission);
$count_page = 0;
$count_have_page = 0;
$count_function = 0;
$count_have_function = 0;
foreach ($permission as $code => $list_page) {
  $count_page += $list_page['count_page'];
  $count_have_page += $list_page['count_have_page'];
  $count_function += $list_page['count_function'];
  $count_have_function += $list_page['count_have_function'];
}
?>
<form method="post" id="form_permission">
  <div class="w-card-title-bar border-bottom">
    <div class="d-flex flex-wrap align-items-center">
      <h3 class="title">Page access and Permission - <span class="text-primary"><?= $get_module['name'] ?></span></h3>
    </div>
    <?php if ($is_edit) { ?>
      <div class="btn-edit-container">
        <a href="?c=<?= $_GET['c'] ?>&page=permission" class="btn-edit cancel"><?= Ty::get('cancel') ?></a>
        <button type="submit" class="btn-edit save" name="submit_update_permission_module"> <?= Ty::get('save', [], ["case" => "ucfirst"]) ?></button>
      </div>
    <?php } else { ?>
      <div class="btn-edit-container">
        <a href="?c=<?= $_GET['c'] ?>&page=permission&is_edit=1" class="btn-edit edit">
          <?= Ty::get('edit_data') ?>
        </a>
      </div>
    <?php } ?>
  </div>
  <div class="premission-title">
    <div class="title">
      <p class=" font-Bold font-16px mb-0">Program module : <span class=" text-primary"><?= $count_module ?></span> </p>
      <p class=" font-15px font-Medium text-secondary">
        กำหนด Program page ที่ User type นี้สามารถเข้าถึงได้และสิทธิการใช้งาน
      </p>
    </div>
    <div class="box-count">
      <?php if ($is_edit) { ?>
        <div class="module-check-all-box">
          <p class="check-all text-danger" onclick="toggleAllModulePermission(false)">Clear all permission</p>
          <p class="check-all text-primary" onclick="toggleAllModulePermission(true)">Check all permission</p>
        </div>
      <?php } ?>
      <div class="page-access">
        <span>Page access</span>
        <span class="count"><span class="text-primary"> <?= $count_have_page ?> </span>/ <?= $count_page ?></span>
      </div>
      <div class="default-permission">
        <span>Default Permission</span>
        <span class="count"><span class="text-success"> <?= $count_have_function ?> </span>/ <?= $count_function ?></span>
      </div>
    </div>
  </div>
  <hr class="border-bg-5px m-0">
  <?php TiwForm::normal('hidden', $get_module['id'], ['name' =>  'id']); ?>
  <?php foreach ($permission as $code => $module) { ?>
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
          <?php if ($is_edit) { ?>
            <div class="module-check-all-box">
              <p class="check-all text-danger" onclick="clearAllPermission(this)">Clear all permission</p>
              <p class="check-all text-primary" onclick="CheckAllPermission(this)">Check all permission</p>
            </div>
          <?php } ?>
          <div class="line-separator"></div>
          <div class="count">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/web_icon.svg') ?>
            <span class="text-count-page"> <span class="text-primary"><?= $module['count_have_page'] ?></span>/ <?= $module['count_page'] ?></span>
            <span class="text-count-function"> <span class="text-success"><?= $module['count_have_function'] ?> </span>/ <?= $module['count_function'] ?></span>
          </div>
        </div>
        <div class="permission-list-table active">
          <table class="table table-custom mb-0">
            <thead>
              <tr>
                <th class="thin-cell">Page access</th>
                <th class="thin-cell min-w-300px">Page name</th>
                <th colspan="2">Default permission</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($module['list'] as $key => $module_list) { ?>
                <?php foreach ($module_list['list'] as $page) { ?>
                  <?php $ck_fn_disabled = ($page['checked'] && $is_edit) ? '' : 'disabled'; ?>
                  <tr>
                    <td>
                      <?php if ($is_edit) { ?>
                        <div class="d-flex justify-content-center scope_access_check">
                          <?php TiwForm::normal('checkbox', 1, ['name' =>  'check_permission[' . $page['key']  . ']', 'checked' => $page['checked'], 'onchange' => 'togglePermissionView(this)', ($is_edit) ? '' : 'disabled' => 'true'], ['style' => '1', 'is_on_off' => true]); ?>
                          <?php TiwForm::normal('hidden', 0, ['name' =>  'permission[' . $page['key']  . ']']); ?>
                        </div>
                      <?php } else { ?>
                        <div class="d-flex justify-content-center">
                          <?php if ($page['checked']) { ?>
                            <?= W_Structure::getfile('_framework/resources/setting/assets/icons/check_icon.svg') ?>
                          <?php } else { ?>
                            <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                          <?php } ?>
                        </div>
                      <?php } ?>
                    </td>
                    <td class=" font-SemiBold text-capitalize"><?= $page['title'] ?></td>
                    <td>
                      <?php if ($is_edit) { ?>
                        <div class="flex-wrap d-flex scope_default_permission">
                          <div class="mr-40px">
                            <?php TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $page['key']  . ']', 'checked' => $page['auto_checked'], $ck_fn_disabled => "true"], ['style' => '3', 'label' => 'View']); ?>
                            <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $page['key']  . ']']); ?>
                          </div>
                        </div>
                      <?php } else { ?>
                        <div class="d-flex justify-content-start">
                          <?php if ($page['auto_checked']) { ?>
                            <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
                            <span class=" font-SemiBold text-capitalize ml-5px">View</span>
                          <?php } else { ?>
                            <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                            <span class=" font-SemiBold text-capitalize ml-5px text-muted">View</span>
                          <?php } ?>
                        </div>
                      <?php } ?>
                    </td>
                    <td class="scope_default_permission">
                      <?php if (isset($page['function'])) { ?>
                        <?php foreach ($page['function'] as $function) { ?>
                          <?php
                          $label = str_replace('_', ' ', $function['name']);
                          $label = ucwords($label);
                          ?>
                          <?php if ($is_edit) { ?>
                            <div class="mr-10px mb-10px">
                              <?php
                              TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $function['key']  . ']', 'checked' => $function['checked'], 'class' => 'green', $ck_fn_disabled => 'true'], ['style' => '3', 'label' =>  $label]);
                              ?>
                              <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $function['key']  . ']']); ?>
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
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php } else { ?>
      <?php $ck_fn_disabled = ($module['list']['checked']  && $is_edit) ? '' : 'disabled'; ?>
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
            <span class="text-count-page"> <span class="text-primary"><?= $module['count_have_page'] ?></span>/ <?= $module['count_page'] ?></span>
            <span class="text-count-function"> <span class="text-success"><?= $module['count_have_function'] ?> </span>/ <?= $module['count_function'] ?></span>
          </div>
        </div>
        <div class="permission-list-table active">
          <table class="table table-custom mb-0">
            <thead>
              <tr>
                <th class="thin-cell">Page access</th>
                <th class="thin-cell min-w-300px">Page name</th>
                <th>Default permission</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <?php if ($is_edit) { ?>
                    <div class="d-flex justify-content-center scope_access_check">
                      <?php TiwForm::normal('checkbox', 1, ['name' => 'check_permission[' . $module['list']['key']  . ']', 'checked' => $module['list']['checked'], 'onchange' => 'togglePermissionView(this)'], ['style' => '1', 'is_on_off' => true]); ?>
                      <?php TiwForm::normal('hidden', 0, ['name' =>  'permission[' . $module['list']['key']   . ']']); ?>
                    </div>
                  <?php } else { ?>
                    <div class="d-flex justify-content-center">
                      <?php if ($module['list']['checked']) { ?>
                        <?= W_Structure::getfile('_framework/resources/setting/assets/icons/check_icon.svg') ?>
                      <?php } else { ?>
                        <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                      <?php } ?>
                    </div>
                  <?php } ?>
                </td>
                <td class=" font-SemiBold text-capitalize"><?= $module['list']['name'] ?></td>
                <td>
                  <div class="flex-wrap scope_default_permission">
                    <?php if ($is_edit) { ?>
                      <div class=" mr-40px">
                        <?php TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $module['list']['key']  . ']', 'checked' => $module['list']['auto_checked'], $ck_fn_disabled => "true"], ['style' => '3', 'label' => 'View']); ?>
                        <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $module['list']['key']  . ']']); ?>
                      </div>
                    <?php } else { ?>
                      <div class="d-flex justify-content-start">
                        <?php if ($module['list']['auto_checked']) { ?>
                          <?= W_Structure::getfile('_framework/resources/setting/assets/icons/check_icon.svg') ?>
                          <span class=" font-SemiBold text-capitalize ml-5px">View</span>
                        <?php } else { ?>
                          <?= W_Structure::getfile('_framework/resources/module/assets/icon/close_icon.svg') ?>
                          <span class=" font-SemiBold text-capitalize ml-5px text-muted">View</span>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
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

<script>
  function eventToggleShow(e) {
    let target = e.target;
    let parent = target.closest('.permission-list');
    let table = parent.querySelector('.permission-list-table');
    let arrow = parent.querySelector('.arrow');
    if (arrow.classList.contains('active')) {
      arrow.classList.remove('active');
      table.classList.remove('active');
    } else {
      arrow.classList.add('active');
      table.classList.add('active');
    }
  }

  function togglePermissionView(t) {
    var check_view_ele = t.closest('tr').querySelectorAll('.scope_default_permission');
    var bool = (t.checked) ? true : false;
    check_view_ele.forEach(function(item) {
      var item_lists = item.querySelectorAll('input[type="checkbox"]')
      toggleCheckDefaultPermission(item_lists, bool)
    });
  }

  function toggleCheckDefaultPermission(check_view_ele, bool) {
    check_view_ele.forEach(function(item) {
      item.checked = bool;
      item.disabled = !bool;
    });
  }

  function sendData(e) {
    let url = 'ajax/ajax_permission.php';
    wx.formData(url, 'form_permission', function(data) {
      var result = JSON.parse(data);
      w.notification(result.response_status ? 'success' : 'error', result.response_message);
      window.location.href = '?c=<?= $_GET['c'] ?>&page=permission'
    });
  }

  function clearAllPermission(t) {
    var checkboxs = t.closest('.permission-list').querySelectorAll('.scope_access_check');
    checkboxs.forEach(function(item) {
      var ele = item.querySelector('input[type="checkbox"]');
      ele.checked = false
      togglePermissionView(ele);
    });
  }

  function CheckAllPermission(t) {
    var checkboxs = t.closest('.permission-list').querySelectorAll('.scope_access_check');
    checkboxs.forEach(function(item) {
      var ele = item.querySelector('input[type="checkbox"]');
      ele.checked = true
      togglePermissionView(ele);
    });
  }

  function toggleAllModulePermission(bool) {
    var permission_lists = document.querySelectorAll('.permission-list');
    permission_lists.forEach(function(item) {
      var access_check = item.querySelectorAll('.scope_access_check')
      access_check.forEach(function(item) {
        var ele = item.querySelector('input[type="checkbox"]');
        ele.checked = bool;
        togglePermissionView(ele);
      });
    });
  }
</script>
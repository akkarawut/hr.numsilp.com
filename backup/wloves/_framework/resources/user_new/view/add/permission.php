<?php
$setting_permission = $module_setup['json_tab_permission'];

if (!$setting_permission) {
  $setting_permission = [
    'is_show_add_page' => 0,
    'is_show_add_profile' => 0,
  ];
}


$options = [
  'permission' =>  $module_setup['setting_permission']
];
$permission = W_Permission::templateConfigUserPermission($options);
$count_function = 0;
$count_have_function = 0;
foreach ($permission as $key_code => $list_page) {
  $count_function += $list_page['count_function'];
  $count_have_function += $list_page['count_have_function'];
}
?>
<?php if ($setting_permission['is_show_add_page']) : ?>
  <?php TiwForm::normal('hidden', 1, ['name' => 'is_permission']); ?>
  <hr class=" border-color-bg-5px m-0">
  <div class="w-card-title-bar border-bottom">
    <div class="d-flex flex-wrap align-items-center">
      <h3 class="title"><?= Ty::get('account_rights_access_system') ?></h3>
    </div>
    <div class="funtion-count">
      <span class=" font-SemiBold font-14px"><?= Ty::get('permission') ?></span>
      <span class="count"><span class="text-success"> <?= $count_have_function ?> </span>/ <?= $count_function ?></span>
    </div>
  </div>
  <?php foreach ($permission as $key_code => $module) { ?>
    <?php if ($module['module'] != 'User') { ?>
      <div class="permission-list">
        <div class="permission-list-title">
          <div class="arrow " onclick="eventToggleShow(event)">
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
          <div class="line-separator"></div>
          <div class="count">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/web_icon.svg') ?>
            <span class="text-count-function"> <span class="text-success"><?= $module['count_have_function'] ?> </span>/ <?= $module['count_function'] ?></span>
          </div>
        </div>
        <div class="permission-list-table ">
          <table class="table table-custom mb-0">
            <thead>
              <tr>
                <th class="thin-cell min-w-300px"><?= Ty::get('page_name') ?></th>
                <th><?= Ty::get('default_permission') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($module['list'] as $key => $module_list) { ?>
                <?php foreach ($module_list['list'] as $page) { ?>
                  <tr>
                    <td class=" font-SemiBold text-capitalize"><?= $page['title'] ?></td>
                    <td>
                      <div class="flex-wrap <?= $page['checked'] ? 'd-flex ' : 'd-flex' ?>">
                        <div class="mr-40px">
                          <?php TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $page['key']  . ']', 'checked' => $page['checked']], ['style' => '3', 'label' => Ty::get('view')]); ?>
                          <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $page['key']  . ']']); ?>
                        </div>
                        <?php if (isset($page['function'])) { ?>
                          <?php foreach ($page['function'] as $function) { ?>
                            <div class="mr-10px">
                              <?php
                              $label = str_replace('_', ' ', $function['name']);
                              $label = ucwords($label);
                              TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $function['key']  . ']', 'checked' => $function['checked'], 'class' => 'green'], ['style' => '3', 'label' =>  $label]);
                              ?>
                              <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $function['key']  . ']']); ?>
                            </div>
                          <?php } ?>
                        <?php } ?>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php } else { ?>
      <div class="permission-list">
        <div class="permission-list-title">
          <div class="arrow " onclick="eventToggleShow(event)">
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
        <div class="permission-list-table ">
          <table class="table table-custom mb-0">
            <thead>
              <tr>
                <th class="thin-cell min-w-300px"><?= Ty::get('page_name')  ?></th>
                <th><?= Ty::get('default_permission')  ?></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class=" font-SemiBold text-capitalize"><?= $module['list']['name'] ?></td>
                <td>
                  <div class="flex-wrap <?= $module['list']['checked'] ? 'd-flex ' : 'd-flex' ?>">
                    <div class="mr-40px">
                      <?php TiwForm::normal('checkbox', 1, ['name' => 'check_function[' . $module['list']['key']  . ']', 'checked' => $module['list']['checked']], ['style' => '3', 'label' => Ty::get('view')]); ?>
                      <?php TiwForm::normal('hidden', 0, ['name' =>  'function[' . $module['list']['key']  . ']']); ?>
                    </div>
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
<?php else : ?>
  <?php TiwForm::normal('hidden', 0, ['name' => 'is_permission']); ?>
<?php endif; ?>
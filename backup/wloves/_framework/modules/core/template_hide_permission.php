<div class="permission-container mx--15px">
  <div class="permission-module-top-action">
    <?php if ($is_edit) { ?>
      <div class="permission-module-checked">
        <div class="permission-check-list text-primary __check_all_permission_event">Check All permission</div>
        <div class="permission-check-list text-danger _clear_all_permission_event">Clear All permission</div>
      </div>
    <?php } else { ?>
      <div></div>
    <?php } ?>
    <div class="permission-top-action pt-10px">
      <div class="t1">View:</div>
      <div class="t-list __show_permission_box_all"><?= Ty::get('show_all') ?></div>
      <div class="t-list __hide_permission_box_all"><?= Ty::get('hide_all') ?></div>
    </div>
  </div>

  <?php
  foreach ($permission_array as $module) { //all module
    if ($module['list']) {
      $module_code = $module['code'];
  ?>
      <div class="permission-module-group">
        <div class="permission-module-title">
          <div class="permission-module-name">
            <span class="text-secondary"><?= Ty::get('module') ?>: <?= $module['module'] ?></span> |
            <span class="text-primary"><?= $module['name'] ?></span> | <span class="text-danger __amount_checked_permission_per_module">0</span><span class="text-secondary __amount_all_permission_per_module">/0</span>
          </div>
          <div class="permission-module-action">
            <div class="btn-action-group">
              <?php if ($is_edit) { ?>
                <div class="btn-action-list text-primary __check_permission_group_event">Check All</div>
                <div class="btn-action-list text-danger __clear_permission_group_event">Clear All</div>
              <?php } ?>

            </div>
            <div class="permission-hide-module-detail">
              <?= Ty::get('hide', [], ["case" => "ucfirst"]) ?> <?= file_get_contents($prefix . 'structure/image/icon/general/arrow.svg') ?>
            </div>
          </div>
        </div>
        <div class="table-permission">
          <table>
            <?php
            if ($module['module'] != 'User') {
              foreach ($module['list'] as $page_group) { //sub module
                if ($page_group['list']) {
            ?>
                  <thead>
                    <tr>
                      <th class="w-300px min-w-200px"><?= $page_group['title'] ?></th>
                      <th class="thin-cell"> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($page_group['list'] as $page) { //menu page
                    ?>
                      <tr>
                        <td class="pt-5px">
                          <div class="d-flex align-items-center">
                            <span class="mt-5px font-SemiBold"><?= $page['title'] ?></span>
                          </div>
                        </td>
                        <td class="thin-cell">
                          <div class="d-flex justify-content-center font-SemiBold">
                            <?php
                            TiwForm::normal('hidden', 0, ['name' => 'permission_all[' . $page['key'] . ']']);
                            self::randerCheckboxHidePermission(['name' => $page['key'], 'is_edit' => $is_edit, 'checked' => $page['checked']]);
                            ?>
                          </div>
                        </td>
                      </tr>
                    <?php
                    } //foreach page
                    ?>
                  </tbody>
              <?php
                }
              }
            } else {
              ?>
              <thead>
                <tr>
                  <th class="w-300px min-w-200px"><?= $module['list']['name'] ?></th>
                  <th class="thin-cell"> </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="pt-5px">
                    <div class="d-flex align-items-center">
                      <span class="mt-5px font-SemiBold"><?= $module['list']['title'] ?></span>
                    </div>
                  </td>
                  <td class="thin-cell">
                    <div class="d-flex justify-content-center font-SemiBold">
                      <?php
                      TiwForm::normal('hidden', 0, ['name' => 'permission_all[' . $module['list']['key']  . ']']);
                      self::randerCheckboxHidePermission(['name' => $module['list']['key'], 'is_edit' => $is_edit, 'checked' => $module['list']['checked']]);
                      ?>
                    </div>
                  </td>
                </tr>
              </tbody>
            <?php
            }
            ?>
          </table>
        </div>
      </div>
  <?php
    }
  }
  ?>
</div>
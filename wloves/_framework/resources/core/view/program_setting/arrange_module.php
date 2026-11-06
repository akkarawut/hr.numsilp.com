<?php

if ($_POST) {
  if (isset($_POST['submit_arrange_module'])) {
    $result = Module::swapModule($_POST['current_id'], $_POST['move_to_id']);
  } else if (isset($_POST['submit_user_management_position'])) {
    $result = Module::setUserMngPostion($_POST['position']);
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

function templateTrUserManagement($position)
{
  echo '<tr>
          <td class="thin-cell text-center">
            <span class="font-14px text-primary font-SemiBold">FIXED</span>
          </td>
          <td>USER MANAGEMENT</td>
          <td class="thin-cell">
            <div class="btn-group dot3">
              <button type="button" class="form-btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ' . W_Structure::getfile('_framework/structure/image/icon/general/more.svg') . '
              </button>
              <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm">
                <form action="" method="post">
                  <input type="hidden" name="position" value="' . ($position == 'top' ? 'bottom' : 'top') . '">
                  <button type="submit" class="btn dropdown-item justify-content-start" name="submit_user_management_position">
                    <span class="ml-5px">' . ($position == 'top' ? 'Bring to Bottom' : 'Bring to Top') . '</span>
                  </button>
                </form>
              </div>
            </div>
          </td>
        </tr>';
}

$file_menu_list = W_Config::$menu_list;
?>
<div class="col-lg-10">
  <div class="editable-card core-new pb-15px mb-10px" style="min-height: unset;">
    <div class="title-detail px-15px py-10px">
      <h3 class="text-uppercase font-SemiBold font-16px text-info mb-0">Arrange Module Position</h3>
      <p class="font-14px font-Regular mb-0">Manage module position on menu</p>
    </div>
    <div class="table-responsive">
      <table class="table-bg-card-back">
        <thead>
          <tr>
            <th>Arrange</th>
            <th>Module</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="thin-cell text-center">
              <span class="font-14px text-primary font-SemiBold">FIXED</span>
            </td>
            <td>CORE</td>
            <td></td>
          </tr>
          <?php
          if ($user_management_position == 'top') {
            templateTrUserManagement('top');
          }
          ?>

          <?php
          foreach ($other_modules as $key => $other_module) {
            $arrange_down = ($key == (count($other_modules) - 1)) ? false : true;
            $arrange_up = ($key == 0) ? false : true;
            $br5px = ($key == 0 || $key == (count($other_modules) - 1)) ? 'br-5px' : '';
            $title_menu = [];
            if (isset($file_menu_list[$other_module['module']]['menu_items'])) {
              foreach ($file_menu_list[$other_module['module']]['menu_items'] as $menu_items) {
                array_push($title_menu, $menu_items['title']);
              }
            }
          ?>
            <tr>
              <td class="thin-cell text-center">
                <div class="arrange-btn-group">
                  <?php if ($arrange_down) { ?>
                    <form action="" method="post">
                      <?php
                      TiwForm::normal('hidden', $other_module['id'], ['name' => 'current_id']);
                      TiwForm::normal('hidden', $other_modules[$key + 1]['id'], ['name' => 'move_to_id']);
                      ?>
                      <button type="submit" name="submit_arrange_module" class="border-0 arrange-down <?= $br5px ?>">
                        <?= W_Structure::getfile('_framework/structure/image/icon/general/down-arrow.svg') ?>
                      </button>
                    </form>
                  <?php } ?>
                  <?php if ($arrange_up) { ?>
                    <form action="" method="post">
                      <?php
                      TiwForm::normal('hidden', $other_module['id'], ['name' => 'current_id']);
                      TiwForm::normal('hidden', $other_modules[$key - 1]['id'], ['name' => 'move_to_id']);
                      ?>
                      <button type="submit" name="submit_arrange_module" class="border-0 arrange-up <?= $br5px ?>">
                        <?= W_Structure::getfile('_framework/structure/image/icon/general/up-arrow.svg') ?>
                      </button>
                    </form>
                  <?php } ?>
                </div>
              </td>
              <td class="text-uppercase">
                <span class=" font-SemiBold"> <?= $other_module['module'] ?> </span> | <?= $other_module['name'] ?> | <span class="text-capitalize"> <?= $title_menu ? implode(', ', $title_menu) : '';  ?></span>
              </td>
              <td></td>
            </tr>
          <?php
          }

          if ($user_management_position == 'bottom') {
            templateTrUserManagement('bottom');
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="editable-card core-new pb-15px" style="min-height: unset;">
    <div class="title-detail px-15px py-10px">
      <h3 class="text-uppercase font-SemiBold font-16px text-info mb-0">Arrange user Module inside user management</h3>
      <p class="font-14px font-Regular mb-0">Manage module position inside user management module.</p>
    </div>
    <div class="table-responsive">
      <table class="table-bg-card-back">
        <thead>
          <tr>
            <th>Arrange</th>
            <th>Module</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($user_modules) {
            foreach ($user_modules as $key => $user_module) {
              $arrange_down = ($key == (count($user_modules) - 1)) ? false : true;
              $arrange_up = ($key == 0) ? false : true;
              $br5px = ($key == 0 || $key == (count($user_modules) - 1)) ? 'br-5px' : '';
          ?>
              <tr>
                <td class="thin-cell text-center">
                  <div class="arrange-btn-group">
                    <?php if ($arrange_down) { ?>
                      <form action="" method="post">
                        <?php
                        TiwForm::normal('hidden', $other_module['id'], ['name' => 'current_id']);
                        TiwForm::normal('hidden', $user_modules[$key + 1]['id'], ['name' => 'move_to_id']);
                        ?>
                        <button type="submit" name="submit_arrange_module" class="border-0 arrange-down <?= $br5px ?>">
                          <?= W_Structure::getfile('_framework/structure/image/icon/general/down-arrow.svg') ?>
                        </button>
                      </form>
                    <?php } ?>
                    <?php if ($arrange_up) { ?>
                      <form action="" method="post">
                        <?php
                        TiwForm::normal('hidden', $other_module['id'], ['name' => 'current_id']);
                        TiwForm::normal('hidden', $user_modules[$key - 1]['id'], ['name' => 'move_to_id']);
                        ?>
                        <button type="submit" name="submit_arrange_module" class="border-0 arrange-up <?= $br5px ?>">
                          <?= W_Structure::getfile('_framework/structure/image/icon/general/up-arrow.svg') ?>
                        </button>
                      </form>
                    <?php } ?>
                  </div>
                </td>
                <td class="text-uppercase"><?= $user_module['module'] ?> | <?= $user_module['name'] ?></td>
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
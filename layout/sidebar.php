<div id="hr-sidebar" data-collapse="false">
  <ul class="main-sidebar">
    <?php foreach ($init_data['menu'] as $key => $menu) { ?>
      <li>
        <?php if ($menu['title']) { ?>
          <div class="title"><?= $menu['title'] ?></div>
        <?php } ?>
        <ul class="sub-sidebar">
          <?php foreach ($menu['sub_menu'] as $key_sub => $submenu) { ?>
            <li class="menu <?= $submenu['name'] == $name_page ? 'active' : '' ?>">
              <a href="<?= $submenu['path_url'] ?>" class="menu-link">
                <div class="_icon">
                  <?= file_get_contents('assets/icons/' . $submenu['icon']) ?>
                </div>
                <div class="_name">
                  <?= $submenu['name'] ?>
                </div>
              </a>
            </li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
    <li>
      <div class="title">บัญชีของฉัน</div>
      <ul class="sub-sidebar">
        <li class="menu <?= $name_page == 'ข้อมูลส่วนตัว' ? 'active' : '' ?>">
          <a href="employee_detail.php?id=<?= $current_user_id ?>&mode=my_profile" class="menu-link">
            <div class="_icon">
              <?= file_get_contents('assets/icons/user.svg') ?>
            </div>
            <div class="_name">
              ข้อมูลส่วนตัว
            </div>
          </a>
        </li>
        <li class="menu <?= $name_page == 'การแจ้งเตือน' ? 'active' : '' ?>">
          <a href="notification.php" class="menu-link">
            <div class="_icon">
              <?= file_get_contents('assets/icons/notification .svg') ?>
            </div>
            <div class="_name">
              การแจ้งเตือน
            </div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>
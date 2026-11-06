<nav class="main-navbar">
  <div class="navbar-container">
    <div class="d-flex align-items-center">
      <div id="nav-hamburger" class="nav-hamburger me-2">
        <span class="hamburger"></span>
        <span class="hamburger"></span>
        <span class="hamburger"></span>
      </div>
      <div class="nav-logo">
        <img src="assets/images/logo.png" alt="">
      </div>
    </div>
    <div class="nav-menu">
      <a href="notification.php" class="nav-menu-link">
        <div class="btn-icon notification <?= $init_data['is_noti'] ? 'has-noti' : '' ?>">
          <?= file_get_contents('assets/icons/notification.svg') ?>
        </div>
      </a>
      <ul class="navbar-menu">
        <li class="main--dropdown dropdown-c">
          <div class="menu-link" type="button" id="dropdown_filter" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" aria-expanded="false">

            <?php if (isset($user_info['image_path']) && $user_info['image_path'] && strpos($user_info['image_path'], '/placeholder/') === false) { ?>
              <span class="avatar-menu mx-3">
                <img src="<?= $user_info['image_path'] ?>" alt="my-avatar">
              </span>
            <?php } else { ?>
              <div class="profile-img-letter --sm mx-3">
                <?= $user_info['full_name'] == '' ? 'A' : mb_substr($user_info['full_name'], 0, 1) ?>
              </div>
            <?php } ?>


            <span class="name">
              <?= $user_info['full_name'] ?> <br>
              <small class="text-grey"> <?= $user_info['username'] ?></small>
            </span>
            <span class="arrow-dropdown"></span>
          </div>

          <ul class="dropdown-menu dropdown-menu-end min-w-300px max-w-500px w-100 bg-c-1" aria-labelledby="dropdown_filter">
            <div class="text-center mx-auto pt-15px">
              <small><?= $user_info['username'] ?></small>
              <div class="px-10px py-10px">
                <?php if (isset($user_info['image_path']) && $user_info['image_path'] && strpos($user_info['image_path'], '/placeholder/') === false) { ?>
                  <div class="profile-img-letter --img mx-auto">
                    <img src="<?= $user_info['image_path'] ?>" alt="my-avatar">
                  </div>
                <?php } else { ?>
                  <div class="profile-img-letter mx-auto">
                    <?= $user_info['full_name'] == '' ? 'A' : mb_substr($user_info['full_name'], 0, 1) ?>
                  </div>
                <?php } ?>
              </div>
              <small class="fw-semibold font-20px"> <?= $user_info['full_name'] ?></small>
            </div>

            <div class="btn-action-dropdown my-20px">
              <a href="employee_detail.php?id=<?= $current_user_id ?>&mode=my_profile" class="btn-link-dropdown">
                <div class="_icon me-2">
                  <?= file_get_contents('assets/icons/my_profile.svg') ?>
                </div>
                <div class="fw-medium">
                  ข้อมูลส่วนตัว
                </div>
              </a>
              <a href="logout.php" class="btn-link-dropdown">
                <div class="_icon me-2">
                  <?= file_get_contents('assets/icons/logout.svg') ?>
                </div>
                <div class="fw-medium">
                  ออกจากระบบ
                </div>
              </a>
            </div>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php
$count_user =  User::countUser($code);
?>

<div class="container-fluid h-md-100">
  <div class="row h-md-100">
    <?php if ($id) { ?>
      <div class="col-lg-12 p-0">
        <div class="w-card h-auto">
          <div class="w-card-title-edit border-bottom">
            <a href="index.php?c=<?= $code ?>" class=" text-secondary"><?= Ty::get('user_info', [], ["case" => "ucfirst"]) ?></a> > <span class="text-primary"><?= $get_user['username'] ?></span></span>
          </div>
        </div>
        <?php
        if ($module_setup['create_tab']) {
          include_once 'view/multiple.php';
        } else {
          include_once 'view/single.php';
        }
        ?>
      </div>
    <?php } else { ?>
      <div class="col-lg-12 p-0">
        <div class="w-card ">
          <div class="w-card-title-bar ">
            <div class="d-flex flex-wrap align-items-center">
              <h3 class="title"><?= $module_setup['name'] ?> <span class="font-15px text-secondary font-Medium"><?= $count_user ?> <?= Ty::get('list') ?></span></h3>
            </div>
            <div class="btn-edit-container min-w-80px">
              <?php if ($module_setup['module_addnew_user']) { ?>
                <button class="btn-edit save w-150px" <?= Tiwdal::register('modal_add_user', []); ?>>+ <?= Ty::get('add_new_user') ?></button>
              <?php } ?>
            </div>
          </div>
          <div id="user_list" class="container-pagination no-border-radius" <?= Homepagify::createHomepagify('user_list', '?c=' . $code . '&id=' . $id, '',  Ty::get('list')) ?>>
            <div class="table-responsive">
              <table class="table table-sort table-search ">
                <thead>
                  <tr>
                    <th class="thin-cell"></th>
                    <th nowrap data-sort="username" data-filter="<?= Homepagify::dataFilter('username', 'text') ?>"><?= Ty::get('user_acc') ?></th>
                    <!-- <th nowrap data-sort="fullname" data-filter="<?= Homepagify::dataFilter('fullname', 'text') ?>">Full name</th> -->
                    <th></th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
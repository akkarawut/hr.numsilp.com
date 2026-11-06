<?php
$_PAGE['permission'] = ['core', 'core_template', 'core_template_2'];

require_once '../../_framework/import.php';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php include_once 'structure/layout/header-default.php'; ?>

  <div class="row">
    <div class="col-lg-2">
      <div class="w-loves-card mt-10px">
        <div class="w-loves-card-header">
          <h3>Product List</h3>
          <p> List of sub categories you've added. </p>
        </div>
        <div class="menu-sub-category">
          <h3>category</h3>
          <a href="?c" class="sub-topic-items filter-menu-items active">
            <span class="sub-topic-title">
              name
            </span>
          </a>
        </div>
      </div>
    </div>
    <div class="col-lg-10">
      <div class="w-loves-card mt-10px">
      </div>
    </div>
  </div>

  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>
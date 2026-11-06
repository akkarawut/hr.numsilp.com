<?php
$_PAGE['permission'] = ['core', 'core_template', 'core_template_1'];

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
    <div class="col-12">
      <div class="topic-page">
        <div class="topic-page-name">
          <h3>topic </h3>
          <span>Sub topic</span>
        </div>
        <div>
          <button class="btn btn-primary"> button </button>
        </div>
      </div>

      <div class="w-loves-card">
        <div class="w-loves-card-header">
          <h3>Product List</h3>
          <p> List of sub categories you've added. </p>
        </div>
      </div>
    </div>
  </div>

  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>
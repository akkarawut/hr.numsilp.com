<?php
$_PAGE['permission'] = ['core', 'core_template', 'toast'];
require_once '../../_framework/import.php';
W_Structure::loadModules();

if (isset($_POST['submit'])) {
  W_Main::notification('test', 'success');
}
?>

<!DOCTYPE html>

<head>
  <?php
  W_Structure::loadMeta('../../');
  ?>
</head>

<body>
  <?php include_once 'structure/layout/header-default.php'; ?>

  <div class="row">
    <div class="col-12">
      <div class="topic-page">
        <div class="topic-page-name">
          <h3>TOAST </h3>
          <span>Ex. Toast</span>
        </div>
      </div>
      <div class="w-loves-card">
        <div class="w-loves-card-header">
          <h3>Toast</h3>
        </div>
        <form method="post">
          <input type="hidden" name="submit">
          <button type="submit" class="btn btn-primary">Show Toast</button>
        </form>
      </div>
    </div>
  </div>

  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>
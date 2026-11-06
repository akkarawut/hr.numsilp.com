<?php
$_PAGE['permission'] = ['core', 'core_template', 'core_template_4'];
require_once '../../_framework/import.php';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php include_once 'structure/layout/header-default.php'; ?>

  <h1>Code Here!</h1>

  <?php
  include_once 'structure/layout/footer.php';
  W_Structure::loadFooter('../../');
  ?>
</body>

</html>
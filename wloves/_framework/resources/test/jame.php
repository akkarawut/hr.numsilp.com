<?php
$_PAGE['permission']  = ['landing', '', ''];
$prefix = '../../../';
require $prefix . '_framework/import.php';
W_Structure::loadModules(['jform']);
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta($prefix);
  ?>
</head>

<body>
  <?php include '../layout/header-default.php'; ?>
  <?php
  include '../layout/footer.php';
  W_Structure::loadFooter($prefix);
  ?>

</body>

</html>
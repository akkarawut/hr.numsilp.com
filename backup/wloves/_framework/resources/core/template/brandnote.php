<?php
$_PAGE['permission'] = ['core', 'core_template', 'brandnote'];
require_once '../../_framework/import.php';
W_Structure::loadModules(['brandnote']);
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

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
          <h3>HTML Editor </h3>
          <span>Ex. html Editor</span>
        </div>
        <div>
          <button class="btn btn-table-custom btn-secondary toggle-modal" data-target="#add-timeline"> show </button>
        </div>
      </div>
      <div class="w-loves-card">
        <div class="w-loves-card-header">
          <h3>Editor (Summernote)</h3>
        </div>
        <?php Brandnote::startNote('id', 'name', '', 500, ''); ?>
      </div>
    </div>
  </div>

  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>
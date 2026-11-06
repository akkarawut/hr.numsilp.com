<div class="w-loves-card border-radius-0 p-0 mb-10px">
  <div id="file_server_log" class="container-pagination no-border-radius" <?= Homepagify::createHomepagify('file_server_log', '?', '', 'Log history') ?>>
    <div class="table-responsive">
      <table class="table table-sort table-search">
        <thead>
          <tr>
            <th nowrap data-sort="datetime" data-filter="<?= Homepagify::dataFilter('datetime', 'date') ?>">Date modify</th>
            <th nowrap data-sort="username" data-filter="<?= Homepagify::dataFilter('username', 'text') ?>">By</th>
            <th nowrap data-sort="action" data-filter="<?= Homepagify::dataFilter('action', 'text') ?>">Action</th>
            <th nowrap data-sort="type" data-filter="<?= Homepagify::dataFilter('type', 'text') ?>">Compare</th>
            <th nowrap data-sort="table_name" data-filter="<?= Homepagify::dataFilter('table_name', 'text') ?>">Table</th>
            <th nowrap data-sort="column_name" data-filter="<?= Homepagify::dataFilter('column_name', 'text') ?>">Column name</th>
            <th nowrap data-sort="column_type" data-filter="<?= Homepagify::dataFilter('column_type', 'text') ?>">Type</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
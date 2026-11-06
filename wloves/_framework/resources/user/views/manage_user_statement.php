<div class="row">
  <div class="col-lg-12">
    <div class="title-detail">
      <div>
        <h3><?= Itlanguage::translate('รายการทางการเงิน'); ?> </h3>
        <p><?= Itlanguage::translate('รายการฝากและถอนเงินของลูกค้า'); ?></p>
      </div>
    </div>
    <div id="statement" class="container-pagination" <?= Homepagify::createHomepagify('statement', '?c=' . $_GET['c'] . '&user_id=' . $user_id) ?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th nowrap><?= Itlanguage::translate('วันที่'); ?></th>
              <th nowrap><?= Itlanguage::translate('รายการ'); ?></th>
              <th nowrap class="text-right"><?= Itlanguage::translate('จำนวน'); ?></th>
              <th nowrap class="text-right"><?= Itlanguage::translate('คงเหลือ'); ?></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
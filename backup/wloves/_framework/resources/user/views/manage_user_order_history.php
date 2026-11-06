<div class="row">
  <div class="col-lg-12">
    <div class="title-detail">
      <div>
        <h3><?= Itlanguage::translate('ORDER HISTORY'); ?> </h3>
        <p><?= Itlanguage::translate('Customer order histories.'); ?></p>
      </div>
    </div>
    <div class="w-100">
      <div class="row">
        <div class="col-12 p-0">
          <div id="order_history" class="container-pagination" <?= Homepagify::createHomepagify('order_history', '?c=' . $_GET['c'] . '&user_id=' . $user_id) ?>>
            <div class="table-responsive">
              <table class="table table-sort">
                <thead>
                  <tr>
                    <th nowrap><?= Itlanguage::translate('Billing from'); ?></th>
                    <th nowrap><?= Itlanguage::translate('Bill No.'); ?></th>
                    <th nowrap><?= Itlanguage::translate('Net Total'); ?></th>
                    <th nowrap><?= Itlanguage::translate('Status'); ?></th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::ajaxModal('view_modal', 'custom-size modal-xl', $options); ?>
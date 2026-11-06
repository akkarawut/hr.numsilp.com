<div class="container-fluid">
  <?php if ($status == 'edit') { ?>
    <div class="row">
      <div class="col-lg-12 p-0">
        <div class="container-detail">
          <div class="header">
            <h5 class="mb-0">DATABASE CONNECTION</h5>
            <p class="mb-0">Set database connection. Don’t touch it when you confuse, Don’t understand or Drunk.</p>
          </div>
          <hr class="hr-install">
          <div class="body">
            <div class="sub-header mb-25px">
              <h5 class="mb-0">DATABASE CONNECTION</h5>
            </div>
            <div class="row ">
              <div class="col-lg-4">
                <label>DATABASE TYPE</label>
              </div>
              <div class="col-lg-8">
                <p>MySQL</p>
              </div>
              <div class="col-lg-4">
                <label>DATABASE SERVER</label>
              </div>
              <div class="col-lg-8">
                <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter', 'class' => 'mb-5px ']); ?>
                <span class="text-alert font-14px font-weight-normal mb-10px hidden">Something wrong. Please recheck server connection again. </span>
                <span class="text-success font-14px font-weight-normal mb-10px">Connected Now…</span>
              </div>
              <div class="col-lg-4">
                <label>DATABASE NAME</label>
              </div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-6">
                    <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter']); ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label>DATABASE USERNAME</label>
              </div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-6">
                    <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter']); ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label>DATABASE PASSWORD</label>
              </div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-6">
                    <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter']); ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label>DATABASE POST</label>
              </div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-3">
                    <?= TiwForm::normal('text', '', ['name' => 'name', 'placeholder' => 'Enter']); ?>
                  </div>
                  <div class="col">
                    <div class="icon-refresh">
                      <?= W_Structure::getfile('_framework/structure/image/icon/icon-refresh.svg'); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  <?php } else { ?>
    <div class="row">
      <div class="col-lg-12 p-0">
        <div class="container-detail">
          <div class="header">
            <h5 class="mb-0">DATABASE CONNECTION</h5>
            <p class="mb-0">Set database connection. Don’t touch it when you confuse, Don’t understand or Drunk.</p>
          </div>
          <hr class="hr-install">
          <div class="body">
            <div class="sub-header mb-25px">
              <h5 class="mb-0">DATABASE CONNECTION</h5>
            </div>
            <div class="row ">
              <div class="col-lg-4">
                <label>DATABASE TYPE</label>
              </div>
              <div class="col-lg-8">
                <p class="font-18px">MySQL</p>
              </div>
              <div class="col-lg-4">
                <label>DATABASE SERVER</label>
              </div>
              <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                  <p>wolves-db-pup-01-do-user-8122825-0.b.db.ondigitalocean.com</p>
                  <span class="text-success font-16px font-weight-normal mb-10px">Connected</span>
                </div>
              </div>
              <div class="col-lg-4">
                <label>DATABASE NAME</label>
              </div>
              <div class="col-lg-8">
                <p>Wolves Test</p>
              </div>
              <div class="col-lg-4">
                <label>DATABASE USERNAME</label>
              </div>
              <div class="col-lg-8">
                <p>wolves_test</p>
              </div>
              <div class="col-lg-4">
                <label>DATABASE PASSWORD</label>
              </div>
              <div class="col-lg-8">
                <p>******</p>
              </div>
              <div class="col-lg-4">
                <label>DATABASE POST</label>
              </div>
              <div class="col-lg-8">
                <p>25060</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  <?php } ?>
</div>
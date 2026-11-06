<div class="w-card">
  <div class="w-card-title-bar">
    <div class="d-flex flex-wrap align-items-center">
      <h3 class="title"><?= Ty::get('alert_list') ?></h3>
      <p class="detail">
        <?= Ty::get('perfect_program') ?>
      </p>
    </div>
  </div>
  <div class="py-15px px-20px">
    <div class="master-form-body-wrap">
      <div class="master-form-items row">
        <div class="col-12 align-self-center">
          <?php if (!$is_error['is_exists']) {
            echo file_get_contents('../../structure/image/icon/general/alert_false.svg');
          } else {
            echo file_get_contents('../../structure/image/icon/general/alert_true.svg');
          } ?>
          <span class="font-weight-bold text-secondary ml-10px"><?= Ty::get('folder_upload') ?></span>
        </div>
      </div>

      <div class="master-form-items row">
        <div class="col-12 align-self-center">
          <?php if (!$is_error['is_writable']) {
            echo file_get_contents('../../structure/image/icon/general/alert_false.svg');
          } else {
            echo file_get_contents('../../structure/image/icon/general/alert_true.svg');
          } ?>
          <span class="font-weight-bold text-secondary ml-10px"><?= Ty::get('folder_permission') ?></span>
        </div>
      </div>
      <hr class="hr-install mx--15px">
      <div class="config-domain-log p-0">
        <?php
        $domain_name = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https://' : 'http://';
        $domain_name .= $_SERVER['SERVER_NAME'];
        $config_domain = F_BRIDGE_API_URL ? explode("/system/", F_BRIDGE_API_URL)[0] : '';
        $is_verify = ($domain_name != $config_domain) ? '<span class="text-danger">โดเมนไม่ตรงกัน</span>' : '<span class="text-success">' . Ty::get('domain_match') . '</span>';
        ?>
        <div class="font-14px text-secondary font-SemiBold"><?= Ty::get('config_domain') ?></div>
        <div class="mb-15px text-info"><?= $config_domain ?></div>
        <div class="font-14px text-secondary font-SemiBold"><?= Ty::get('web_domain') ?></div>
        <div class="mb-15px text-info"><?= $domain_name ?></div>
        <div class="d-flex align-items-center">
          <div class="status-config-domain-img">
            <div class="img mr-10px">
              <img src="../../structure/image/etc/<?= ($domain_name == $config_domain) ? 'correct.gif' : 'wrong.gif'; ?>" alt="">
            </div>
          </div>
          <div class="verify-detail mb-20px">
            <span class="font-14px text-secondary"></span>
            <?= $is_verify ?>
          </div>
        </div>

        <a href="download/config.text" class="btn-download-config btn_download_config_event btn-primary" target="_blank" download="config.php"><?= Ty::get('dowload_config') ?></a>
        <div class="text-danger font-14px mt-10px"><?= Ty::get('note') ?><br>- <?= Ty::get('rec_download') ?><br>-<?= Ty::get('module_777') ?></div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $(document).on('click', '.btn_download_config_event', function(e) {
      var url = 'ajax/ajax_generate_config.php';
      $.post(url, {}).done(function(data) {
        return true;
      });
    });
  });
</script>
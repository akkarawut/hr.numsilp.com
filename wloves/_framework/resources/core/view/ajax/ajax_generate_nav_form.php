<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import.php';
W_Structure::loadMetaForAjax('../../../../');
$type = (isset($_POST['type'])) ? $_POST['type'] : '';
$color_status_options = [
  'list' => [
    [
      'value' => '',
      'name' => 'เลือกสีของสเตตัส',
      'disabled' => true
    ],
    [
      'value' => 'red-status',
      'name' => 'danger',
    ],
    [
      'value' => 'yellow-status',
      'name' => 'warning',
    ],
    [
      'value' => 'green-status',
      'name' => 'success',
    ],
  ],
];
?>

<?php if ($type == '2') { ?>
  <div class="list_template_main" data-idx="0">
    <div class="w-100 d-flex align-items-end">
      <div class="row w-100">
        <div class="col-md-6">
          <div class=" mb-5px">Menu </div>
          <?= TiwForm::normal('text', '', ['name' => 'data[0][menu]', 'placeholder' => 'ชื่อของเมนู']); ?>
        </div>
        <div class="col-md-6">
          <div class=" mb-5px">Icon</div>
          <?= TiwForm::normal('text', '', ['name' => 'data[0][img]', 'placeholder' => 'Image path or url']); ?>
        </div>
      </div>
      <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_type2_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
    </div>
    <div class="w-100 d-flex align-items-end">
      <div class="row w-100 pl-15px">
        <div class="col-md-6">
          <div class=" mb-5px">Sub Menu </div>
          <?= TiwForm::normal('text', '', ['name' => 'data[0][list][][menu]', 'placeholder' => 'ชื่อของเมนู']); ?>
        </div>
        <div class="col-md-6">
          <div class=" mb-5px">Icon</div>
          <?= TiwForm::normal('text', '', ['name' => 'data[0][list][][img]', 'placeholder' => 'Image path or url']); ?>
        </div>
      </div>
      <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_sub_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
    </div>
    <div class="list_area_sub_event w-100"></div>
  </div>
  <div class=" d-none list_template_event">
    <div class="w-100 d-flex align-items-end">
      <div class="row w-100">
        <div class="col-md-6">
          <div class=" mb-5px">Menu </div>
          <?= TiwForm::normal('text', '', ['class' => 'demo_name_event', 'placeholder' => 'ชื่อของเมนู']); ?>
        </div>
        <div class="col-md-6">
          <div class=" mb-5px">Icon</div>
          <?= TiwForm::normal('text', '', ['class' => 'demo_image_event', 'placeholder' => 'Image path or url']); ?>
        </div>
      </div>
      <?= TiwForm::normal('btn', '', ['tooltip' => 'Delete', 'class' => 'mb-10px ml-10px delete_list_event', 'type' => 'button'], ['type' => 'delete', 'prefix' => '../../../../']); ?>
    </div>
    <div class="w-100 d-flex align-items-end">
      <div class="row w-100 pl-15px">
        <div class="col-md-6">
          <div class=" mb-5px">Sub Menu </div>
          <?= TiwForm::normal('text', '', ['class' => 'demo_name_sub_event', 'placeholder' => 'ชื่อของเมนู']); ?>
        </div>
        <div class="col-md-6">
          <div class=" mb-5px">Icon</div>
          <?= TiwForm::normal('text', '', ['class' => 'demo_image_sub_event', 'placeholder' => 'Image path or url']); ?>
        </div>
      </div>
      <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_sub_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
    </div>
    <div class="list_area_sub_event w-100"></div>
  </div>
  <div class="w-100  align-items-end d-none list_template_sub_event">
    <div class="row w-100 pl-15px">
      <div class="col-md-6">
        <div class=" mb-5px">Sub Menu </div>
        <?= TiwForm::normal('text', '', ['class' => 'demo_name_sub_event', 'placeholder' => 'ชื่อของเมนู']); ?>
      </div>
      <div class="col-md-6">
        <div class=" mb-5px">Icon</div>
        <?= TiwForm::normal('text', '', ['class' => 'demo_image_sub_event', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <?= TiwForm::normal('btn', '', ['tooltip' => 'Delete', 'class' => 'mb-10px ml-10px delete_list_sub_event', 'type' => 'button'], ['type' => 'delete', 'prefix' => '../../../../']); ?>
  </div>
  <div class="list_area_event w-100"></div>
<?php } else if ($type == '3') { ?>
  <div class="w-100 d-flex align-items-end">
    <div class="row w-100">
      <div class="col-sm-6">
        <div class=" mb-5px">Menu</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_name[]', 'placeholder' => 'ชื่อของเมนู']); ?>
      </div>
      <div class="col-md-6">
        <div class=" mb-5px">Icon</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_img[]', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
  </div>
  <div class="w-100 align-items-end d-none list_template_event">
    <div class="row w-100">
      <div class="col-sm-6">
        <?= TiwForm::normal('text', '', ['class' => 'demo_name_event', 'placeholder' => 'name']); ?>
      </div>
      <div class="col-md-6">
        <?= TiwForm::normal('text', '', ['class' => 'demo_image_event', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <?= TiwForm::normal('btn', '', ['tooltip' => 'Delete', 'class' => 'mb-10px ml-10px delete_list_event', 'type' => 'button'], ['type' => 'delete', 'prefix' => '../../../../']); ?>
  </div>
  <div class="list_area_event w-100"></div>
<?php } else { ?>
  <div class="w-100 d-flex align-items-end">
    <div class="row w-100">
      <div class="col-md-3">
        <div class=" mb-5px">Menu</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_name[]', 'placeholder' => 'ชื่อของเมนู']); ?>
      </div>
      <div class="col-md-3">
        <div class=" mb-5px">Color status</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_text_status[]', 'placeholder' => 'ข้อความสเตตัส']); ?>
      </div>
      <div class="col-md-3">
        <div class=" mb-5px">Color status</div>
        <?= TiwForm::normal('select', '', ['name' => 'list_color_status[]'], $color_status_options); ?>
      </div>
      <div class="col-md-3">
        <div class=" mb-5px">Icon</div>
        <?= TiwForm::normal('text', '', ['name' => 'list_img[]', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <div class="form-btn-icon mb-10px ml-10px cursor-pointer add_list_event" tooltip="Add"><?= W_Structure::getfile('_framework/structure/image/icon/arrow/icon-add.svg') ?></div>
  </div>
  <div class="w-100 align-items-end d-none list_template_event">
    <div class="row w-100">
      <div class="col-md-3">
        <?= TiwForm::normal('text', '', ['class' => 'demo_name_event', 'placeholder' => 'name']); ?>
      </div>
      <div class="col-md-3">
        <?= TiwForm::normal('text', '', ['class' => 'demo_text_status_event', 'placeholder' => 'ข้อความสเตตัส']); ?>
      </div>
      <div class="col-md-3">
        <?= TiwForm::normal('select', '', ['class' => 'demo_select_color_event'], $color_status_options); ?>
      </div>
      <div class="col-md-3">
        <?= TiwForm::normal('text', '', ['class' => 'demo_image_event', 'placeholder' => 'Image path or url']); ?>
      </div>
    </div>
    <?= TiwForm::normal('btn', '', ['tooltip' => 'Delete', 'class' => 'mb-10px ml-10px delete_list_event', 'type' => 'button'], ['type' => 'delete', 'prefix' => '../../../../']); ?>
  </div>
  <div class="list_area_event w-100"></div>
<?php } ?>
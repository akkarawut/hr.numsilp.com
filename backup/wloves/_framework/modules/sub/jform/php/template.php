<?php
class Jform
{
  /**
  | Title | ADD EDIT DELETE
  | Description | สำหรับเพิ่มลบแก้ไขข้อมูลใน Table Field
   */
  public static function post_table_field($code, $table_name)
  {
    if ($_POST) {

      $temp_table_name = str_replace('func_' . $code, '', $table_name);

      if (strpos($temp_table_name, 'sys_') !== false) {
        $code = 'System';
      }

      if (isset($_POST['jf_submit_add_data'])) {
        unset($_POST['jf_submit_add_data']);
        $data = [
          'code' => $code,
          'table_name' => $temp_table_name,
          'name' => (isset($_POST['name']) && $_POST['name']) ? $_POST['name'] : '',
          'category' => (isset($_POST['category']) && $_POST['category']) ? $_POST['category'] : '',
          'type' => (isset($_POST['type']) && $_POST['type']) ? $_POST['type'] : '',
          'digit' => (isset($_POST['digit']) && $_POST['digit']) ? $_POST['digit'] : '',
          'decimal' => (isset($_POST['decimal']) && $_POST['decimal']) ? $_POST['decimal'] : '',
          'remark' => (isset($_POST['remark']) && $_POST['remark']) ? $_POST['remark'] : '',
          'json_data' => (isset($_POST['option']) && $_POST['option']) ? $_POST['option'] : '',
        ];
        $result = Extra::addNewExtraColumns($data);
      } else if (isset($_POST['jf_submit_edit_data'])) {
        $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : '';
        $data = [
          'code' => $code,
          'table_name' => $temp_table_name,
          'name' => (isset($_POST['name']) && $_POST['name']) ? $_POST['name'] : '',
          'category' => (isset($_POST['category']) && $_POST['category']) ? $_POST['category'] : '',
          'type' => (isset($_POST['type']) && $_POST['type']) ? $_POST['type'] : '',
          'digit' => (isset($_POST['digit']) && $_POST['digit']) ? $_POST['digit'] : '',
          'decimal' => (isset($_POST['decimal']) && $_POST['decimal']) ? $_POST['decimal'] : '',
          'remark' => (isset($_POST['remark']) && $_POST['remark']) ? $_POST['remark'] : '',
          'json_data' => (isset($_POST['option']) && $_POST['option']) ? $_POST['option'] : '',
        ];
        $result = Extra::updateExtraColumns($id, $data);
      } else if (isset($_POST['jf_submit_delete_data'])) {
        $id = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : '';
        $result =  Extra::deleteExtraColumns($id);
      }
      if (isset($result) && $result) {
        $response_message = isset($message) ? $message : $result['response_message'];
        $response_status = isset($result['response_status']) && $result['response_status'] ? 'success' : 'error';
        $response_redirect = isset($redirect) && $response_status != 'error' ? $redirect : '';

        W_Main::notification($response_message, $response_status);
        W_Main::redirect($response_redirect);
      }
    }
  }

  /**
  | Title | เรียกใช้ Table Field
  | Description | 
    $code = code ของ module
    $table_name = ต้องถามจาก Backend,
    $title = แสดงข้อความตรง Header,
    $is_module = ถ้าอยู่ใน Module ต้องใช้ true ถ้าอยู่ใน Framework ไม่ต้องมี
   */
  public static function generate_table_field($code, $table_name, $title = null, $is_module = false)
  {
    $path = '../../../_framework/modules/sub/jform/';
    $this_url = $_SERVER['REQUEST_URI'];
    $this_url =  explode('/', $this_url);
    $key = array_search('_framework', $this_url);
    if (!$key) {
      $path = '../../_framework/modules/sub/jform/';
    }
    $temp_table_name = str_replace('func_' . $code, '', $table_name);
    $where = [
      'code' => $code,
      'table_name' => $temp_table_name,
    ];
    $options = [
      'total_count' => true,
    ];
    if (strpos($temp_table_name, 'sys_') !== false) {
      unset($where['code']);
    }
    $data_list =  Extra::selectExtraColumns($code, $where, $options);
    $total_count = $data_list['total_count'];

    ob_start();
?>
    <div class="w-card h-auto">
      <div class="w-card-title-bar">
        <div class="d-flex align-items-center">
          <div class="fw-bold font-18px">
            <?= $title ?> <?= $total_count ?> list(s)
          </div>
        </div>
        <div class="btn-edit-container justify-content-end toggle-setting">
          <div class="btn-edit cancel min-w-50px max-w-50px btn-icon-light" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="bottom" title='<div class="font-15px text-start-list">
              <div class="font-16px font-SemiBold">การสร้าง User database column</div>
                <div class="text-pre">สร้างพิ้นฐานข้อมูลสำหรับใช้ประกอบการสร้าง Field ของ Module User</div>
               </div>'>
          </div>
          <button class="btn-edit save min-w-150px" <?= Tiwdal::register('modal_add_data', []); ?>>
            <div class="ms-2">+ Add data</div>
          </button>
        </div>
      </div>
      <div class="table-user-field p-0">
        <div id="user_field_list" class="container-pagination" <?= Homepagify::createHomepagify('user_field_list', '?c=' . $code . '&table_name=' . $table_name . '&is_module=' . $is_module, '', '', $path) ?>>
          <div class="table-responsive">
            <table class="table table-sort table-search">
              <thead>
                <tr>
                  <th nowrap class="w-200px" data-sort="name" data-filter="<?= Homepagify::dataFilter('name', 'text') ?>">หัวข้อช่องกรอก</th>
                  <th nowrap class="w-150px" data-sort="category" data-filter="<?= Homepagify::dataFilter('category', 'text') ?>">หมวดหมู่</th>
                  <th nowrap class="w-150px" data-sort="type" data-filter="<?= Homepagify::dataFilter('type', 'text') ?>">รูปแบบ</th>
                  <th nowrap class="w-200px" data-sort="db_column_name" data-filter="<?= Homepagify::dataFilter('db_column_name', 'text') ?>">DB Column name</th>
                  <th nowrap data-sort="">หมายเหตุ</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php Tiwdal::startModal('modal_add_data', 'max-w-1200px'); ?>
    <form method="post">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-header">
        <h5 class="modal-title text-primary">สร้างช่องป้อนข้อมูล</h5>
      </div>
      <div class="modal-body px-0 pb-0">
        <div class="main-option">
          <div class="left-side-option">
            <div class="container-menu-option">
              <ul class="nav-menu--modal">
                <li class="menu--link active" data-type="string" onclick="selectMenu(event);">
                  <div class="">ช่องกรอกข้อมูล</div>
                </li>
                <li class="menu--link" data-type="number" onclick="selectMenu(event);">
                  <div class="">ช่องกรอกตัวเลข</div>
                </li>
                <li class="menu--link" data-type="date_and_time" onclick="selectMenu(event);">
                  <div class="">วันที่และเวลา</div>
                </li>
                <li class="menu--link" data-type="upload" onclick="selectMenu(event);">
                  <div class="">อัปโหลด</div>
                </li>
                <li class="menu--link" data-type="option" onclick="selectMenu(event);">
                  <div class="">สร้างตัวเลือก</div>
                </li>
                <li class="menu--link" data-type="other" onclick="selectMenu(event);">
                  <div class="">อื่นๆ</div>
                </li>
              </ul>
            </div>
          </div>

          <div id="event_show_form" class="right-side-option">
            <?= f_template_data_and_number() ?>
            <?= f_template_form_short_text() ?>
          </div>

        </div>

      </div>
      <div class="modal-footer">
        <?=
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
        TiwForm::normal('btn', '', ['name' => 'jf_submit_add_data', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'เพิ่ม', 'type' => '']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>
    <!-- Modal Edit Data -->
    <?php Tiwdal::ajaxModal('edit_short_text', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_long_text', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_number', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_decimal', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_date', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_time', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_datetime', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_upload', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_select_tag', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_select_dropdown', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_google_map', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_lat_long', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_youtube_embed', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_address', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_link', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_radio', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_checkbox', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('edit_switch', 'modal-lg', ['is_ajax' => true]); ?>


    <!-- Modal Delete Data -->
    <?php Tiwdal::startModal('delete_user_field', 'max-w-650px'); ?>
    <form action="" method="post">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pt-15px">
        <div class="font-16px text-uppercase font-SemiBold text-center mb-10px">Delete DATA</div>
        <div class="text-secondary text-center">Are you sure to delete <span class="text-danger text-uppercase">“This Row”</span><br>your delete is not effect with recent history</div>
      </div>
      <div class="modal-footer">
        <?php
        TiwForm::normal('hidden', '', ['name' => '{id}']);
        TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก']);
        TiwForm::normal('btn', '', ['name' => 'jf_submit_delete_data', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => 'ลบข้อมูล']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>
    <script>
      const selectMenu = (e) => {
        let scope_menu = e.currentTarget;
        let menu_link = scope_menu.getAttribute('data-type');
        let show_form = document.getElementById('event_show_form');
        let link = document.querySelectorAll('.menu--link');

        link.forEach((ele) => {
          ele.classList.remove('active');
        })
        e.currentTarget.classList.add('active');
        show_form.innerHTML = '';
        if (menu_link == 'string') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_data_and_number() ?>`);
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_short_text() ?>`);
        } else if (menu_link == 'number') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_number() ?>`);
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_number() ?>`);
        } else if (menu_link == 'date_and_time') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_date_and_time() ?>`);
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_date() ?>`);
        } else if (menu_link == 'upload') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_upload() ?>`);
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_upload() ?>`);
        } else if (menu_link == 'option') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_option() ?>`);
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_select_dropdown() ?>`);
          sortList('scope_list_fill');
        } else if (menu_link == 'other') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_other() ?>`);
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_auto_address() ?>`);
        }
      }

      const selectOption = (e) => {
        let scope_menu = e.currentTarget;
        let menu_option = scope_menu.getAttribute('data-type');
        let show_form = document.getElementById('event_show_form');
        let form_container = document.getElementById('scope_show_form');
        let link = document.querySelectorAll('.card-option');

        link.forEach((ele) => {
          ele.classList.remove('selected');
        })
        e.currentTarget.classList.add('selected');
        if (form_container != null) {
          form_container.remove();
        }

        if (menu_option == 'short_text') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_short_text() ?>`);
        } else if (menu_option == 'paragrahp') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_paragraph() ?>`);
        } else if (menu_option == 'number') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_number() ?>`);
        } else if (menu_option == 'decimal') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_decimal() ?>`);
        } else if (menu_option == 'date') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_date() ?>`);
        } else if (menu_option == 'time') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_time() ?>`);
        } else if (menu_option == 'datetime') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_date_time() ?>`);
        } else if (menu_option == 'upload') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_upload() ?>`);
        } else if (menu_option == 'select_tag') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_select_tag() ?>`);
          sortList('scope_list_fill');
        } else if (menu_option == 'select_dropdown') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_select_dropdown() ?>`);
          sortList('scope_list_fill');
        } else if (menu_option == 'radio') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_radio() ?>`);
          sortList('scope_list_fill');
        } else if (menu_option == 'checkbox') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_checkbox() ?>`);
          sortList('scope_list_fill');
        } else if (menu_option == 'auto_address') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_auto_address() ?>`);
        } else if (menu_option == 'google_map') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_google_map() ?>`);
        } else if (menu_option == 'latitude_longitude') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_lat_long() ?>`);
        } else if (menu_option == 'link') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_link() ?>`);
        } else if (menu_option == 'youtube_embed') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_youtude_embed() ?>`);
        } else if (menu_option == 'switch') {
          show_form.insertAdjacentHTML('beforeend', `<?= f_template_form_switch() ?>`);
        }
      }
      var modal_edit_select_tag = document.getElementById('edit_select_tag')
      modal_edit_select_tag.addEventListener('show.bs.modal', function(event) {
        sortList('scope_list_fill_edit');
      });
      var modal_edit_select_dropdown = document.getElementById('edit_select_dropdown')
      modal_edit_select_dropdown.addEventListener('show.bs.modal', function(event) {
        sortList('scope_list_fill_edit');
      });

      var modal_edit_radio = document.getElementById('edit_radio')
      modal_edit_radio.addEventListener('show.bs.modal', function(event) {
        sortList('scope_list_fill_edit');
      });

      var modal_edit_checkbox = document.getElementById('edit_checkbox')
      modal_edit_checkbox.addEventListener('show.bs.modal', function(event) {
        sortList('scope_list_fill_edit');
      })

      function addOption(id) {
        let scope_list_fill = document.getElementById(id);
        let html = `
      <div class="card-sortable" >
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
          <?= TiwForm::normal('text', '', ['name' => 'option[]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?> 
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'` + id + `')"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
          </div>
        </div>
      </div>
      `;
        scope_list_fill.insertAdjacentHTML('beforeend', html);
      }

      function addOptionRadio(id) {
        let scope_list_fill = document.getElementById(id);
        let html = `
      <div class="card-sortable" >
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
          <?php
          $id = TiwForm::randomName(5);
          ?>
          <?= TiwForm::normal('text', '', ['name' => 'option[' . $id . '][value]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?> 
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'` + id + `')"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
          </div>
        </div>
      </div>
      `;
        scope_list_fill.insertAdjacentHTML('beforeend', html);
      }

      function addOptionOtherRadio(id) {
        let scope_list_fill = document.getElementById(id);
        let html = `
      <div class="card-sortable" >
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input d-flex justify-content-between">
          <?php
          $id = TiwForm::randomName(5);
          ?>
            <?= TiwForm::normal('text', 'อื่น ๆ', ['name' => 'option[' . $id . '][value]', 'class' => 'max-w-300px border-0 ', 'readonly' => 'true', 'placeholder' => 'เพิ่มตัวเลือก']); ?> 
            <?= TiwForm::normal('checkbox', '1', ['name' => 'option[' . $id . '][is_text_other]', 'main_class' => 'w-200px'], ['style' => '3', 'label' => 'พร้อมช่องกรอก']); ?> 
            <?= TiwForm::normal('hidden', '1', ['name' => 'option[' . $id . '][is_other]']); ?> 
          </div>
          <div class="__field-action d-flex justify-content-end">
            <button type="button" class="btn-action __btn--delete js-remove" data-remove="other"></button>
          </div>
        </div>
      </div>
      `;
        hideBtnaddOther();
        scope_list_fill.insertAdjacentHTML('beforeend', html);
      }

      function addOptionCheckbox(id) {
        let scope_list_fill = document.getElementById(id);
        let html = `
      <div class="card-sortable" >
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
          <?php
          $id = TiwForm::randomName(5);
          ?>
          <?= TiwForm::normal('text', '', ['name' => 'option[list][' . $id . '][value]', 'placeholder' => 'เพิ่มตัวเลือก', 'oninput' => "addValueOption(event)"]); ?> 
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone" onclick="copyOption(event,'` + id + `')"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
          </div>
        </div>
      </div>
      `;
        scope_list_fill.insertAdjacentHTML('beforeend', html);
      }

      function addOptionOtherCheckbox(id) {
        let scope_list_fill = document.getElementById(id);
        let html = `
      <div class="card-sortable" >
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input d-flex justify-content-between">
          <?php
          $id = TiwForm::randomName(5);
          ?>
            <?= TiwForm::normal('text', 'อื่น ๆ', ['name' => 'option[list][' . $id . '][value]', 'class' => 'max-w-300px border-0 ', 'readonly' => 'true', 'placeholder' => 'เพิ่มตัวเลือก']); ?> 
            <?= TiwForm::normal('checkbox', '1', ['name' => 'option[list][' . $id . '][is_text_other]', 'main_class' => 'w-200px'], ['style' => '3', 'label' => 'พร้อมช่องกรอก']); ?> 
            <?= TiwForm::normal('hidden', '1', ['name' => 'option[list][' . $id . '][is_other]']); ?> 
          </div>
          <div class="__field-action d-flex justify-content-end">
            <button type="button" class="btn-action __btn--delete js-remove" data-remove="other"></button>
          </div>
        </div>
      </div>
      `;
        hideBtnaddOther();
        scope_list_fill.insertAdjacentHTML('beforeend', html);
      }

      function eventSelectTypeSwite(e) {
        let value = e.target.value;
        let modal = e.target.closest('.modal-content');
        let scope_true_text = modal.querySelector('.scope_true_text');
        let scope_false_text = modal.querySelector('.scope_false_text');
        if (value == 'default') {
          scope_true_text.value = '';
          scope_true_text.removeAttribute('readonly');
          scope_false_text.value = '';
          scope_false_text.removeAttribute('readonly');
        } else if (value == 'YesorNo') {
          scope_true_text.value = 'Yes';
          scope_true_text.setAttribute('readonly', true);
          scope_false_text.value = 'No';
          scope_false_text.setAttribute('readonly', true);
        } else if (value == 'TrueorFalse') {
          scope_true_text.value = 'True';
          scope_true_text.setAttribute('readonly', true);
          scope_false_text.value = 'False';
          scope_false_text.setAttribute('readonly', true);
        }
      }

      function hideBtnaddOther() {
        let hide_has_add_other = document.querySelectorAll('.hide_has_add_other');
        hide_has_add_other.forEach((element) => {
          element.classList.add('d-none');
        });
      }

      function showBtnaddOther() {
        console.log('test');
        let hide_has_add_other = document.querySelectorAll('.hide_has_add_other');
        hide_has_add_other.forEach((element) => {
          element.classList.remove('d-none');
        });
      }

      function copyOption(e, id) {
        const scope_list_fill = document.getElementById(id);
        let html = e.target.closest('.card-sortable').outerHTML;
        scope_list_fill.insertAdjacentHTML('beforeend', html);
      }

      function addValueOption(e) {
        let value = e.target.value;
        // add attribute value this input
        e.target.setAttribute('value', value);

      }

      function sortList(id) {
        let scope_list_fill = document.getElementById(id);
        var sortable = Sortable.create(scope_list_fill, {
          ghostClass: "ghost",
          handle: '.handle',
          group: {
            name: 'order',
          },
          dataIdAttr: 'data-order',
          filter: ".js-remove, .js-edit",
          onFilter: function(evt) {
            var item = evt.item,
              ctrl = evt.target;
            if (Sortable.utils.is(ctrl, ".js-remove")) { // Click on remove button
              var is_other = ctrl.getAttribute('data-remove');
              if (is_other == 'other') {
                showBtnaddOther();
              }
              item.parentNode.removeChild(item);
            }
          },
          onEnd: function( /**Event*/ evt) {},
        });
      }
    </script>
    <?php return ob_get_clean();
  }


  /**
  | Title | เรียกใช้ปุ่ม Action ใน Table Field
  | Description | นำไปใส่ไว้ใน column table ที่ต้องการแสดง และต้อง Import module ด้วย
   */
  public static function action_field($code = null, $data = null, $is_module = false)
  {
    $path = '../../../_framework/modules/sub/jform/';
    $this_url = $_SERVER['REQUEST_URI'];
    $this_url =  explode('/', $this_url);
    $key = array_search('_framework', $this_url);
    if ($is_module) {
      $path = '../../_framework/modules/sub/jform/';
    }
    if ($data) {
      if ($data['system_column'] != 1) : ?>
        <div class="d-flex align-items-center">
          <?php if ($data['type'] == 'Varchar' && $data['category'] == 'String') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_short_text', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Longtext') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_long_text', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Int') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_number', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Decimal') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_decimal', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Date') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_date', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Time') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_time', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'DateTime') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_datetime', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Upload') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_upload', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Tag') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_select_tag', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Dropdown') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_select_dropdown', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Auto address') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_address', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Google map link') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_google_map', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Link') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_link', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Latitude_Longtitude') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_lat_long', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Youtube_embed') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_youtube_embed', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Radio') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_radio', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Checkbox') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_checkbox', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Switch') : ?>
            <?= TiwForm::normal('btn', '', ['type' => 'button'], ['type' => 'edit', 'prefix' => '', 'modal_id' => 'edit_switch', 'modal_data' => $data, 'is_ajax' => true, 'modal_prefix' => $path]); ?>
          <?php endif; ?>
          <?php
          TiwForm::normal('btn', '', ['type' => 'submit'], ['type' => 'delete', 'prefix' => '', 'modal_id' => 'delete_user_field', 'modal_data' => $data]);
          ?>
        </div>
    <?php endif;
    }
  }

  public static function extra_template_header($code, $table_name, $template_name, $options = [])
  {
    ob_start();
    $mode = '';
    if (isset($options['mode'])) {
      $mode = $options['mode'];
    }
    ?>
    <div class="w-card h-auto mx-10px">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="icon">
            <img src=" <?= W_Structure::getLinkURL('_framework/modules/sub/jform/icon/form.svg') ?>" alt="">
          </div>
          <div class="font-16px fw-semibold ml-10px">
            <?php
            if ($template_name == 'user_detail') {
              echo 'ตั้งค่าหน้า : User details';
            } else if ($template_name == 'add_data') {
              echo 'ตั้งค่าหน้าเพิ่มข้อมูลใหม่ (Create / Add)';
            } else if ($template_name == 'profile') {
              echo 'ตั้งค่าการแสดงผลหน้า “โปรไฟล์” เมื่อผู้ใช้งานกดดูหน้าโปรไฟล์';
            } else {
              echo 'ตั้งค่า ' . $template_name;
            }
            ?>
          </div>
        </div>
        <div class="btn-edit-container">
          <button type="button" class="btn-edit cancel min-w-150px" onclick="clearPageAll();">
            <div class="icon-svg-hover mr-5px">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/clear.svg') ?>
            </div>
            <span>Clear All</span>
          </button>
          <?php
          $page = isset($_GET['page']) ? $_GET['page'] : '';
          $get_page = $page != '' ? '&page=' . $page : '';
          ?>
          <a href="?c=<?= $code ?>&template=<?= $template_name ?>&is_edit=1&mode=edit_mode<?= $get_page ?>" class="btn-edit cancel min-w-150px <?= $mode == 'edit_mode' ? 'btn-active' : '' ?>">
            <div class="icon-svg-hover mr-5px">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/form_edit.svg') ?>
            </div>
            <span>Edit Mode</span>
          </a>
          <a href="?c=<?= $code ?>&template=<?= $template_name ?>&mode=preview_mode<?= $get_page ?>" class="btn-edit cancel min-w-150px <?= $mode == 'preview_mode' ? 'btn-active' : '' ?>">
            <div class="icon-svg-hover mr-5px">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/preview.svg') ?>
            </div>
            <span>Preview mode</span>
          </a>
        </div>
      </div>
    </div>
  <?php return ob_get_clean();
  }

  /**
  | Title | เรียกใช้ Ui Setting ในพื้นที่นั้นๆ
  | Description | 
    ต้องรับค่า $code และชื่อ Template และ Tabname ก่อน
    ถ้าไม่มี Template หรือ Tabname จะสร้างให้ตามที่ส่งมา
    $table_name จะดึง Field ตาม table ถ้าไม่มีจะดึงมาทั้งหมด
   */
  public static function extra_template($code, $template, $tab_id = null, $table_name = null, $options = [])
  {
    $tools_data = [
      [
        'name' => 'ตัวหนังสือ',
        'icon' => 'font.svg',
        'action' => '',
        'modal' => 'modal_add_text',
        'is_ajax' => false,
        'list' => '',
        'disabled' => false
      ],
      [
        'name' => 'ช่องป้อนข้อมูล',
        'icon' => 'forms.svg',
        'action' => '',
        'modal' => 'add_fill',
        'is_ajax' => true,
        'list' => '',
        'disabled' => false
      ],
      [
        'name' => 'การตกแต่งหน้ากระดาษ',
        'icon' => 'customize.svg',
        'action' => '',
        'modal' => 'customize_page',
        'is_ajax' => false,
        'list' => '',
        'disabled' => false
      ],
      [
        'name' => 'แทรกตาราง',
        'icon' => 'table.svg',
        'action' => '',
        'modal' => '',
        'is_ajax' => false,
        'list' => '',
        'disabled' => true
      ],
      [
        'name' => 'นำเข้าข้อมูล',
        'icon' => 'import.svg',
        'action' => '',
        'modal' => 'modal_import',
        'is_ajax' => false,
        'list' => '',
        'disabled' => false
      ],
      [
        'name' => 'ตั้งค่าหน้ากระดาษ',
        'icon' => 'setting-cog.svg',
        'action' => '',
        'modal' => 'page_setting',
        'is_ajax' => true,
        'disabled' => false
      ],
    ];

    $code = $code;
    $template_name = $template;
    $tab_id = isset($tab_id) ? $tab_id : (isset($_GET['tab']) ? $_GET['tab'] : '');
    $is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
    $mode  = isset($_GET['mode']) ? $_GET['mode'] : 'edit_mode';
    $get_module = Module::getModuleByCode($code);
    $sys_function_id = $get_module['id'];
    $get_module_by_id = Module::getModuleByID($sys_function_id);
    $is_tab = $get_module_by_id['create_tab'] == 1 ? 1 : 0;

    $has_preview = true;
    if (isset($options['has_preview']) && $options['has_preview'] == false) {
      $has_preview = false;
    }
    if (isset($_POST)) {
      if (isset($_POST['submit_add_tab'])) {
        unset($_POST['submit_add_tab']);
        $name = isset($_POST['tab_name']) ? $_POST['tab_name'] : '';
        $remark = isset($_POST['remark_tab']) ? $_POST['remark_tab'] : '';
        $result = Extra::addExtraTemplateTab($code, $template, $_POST['tab_name']);
        if (isset($result) && $result) {
          $response_message = isset($message) ? $message : $result['response_message'];
          $response_status = isset($result['response_status']) && $result['response_status'] ? 'success' : 'error';
          $response_redirect = isset($redirect) && $response_status != 'error' ? $redirect : '';

          W_Main::notification($response_message, $response_status);
          W_Main::redirect($response_redirect);
        }
      }

      if (isset($_POST['submit_edit_tab'])) {
        unset($_POST['submit_edit_tab']);
        $new_name = isset($_POST['tab_name']) ? $_POST['tab_name'] : '';
        $remark = isset($_POST['remark_tab']) ? $_POST['remark_tab'] : '';
        $tab__id = isset($_POST['tab_id']) ? $_POST['tab_id'] : '';
        $data = [
          'tab_name' => $new_name,
          // 'remark' => $remark
        ];
        $result = Extra::updateExtraTemplateTab($tab__id, $data);
        if (isset($result) && $result) {
          $response_message = isset($message) ? $message : $result['response_message'];
          $response_status = isset($result['response_status']) && $result['response_status'] ? 'success' : 'error';
          $response_redirect = isset($redirect) && $response_status != 'error' ? $redirect : '';

          W_Main::notification($response_message, $response_status);
          W_Main::redirect($response_redirect);
        }
      }
      if (isset($_POST['submit_delete_tab'])) {
        unset($_POST['submit_delete_tab']);
        $tab__id = isset($_POST['tab_id']) ? $_POST['tab_id'] : '';
        $result = Extra::deleteExtraTemplateTab($tab__id);
        if (isset($result) && $result) {
          $response_message = isset($message) ? $message : $result['response_message'];
          $response_status = isset($result['response_status']) && $result['response_status'] ? 'success' : 'error';
          $response_redirect = isset($redirect) && $response_status != 'error' ? $redirect : '';

          W_Main::notification($response_message, $response_status);
          W_Main::redirect($response_redirect);
        }
      }
    }

    $select_tab = Extra::getExtraTemplate($code, $template);
    $id_template = isset($select_tab['id']) ? $select_tab['id'] : '';
    if ($tab_id == '') {
      $tab_id = isset($select_tab['meta_data'][0]['tab_id']) ? $select_tab['meta_data'][0]['tab_id'] : '';
    }

    if (is_null($select_tab)) {
      $result =  Extra::addExtraTemplateTab($code, $template, 'Default');
      $select_tab = Extra::getExtraTemplate($code, $template);
      $tab_id = $select_tab['meta_data'][0]['tab_id'];
      $id_template = isset($select_tab['id']) ? $select_tab['id'] : '';
    }

    $data = [
      'code' => $code,
      'template' => $template_name,
      'mode' => $mode,
      'sys_function_id' => $sys_function_id,
      'is_tab' => $is_tab,
      'tab_id' => $tab_id,
      'tab_data' => isset($select_tab['meta_data']) ? $select_tab['meta_data'] : [],
    ];

    $list_font_size = [];
    foreach (range(10, 28) as $number) {
      $list_font_size[] = [
        'value' => $number,
        'name' => $number . 'px'
      ];
    }

    $font_size =
      [
        'list' => $list_font_size,
      ];

    $size_display = [
      'list' => [
        [
          'value' => '49',
          'name' => '50%'
        ],
        [
          'value' => '100',
          'name' => '100%'
        ],
      ]
    ];

    $size_display_input = [
      'list' => [
        [
          'value' => '410',
          'name' => '100%'
        ],
        [
          'value' => '200',
          'name' => '50%'
        ],
        [
          'value' => '100',
          'name' => '25%'
        ],
      ]
    ];
    $path = '../../../_framework/modules/sub/jform/';
    $this_url = $_SERVER['REQUEST_URI'];
    $this_url =  explode('/', $this_url);
    $key = array_search('_framework', $this_url);
    if (!$key) {
      $path = '../../_framework/modules/sub/jform/';
    }
    ob_start();
  ?>
    <div class="w-card h-auto mb-10px">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="icon">
            <img src=" <?= W_Structure::getLinkURL('_framework/modules/sub/jform/icon/form.svg') ?>" alt="">
          </div>
          <div class="font-16px fw-semibold ml-10px">
            <?php
            if ($template_name == 'user_detail') {
              echo 'ตั้งค่าหน้า : User details';
            } else if ($template_name == 'add_data') {
              echo 'ตั้งค่าหน้าเพิ่มข้อมูลใหม่ (Create / Add)';
            } else if ($template_name == 'profile') {
              echo 'ตั้งค่าการแสดงผลหน้า “โปรไฟล์” เมื่อผู้ใช้งานกดดูหน้าโปรไฟล์';
            } else {
              echo 'ตั้งค่า ' . $template_name;
            }
            ?>
          </div>
        </div>
        <div class="btn-edit-container">
          <button type="button" class="btn-edit cancel min-w-150px" onclick="clearPageAll();">
            <div class="icon-svg-hover mr-5px">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/clear.svg') ?>
            </div>
            <span>Clear All</span>
          </button>
          <?php
          $page = isset($_GET['page']) ? $_GET['page'] : '';
          $get_page = $page != '' ? '&page=' . $page : '';
          ?>
          <a href="?c=<?= $code ?>&template=<?= $template_name ?>&is_edit=1&mode=edit_mode<?= $get_page ?>" class="btn-edit cancel min-w-150px <?= $data['mode'] == 'edit_mode' ? 'btn-active' : '' ?>">
            <div class="icon-svg-hover mr-5px">
              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/form_edit.svg') ?>
            </div>
            <span>Edit Mode</span>
          </a>
          <?php if ($has_preview == true) { ?>
            <a href="?c=<?= $code ?>&sys_function_id=<?= $data['sys_function_id'] ?>&template=<?= $template_name ?>&mode=preview_mode<?= $get_page ?>" class="btn-edit cancel min-w-150px <?= $data['mode'] == 'preview_mode' ? 'btn-active' : '' ?>">
              <div class="icon-svg-hover mr-5px">
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/preview.svg') ?>
              </div>
              <span>Preview mode</span>
            </a>
          <?php } ?>
        </div>
      </div>

      <?php if ($data['mode'] == 'edit_mode') : ?>
        <?php if ($data['is_tab'] == 1 && count($data['tab_data'])) : ?>
          <div class="container-toolbar">
            <div class="inner-toolbar">
              <?php foreach ($tools_data as $tool) : ?>
                <?php $is_ajax = $tool['is_ajax'] == true ? true : false ?>
                <div class="__tool-item event_select_tool <?= $tool['disabled'] == true ? 'disabled' : '' ?>" <?= $tool['modal'] != '' ? Tiwdal::register($tool['modal'], ['table_name' => $table_name, 'template' => $template], ['is_ajax' => $is_ajax, 'modal_prefix' => $path]) : '' ?>>
                  <div class="__icon">
                    <?= W_Structure::getfile('_framework/modules/sub/jform/icon/' . $tool['icon']) ?>
                  </div>
                  <div class="__name">
                    <?= $tool['name'] ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else : ?>
          <div class="container-toolbar">
            <div class="inner-toolbar">
              <?php foreach ($tools_data as $tool) : ?>
                <?php $is_ajax = $tool['is_ajax'] == true ? true : false ?>
                <div class="__tool-item event_select_tool <?= $tool['disabled'] == true ? 'disabled' : '' ?>" <?= $tool['modal'] != '' ? Tiwdal::register($tool['modal'], ['table_name' => $table_name, 'template' => $template], ['is_ajax' => $is_ajax, 'modal_prefix' => $path]) : '' ?>>
                  <div class="__icon">
                    <?= W_Structure::getfile('_framework/modules/sub/jform/icon/' . $tool['icon']) ?>
                  </div>
                  <div class="__name">
                    <?= $tool['name'] ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>


    <div class="main-container-page">
      <?php if ($data['is_tab'] == 1) : ?>
        <div class="container-create-tab">
          <div class="__header">
            <div class="font-16px fw-semibold">จัดการ Tab (หน้าเพิ่มเติม)</div>
          </div>
          <button type="button" class="btn btn-primary w-100 border-radius-0" <?= Tiwdal::register('modal_add_tab', []); ?>>
            <div class="font-16px fw-semibold">+ เพิ่ม Tab ใหม่</div>
          </button>
          <div class="__body">
            <div class="font-15px fw-bold text-primary mb-10px">รายการ Tab</div>
            <div id="scope_list_tab" class="list_tab">
              <?php if (count($select_tab) > 0) : ?>
                <?php foreach ($select_tab['meta_data'] as $key_tab => $tab) : ?>
                  <div class="card-list-tab <?= $tab['tab_id'] == $data['tab_id'] ? "active" : '' ?>" data-order="<?= $tab['tab_id'] ?>">
                    <div class="__handle-tab">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
                    </div>
                    <a href="?c=<?= $code ?>&id=<?= $data['sys_function_id'] ?>&template=<?= $template_name ?>&tab=<?= $tab['tab_id'] ?>" class="__menu-name">
                      <div class="text-dark"><?= isset($tab['tab_name']) ? $tab['tab_name'] : '' ?></div>
                    </a>
                    <div class="__menu-action">
                      <button class="btn-action __btn--edit" <?= Tiwdal::register('modal_edit_tab', $tab); ?>></button>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else : ?>
                <div class="font-18px fw-bold text-danger">ยังไม่มี Tab</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="container-dropzone">
        <form id="form_json" method="post">
          <div id="scope_list_fill" class="list-fill"></div>
        </form>
      </div>
    </div>


    <div id="scope_modal_edit_option"></div>
    <div id="scope_modal_edit_text"></div>
    <div id="scope_modal_edit_line"></div>
    <div id="scope_modal_edit_image"></div>
    <div id="scope_modal_edit_option_slider_image"></div>

    <?php Tiwdal::ajaxModal('add_fill', 'modal-lg', ['is_ajax' => true]); ?>
    <?php Tiwdal::ajaxModal('page_setting', 'max-w-700px', ['is_ajax' => true, 'slide' => true, 'modal_prefix' => $path]); ?>
    <?php Tiwdal::ajaxModal('add_option_slider_image', 'max-w-700px', ['is_ajax' => true, 'slide' => true]); ?>

    <?php Tiwdal::startModal('modal_add_option_fill', 'max-w-1200px', ['slide' => true]); ?>
    <form id="form_option">
      <div class="modal-body p-0">
        <div class="w-card h-auto">
          <div class="w-card-title-bar border-bottom">
            <div class="d-flex flex-wrap align-items-center">
              <div class="font-16px fw-semibold text-primary">เพิ่มช่องกรอกข้อมูล</div>
            </div>
            <div class="btn-edit-container">
              <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
              <button type="button" class="btn-edit save min-w-150px" onclick="confirmAddFill();">Confirm</button>
            </div>
          </div>
        </div>
        <div class="container-fluid px-20px">
          <div class="row">
            <div class="col-lg-7">
              <div id="scope_connect_data"></div>
              <hr>
              <div id="scope_show_template">
                <?= template_form_datetime(); ?>
              </div>
              <hr>
            </div>
            <div class="col-lg-5" id="scope_show_setting_template">
            </div>
          </div>
        </div>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <?php Tiwdal::startModal('modal_add_text', 'max-w-700px', ['slide' => true]); ?>
    <form id="form_option_text">
      <div class="modal-body p-0">
        <div class="w-card h-auto">
          <div class="w-card-title-bar border-bottom">
            <div class="d-flex flex-wrap align-items-center">
              <div class="font-16px fw-semibold text-primary">เพิ่มหัวข้อความ</div>
            </div>
            <div class="btn-edit-container">
              <input type="hidden" name="type" value="text">
              <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
              <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmAddText();">Confirm</button>
            </div>
          </div>
        </div>
        <div class="container-fluid px-20px">
          <div class="row">
            <div class="col-lg-12">
              <div id="scope_customize_title">

                <div class="row mt-10px mb-15px">
                  <div class="col-sm-12">
                    <div class="font-15px fw-bold">การปรับแต่งตัวหนังสือ</div>
                  </div>
                </div>

                <div class="row mb-15px">
                  <div class="col-sm-4">
                    <div class="font-16px text-secondary fw-semibold required">ข้อความที่แสดง</div>
                  </div>
                  <div class="col-sm-8">
                    <?= TiwForm::normal('text', '', ['id' => 'event_preview_text', 'name' => 'title_text', 'required' => 'true'], []); ?>
                  </div>
                </div>
                <div class="row mb-15px">
                  <div class="col-sm-4">
                    <div class="font-16px text-secondary fw-semibold">รูปแบบตัวอักษร</div>
                  </div>
                  <div class="col-sm-8">
                    <div class="d-flex">
                      <div class="max-w-150px mx-10px w-100">
                        <?php $font_family = ['list_encode' => '[{"value":"Sarabun","name":"Sarabun"}]',]; ?>
                        <?= TiwForm::normal('select', '', ['name' => 'font_family', 'class' => "input-bordered"], $font_family); ?>
                      </div>
                      <div class="max-w-100px  mx-10px w-100">
                        <?= TiwForm::normal('select', '16', ['name' => 'font_size', 'class' => "input-bordered"], $font_size); ?>
                      </div>
                      <div class="mx-10px pr-10px border-right">
                        <div class="checkbox-bold">
                          <input type="checkbox" value="1" name="font_bold" id="checkbox-title-bold" onchange="previewText()">
                          <label for="checkbox-title-bold">
                            <div class="checkbox-img ">
                              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/bold.svg') ?>
                            </div>
                          </label>
                        </div>
                      </div>
                      <div class="mx-10px pr-10px border-right">
                        <div class="checkbox-bold">
                          <input type="checkbox" value="1" name="font_italic" id="checkbox-title-italic" onchange="previewText()">
                          <label for="checkbox-title-italic">
                            <div class="checkbox-img ">
                              <?= W_Structure::getfile('_framework/modules/sub/jform/icon/italic.svg') ?>
                            </div>
                          </label>
                        </div>
                      </div>
                      <div class="d-flex align-items-center">
                        <label class="btn-action-icon" for="custom_color__text">
                          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
                          <input id="custom_color__text" type="color" name="custom_color_text" class="input-color-sm" onchange="previewText()">
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-15px">
                  <div class="col-sm-4">
                    <div class="font-16px text-secondary fw-semibold">ตำแหน่งตัวอักษร</div>
                  </div>
                  <div class="col-sm-8">
                    <div class="d-flex pr-10px mx-10px">
                      <div class="checkbox-bold mr-10px">
                        <input type="radio" value="left" name="align_text" id="checkbox-align-text-left" checked onchange="previewText()">
                        <label for="checkbox-align-text-left">
                          <div class="checkbox-img ">
                            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/left-align.svg') ?>
                          </div>
                        </label>
                      </div>
                      <div class="checkbox-bold mr-10px">
                        <input type="radio" value="center" name="align_text" id="checkbox-align-text-center" onchange="previewText()">
                        <label for="checkbox-align-text-center">
                          <div class="checkbox-img ">
                            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/align-center.svg') ?>
                          </div>
                        </label>
                      </div>
                      <div class="checkbox-bold">
                        <input type="radio" value="right" name="align_text" id="checkbox-align-text-right" onchange="previewText()">
                        <label for="checkbox-align-text-right">
                          <div class="checkbox-img ">
                            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/right-align.svg') ?>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div id="scope_customize_input">
                <div class="row">
                  <div class="col-sm-12 mb-20px">
                    <div class="font-15px fw-bold">การปรับแต่งการแสดงผล</div>
                  </div>
                </div>
                <div class="row mb-15px">
                  <div class="col-sm-4">
                    <div class="font-16px text-secondary fw-semibold">ขนาดช่อง<span class="text-danger">*</span></div>
                  </div>
                  <div class="col-sm-8">
                    <div class="max-w-150px w-100">
                      <?= TiwForm::normal('select', '100', ['id' => 'size_display_text', 'name' => 'size_display_text'], $size_display); ?>
                    </div>
                  </div>
                </div>
                <div class="row mb-15px">
                  <div class="col-sm-4">
                    <div class="font-16px text-secondary fw-semibold">การแสดงผล</div>
                  </div>
                  <div class="col-sm-8">
                    <div class="d-flex pr-10px mx-10px">
                      <div class="checkbox-bold mr-10px">
                        <input type="radio" value="left" name="alignment_display" id="checkbox-align-display-left" checked>
                        <label for="checkbox-align-display-left">
                          <div class="checkbox-img ">
                            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
                          </div>
                        </label>
                      </div>
                      <div class="checkbox-bold mr-10px">
                        <input type="radio" value="center" name="alignment_display" id="checkbox-align-display-center">
                        <label for="checkbox-align-display-center">
                          <div class="checkbox-img ">
                            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/center-alignment.svg') ?>
                          </div>
                        </label>
                      </div>
                      <div class="checkbox-bold">
                        <input type="radio" value="right" name="alignment_display" id="checkbox-align-display-right">
                        <label for="checkbox-align-display-right">
                          <div class="checkbox-img ">
                            <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>
          </div>
        </div>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <?php Tiwdal::startModal('modal_set_image_profile', 'max-w-700px', ['slide' => true]); ?>
    <div class="modal-body p-0">
      <div class="w-card h-auto">
        <div class="w-card-title-bar border-bottom">
          <div class="d-flex flex-wrap align-items-center">
            <div class="font-16px fw-semibold text-primary">กำหนดรูปแบบรูปโปรไฟล์</div>
          </div>
          <div class="btn-edit-container">
            <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
            <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px">Confirm</button>
          </div>
        </div>
      </div>
      <div class="container-fluid px-20px">
        <div class="row">
          <div class="col-sm-12 mt-20px mb-20px">
            <div class="font-15px fw-bold">การปรับแต่ง</div>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold">รูปทรง<span class="text-danger">*</span></div>
          </div>
          <div class="col-sm-8">
            <div class="mb-15px">
              <?= TiwForm::normal('radio', '', ['name' => ''], ['style' => '2', 'label' => 'วงกลม']); ?>
            </div>
            <div>
              <?= TiwForm::normal('radio', '', ['name' => ''], ['style' => '2', 'label' => 'สี่เหลี่ยมจตุรัส']); ?>
            </div>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold">ขนาด<span class="text-danger">*</span></div>
          </div>
          <div class="col-sm-8">
            <div class="d-flex align-items-center">
              <div class="max-w-100px w-100">
                <?= TiwForm::normal('text', '', ['name' => ''], []); ?>
              </div>
              <div class="fonf-16px fw-medium">Pixel</div>
            </div>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold">ตำแหน่ง</div>
          </div>
          <div class="col-sm-8">
            <div id="scope_button_group_alignment" class="d-flex">
              <div class="mr-10px">
                <button type="button" class="btn-action-icon secondary active">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/left-alignment.svg') ?>
                </button>
              </div>
              <div class="mr-10px">
                <button type="button" class="btn-action-icon secondary">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/center-alignment.svg') ?>
                </button>
              </div>
              <div>
                <button type="button" class="btn-action-icon secondary">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/right-alignment.svg') ?>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold">การใช้งาน</div>
          </div>
          <div class="col-sm-8">
            <?= TiwForm::normal('checkbox', '', ['name' => '', 'checked' => true], ['style' => '1', 'label' => 'เปิดใช้งาน', 'is_on_off' => true]); ?>
          </div>
        </div>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold pt-10px">รูปโปรไฟล์ตั้งต้น</div>
          </div>
          <div class="col-sm-8">
            <div><?= TiwForm::normal('file', '', ['name' => ''], []); ?></div>
            <div class="text-secondary font-15px mt-5px">
              <span class="fw-semibold">ข้อแนะนำ</span>: ไฟล์รูปภาพ .jpg หรือ .png สี่เหลี่ยมจตุรัส (1:1) <br>
              ขนาดไฟล์ไม่เกิน 25MB
            </div>
            <div class="img-preview mt-10px">
              <img src="assets/icon/User Default photo.png" alt="">
            </div>
          </div>
        </div>

        <hr>
        <div class="row mb-20px">
          <div class="col-sm-12">
            <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12 mb-15px">
            <div class="d-flex align-items-center">
              <div>
                <?= W_Structure::getfile('_framework/modules/sub/jform/icon/check-primary.svg') ?>
              </div>
              <div class="text-primary ml-10px">
                จำกัดการอัปโหลดเพียง 1 รูป
              </div>
            </div>
          </div>
          <div class="col-sm-12 mb-15px">
            <?= TiwForm::normal('checkbox', '', ['name' => ''], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล (อัปโหลดเพื่อแก้ไขภายหลัง)', 'is_on_off' => true]); ?>
          </div>
          <div class="col-sm-12 mb-15px">
            <?= TiwForm::normal('checkbox', '', ['name' => ''], ['style' => '1', 'label' => 'ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้', 'is_on_off' => true]); ?>
          </div>
          <div class="col-sm-12 mb-15px">
            <?= TiwForm::normal('checkbox', '', ['name' => ''], ['style' => '1', 'label' => 'แสดงในหน้ารายการ (List)', 'is_on_off' => true]); ?>
          </div>
        </div>
        <hr>
      </div>
    </div>
    <?php Tiwdal::endModal() ?>


    <?php Tiwdal::startModal('customize_page', 'max-w-800px'); ?>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
    <div class="modal-body bg-inner-modal">
      <div class="row mb-20px mt-30px">
        <div class="col-lg-12 text-center">
          <div class="font-20px fw-bold text-primary">การตกแต่งหน้ากระดาษ</div>
          <div class="font-17px fw-medium text-secondary mt-10px">เลือกรูปแบบการตกแต่งที่คุณต้องการ</div>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-center mb-30px">
        <div class="card-box mx-10px" data-bs-dismiss="modal" <?= Tiwdal::register('modal_set_line', []); ?>>
          <div class="__body">
            <div class="__icon">
              <img src="<?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/customize-line.png') ?>" alt="เส้น">
            </div>
            <div class="__header">
              เส้น
            </div>
            <div class="__sub-text">
              วาดเส้นตรง ใช้สำหรับคั่น
              ส่วนต่าง ๆ หรือตกแต่ง
            </div>
          </div>
        </div>
        <div class="card-box mx-10px" data-bs-dismiss="modal" <?= Tiwdal::register('modal_set_image', []); ?>>
          <div class="__body">
            <div class="__icon">
              <img src=" <?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/customize-image.png') ?>" alt="ภาพประกอบ">
            </div>
            <div class="__header">
              ภาพประกอบ
            </div>
            <div class="__sub-text">
              วางภาพประกอบเพื่อการ
              ตกแต่งหน้ากระดาษ
            </div>
          </div>
        </div>
        <div class="card-box mx-10px" data-bs-dismiss="modal" <?= Tiwdal::register('add_option_slider_image', ['table_name' => $table_name, 'template' => $template], ['is_ajax' => true, 'modal_prefix' => $path]); ?>>
          <div class="__body">
            <div class="__icon">
              <img src=" <?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/slider-image.png') ?>" alt="อัลบั้ม / สไลด์โชว์">
            </div>
            <div class="__header">
              อัลบั้ม / สไลด์โชว์
            </div>
            <div class="__sub-text">
              แสดงผลรูปภาพจาก
              ข้อมูลรูปในระบบ
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php Tiwdal::endModal() ?>

    <?php Tiwdal::startModal('modal_set_line', 'max-w-700px'); ?>
    <form id="form_option_line">
      <input type="hidden" name="type" value="line">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-header">
        <h5 class="modal-title text-primary">การตกแต่งหน้ากระดาษ</h5>
      </div>
      <div class="modal-body p-0">
        <div class="container-fluid px-20px">
          <div class="row">
            <div class="col-sm-12 mt-20px mb-20px">
              <div class="font-15px fw-bold text-primary">กำหนดรูปแบบเส้น</div>
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รุปแบบเส้น</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px fw-medium">เส้นตรง</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ขนาดเส้น</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center">
                <div class="max-w-100px w-100">
                  <?= TiwForm::normal('number', '1', ['name' => 'size_line'], []); ?>
                </div>
                <div class="fonf-16px fw-medium">Pixel</div>
              </div>
            </div>
          </div>

          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">สี</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center scope_color_checkbox">
                <div class="radio-custom mr-5px">
                  <input type="radio" value="var(--v2-color-info)" name="color_line" id="radio-info--line" checked>
                  <label for="radio-info--line" class="bg-info">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="radio-custom mr-5px">
                  <input type="radio" value="var(--v2-color-secondary)" name="color_line" id="radio-secondary--line">
                  <label for="radio-secondary--line" class="bg-secondary">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="radio-custom pr-5px mr-5px">
                  <input type="radio" value="var(--v2-color-border)" name="color_line" id="radio-border--line">
                  <label for="radio-border--line" style="color:var(--v2-color-border);">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="radio-custom pr-5px mr-5px border-right">
                  <input type="radio" value="var(--v2-color-table-disable)" name="color_line" id="radio-disable--line">
                  <label for="radio-disable--line" style="color:var(--v2-color-table-disable);">
                    <div class="radio-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/checking-mark.svg') ?>
                    </div>
                  </label>
                </div>
                <label class="btn-action-icon" for="event_custom_color">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/color.svg') ?>
                  <input id="event_custom_color" type="color" name="custom_color_line" class="input-color-sm" onchange="customInputTitleColor(this);">
                </label>
              </div>
              <div class="text-secondary font-15px mt-5px">ในกรณีที่คุณกำหนดเอง สีจะไม่เปลี่ยนเมื่อสลับธีมระบบ</div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12 mt-20px mb-20px">
              <div class="font-15px fw-bold text-dark">การแสดงผล</div>
            </div>
          </div>

          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center">
                <div class="max-w-100px w-100">
                  <?= TiwForm::normal('select', '100', ['name' => 'size_display_line'], $size_display); ?>
                </div>
                <div class="fonf-16px fw-medium">ของพื้นที่</div>
              </div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex">
                <div class="checkbox-bold mr-10px">
                  <input type="radio" value="left" name="alignment_line" id="checkbox-align-line-left" checked>
                  <label for="checkbox-align-line-left">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="checkbox-bold mr-10px">
                  <input type="radio" value="center" name="alignment_line" id="checkbox-align-line-center">
                  <label for="checkbox-align-line-center">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/center-alignment.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="checkbox-bold">
                  <input type="radio" value="right" name="alignment_line" id="checkbox-align-line-right">
                  <label for="checkbox-align-line-right">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
                    </div>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <hr>
        </div>
      </div>
      <div class="modal-footer">
        <?=
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
        TiwForm::normal('btn', '', ['id' => '', 'name' => '', 'type' => 'button', 'class' => 'min-w-120px', 'data-bs-dismiss' => 'modal', 'onclick' => 'confirmAddLine();'], ['text' => 'ยินยัน', 'type' => '']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <?php Tiwdal::startModal('modal_set_image', 'max-w-700px'); ?>
    <form id="form_set_image" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="image">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-header">
        <h5 class="modal-title text-primary">การตกแต่งหน้ากระดาษ</h5>
      </div>
      <div class="modal-body p-0">
        <div class="container-fluid px-20px">
          <div class="row">
            <div class="col-sm-12 mt-20px mb-20px">
              <div class="font-15px fw-bold text-primary">กำหนดการแสดงรูปภาพ</div>
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">อัปโหลดรูปภาพ</div>
            </div>
            <div class="col-sm-8">
              <div>
                <?= TiwForm::normal('file', '', ['id' => 'event_upload_file', 'name' => 'image', 'onchange' => "uploadImage(event,'event_show_preview_img')"], []); ?>
              </div>
              <div class="text-secondary font-15px">
                <span class="font-semibold">ข้อแนะนำ:</span> ไฟล์รูปภาพ .jpg หรือ .png ขนาดไฟล์ไม่เกิน 25MB <br>
                *อัปโหลดไฟล์ที่พอดีกับขนาดจอเพื่อการแสดงผลที่ดีที่สุด
              </div>

              <div id="event_show_preview_img" class="img-preview">
                <img src="" alt="" class="mt-20px mw-100">
              </div>

            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ขนาดรูปภาพ (pixel)</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center">
                <div class="max-w-100px w-100">
                  <?= TiwForm::normal('number', '', ['name' => 'img_width'], []); ?>
                  <div class="mt-5px text-secondary">กว้าง</div>
                </div>
                <div class="mx-5px">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/lock.svg') ?>
                </div>
                <div class="max-w-100px w-100">
                  <?= TiwForm::normal('number', '', ['name' => 'img_height'], []); ?>
                  <div class="mt-5px text-secondary">สูง</div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12 mt-20px mb-20px">
              <div class="font-15px fw-bold text-dark">การแสดงผล</div>
            </div>
          </div>

          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex align-items-center">
                <div class="max-w-100px w-100">
                  <?= TiwForm::normal('select', '', ['name' => 'size_display_image'], $size_display); ?>
                </div>
                <div class="fonf-16px fw-medium">ของพื้นที่</div>
              </div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
            </div>
            <div class="col-sm-8">
              <div class="d-flex">
                <div class="checkbox-bold mr-10px">
                  <input type="radio" value="left" name="alignment_img" id="checkbox-align-image--left" checked>
                  <label for="checkbox-align-image--left">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="checkbox-bold mr-10px">
                  <input type="radio" value="center" name="alignment_img" id="checkbox-align-image--center">
                  <label for="checkbox-align-image--center">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/center-alignment.svg') ?>
                    </div>
                  </label>
                </div>
                <div class="checkbox-bold">
                  <input type="radio" value="right" name="alignment_img" id="checkbox-align-image--right">
                  <label for="checkbox-align-image--right">
                    <div class="checkbox-img ">
                      <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
                    </div>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <hr>
        </div>
      </div>
      <div class="modal-footer">
        <?=
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
        TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_set_image_page', 'type' => 'button', 'data-bs-dismiss' => 'modal', 'class' => 'min-w-120px', 'onclick' => "confirmImage();"], ['text' => 'ยินยัน', 'type' => '']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <?php Tiwdal::startModal('modal_import', 'max-w-600px'); ?>
    <form id="form_import">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-header">
        <h5 class="modal-title text-primary">นำเข้าข้อมูล</h5>
      </div>
      <div class="modal-body px-0">
        <div class="row mb-20px px-20px">
          <div class="col-12">
            <div class="font-16px fw-semibold text-primary">การนำเข้าข้อมูล</div>
            <div class="font-15px text-secondary">คุณสามารถนำเข้าข้อมูลที่ปรับแต่งไว้จากหน้าอื่น เข้ามายังหน้านี้ได้</div>
          </div>
        </div>
        <div id="scope_event_click_import" class="d-flex justify-content-center px-20px mb-20px">
          <div class="card-list-select mx-10px active" data-type="import_all" onclick="selectTypeImport(this)">
            <div class="__img">
              <img src="<?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/import-1.png') ?>" alt="">
            </div>
            <div class="__title">
              นำเข้าข้อมูลทั้งหมด
            </div>
            <div class="__des">
              นำเข้าข้อมูลทั้งหมดจาก
              Template อื่น
            </div>
          </div>
          <div class="card-list-select mx-10px disabled-box" data-type="import" onclick="selectTypeImport(this)">
            <div class="__img">
              <img src="<?= W_Structure::getLinkURL('_framework/modules/sub/jform/images/import-2.png') ?>" alt="">
            </div>
            <div class="__title">
              เจาะจงการนำเข้าข้อมูล
            </div>
            <div class="__des">
              เลือกนำเข้าข้อมูล 1 อย่าง
              จาก Template อื่น
            </div>
          </div>
        </div>
        <hr>
        <div id="scope_show_form_import">
          <?= template_form_import_all($code); ?>
        </div>
      </div>
      <div class="modal-footer">
        <?=
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
        TiwForm::normal('btn', '', ['id' => '', 'name' => '', 'type' => 'button', 'class' => 'min-w-120px', 'onclick' => 'confirmImportAll();', 'data-bs-dismiss' => 'modal'], ['text' => 'นำเข้า', 'type' => '']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <!-- Modal Create Tab -->
    <?php Tiwdal::startModal('modal_add_tab', 'max-w-700px'); ?>
    <form id="form_add_tab" method="post">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-header">
        <h5 class="modal-title text-primary">เพิ่ม Tab ใหม่</h5>
      </div>
      <div class="modal-body p-0">
        <div class="container-fluid px-20px">
          <div class="row mt-10px mb-20px">
            <div class="col-lg-12">
              <div class="font-15px fw-bold text-primary">รายละเอียด Tab</div>
            </div>
          </div>
          <div class="row mb-15px align-items-center">
            <div class="col-sm-4">
              <div class="font-16px fw-semibold text-secondary required">ชื่อ Tab</div>
            </div>
            <div class="col-sm-8">
              <?= TiwForm::normal('text', '', ['name' => 'tab_name', 'required' => 'true'], []); ?>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px fw-semibold text-secondary">หมายเหตุ</div>
            </div>
            <div class="col-sm-8">
              <?= TiwForm::normal('textarea', '', ['name' => 'remark', 'placeholder' => 'กรอกหากมี', 'class' => 'h-auto'], []); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <?=
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
        TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_add_tab', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'เพิ่ม', 'type' => '']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <?php Tiwdal::startModal('modal_edit_tab', 'max-w-700px'); ?>
    <form id="form_edit_tab" method="post">
      <input type="hidden" name="type" value="line">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-header">
        <h5 class="modal-title text-primary">ตั้งค่า Tab</h5>
      </div>
      <div class="modal-body p-0">
        <div class="container-fluid px-20px">
          <div class="row mt-10px mb-20px">
            <div class="col-lg-12">
              <div class="font-15px fw-bold text-primary">รายละเอียด Tab</div>
            </div>
          </div>
          <div class="row mb-15px align-items-center">
            <div class="col-sm-4">
              <div class="font-16px fw-semibold text-secondary required">ชื่อ Tab</div>
            </div>
            <div class="col-sm-8">
              <?= TiwForm::normal('text', '', ['name' => '{tab_name}', 'required' => 'true'], []); ?>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px fw-semibold text-secondary">หมายเหตุ</div>
            </div>
            <div class="col-sm-8">
              <?= TiwForm::normal('textarea', '', ['name' => '{remark}', 'placeholder' => 'กรอกหากมี', 'class' => 'h-auto'], []); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="{tab_id}">
        <input type="hidden" name="{sys_function_id}" value="<?= $data['sys_function_id'] ?>">
        <?=
        TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_delete_tab', 'type' => 'submit', 'class' => 'btn-close-modal btn-danger min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ลบข้อมูล', 'type' => '']);
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
        TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_edit_tab', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'แก้ไข', 'type' => '']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal() ?>

    <!-- Script -->
    <script>
      var uri = window.location.href;
      var uri = uri.split('/');
      var index_framework = uri.indexOf('_framework');
      var path__link;
      if (index_framework != -1) {
        path__link = '../../../_framework/modules/sub/jform/';
      } else {
        path__link = '../../_framework/modules/sub/jform/';
      }

      window.addEventListener('DOMContentLoaded', (event) => {
        getListFill();
      });


      const form_json = document.getElementById('form_json');
      const scope_list_fill = document.getElementById('scope_list_fill');

      // Ajax HTTP
      const sendHttpRequest = (method, url, data) => {
        const promise = new Promise((resolve, reject) => {
          const xhr = new XMLHttpRequest();
          xhr.open(method, url, true);
          xhr.responseType = 'json';
          if (data) {
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          }
          xhr.onload = () => {
            if (xhr.status >= 400) {
              reject(xhr.response);
            } else {
              resolve(xhr.response);
            }
          }
          xhr.onerror = () => {
            reject('Someting wrong');
          }
          xhr.send(data);
        });
        return promise;
      }

      // GetListFill Page
      const getListFill = () => {
        let html = '';
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_show_list_fill_input.php?c=<?= $code ?>&tab=<?= $tab_id ?>&func=<?= $data['sys_function_id'] ?>&template=<?= $template_name ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            html = this.response;
            scope_list_fill.innerHTML = '';
            scope_list_fill.insertAdjacentHTML('beforeend', html);
            let json_data_form = w.serializeJSON(form_json);
          }
        };
        xhr.send();
      }

      const confirmAddFill = async () => {
        var form_data_string = '';
        let url = path__link + 'ajax/ajax_form_option.php';
        wx.formData(url, 'form_option', function(data) {
          form_data_string = JSON.parse(data);
          if (document.forms["form_option"]["input_title"].value != '') {
            let myModal = document.getElementById('modal_add_option_fill');
            let modal = bootstrap.Modal.getInstance(myModal)
            modal.hide();
            templateFormFill(form_data_string);
          }
        });
      }

      const confirmAddSliderImage = async () => {
        var form_data_string = '';
        let url = path__link + 'ajax/ajax_form_option.php';
        wx.formData(url, 'form_option_slider_image', function(data) {
          form_data_string = JSON.parse(data);
          let slider_first = document.getElementById('event_slider_image_0').value;
          console.log(slider_first);
          if (slider_first != '') {
            let myModal = document.getElementById('add_option_slider_image');
            let modal = bootstrap.Modal.getInstance(myModal)
            modal.hide();
            templateFormFill(form_data_string);
          }
        });
      }

      const confirmEditImageSlider = async (inx) => {
        let url = path__link + 'ajax/ajax_form_option.php';
        wx.formData(url, 'form_option_edit_slider_image', function(data) {
          let json_data_form = w.serializeJSON(form_json);
          saveEditPage(json_data_form, data, inx);
        });
      }


      const editImageSlider = (ele, inx) => {
        let scope = document.getElementById(ele);
        let event_get_dataset = scope.querySelector('.event_get_dataset');
        let data = event_get_dataset.value;
        let json_data = JSON.parse(data);
        let scope_modal_edit_option_slider_image = document.getElementById('scope_modal_edit_option_slider_image');
        let params = 'sys_function_id=<?= $data['sys_function_id'] ?>&meta_data=' + data + '&index=' + inx + '&table_name=<?= $table_name ?>';
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_edit_option_slider_image.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            scope_modal_edit_option_slider_image.innerHTML = `${this.response}`;
            let modal_edit_option_slider_image = new bootstrap.Modal(document.getElementById('modal_option_edit_slider_image'));
            modal_edit_option_slider_image.show();
          }
        };
        xhr.send(params);
      }

      const confirmAddLine = async () => {
        const myForm = document.getElementById('form_option_line');
        const formData = new FormData(myForm);
        let form_data = {};
        for (const [key, value] of formData) {
          form_data[key] = value;
        }
        let form_data_string = JSON.parse(JSON.stringify(form_data));
        templateLine(form_data_string);
      }

      const confirmAddText = async () => {
        const myForm = document.getElementById('form_option_text');
        const formData = new FormData(myForm);
        let form_data = {};
        for (const [key, value] of formData) {
          form_data[key] = value;
        }
        let form_data_string = JSON.parse(JSON.stringify(form_data));
        templateText(form_data_string);
      }


      const confirmImage = async () => {
        const myForm = document.getElementById('form_set_image');
        let formData = new FormData(myForm);
        let myFileInput = document.getElementById('event_upload_file');
        let form_data = {};
        for (const [key, value] of formData) {
          form_data[key] = value;
        }
        formData.append("image", myFileInput.files[0]);
        let form_data_string = JSON.stringify(form_data);
        let url = path__link + 'ajax/ajax_upload_image.php?c=<?= $code ?>';
        wx.formData(url, 'form_set_image', function(res) {
          let data_json = JSON.parse(res);
          templateImage(data_json);
        });
      }

      const confirmEditImage = async (old_img, inx) => {
        const myForm = document.getElementById('form_edit_set_image');
        let formData = new FormData(myForm);
        let myFileInput = document.getElementById('event_edit_file');
        let url = path__link + 'ajax/ajax_upload_image.php?c=<?= $code ?>&is_edit=1&unique_name=' + old_img + '&index=' + inx + '&template=<?= $template_name ?>';
        wx.formData(url, 'form_edit_set_image', function(res) {
          let json_data_form = w.serializeJSON(form_json);
          saveEditPage(json_data_form, res, inx);
        });
      }

      const editImage = (ele, inx) => {
        let scope = document.getElementById(ele);
        let event_get_dataset = scope.querySelector('.event_get_dataset');
        let data = event_get_dataset.value;
        let json_data = JSON.parse(data);
        let scope_modal_edit_image = document.getElementById('scope_modal_edit_image');
        let params = 'sys_function_id=<?= $data['sys_function_id'] ?>&meta_data=' + data + '&index=' + inx;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_edit_image.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            scope_modal_edit_image.innerHTML = `${this.response}`;
            let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_set_image'));
            modal_edit_option_fill.show();
            let scope_edit_file = document.getElementById('scope_edit_file');
            let btn_delete_file = scope_edit_file.querySelector('.delete_normal_file_event');
            let myFileInput = document.getElementById('event_edit_file');
            btn_delete_file.addEventListener('click', function() {
              scope_edit_file.querySelector('.form-file').classList.remove('active');
              document.getElementById('event_show_preview_img').querySelector('img').src = '';
            });
          }
        };
        xhr.send(params);
      }

      // Event Edit Option Fill Form
      const confirmEditFill = async (inx) => {
        let url = path__link + 'ajax/ajax_form_option.php';
        wx.formData(url, 'form_edit_option', function(data) {
          let json_data_form = w.serializeJSON(form_json);
          saveEditPage(json_data_form, data, inx);
        });
      }

      const editFill = (ele, inx) => {
        let scope = document.getElementById(ele);
        let event_get_dataset = scope.querySelector('.event_get_dataset');
        let data = event_get_dataset.value;
        let json_data = JSON.parse(data);
        let scope_modal_edit_option = document.getElementById('scope_modal_edit_option');

        let params = 'sys_function_id=<?= $data['sys_function_id'] ?>&meta_data=' + data + '&index=' + inx;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_edit_option_fill.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            scope_modal_edit_option.innerHTML = `${this.response}`;
            let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_option_fill'));
            modal_edit_option_fill.show();
          }
        };
        xhr.send(params);
      }


      // Event Edit Option Text
      const confirmEditText = async (inx) => {
        const myForm = document.getElementById('form_edit_option_text');
        const formData = new FormData(myForm);
        let form_data = {};
        for (const [key, value] of formData) {
          form_data[key] = value;
        }
        let edit_data = JSON.stringify(form_data);
        let json_data_form = w.serializeJSON(form_json);
        saveEditPage(json_data_form, edit_data, inx);
      }

      const editText = (ele, inx) => {
        let scope = document.getElementById(ele);
        let event_get_dataset = scope.querySelector('.event_get_dataset');
        let data = event_get_dataset.value;
        let json_data = JSON.parse(data);
        let scope_modal_edit_text = document.getElementById('scope_modal_edit_text');
        let params = 'sys_function_id=<?= $data['sys_function_id'] ?>&meta_data=' + data + '&index=' + inx;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_edit_option_text.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            scope_modal_edit_text.innerHTML = `${this.response}`;
            let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_option_text'));
            modal_edit_option_fill.show();
            previewEditText();
          }
        };
        xhr.send(params);
      }


      function selectOptionAllType() {
        const scope_condition_upload = document.getElementById('scope_condition_upload');
        const select_all_type = document.querySelector('input[name=type_select_all]');
        let input_checkbox_all = scope_condition_upload.querySelectorAll('.scope_event_checked_all input');
        if (select_all_type.checked == true) {
          input_checkbox_all.forEach((e) => {
            e.checked = true
          });
        } else {
          input_checkbox_all.forEach((e) => {
            e.checked = false
          });
        }
      }

      function selectOptionTypeImage() {
        let scope_condition_upload = document.getElementById('scope_condition_upload');
        let select_all_img = document.querySelector('input[name=type_img_all]');
        let input_checkbox_img = scope_condition_upload.querySelectorAll('.event_check_img_all input[type="checkbox"]');
        let input_checkbox_img_checked = scope_condition_upload.querySelectorAll('.event_check_img_all input[type="checkbox"]:checked');
        if (input_checkbox_img.length == input_checkbox_img_checked.length) {
          select_all_img.checked = true;
        } else {
          select_all_img.checked = false;
        }
      }

      function selectOptionTypeVideo() {
        let scope_condition_upload = document.getElementById('scope_condition_upload');
        let select_all_video = document.querySelector('input[name=type_video_all]');
        let input_checkbox_video = scope_condition_upload.querySelectorAll('.event_check_video_all input[type="checkbox"]');
        let input_checkbox_video_checked = scope_condition_upload.querySelectorAll('.event_check_video_all input[type="checkbox"]:checked');
        if (input_checkbox_video.length == input_checkbox_video_checked.length) {
          select_all_video.checked = true;
        } else {
          select_all_video.checked = false;
        }
      }

      function selectOptionTypeFile() {
        let scope_condition_upload = document.getElementById('scope_condition_upload');
        let select_all_file = document.querySelector('input[name=type_file_all]');
        let input_checkbox_file = scope_condition_upload.querySelectorAll('.event_check_file_all input[type="checkbox"]');
        let input_checkbox_file_checked = scope_condition_upload.querySelectorAll('.event_check_file_all input[type="checkbox"]:checked');
        if (input_checkbox_file.length == input_checkbox_file_checked.length) {
          select_all_file.checked = true;
        } else {
          select_all_file.checked = false;
        }
      }

      function selectOptionTypeUpload(mode) {
        const scope_condition_upload = document.getElementById('scope_condition_upload');
        const select_all_type = document.querySelector('input[name=type_select_all]');
        const select_all_img = document.querySelector('input[name=type_img_all]');
        const select_all_video = document.querySelector('input[name=type_video_all]');
        const select_all_file = document.querySelector('input[name=type_file_all]');

        if (mode == 'img') {
          let input_checkbox_img = scope_condition_upload.querySelectorAll('.event_check_img_all input');
          if (select_all_img.checked == true) {
            input_checkbox_img.forEach((e) => {
              e.checked = true
            });
          } else {
            input_checkbox_img.forEach((e) => {
              e.checked = false
            });
          }
        }

        if (mode == 'video') {
          let input_checkbox_video = scope_condition_upload.querySelectorAll('.event_check_video_all input');
          if (select_all_video.checked == true) {
            input_checkbox_video.forEach((e) => {
              e.checked = true
            });
          } else {
            input_checkbox_video.forEach((e) => {
              e.checked = false
            });
          }
        }

        if (mode == 'file') {
          let input_checkbox_file = scope_condition_upload.querySelectorAll('.event_check_file_all input');
          if (select_all_file.checked == true) {
            input_checkbox_file.forEach((e) => {
              e.checked = true
            });
          } else {
            input_checkbox_file.forEach((e) => {
              e.checked = false
            });
          }
        }

      }

      // Event Edit Option line
      const confirmEditLine = async (inx) => {
        const myForm = document.getElementById('form_edit_option_line');
        const formData = new FormData(myForm);
        let form_data = {};
        for (const [key, value] of formData) {
          form_data[key] = value;
        }
        let edit_data = JSON.stringify(form_data);
        let json_data_form = w.serializeJSON(form_json);
        saveEditPage(json_data_form, edit_data, inx);
      }

      const editLine = (ele, inx) => {
        let scope = document.getElementById(ele);
        let event_get_dataset = scope.querySelector('.event_get_dataset');
        let data = event_get_dataset.value;
        let json_data = JSON.parse(data);
        let scope_modal_edit_line = document.getElementById('scope_modal_edit_line');
        let params = 'sys_function_id=<?= $data['sys_function_id'] ?>&meta_data=' + data + '&index=' + inx;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_edit_option_line.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            scope_modal_edit_line.innerHTML = `${this.response}`;
            let modal_edit_option_fill = new bootstrap.Modal(document.getElementById('modal_edit_option_line'));
            modal_edit_option_fill.show();
          }
        };
        xhr.send(params);
      }

      // Template
      function templateFormFill(data) {
        let id = w.tiwform.randomName(10);
        let html = '';
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_create_input.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            html = this.response;
            scope_list_fill.insertAdjacentHTML('beforeend', html);
            let json_data_form = w.serializeJSON(form_json);
            savePage(json_data_form);
          }
        };
        let data_base64 = btoa(unescape(encodeURIComponent(JSON.stringify(data))));

        let params = 'data_send=' + data_base64;
        xhr.send(params);
      }

      function templateLine(data) {
        let id = w.tiwform.randomName(10);
        let html = `
      <div class="card-sortable">
        <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            <div style="width:${data.size_display_line}%;background-color:${data.color_line};height:${data.size_line}px;"></div>
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit"></button>
          </div>
        </div>
      </div>
      `;
        scope_list_fill.insertAdjacentHTML('beforeend', html);
        let json_data_form = w.serializeJSON(form_json);
        savePage(json_data_form);
      }

      function templateText(data) {
        let id = w.tiwform.randomName(10);
        let align_text = data.align_text ?? data.align_text;
        let color = data.custom_color_text ?? data.custom_color_text;
        let font_size = data.font_size ?? data.font_size;
        let font_family = data.font_family ?? data.font_family;
        let size_display_text = data.size_display_text ?? data.size_display_text;
        let alignment_display = data.alignment_display ?? data.alignment_display;
        if (alignment_display == 'left') {
          alignment_display = 'me-auto';
        } else if (alignment_display == 'center') {
          alignment_display = 'mx-auto';
        } else if (alignment_display == 'right') {
          alignment_display = 'ms-auto';
        } else {
          alignment_display = '';
        }

        let html = `
      <div class="card-sortable ${alignment_display}" style="width:${size_display_text}%">
        <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            <div style="color:${color};text-align:${align_text};font-size:${font_size}px;font-family:${font_family};" class="${data.font_bold && data.font_bold == 1 ? 'fw-bold' : ''} ${data.font_italic && data.font_italic == 1 ? 'fst-italic' : ''}">${data.title_text}</div>
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit"></button>
          </div>
        </div>
      </div>
      `;
        scope_list_fill.insertAdjacentHTML('beforeend', html);
        let json_data_form = w.serializeJSON(form_json);
        savePage(json_data_form);
      }

      function templateImage(data) {
        let params = 'file=' + data.file;

        sendHttpRequest('POST', path__link + 'ajax/ajax_get_file.php?c=<?= $code ?>&tab=<?= $tab_id ?>', params).then(responseData => {
          let id = w.tiwform.randomName(10);
          let alignment_img = data.option_file.alignment_img ?? data.option_file.alignment_img;
          if (alignment_img == 'left') {
            alignment_img = 'me-auto';
          } else if (alignment_img == 'center') {
            alignment_img = 'mx-auto';
          } else if (alignment_img == 'right') {
            alignment_img = 'ms-auto';
          } else {
            alignment_img = '';
          }
          let html = `
        <div style="width:${data.option_file.size_display_image}%;">
          <input type="hidden" name="list[${id}][dataset]" value='${JSON.stringify(data)}'>
          <img src="${responseData.unique_name}" style="max-width:100%;width:${data.option_file.img_width}px;height:${data.option_file.img_height}px;">
        </div>
        `;
          scope_list_fill.insertAdjacentHTML('beforeend', html);
          let json_data_form = w.serializeJSON(form_json);
          savePage(json_data_form);
        }).catch(err => {
          console.log(err);
        })
      }


      function cloneItem(ele, type) {
        let scope = document.getElementById(ele);
        let data = scope.querySelector('.event_get_dataset').value;
        let json_data = JSON.parse(data);
        if (type == 'text') {
          templateText(json_data);
        } else if (type == 'line') {
          templateLine(json_data);
        } else if (type == 'image') {
          templateImage(json_data);
        }
      }

      const savePage = async (dataset) => {
        let params = 'func=<?= $data['sys_function_id'] ?>&meta_data=' + dataset;
        sendHttpRequest('POST', path__link + 'ajax/ajax_save_page.php?c=<?= $code ?>&tab=<?= $tab_id ?>&template=<?= $template_name ?>', params).then(responseData => {
          setTimeout(() => {
            getListFill();
            let result = responseData;
            w.notification(result.response_status ? 'success' : 'error', result.response_message);
          }, 1000);
        }).catch(err => {
          console.log(err);
        })
      }

      const saveEditPage = async (dataset, edit_data, inx) => {
        let params = 'func=<?= $data['sys_function_id'] ?>&meta_data=' + dataset + '&index=' + inx + '&edit_data=' + edit_data;
        sendHttpRequest('POST', path__link + 'ajax/ajax_save_page.php?c=<?= $code ?>&tab=<?= $tab_id ?>&is_edit=1&template=<?= $template_name ?>', params).then(responseData => {
          getListFill();
          let result = responseData;
          w.notification(result.response_status ? 'success' : 'error', result.response_message);
        }).catch(err => {
          console.log(err);
        })
      }

      function confirmSettingPage() {
        const myForm = document.getElementById('form_setting_page');
        const formData = new FormData(myForm);
        let form_data = {};
        for (const [key, value] of formData) {
          form_data[key] = value;
        }
        let data_form_setting = JSON.stringify(form_data);
        let json_data_form = w.serializeJSON(form_json);
        saveSettingPage(data_form_setting, json_data_form);
      }

      const saveSettingPage = async (data, dataset) => {
        let params = 'func=<?= $data['sys_function_id'] ?>&tab_setting=' + data + '&meta_data=' + dataset;
        sendHttpRequest('POST', path__link + 'ajax/ajax_page_setting.php?c=<?= $code ?>&tab=<?= $tab_id ?>&template=<?= $template_name ?>', params).then(responseData => {
          setTimeout(() => {
            getListFill();
          }, 1000);
          // console.log(responseData);
        }).catch(err => {
          console.log(err);
        })
      }

      // Sortable
      sortList();

      function sortList() {
        var sortable = Sortable.create(scope_list_fill, {
          ghostClass: "ghost",
          handle: '.handle',
          group: {
            name: 'order',
          },
          scroll: true,
          dataIdAttr: 'data-order',
          filter: ".js-remove, .js-edit",
          onFilter: function(evt) {
            var item = evt.item,
              ctrl = evt.target;
            if (Sortable.utils.is(ctrl, ".js-remove")) { // Click on remove button
              item.parentNode.removeChild(item);
              let json_data_form = w.serializeJSON(form_json);
              savePage(json_data_form);
            }
          },
          onEnd: function( /**Event*/ evt) {
            let json_data_form = w.serializeJSON(form_json);
            savePage(json_data_form);
          },
        });
      }

      <?php if ($data['is_tab'] == 1) { ?>
        let scope_list_tab = document.getElementById('scope_list_tab');
        var sortableTab = Sortable.create(scope_list_tab, {
          ghostClass: "ghost",
          handle: '.__handle-tab',
          group: {
            name: 'order',
          },
          dataIdAttr: 'data-order',
          onEnd: function( /**Event*/ evt) {
            saveOrderTab();
          },
        });

        function saveOrderTab() {
          let order = JSON.stringify(sortableTab.toArray());
          let params = 'func=<?= $data['sys_function_id'] ?>&order_tab=' + order + '&template_id=<?= $id_template ?>';
          sendHttpRequest('POST', path__link + 'ajax/ajax_swap_tab.php?c=<?= $code ?>', params).then(responseData => {
            let result = responseData;
            w.notification(result.response_status ? 'success' : 'error', result.response_message);
            window.location.reload();
          }).catch(err => {
            console.log(err);
          })
        }
      <?php } ?>

      // Select Field
      const selectUserField = (id) => {
        sendHttpRequest('GET', path__link + 'ajax/ajax_select_field.php?c=<?= $code ?>&id=' + id + '&table_name=<?= $table_name ?>').then(responseData => {
          let html = ``;
          let scope_show = document.getElementById('scope_show_detail');
          scope_show.innerHTML = ''
          let data = responseData;
          if (data) {
            html +=
              `
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-9">
              ${data.type}
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">Code</div>
            </div>
            <div class="col-9">
              ${data.code}
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">Table Name</div>
            </div>
            <div class="col-9">
              ${data.table_name}
            </div>
          </div>

          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">DB Column Name</div>
            </div>
            <div class="col-9">
              ${data.db_column_name}
            </div>
          </div>
          <div class="row mb-20px">
            <div class="col-3">
              <div class="font-16px fw-semibold text-secondary">หมายเหตุ</div>
            </div>
            <div class="col-9">
              ${data.remark ? data.remark : '-'}
            </div>
          </div>
          `;
          }
          scope_show.insertAdjacentHTML('beforeend', html);
          hideOptionOnSelect();
        }).catch(err => {
          console.log(err);
        })
      }

      function hideOptionOnSelect() {
        var card_sortable = document.querySelectorAll('.card-sortable');
        var fill_arr = []
        card_sortable.forEach((e) => {
          var field_id_value = e.getAttribute('data-fill');
          fill_arr.push(field_id_value);
        });

        fill_arr.forEach(function(data) {
          let option = event_select_field.querySelectorAll('option');
          option.forEach((opt) => {
            if (opt.value == data) {
              opt.setAttribute('hidden', 'hidden');
            }
          })
        });
      }

      const getOptionSelectField = (id) => {
        sendHttpRequest('GET', path__link + 'ajax/ajax_select_field_option.php?c=<?= $code ?>&id=' + id + '&table_name=<?= $table_name ?>').then(responseData => {
          let data = responseData;
          let scope_show = document.getElementById('scope_connect_data');
          let scope_template = document.getElementById('scope_show_template');
          let scope_show_setting_template = document.getElementById('scope_show_setting_template');
          scope_show.innerHTML = '';
          scope_template.innerHTML = ''
          scope_show_setting_template.innerHTML = '';

          scope_show.insertAdjacentHTML('beforeend', data.html);
          scope_template.insertAdjacentHTML('beforeend', data.form_condition);
          scope_show_setting_template.insertAdjacentHTML('beforeend', data.form_setting_template);

        }).catch(err => {
          console.log(err);
        })
      }

      function previewText() {
        let input_text_preview = document.getElementById('event_preview_text');
        let bold = document.getElementById('checkbox-title-bold');
        let italic = document.getElementById('checkbox-title-italic');
        let color = document.getElementById('custom_color_text');

        var checkedValue = null;
        var inputElements = document.getElementsByName('align_text');
        for (var i = 0; inputElements[i]; ++i) {
          if (inputElements[i].checked) {
            checkedValue = inputElements[i].value;
            if (checkedValue == 'left') {
              input_text_preview.classList.add('text-left');
              input_text_preview.classList.remove('text-center');
              input_text_preview.classList.remove('text-end');
            } else if (checkedValue == 'center') {
              input_text_preview.classList.add('text-center');
              input_text_preview.classList.remove('text-left');
              input_text_preview.classList.remove('text-end');
            } else if (checkedValue == 'right') {
              input_text_preview.classList.add('text-end');
              input_text_preview.classList.remove('text-left');
              input_text_preview.classList.remove('text-center');
            } else {
              input_text_preview.classList.add('text-left');
              input_text_preview.classList.remove('text-center');
              input_text_preview.classList.remove('text-end');
            }
            break;
          }
        }

        if (bold.checked == true) {
          input_text_preview.classList.add('fw-bold');
        } else {
          input_text_preview.classList.remove('fw-bold');
        }
        if (italic.checked == true) {
          input_text_preview.classList.add('fst-italic');
        } else {
          input_text_preview.classList.remove('fst-italic');
        }
        input_text_preview.style.color = color.value;
      }

      function previewEditText() {
        let input_text_preview = document.getElementById('event_preview_text_edit');
        let bold = document.getElementById('checkbox-title-bold_edit');
        let italic = document.getElementById('checkbox-title-italic_edit');
        let color = document.getElementById('custom_color_text_edit');

        let checkedValue = null;
        let inputElements = document.getElementsByName('align_text');
        for (let i = 0; inputElements[i]; ++i) {
          if (inputElements[i].checked) {
            checkedValue = inputElements[i].value;
            if (checkedValue == 'left') {
              input_text_preview.classList.add('text-left');
              input_text_preview.classList.remove('text-center');
              input_text_preview.classList.remove('text-end');
            } else if (checkedValue == 'center') {
              input_text_preview.classList.add('text-center');
              input_text_preview.classList.remove('text-left');
              input_text_preview.classList.remove('text-end');
            } else if (checkedValue == 'right') {
              input_text_preview.classList.add('text-end');
              input_text_preview.classList.remove('text-left');
              input_text_preview.classList.remove('text-center');
            } else {
              input_text_preview.classList.add('text-left');
              input_text_preview.classList.remove('text-center');
              input_text_preview.classList.remove('text-end');
            }
            break;
          }
        }

        if (bold.checked == true) {
          input_text_preview.classList.add('fw-bold');
        } else {
          input_text_preview.classList.remove('fw-bold');
        }
        if (italic.checked == true) {
          input_text_preview.classList.add('fst-italic');
        } else {
          input_text_preview.classList.remove('fst-italic');
        }
        input_text_preview.style.color = color.value;
      }

      function clearPageAll() {
        scope_list_fill.innerHTML = '';
        let json_data_form = w.serializeJSON(form_json);
        savePage(json_data_form);
      }

      function selectTypeImport(t) {
        let ele = t.getAttribute('data-type');
        let items = document.querySelectorAll('.card-list-select');
        let scope_show_form_import = document.getElementById('scope_show_form_import');
        scope_show_form_import.innerHTML = '';
        items.forEach((e) => {
          e.classList.remove('active');
        });
        if (ele == 'import_all') {
          scope_show_form_import.innerHTML = `<?= template_form_import_all($code) ?>`;
          let event_select_page = document.getElementById('event_select_page');
          let id = event_select_page.value;
        } else if (ele == 'import') {
          scope_show_form_import.innerHTML = `<?= template_form_import($code) ?>`;
          selectFieldImport();
        }

        t.classList.add('active');
      }

      const confirmImportAll = () => {
        let event_select_page = document.getElementById('event_select_page');
        let template_id = event_select_page.value;
        sendHttpRequest('GET', path__link + 'ajax/ajax_import_template_all.php?c=<?= $code ?>&template=<?= $template ?>&template_id=' + template_id).then(responseData => {
          window.location.reload();
        }).catch(err => {
          console.log(err);
        })
      }

      // const confirmImport = () => {
      //   let event_select_page = document.getElementById('event_select_page');
      //   let template_id = event_select_page.value;
      //   sendHttpRequest('GET', path__link + 'ajax/ajax_import_template_all.php?c=<?= $code ?>&template=<?= $template ?>&template_id=' + template_id).then(responseData => {
      //     window.location.reload();
      //   }).catch(err => {
      //     console.log(err);
      //   })
      // }

      function selectFieldImport() {
        let event_select_page = document.getElementById('event_select_page');
        let template_id = event_select_page.value;
        sendHttpRequest('GET', path__link + 'ajax/ajax_select_field_import.php?c=<?= $code ?>&template=<?= $template ?>&template_id=' + template_id).then(responseData => {
          renderHtmlSelectField(responseData.id)
        }).catch(err => {
          console.log(err);
        })
      }

      function renderHtmlSelectField(id) {
        let params = 'id=' + id;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/html_select_import_field.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            console.log(this);
            let scope_show_detail_import = document.getElementById('scope_show_detail_import');
            scope_show_detail_import.innerHTML = `${this.response}`;
          }
        };
        xhr.send(params);
      }

      function showDetailImport() {
        let event_select_field_name = document.getElementById('event_select_field_name');
        let field_name = event_select_field_name.value;
        let params = 'field_name=' + field_name;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', path__link + 'ajax/ajax_show_detail_import.php?c=<?= $code ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (this.status == 200) {
            let json = JSON.parse(this.response);
            let import_type = document.getElementById('import_type').innerHTML = json[0].name;
            let import_db_column = document.getElementById('import_db_column').innerHTML = json[0].db_column_name;
            let import_db_remark = document.getElementById('import_db_remark').innerHTML = json[0].remark ? json[0].remark : '-';
          }
        };
        xhr.send(params);
      }

      function uploadImage(e, id) {
        const file = e.target.files[0];
        let scope_preview = document.getElementById(id);
        // console.log(scope_preview);
        let img_preview = scope_preview.querySelector('img');
        if (file) {
          var reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = function() {
            img_preview.src = reader.result
          }
        }
      }

      function customInputTitleColor(t) {
        let scope_color_checkbox = t.closest('.scope_color_checkbox');
        let checkbox_color = scope_color_checkbox.querySelectorAll('input[type="radio"]');
        checkbox_color.forEach((e) => {
          e.checked = false;
        });
      }
    </script>

  <?php return ob_get_clean();
  }


  /**
  | Title | เรียกใช้ UI Form
  | Description | 
    $code = code ของ module
    $table_name = ต้องถามจาก Backend ว่าจะเอา Table ไหนมาใช้,
    $template = ใส่ชื่อ Template เพื่อเรียกใช้ Template ของตัวนั้นๆ,
    $template = ใส่ชื่อ Tabname เรียกใช้ form ตาม Tabname,
    $is_edit = ถ้าเป็น True จะสามารถแก้ไข ได้เลย
   */

  public static function generate_form($code, $table_name, $template, $tab = [], $is_edit = false, $mode = '')
  {
    $code  = $code;
    $id  = '';
    $obj = [];
    $file = [];
    if (isset($tab['tab_name'])) {
      $data_module = Extra::getExtraTemplate($code, $template, $tab['tab_name']);
    } else if (isset($tab['tab_id'])) {
      $data_module = Extra::getExtraTemplateTab($tab['tab_id']);
    }

    if (isset($tab['id'])) {
      $id = $tab['id'];
    }
    $extra_record = [];
    if ($id) {
      $extra_record = Extra::getExtraRecordData($code, $table_name, $id);
    }

    if (isset($data_module['meta_data'][0]['meta_data'])) {
      $meta_data = isset($data_module['meta_data'][0]['meta_data']) ? $data_module['meta_data'][0]['meta_data'] :  '';
      $get_setting = isset($data_module['meta_data'][0]['setting']) ? $data_module['meta_data'][0]['setting'] :  '';
      if ($meta_data) {
        foreach ($meta_data as $key => $data) {
          if (isset($data['unique_name'])) {
            $obj[$key] = $data['unique_name'];
          }
        }
        foreach ($obj as $key => $file_path) {
          $file[$key] = File::getPath($file_path);
        }
      }
      $datas = [
        'list' => [
          'file_path' => $file,
          'meta_data' => $meta_data,
        ],
      ];
    }
    ob_start();
  ?>
    <div class="container-w-form mx-auto">
      <?php if (isset($datas['list']['meta_data']) && $datas['list']['meta_data'] != '') : ?>
        <?php $temp_template_data = []; ?>
        <?php foreach ($datas['list']['meta_data'] as $key => $data) : ?>
          <?php
          $type = '';
          if (isset($data['type'])) {
            $type = $data['type'];
          } else {
            if (isset($data['option_file'])) {
              $type = $data['option_file']['type'];
            }
          }
          ?>
          <?php if ($type == 'text') { ?>
            <?= ExtraInput::title($data) ?>
          <?php } else if ($type == 'line') { ?>
            <?= ExtraInput::line($data) ?>
          <?php } else if (isset($data['option_file']['type']) == 'image') { ?>
            <?= ExtraInput::image($data, $datas, $key) ?>
          <?php } else if (isset($data['db_column_name'])) { ?>
            <?php
            $val = '';
            if ($id) {
              $val =  isset($extra_record) ? $extra_record[$data['db_column_name']] : '-';
            }
            $page_spacing = isset($get_setting['page_spacing']) && $get_setting['page_spacing'] ? $get_setting['page_spacing'] : '100';
            $data['category'] = isset($data['category']) ? $data['category'] : '';
            $not_varchar = ['Tag', 'Radio', 'Switch', 'Checkbox', 'Dropdown', 'Auto address', 'Google map link', 'Latitude_Longtitude', 'Link', 'Youtube_embed'];
            ?>
            <?php if ($data['type'] == 'Decimal' || $data['type'] == 'Int' || $data['type'] == 'Number') : ?>
              <?= ExtraInput::numberic($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif (($data['type'] == 'Varchar' || $data['type'] == 'Longtext') && !in_array($data['category'], $not_varchar)) : ?>
              <?= ExtraInput::textbox($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Auto address') : ?>
              <?= ExtraInput::auto_address($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Youtube_embed') : ?>
              <?= ExtraInput::youtube_embed($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Google map link') : ?>
              <?= ExtraInput::google_map_link($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Link') : ?>
              <?= ExtraInput::link($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Latitude_Longtitude') : ?>
              <?= ExtraInput::latitude_longitude($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'DateTime' || $data['type'] == 'Datetime' || $data['type'] == 'Time' || $data['type'] == 'Date') : ?>
              <?= ExtraInput::date_time($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Upload') : ?>
              <?= ExtraInput::upload($id, $code, $val, $data, $extra_record, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Tag') : ?>
              <?= ExtraInput::tag($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Dropdown') : ?>
              <?= ExtraInput::dropdown($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Radio') : ?>
              <?= ExtraInput::radio($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Checkbox') : ?>
              <?= ExtraInput::checkbox($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php elseif ($data['type'] == 'Varchar' && $data['category'] == 'Switch') : ?>
              <?= ExtraInput::switch($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php else : ?>
              <?= ExtraInput::textbox($id, $code, $val, $data, $page_spacing, $key, $is_edit, $mode); ?>
            <?php endif; ?>
          <?php } ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <button class="d-none" type="submit" id="__submit_extra_form"></button>

    <script>
      let scope_event_file = document.querySelectorAll('.scope_event_file');
      let delete_btnn_file = document.querySelectorAll('.btn-delete-file')

      scope_event_file.forEach((scope) => {
        let btn_delete_file = scope.querySelector('.btn-delete-file');

        btn_delete_file.addEventListener('click', function() {
          let input_delete_file = scope.querySelector('.change_val_delete');
          if (input_delete_file.value == 0) {
            input_delete_file.value = 1
          } else {
            input_delete_file.value = 0
          }
        });
      })

      function changeUploadFileUpload(e) {
        let file = e.target.files[0];
        let parent = e.target.parentElement;
        let input = parent.nextElementSibling;
        if (file) {
          input.value = '0';
        } else {
          input.value = '1';
        }
      }
    </script>
<?php return ob_get_clean();
  }

  public static function post_extra_form($func, $record_id, $form_data, $file = [])
  {
    $func = $func;
    $record_id = $record_id;

    $temp = [];
    // W_Main::display($_POST);
    // die;
    if ($file != [] && $file != '') {
      $temp_file = [];
      foreach ($file as $file_key => $file_image) {
        foreach ($file_image as $key2 => $f2) {
          foreach ($f2 as $inx => $arr_img) {
            $temp_file[$file_key][] = $arr_img;
          }
        }
      }

      $file_ary = array();
      $file_count = count($temp_file['name']);
      $file_keys = array_keys($temp_file);



      $temp_rename = [];
      for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
          $file_ary[$i][$key] = $temp_file[$key][$i];
        }
      }
    }


    foreach ($form_data as $table => $data) {
      $table_name = $table;
      $temp_table = explode('_', $table);
      $code = $temp_table[0];
      if (strpos($table, 'sys_') !== false) {
        $table_name  = str_replace($code . '_', '', $table);
      } else {
        $table_name  = str_replace($code, '', $table);
      }

      if ($file != [] && $file != '') {
        if ($_POST) {
          $f = 0;

          foreach ($file['name'][$code . '' . $table_name] as $key_field => $field) {
            if ($file_ary[$f]['size'] > 0) {
              $unique_name  = 'jform_' . $code . '_' . $key_field . '_' . $record_id;
              $temp_rename[] = $unique_name;
              $result = File::add($unique_name, $file_ary[$f]);
            } else {
              $temp_rename[] = '';
            }
            $f++;
          }
        }

        $i = 0;
        foreach ($file['name'][$code . '' . $table_name] as $key_filename => $file_name) {
          if ($temp_rename[$i]) {
            $file['name'][$code . '' . $table_name][$key_filename] = $temp_rename[$i];
          } else {
            $file['name'][$code . '' . $table_name][$key_filename] = $data['filename'][$i];
            if ($data['deletefile'][$i] == 1) {
              $result = File::delete($file['name'][$code . '' . $table_name][$key_filename]);
              $file['name'][$code . '' . $table_name][$key_filename] = '';
            }
          }
          $i++;
        }

        unset($data['filename']);
        unset($data['deletefile']);

        $data = array_merge($data, $file['name'][$code . '' . $table_name]);
      }


      $temp[] = [
        'code' => $code,
        'table_name' => $table_name,
        'data' => $data
      ];
    }

    if ($_POST) {
      foreach ($temp as $update_data) {
        $data = $update_data['data'];
        foreach ($data as $key => $val) {
          if (is_array($val)) {
            $data[$key] = json_encode($val);
          }
        }
        $update_data['data'] = $data;
        $result = Extra::updateDataExtraColumns($record_id, $update_data);
        return json_encode($result);
      }
    }
  }
}

<div class="content-header-container">
  <h3 class="header-title">Table pagination</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <label class="font-weight-bold">โดยต้องใส่ Class <span class="text-danger"> container-pagination </span> และตั้งชื่อ id ควบ div:: table-responsive ที่สร้าง </label>
    </div>
    <div class="col-12 pt-5px">
      <label class="font-weight-bold">เรียกฟังก์ชัน <span class="text-danger"> Homepagify::createHomepagify('id','get') </span> ใน div::container-pagination </label>
    </div>
    <div class="col-12 pt-5px">
      <p class="mb-5px"> ('id') ต้องชื่อเดียวกับไอดีที่ตั้ง</p>
      <p class="mb-5px"> ('get') ค่า $_GET ที่ต่อ URL </p>
      <p class="mb-5px">สร้างไฟล์ ajax ชื่อ table_(ชื่อไอดี) ไว้ในโฟลเดอร์ที่มีชื่อว่า ajax </p>
      <p class="mb-5px font-weight-bold">การ Sort ข้อมูล</p>
      <p class="mb-5px"> หากไม่ต้องการ sort บางช่องใส่ Class no-sort ใน &lt;th&gt;</p>
      <p class="mb-5px"> ใส่ <span class="text-danger"> data-sort="ชื่อฟิลด์ดาต้าเบส" </span> ใน &lt;th&gt; ที่ต้องการ Sort ข้อมูล</p>
      <p class="mb-5px">การปิดใช้งาน sort : ใส่ Class table-no-sort ใน &lt;table class="table-no-sort"&gt;</p>
      <p class="mb-5px font-weight-bold">การ Export Excel</p>
      <p class="mb-5px">การเปิดใช้งาน export : ใส่ Class table-excel ใน &lt;table class="table-excel"&gt;</p>
      <p class="mb-5px font-weight-bold">การ Colvis (ซ่อน Column)</p>
      <p class="mb-5px">การเปิดใช้งาน Colvis : ใส่ Class table-colvis ใน &lt;table class="table-colvis"&gt;</p>
      <!-- <p class="mb-5px  font-weight-bold">การค้นหาข้อมูล</p> -->
      <!-- <p class="mb-5px"> การเปิดใช้งาน : ใส่ Class table-search ใน &lt;table class="table-search"&gt;</p> -->
    </div>
  </div>
  <h6 class="mt-20px">ตัวอย่างตารางปกติ</h6>
  <div id="example" class="container-pagination" <?= Homepagify::createHomepagify('example') ?>>
    <div class="table-responsive">
      <table class="table table-sort">
        <thead>
          <tr>
            <th nowrap data-sort="name">Product name</th>
            <th class="" nowrap data-sort="qty">Qty.</th>
            <th class="" nowrap data-sort="price">Sale Price</th>
            <th class="" nowrap data-sort="detail">Detail</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <h6 class="mt-20px">ตัวอย่าง ไฟล์เรียก Ajax</h6>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
  &lt;div id="example" class="container-pagination" &lt;?=Homepagify::createHomepagify(example);?&gt;
    &lt;div class="table-responsive"&gt;
      &lt;table class="table table-sort" &gt;
        &lt;thead&gt;
          &lt;tr>
            &lt;th nowrap data-sort="name">Product name &lt;/th&gt;
            &lt;th nowrap class="thin-cell" data-sort="Qty">Qty. &lt;/th&gt;
            &lt;th nowrap class="no-sort" data-sort="price">Sale Price &lt;/th&gt;
            &lt;th nowrap class="no-sort" data-sort="Detail">Detail &lt;/th&gt;
          &lt;/tr>
        &lt;/thead&gt;
      &lt;/table"&gt;
    &lt;/div"&gt;
  &lt;/div&gt;</code></pre>
  </div>
  <h6 class="mt-20px">ไฟล์ Ajax</h6>
  <p class="mb-5px font-weight-bold">การส่งค่า Total count</p>
  <p class="mb-5px">หลังบ้านส่งค่า จำนวนทั้งหมดให้จาก 'total_count' => true ที่ใส่ไปใน Option </p>
  <p class="mb-0">นำค่า Total count มาใส่ไว้ใน <span class='text-danger'> &lt;tbody data-total_count=" ค่า Total count"&gt; </span> </p>

  <div class="code-container mt-10px">
    <pre><code data-language="php">
    $_PAGE['permission'] =  ตามตัวไฟล์ที่เรียก
    require_once '../../../_framework/import.php';
    $where = [
      'search_text' => $_POST['search_text']  //หากเปิด ฟังก์ชัน Search ต้องมี
    ];
    //option ต้องมีค่า default
    $options = [
      'total_count' => true, //หลังบ้านส่งค่า จำนวนทั้งหมดให้
      'page_no'     => $_POST['page_no'],
      'page_size'   => $_POST['page_size'],
      'grouped_by'  => '', //หากต้องการ array group
      'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : [] //หากเปิด ฟังก์ชัน Sort ต้องมี
    ];
  ?&gt;
  &lt;tbody data-total_count="ค่า total count"&gt;
    &lt;tr&gt;
      //วาดข้อมูล
    &lt;/tr"&gt;
  &lt;/tbody&gt;</code></pre>
  </div>

  <h6 class="mt-20px">ตัวอย่างตาราง Full Function</h6>
  <button type="button" class="btn btn-danger mb-10px" data-is_check_table="example2" <?= Tiwdal::register('delete_modal'); ?>>Delete</button>
  <div id="example2" class="container-pagination" <?= Homepagify::createHomepagify('example2', '', '', 'example') ?>>
    <div class="table-responsive">
      <table class="table table-sort table-excel table-colvis">
        <thead>
          <tr>
            <th nowrap class="thin-cell no-sort">
              <?= Homepagify::createCheckboxThead('checkboxAll1', 'value', []); ?>
            </th>
            <th nowrap data-sort="name" data-filter="<?= Homepagify::dataFilter('name', 'text') ?>">Product name</th>
            <th class="" nowrap data-sort="qty">Qty.</th>
            <th class="" nowrap data-sort="price">Sale Price</th>
            <th class="" nowrap data-sort="detail">Detail</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <h6 class="mt-20px">ตัวอย่างใช้ button กับ checkbox </h6>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
  **ใส้ชื่อ id container-pagination ใน data-is_check_table="id"
  &lt;button type="button" class="btn btn-danger mb-10px" data-is_check_table="example2" &lt;?=Tiwdal::register('delete_modal');?&gt;&gt;
    Delete
  &lt;/button&gt;
  **วิธีเรียกค่า Checkbox
  &lt;script&gt;
    $(document).ready(function () {
      $('#delete_modal').on('shown.bs.modal', function () {
        //เรียกฟังก์ชัน valueChecklist('id'); (id ของ container-pagination)
        var checked_list = valueChecklist('example2');
        //นำค่าไปใส่ input ชื่อ checked_list ใน modal
        $("input[name='checked_list']").val(checked_list);

      });
    });
  &lt;/script&gt;
  </code></pre>
  </div>

  <h6 class="mt-20px">ตัวอย่าง ไฟล์เรียก Ajax</h6>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
  &lt;div id="example2" class="container-pagination" &lt;?=Homepagify::createHomepagify(example2, '', '', 'example');?&gt;
    &lt;div class="table-responsive"&gt;
      &lt;table class="table table-sort table-excel table-colvis"&gt;
        &lt;thead&gt;
          &lt;tr>
            &lt;th nowrap class="thin-cell no-sort"> &lt;?=Homepagify::createCheckboxThead('checkboxAll1', 'value', []);?&gt; &lt;/th&gt;
            &lt;th nowrap data-sort="name" data-filter="&lt;?=Homepagify::dataFilter('name', 'text')?&gt;" >Product name &lt;/th&gt;
            &lt;th nowrap class="thin-cell" data-sort="Qty">Qty. &lt;/th&gt;
            &lt;th nowrap class="no-sort" data-sort="price">Sale Price &lt;/th&gt;
            &lt;th nowrap class="no-sort" data-sort="Detail">Detail &lt;/th&gt;
        &lt;/thead&gt;
      &lt;/table"&gt;
    &lt;/div"&gt;
  &lt;/div&gt;</code></pre>
  </div>
  <h6 class="mt-20px">ไฟล์ Ajax</h6>
  <p class="mb-5px font-weight-bold">การส่งค่า Total count</p>
  <p class="mb-5px">หลังบ้านส่งค่า จำนวนทั้งหมดให้จาก 'total_count' => true ที่ใส่ไปใน Option </p>
  <p class="mb-0">นำค่า Total count มาใส่ไว้ใน <span class='text-danger'> &lt;tbody data-total_count=" ค่า Total count"&gt; </span> </p>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
    $_PAGE['permission'] =  ตามตัวไฟล์ที่เรียก
    require_once '../../../_framework/import.php';
    W_Structure::loadMetaForAjax('../../../');

    $where = [
      'name' => $_POST['name']  //หากเปิด ฟังก์ชัน Search ต้องมี
    ];
    //option ต้องมีค่า default
    $options = [
      'total_count' => true, //หลังบ้านส่งค่า จำนวนทั้งหมดให้
      'page_no'     => $_POST['page_no'],
      'page_size'   => $_POST['page_size'],
      'grouped_by'  => 'type',
      'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : [] //หากเปิด ฟังก์ชัน Sort ต้องมี
    ];
  ?&gt;
  &lt;tbody data-total_count="ค่า total count"&gt;
    &lt;php
    foreach ($data_list as $idx => $group) {
    &gt;
      &lt;tr class="title-group" data-group="&lt;?=$group['name'].'_'.$group['id']? &gt;"&gt;
        &lt;td colspan="4">
          &lt;div class="btn-toggle">
          &lt;p&gt; &lt;?=$group['name']?&gt; &lt;/p&gt;
          &lt;/div&gt;
          &lt;/td&gt;
        &lt;/tr&gt;
        &lt;?php
          foreach ($group['list'] as $idx => $data) {
        ?&gt;
        &lt;tr&gt; class="tr-link &lt;?=$group['name'].'_'.$group['id']?&gt;"&gt;
          &lt;td&gt;
          &lt;?=Homepagify::createCheckboxTBody('checkbox_'.$data['id'], $data['id'], []);?&gt;
          &lt;/td&gt;
          &lt;td&gt;
            &lt;?=$data['name'];?&gt;
          &lt;/td&gt;
          &lt;td&gt;
            &lt;?=$data['Qty'];?&gt;
          &lt;/td&gt;
          &lt;td&gt;
            &lt;?=$data['price'];?&gt;
          &lt;/td&gt;
          &lt;td&gt;
             &lt;?=$data['detail'];?&gt;
          &lt; /td &gt;
        &lt;/tr &gt;
    &lt?php
      }
    }
    ?&gt;
  &lt;/tbody&gt;</code></pre>
  </div>


</div>

<?php Tiwdal::startModal('delete_modal'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
<form method="post">
  <div class="modal-body">
    <div class="col-12 form-group text-center">
      <p class="text-danger mb-5px mt-20px">Delete data</p>
      <p>
        Are you sure to delete
      </p>
      <input type="text" name="checked_list" class="form-control">
    </div>
  </div>
</form>

<?php Tiwdal::endModal() ?>

<script>
  $(document).ready(function() {
    $('#delete_modal').on('shown.bs.modal', function() {
      var checked_list = valueChecklist('example2');
      //นำค่าไปใส่ input ชื่อ checked_list ใน modal
      $("input[name='checked_list']").val(checked_list);

    });
  });
</script>
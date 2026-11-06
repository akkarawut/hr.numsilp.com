<div class="content-header-container">
  <h3 class="header-title">Table pagination</h3>
</div>
<div class="content-body-container">
  <h6 class="mt-20px">ตัวอย่างใช้งานฟังก์ชันตาราง</h6>
  <div class="col-12 mb-15px px-0">
    Navigator :
    <a href="#sort">Sort</a>,
    <a href="#fitter">Fitter</a>,
    <a href="#checkbox">Checkbox</a>,
    <a href="#group">Group</a>,
    <a href="#export_excel">Export excel</a>,
    <a href="#colvis">Colvis </a>,
  </div>
  <p id="sort" class="mb-5px font-weight-bold">ฟังก์ชัน Sort</p>
  <p class="mb-5px">การใช้งาน ใส่ <span class="text-danger"> data-sort="ชื่อฟิลด์ดาต้าเบส" </span> ใน &lt;th&gt; ที่ต้องการ Sort ข้อมูล</p>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
  &lt;table class="table" &gt;
    &lt;thead&gt;
      &lt;tr>
        &lt;th nowrap data-sort="pass_no"> Booking no. &lt;/th&gt;
        &lt;th nowrap class="thin-cell" data-sort="customer_email">customer Email &lt;/th&gt;
        &lt;th nowrap class="no-sort" data-sort="insert_date_time">datetime &lt;/th&gt;
      &lt;/tr>
    &lt;/thead&gt;
  &lt;/table"&gt;
  </code></pre>
  </div>

  <!-- <p class="mb-5px">การปิดใช้งาน sort : ใส่ Class table-no-sort ใน &lt;table class="table-no-sort"&gt;</p>
  <pre><code data-language="php">
  &lt;table class="table table-no-sort" &gt;
  &lt;/table"&gt;
  </code></pre>
  <p class="mb-5px  font-weight-bold">ฟังก์ชัน Search </p>
  <p class="mb-5px">การใช้งาน : ใส่ Class table-search ใน &lt;table class="table-search"&gt;</p>
  <pre><code data-language="php">
  &lt;table class="table table-search" &gt;
  &lt;/table"&gt;
  </code></pre> -->

  <p id="fitter" class="mb-5px font-weight-bold">ฟังก์ชัน Fitter</p>
  <p class="mb-5px font-weight-bold">การส่งตัวแปร where เพิ่มเติม</p>
  <p class="mb-5px">form ส่งค่าเป็น เป็น "POST" </p>
  <p class="mb-5px"> ให้รับค่า เป็น POST ในไฟล์ Ajax </span> </p>
  <p class="mb-5px"><u> ตัวอย่างการใช้</u> </p>
  <p class="mb-5px"> สำหรับ input ปกติ</span> </p>
  <p class="mb-5px "> ใส่ data-filter= "&lt;?=Homepagify::dataFilter( 'ชื่อ input' , text/name/date)?&gt;"</p>
  <div class="col-lg-2 homepage-form px-0">
    <div class="form-group">
      <label>ชื่อ</label>
      <input type="name" class="form-control filter-param" name="name">
    </div>
  </div>
  <p class="mb-5px"> สำหรับ input แบ่ง 2 ช่อง </p>
  <p class="mb-5px "> ใส่ data-filter= "&lt;?=Homepagify::dataFilter(['ชื่อ input1', 'ชื่อ input2'], 'number_between')?&gt;"</p>
  <div class="col-lg-2 homepage-form px-0">
    <div class="form-group">
      <label>Total Price</label>
      <div class="row d-flex align-items-center">
        <div class="col">
          <input type="number" class="form-control filter-param" name="name">
        </div>
        <div class="col-auto">
          -
        </div>
        <div class="col">
          <input type="number" class="form-control filter-param" name="name">
        </div>
      </div>
    </div>
  </div>
  <p class="mb-5px"> สำหรับ select</p>
  <p class="mb-5px "> ใส่ data-filter= "&lt;?=Homepagify::dataFilter('ชื่อ select', 'select',)?&gt;"</p>
  <p class="mb-5px "> รูปแบบ Array</p>
  <p class="mb-5px ">Array = [ ['value' => '1','text' => 'ประเภท1'],['value' => '2','text' => 'ประเภท2'] ]</p>

  <div class="col-lg-2 homepage-form px-0">
    <div class="form-group">
      <label>ประเภท</label>
      <select class="form-control filter-param" name="name">
        <option>all</option>
        <option>ประเภท1</option>
        <option>ประเภท2</option>
      </select>
    </div>
  </div>
  <pre><code data-language="php">
  &lt;table class="table" &gt;
    &lt;thead&gt;
      &lt;tr>
        &lt;th nowrap data-filter="&lt;?=Homepagify::dataFilter('booking_no', 'text')?&gt;"> Booking no. &lt;/th&gt;
        &lt;th nowrap data-filter="&lt;?=Homepagify::dataFilter(['price_min', 'price_max'], 'number_between')?&gt;">Price &lt;/th&gt;
        &lt;th nowrap data-filter="&lt;?=Homepagify::dataFilter('type', 'select', $Array)?&gt;">Type &lt;/th&gt;
      &lt;/tr>
    &lt;/thead&gt;
  &lt;/table"&gt;
  </code></pre>

  <p id="checkbox" class="mb-5px font-weight-bold">ฟังก์ชัน checkbox</p>
  <p class="mb-5px font-weight-bold">เพื่อเก็บค่า checkbox เมื่อมีการ action เกี่ยวกับตาราง</p>
  <p class="mb-5px">**ต้องประกาศ <span class="text-danger font-Bold">W_Structure::loadMetaForAjax('../../../'); </span> ในไฟล์ Ajax <span class="text-secondary">(เพราะต้องมีการเรียก TIW FORM)</span> </p>

  <p class="mb-5px"> <u>การเรียกใช้ checkbox All </u> <span class="text-secondary"> (สั่ง checkbox ในหน้าที่เห็น checked ทั้งหมด)</span> </p>
  <p class="mb-5px">เรียกผ่าน &lt;=Homepagify::createCheckboxThead('checkbox_id', 'class');?&gt;</p>
  <p class="mb-5px">checkbox_id = ชื่อ id ของตัว checkbox</p>
  <p class="mb-5px">class = ชื่อ class เมื่อเรามีการ custom <span class="text-info"> (ต้องcustom css เหมือน checkbox ใน TIW FORM) </span> </p>

  <p class="mb-5px"> <u>การเรียกใช้ checkbox ปกติ </u> </p>
  <p class="mb-5px">เรียกผ่าน &lt;=Homepagify::createCheckboxTBody('checkbox_id', 'checkbox_value', 'class');?&gt;</p>
  <p class="mb-5px">checkbox_id = ชื่อ id ของตัว checkbox</p>
  <p class="mb-5px">checkbox_value = ค่าของตัว checkbox</p>
  <p class="mb-5px">class = ชื่อ class เมื่อเรามีการ custom <span class="text-info"> (ต้องcustom css เหมือน checkbox ใน TIW FORM) </span> </p>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
&lt;table class="table" &gt;
    &lt;thead&gt;
      &lt;tr>
        &lt;th nowrap">
        &lt;Homepagify::createCheckboxThead('checkboxAll', 'class');&gt;
        &lt;/th&gt;
      &lt;/tr>
    &lt;/thead&gt;
  &lt;/table"&gt;
**ไฟล์ Ajax
&lt;tbody data-total_count="ค่า total count"&gt;
  &lt;?php
    foreach($table_list as $list) {
  ?&gt;
    &lt;tr&gt;
      &lt;td&gt;
       &lt;=Homepagify::createCheckboxTBody('checkbox_'.$list['id'], $list['id']', '');?&gt;
      &lt;/td&gt;
    &lt;/tr"&gt;
  &lt;?php
    }
  ?&gt;
&lt;/tbody&gt;</code></pre>
  </div>
  <p class="mb-5px"> <u>หากต้องการให้ button disabled true/false เมื่อมีการ checked ในตาราง </u> </p>
  <p class="mb-10px"> ใส่ ชื่อ id ที่ตั้ง container-pagination ใน data-is_check_table="table_example" ไว้ที่ button </p>
  <p class="mb-5px"> <u>เรียกค่า checkbox ที่ checked ในตาราง </u> </p>
  <p class="mb-5px">เขียน active script และเรียกฟังก์ชัน <span>valueChecklist('id');</span></p>
  <p class="mb-5px">id = ชื่อ id ที่ตั้งไว้ใน class container-pagination </p>
  <pre><code data-language="php">
&lt;script &gt;
  $(document).ready(function () {
    $(document).on("click", ".btn-modal", function (e) {
      var checked_list = valueChecklist('table_example');
    });
  });
&lt;/script&gt;</code></pre>

  <p id="group" class="mb-5px font-weight-bold">ฟังก์ชัน Group</p>
  <p class="mb-5px">**ต้องประกาศ <span class="text-danger font-Bold">W_Structure::loadMetaForAjax('../../../'); </span> ในไฟล์ Ajax </p>
  <p class="mb-5px">ต้องเพิ่ม <span class="text-danger">'grouped_by' => 'type' </span> ใน option ของไฟล์ ajax </p>
  <p class="mb-5px">type = ชื่อฟิลด์ดาต้าเบสที่ต้องการ group</p>
  <p class="mb-5px text-info">** array จะออกมา เป็น 2 มิติ ต้องวน loop 2 รอบ</p>
  <p class="mb-5px">loop 1 : ชื่อ group</p>
  <p class="mb-5px">ใส่ class="title-group" และ data-group="group_name" ใน <b>tr</b> </p>
  <p class="mb-5px">
    ใส่ ปุ่มกด show/hide ข้อมูล <br>
    &lt;td&gt; <br>
    &nbsp; &lt;div class="btn-toggle"&gt; <br>
    &nbsp; &nbsp; &lt;p&gt; ชื่อ &lt;/p&gt; <br>
    &nbsp; &lt;/div> <br>
    &lt;/td&gt;
  </p>
  <p class="mb-5px">loop 2 : ข้อมูล</p>
  <p class="mb-5px">ใส่ class="group_name" <b>tr</b> </p>
  <p class="mb-5px">group_name = ชื่อที่เราตั้งไว้ </p>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
$options = [
  'total_count' => true, //หลังบ้านส่งค่า จำนวนทั้งหมดให้
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'grouped_by'  => 'type', //หากต้องการ array group
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : [] //หากเปิด ฟังก์ชัน Sort ต้องมี
];
&lt;tbody data-total_count="ค่า total count"&gt;
  &lt;?php
    foreach($table_list as $group) {
  ?&gt;
    &lt;tr class="title-group" data-group="group_&lt;=$group['id']&gt;" &gt;
      &lt;td&gt;
       &lt;div class="btn-toggle"&gt;
        &lt;p&gt; $group['name'] &lt;/p&gt;
       &nbsp; &lt;/div>
      &lt;/td&gt;
    &lt;/tr"&gt;
    &lt;?php
      foreach($group['list'] as $list) {
    ?&gt;
      &lt;tr class="group_&lt;=$group['id']&gt;" &gt;

      &lt;/tr"&gt;
    &lt;?php
      }
    ?&gt;
  &lt;?php
    }
  ?&gt;
&lt;/tbody&gt;</code></pre>
  </div>

  <p id="export_excel" class="mb-5px font-weight-bold">ฟังก์ชัน Export Excel</p>
  <p class="mb-5px">**ต้องประกาศ <span class="text-danger font-Bold">W_Structure::loadMetaForAjax('../../../'); </span> ในไฟล์ Ajax <span class="text-secondary">(เพราะต้องมีการเรียก TIW FORM)</span> </p>
  <p class="mb-5px">ใส่ Class <span class="text-info"> table-excel </span> ใน &lt;table class="table-excel" &gt; สำหรับเรียกปุ่ม EXCEL </p>
  <div class="container-pagination my-5px">
    <a class="btn btn-export pagify-export px-15px"> EXPORT </a>
  </div>
  <p class="mb-0">เรียกฟังก์ชัน data-export="<span class='text-info'> &lt;?=Homepagify::getExportLink('class', 'aip', 'code', $where, $options);?&gt; </span> ไว้ใน Tbody </p>
  <p class="mb-5px">class = ชื่อ Class API ของหลังบ้าน</p>
  <p class="mb-5px">aip = ชื่อ API ของหลังบ้าน</p>
  <p class="mb-5px">code = code โปรเจค</p>
  <p class="mb-5px">$where = ใช้ where เดียวกับ ส่งให้หลังบ้านในไฟล์ ajax </p>
  <p class="mb-5px">$options = ใช้ options เดียวกับ ส่งให้หลังบ้านในไฟล์ ajax </p>

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
&lt;tbody data-total_count="ค่า total count"  data-export="&lt;?=Homepagify::getExportLink('Class', 'API', '', $where, $options);?&gt;"&gt;
  &lt;tr&gt;
    //วาดข้อมูล
  &lt;/tr"&gt;
&lt;/tbody&gt;</code></pre>
  </div>

  <p id="colvis" class="mb-5px font-weight-bold">ฟังก์ชัน Colvis <span class="font-14px text-secondary">(การเลือกโชว์ หรือ แสดงColumn)</span></p>
  <p class="mb-5px">ใส่ Class <span class="text-info"> table-colvis </span> ใน &lt;table class="table-colvis" &gt; สำหรับเรียกปุ่ม colvis </p>
  <div class="container-pagination my-5px">
    <button class="btn btn-export btn-column"></button>
  </div>
  <p class="mb-5px">**วาดข้อมูลทั้งหมดในไฟล์ Ajax</p>
  <div class="code-container mt-10px">
    <pre><code data-language="php">
  &lt;table class="table table-colvis" &gt;
    &lt;thead&gt;
      &lt;tr>
        &lt;th nowrap data-filter="&lt;?=Homepagify::dataFilter('booking_no', 'text')?&gt;"> Booking no. &lt;/th&gt;
        &lt;th nowrap data-filter="&lt;?=Homepagify::dataFilter(['price_min', 'price_max'], 'number_between')?&gt;">Price &lt;/th&gt;
        &lt;th nowrap data-filter="&lt;?=Homepagify::dataFilter('type', 'select', $Array)?&gt;">Type &lt;/th&gt;
      &lt;/tr>
    &lt;/thead&gt;
  &lt;/table"&gt;
  </code></pre>






  </div>
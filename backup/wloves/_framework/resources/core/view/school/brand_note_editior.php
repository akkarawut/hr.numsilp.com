<div class="content-header-container">
  <h3 class="header-title">การใช้งาน HTML Editor<p class="font-14px mt-5px">waiting . . .</p>
  </h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <label>ตัวอย่าง</label>
      <div class="mb-10px">
        <?php Brandnote::startNote('id', 'name', '', 200, ''); ?>
      </div>
      <div class="code-container">
        <pre><code data-language="php">&lt;?php
  //เรียกใช้ Module brandnote ไว้ที่หัวของหน้านั้น ๆ
  W_Structure::loadModules(['brandnote']);

  //ตัวแปรที่ใช้ในการแทนค่า
  $id     = id    //id ของ html editor
  $name   = name  //name ของ html editor
  $value  = ''    //ใช้รับข้อมูล
  $height = 200   //กำหนดความสูงของ html editor (ส่งค่าว่างจะ default ไว้ 400)
  $class  = ''    //ใส่ class เมื่อต้องการ custom เอง
?&gt;

 &lt;?php Brandnote::startNote($id, $name, $value, $height, $class);?&gt;
</code></pre>
      </div>
    </div>
  </div>
</div>
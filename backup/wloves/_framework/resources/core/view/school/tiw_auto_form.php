<div class="content-header-container">
  <h3 class="header-title">AUTO FORM</h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="code-container mt-10px">
        <pre><code data-language="php">Question 1 : ทำไมใช้ tiwform ในไฟล์ Ajax, Ajax Table, Ajax Modal ไม่ได้ ?
ตอบ : ต้อง include ไฟล์ tiwform เข้าไปในไฟล์ Ajax ด้วย
W_Structure::loadMetaForAjax('../../');</code></pre>
      </div>
    </div>
    <div class="col pt-20px">
      <label class="font-weight-bold text-primary">ใส่ข้อมูลใน input, textarea, radio, checkbox, img, span, div, select option ให้อัตโนมัติ</label>
      <label class="font-weight-bold">โดยต้องใส่ <span class="text-danger">&lt;?=TiwForm::autoForm($data);?&gt;</span> ไว้ที่พื้นที่ ๆ ต้องการ Auto form</label>
      <label class="font-weight-bold">และใส่ <span class="text-danger">{}</span> ครอบ name ตัวอย่าง name="{name}"</label>
    </div>
  </div>
  <h6 class="mt-20px">ตัวอย่าง</h6>
  <div class="code-container mt-10px">
    <pre><code data-language="php">$data = [
  'input' => 'test input',
  'textarea' => 'test textarea',
  'radio' => 'radio1',
  'checkbox' => 'checkbox1',
  'img' => 'https://wolves.bar/hq/system/resource/placeholder/placeholder_user_square.jpg',
  'span' => 'test span',
  'div' => 'test div',
  'select' => 'option2'
];
?&gt;
&lt;div &lt;?=TiwForm::autoForm($data);?&gt;&gt;
  //input
  <input type="text" name="{input}" class="form-control">
  //textarea
  <textarea name="{textarea}" class="form-control"></textarea>
  //radio
  <div class="d-flex align-items-center my-10px">
    &lt;?=TiwForm::checkboxRadio(2, 'radio', [], '{radio}', 'radio1', ['for' => 'radio']);?&gt;
    <label class="mb-0 ml-10px" for="radio"> Radio</label>
  </div>
  //checkbox
  <div class="d-flex align-items-center my-10px">
    &lt;?=TiwForm::checkboxRadio(3, 'checkbox', [], '{checkbox}', 'checkbox1', ['for' => 'checkbox']);?&gt;
    <label class="mb-0 ml-10px" for="checkbox"> Checkbox</label>
  </div>
  //img
  <img name="{img}" is_placeholder="1">
  //span
  <span name="{span}"></span>
  //div
  <div name="{div}"></div>
  //select
  <select name="{select}" class="form-control">
    <option value="option1">option1</option>
    <option value="option2">option2</option>
    <option value="option3">option3</option>
  </select>
&lt;/div&gt;</code></pre>
  </div>

</div>
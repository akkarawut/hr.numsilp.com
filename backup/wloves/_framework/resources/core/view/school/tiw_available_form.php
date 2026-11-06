<div class="content-header-container">
  <h3 class="header-title">Available Form</h3>
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
    <div class="col-12 pt-20px">
      <h4>Password</h4>
      <form action="" method="post">
        <?= TiwForm::normal('text', '', ['name' => 'password', 'placeholder' => 'Password', 'class' => 'set_check_password']); ?>
        <?= TiwForm::normal('text', '', ['name' => 'confirm_password', 'placeholder' => 'Confirm Password', 'class' => 'set_check_password']); ?>

        <?= TiwForm::checkForm('set_check_password', 'set_check_btn', ['is-char' => true, 'is-word' => true, 'is-number' => true, 'is-match' => true,]); ?>

        <?= TiwForm::normal('btn', '', ['class' => 'set_check_btn mt-10px'], ['text' => 'Submit']); ?>
      </form>

      <div class="code-container mt-10px">
        <pre><code data-language="php">TiwForm::normal('text', '', ['name' => 'password', 'placeholder' => 'Password', 'class' => 'set_check_password']);
TiwForm::normal('text', '', ['name' => 'confirm_password', 'placeholder' => 'Confirm Password', 'class' => 'set_check_password']);

$input_class = 'Class ที่ต้องเอาไปใส่ที่ Input ทั้ง Input และ Confirm Input ex. set_check_password (ใส่เป็นชื่ออะไรก็ได้)';
$button_class = 'Class Button ex. set_check_btn (ใส่เป็นชื่ออะไรก็ได้)';
$options = [
  'is-char' => true, //ถ้าต้องการให้เช็ค ตัวอักษร 8 ตัวให้ใส่บรรทัดนี้มา
  'is-word' => true, //ถ้าต้องการให้เช็ค ตัวอักษร a-z ให้ใส่บรรทัดนี้มา
  'is-number' => true, //ถ้าต้องการให้เช็ค ตัวเลขและตัวอักษรพิเศษอื่น ๆ ให้ใส่บรรทัดนี้มา
  'is-match' => true, //ถ้าต้องการให้เช็คว่า Input และ Confirm Input ว่าเหมือนกันหรือไม่ให้ใส่บรรทัดนี้มา
];
TiwForm::checkForm($input_class, $button_class, $options);

TiwForm::normal('btn', '', ['class' => 'set_check_btn mt-10px'], ['text' => 'Submit']);</code></pre>
      </div>
    </div>

    <div class="col-12 pt-20px">
      <h4>เช็คค่าซ้ำใน Database</h4>
      <form action="" method="post">
        <?php
        $api = [
          'api' => 'bue_employee::checkHKID',
          'params' => [
            'code' => 'bmlwm',
            'hk_id' => '{hk_id}',
          ]
        ];
        ?>

        <input type="text" name="hk_id" class="form-text mb-10px" placeholder="Enter" <?= TiwForm::availableForm($api); ?>>
      </form>

      <div class="code-container mt-20px">
        <pre><code data-language="php">//API ที่จะยิงค่าไปเช็คว่าซ้ำหรือไม่
$api = [
  'api' => 'bue_employee::checkHKID',
  'params' => [
    'code' => 'bmlwm',
    'hk_id' => '{hk_id}',
  ]
];
//ใส่ name ให้ตรงกับค่าใน database
//$message จะใส่หรือไม่ใส่ก็ได้ ถ้าไม่ใส่จะเอาข้อความจาก api มาแสดง
//ใส่ &lt;?=TiwForm::availableForm($api, $message);?&gt; ไว้ใน input

&lt;input type="text" name="hk_id" class="form-text mb-10px"  placeholder="Enter" &lt;?=TiwForm::availableForm($api);?&gt;&gt;</code></pre>
      </div>
    </div>

  </div>
</div>
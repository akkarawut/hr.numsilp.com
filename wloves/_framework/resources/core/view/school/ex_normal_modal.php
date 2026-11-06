<div class="content-header-container">
  <h3 class="header-title">การเขียน Edit Modal<p class="font-14px mt-5px">ใช้กรณีที่ต้องการให้ตัวแปรที่ส่งไป แทนค่าใน Tag Html ให้ โดยอัตโนมัติ</p>
  </h3>
</div>
<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="code-container mt-10px">
        <pre><code data-language="php">Question 1 : ทำไมใช้ tiwdal ในไฟล์ Ajax, Ajax Table ไม่ได้ ?
ตอบ : ต้อง include ไฟล์ tiwform เข้าไปในไฟล์ Ajax ด้วย
W_Structure::loadMetaForAjax();</code></pre>
      </div>
    </div>
    <div class="col-12">
      <h6>อธิบายเพิ่มเติม</h6>
      <div>
        <p>Tag ที่สามารถถูกแทนค่าอัตโนมัติ ได้แก่<br>input , select option, img, checkbox, textarea, span, div<br>โดยการใส่ name="{key name}" ใน Tag นั้น ๆ ให้ตรงกับตัวแปรที่ส่งค่าเข้าไป</p>
      </div>
      <h5>ตัวอย่างปุ่มกด</h5>
      <div class="mb-10px">
        <?php
        $edit_data = [
          'id'   => 1,
          'name' => 'Edit Name',
          'description' => 'Edit Description',
        ];
        ?>
        <?= TiwForm::normal('btn', '', ['type' => 'button'], ['text' => 'Click for open edit modal.', 'modal_id' => 'edit_modal', 'modal_data' => $edit_data]); ?>
      </div>
      <div class="code-container">
        <pre><code data-language="php">&lt;?php
  // Param 1 = ไอดีของ Modal
  $data = [
    'id'   => 1,
    'name' => 'Edit Name',
    'description' => 'Edit Description',
  ]; //ข้อมูลที่ต้องการส่งไปที modal เพื่อใช้ auto form (ไม่มีไม่ต้องส่งไป)
?&gt;

&lt;button class="btn btn-primary" &lt;?= Tiwdal::register('modal_id', $data);?&gt;>
  Click for open edit modal.
&lt;/button&gt;

หรือ

&lt;?= TiwForm::normal('btn', '', ['type' => 'button'], ['text' => 'Click for open edit modal.', 'modal_id' => 'modal_id', 'modal_data' => $data]); ?&gt;</code></pre>
      </div>
    </div>
  </div>
</div>

<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <h5>ตัวอย่าง Modal</h5>
      <p>
        $id = id ของ Modal<br>
        $size = ขนาด Modal (ใส่หรือไม่ใส่ก็ได้) / modal-sm, modal-md, modal-lg, modal-xl, max-w-500px<br>
        $options = เงื่อนไขอื่น ๆ ที่ต้องการวาดที่หัว modal (ใส่หรือไม่ใส่ก็ได้) เช่น ['class' => 'test1 test2', 'data-id' => '1']<br>
      </p>
      <div class="code-container">
        <pre><code data-language="php">&lt;?php Tiwdal::startModal($id, $size, $options);?&gt;
  <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'></button>
  <div class="modal-header">
    <h5 class="modal-title">Ex. Edit Modal</h5>
  </div>
  <div class="modal-body">
    <div class="row mb-15px">
      <div class="col-sm-4 pt-7px">Name<span class="text-danger">*</span></div>
      <div class="col-sm-8">
        &lt;?= TiwForm::normal('text', '', ['name' => '{name}', 'placeholder' => 'Enter', 'class' => 'mb-0']) ?&gt;
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 pt-7px">Description</div>
      <div class="col-sm-8">
        &lt;?= TiwForm::normal('textarea', '', ['name' => '{description}', 'placeholder' => 'Enter', 'class' => 'mb-0 min-h-50px']) ?&gt;
      </div>
    </div>
  </div>
  <div class="modal-footer">
    &lt;?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'ADD', 'type' => '']);
    ?&gt;
  </div>
&lt;?php Tiwdal::endModal()?&gt;</code></pre>
      </div>
    </div>
  </div>
</div>

<?php Tiwdal::startModal('edit_modal', 'modal-md'); ?>
<button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'></button>
<div class="modal-header">
  <h5 class="modal-title">Ex. Edit Modal</h5>
</div>
<form action="" method="post">
  <div class="modal-body">
    <div class="row mb-15px">
      <div class="col-sm-4 pt-7px">Name<span class="text-danger">*</span></div>
      <div class="col-sm-8">
        <?= TiwForm::normal('text', '', ['name' => '{name}', 'placeholder' => 'Enter', 'class' => 'mb-0']) ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 pt-7px">Description</div>
      <div class="col-sm-8">
        <?= TiwForm::normal('textarea', '', ['name' => '{description}', 'placeholder' => 'Enter', 'class' => 'mb-0 min-h-50px']) ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'submit', 'class' => 'min-w-120px'], ['text' => 'ADD', 'type' => '']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>
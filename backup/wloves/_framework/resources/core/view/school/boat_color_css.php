<div class="content-header-container mt-3 d-flex align-items-center justify-content-between">
  <h3 class="header-title text-info">เขียน CSS แบบ WOLVES</h3>
  <!-- <a href="school.php?topic=800&sub=803" class="btn btn-light">test form</a> -->
</div>
<div class="content-body-container">
  <div class="row">
    <h5 class="col-12 text-danger">!! อ่านก่อนนำไปใช้งาน : การเขียน CSS รูปแบบ WOLVES </h5>
    <h6 class="col-12 text-warning">!! ถ้ามีการ Custom ต่างๆของ CSS </h6>
    <div class="col-12">
      <h6>ตัวอย่าง</h6>
      <div class="code-container mb-3">
        <pre>
          <code data-language="css">
/* แบบที่ใช้ตอนนี้*/
{
  background-color: var(--v2-color-bg);
  color: var(--v2-color-bg);
}
/* แบบที่ไม่ใช้แล้ว จะไม่มีการใช้โค้ดสี (ตัวอย่าง : #FFFFFF) */
{
  background-color:#FFFFFF;
  color: #FFFFFF;
}
:root {
  --color-dark-or-light: #ffffff;
  --v2-color-text-info: #202125;
  --v2-color-text-secondary: #676A6F;
  --v2-color-text-placeholder: #979898;
  --v2-color-border-input: #B3D7FF;
  --v2-color-select-input: #E3F5FF;
  --v2-color-hover: #ECF3F7;
  --v2-color-border: #DBDCDF;
  --v2-color-bg-card: #FFFFFF;
  --v2-color-bg: #F1F3F4;
  --v2-color-optional-1: #C8E9FC;
  --v2-color-table-bg: #FFFFFF;
  --v2-color-table-secondary: #F8F8F8;
  --v2-color-table-disable: #EDEDED;
  --v2-color-info: #202125;
  --v2-color-info-hover: #3A4248;
  --v2-color-secondary: #676A6F;
  --v2-color-secondary-hover: #58606E;
  --v2-color-primary: #3872E1;
  --v2-color-primary-hover: #004EE2;
  --v2-color-success: #1FA700;
  --v2-color-success-hover: #198B00;
  --v2-color-warning: #FFAD00;
  --v2-color-warning-hover: #FFC400;
  --v2-color-danger: #ED4234;
  --v2-color-danger-hover: #DE1100;
}

[data-theme='dark'] {
  --color-dark-or-light: #000000;
  --v2-color-text-info: #FFFFFF;
  --v2-color-text-secondary: #9CA2AC;
  --v2-color-text-placeholder: #3F454E;
  --v2-color-border-input: #1A2F58;
  --v2-color-select-input: #2C3341;
  --v2-color-hover: #111117;
  --v2-color-border: #2C2D2F;
  --v2-color-bg-card: #17171C;
  --v2-color-bg: #121215;
  --v2-color-optional-1: #222E45;
  --v2-color-table-bg: #17171C;
  --v2-color-table-secondary: #1A1A1F;
  --v2-color-table-disable: #1B1D21;
  --v2-color-info: #FFFFFF;
  --v2-color-info-hover: #DADBE0;
  --v2-color-secondary: #9CA2AC;
  --v2-color-secondary-hover: #A5AABE;
  --v2-color-primary: #245CC7;
  --v2-color-primary-hover: #3872E1;
  --v2-color-success: #198B00;
  --v2-color-success-hover: #1FA700;
  --v2-color-warning: #FFAD00;
  --v2-color-warning-hover: #FFC400;
  --v2-color-danger: #DE1100;
  --v2-color-danger-hover: #ED4234;
}
          </code>
        </pre>
      </div>
    </div>
    <h6 class="col-12 text-warning">!! ถ้าจะตัดแบบพวกปุ่ม หรือเปลี่ยนสี background ให้ใส่เป็น class แทน</h6>
    <div class="col-12">
      <h6>ตัวอย่าง</h6>
      <div class="code-container mb-3">
        <pre><code data-language="php"><div class="bg-primary text-warning"></div></code></pre>
      </div>
    </div>
  </div>
</div>
<div class="content-header-container mt-3">
  <h3 class="header-title text-info">Class Theme</h3>
</div>
<div class="content-body-container">
  <p class="font-weight-bold text-info"> ปุ่มต่างๆ </p>
  <div class="row">
    <div class="col-12 mb-3 d-flex flex-wrap align-items-center">
      <button type="button" class="copyColor mr-10px btn btn-primary">.btn-primary</button>
      <button type="button" class="copyColor mr-10px btn btn-secondary">.btn-secondary</button>
      <button type="button" class="copyColor mr-10px btn btn-success">.btn-success</button>
      <button type="button" class="copyColor mr-10px btn btn-danger">.btn-danger</button>
      <button type="button" class="copyColor mr-10px btn btn-warning">.btn-warning</button>
      <button type="button" class="copyColor mr-10px btn btn-info">.btn-info</button>
      <button type="button" class="copyColor mr-10px btn btn-light">.btn-light</button>
      <button type="button" class="copyColor mr-10px btn btn-dark">.btn-dark</button>
    </div>
    <div class="col-12 mb-3 d-flex flex-wrap align-items-center">
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-primary">.btn-outline-primary</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-secondary">.btn-outline-secondary</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-success">.btn-outline-success</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-danger">.btn-outline-danger</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-warning">.btn-outline-warning</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-info">.btn-outline-info</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-light">.btn-outline-light</button>
      <button type="button" class="copyColor mb-10px mr-10px btn btn-outline-dark">.btn-outline-dark</button>
    </div>
  </div>
  <p class="font-weight-bold text-info"> สีต่างๆ </p>
  <div class="row">
    <div class="col-12 mb-3 ">
      <div class="p-3 mb-2 bg-primary text-dark">.bg-primary</div>
      <div class="p-3 mb-2 bg-secondary text-dark">.bg-secondary</div>
      <div class="p-3 mb-2 bg-success text-dark">.bg-success</div>
      <div class="p-3 mb-2 bg-danger text-dark">.bg-danger</div>
      <div class="p-3 mb-2 bg-warning text-dark">.bg-warning</div>
      <div class="p-3 mb-2 bg-info text-light">.bg-info</div>
      <div class="p-3 mb-2 bg-light text-dark">.bg-light</div>
      <div class="p-3 mb-2 bg-dark text-light">.bg-dark</div>
      <div class="p-3 mb-2 bg-white">.bg-white</div>
    </div>
  </div>
  <p class="font-weight-bold text-info"> สีตัวหนังสือต่างๆ </p>
  <div class="row">
    <div class="col-12 mb-3 ">
      <p class="text-primary">.text-primary</p>
      <p class="text-success">.text-success</p>
      <p class="text-danger">.text-danger</p>
      <p class="text-warning">.text-warning</p>
      <p class="text-info">.text-info <span class="text-danger ml-30px"> ( ใช้บ่อยส่วน title )</span></p>
      <p class="text-secondary">.text-secondary <span class="text-danger ml-30px"> ( ใช้บ่อยส่วน detail )</span></p>
      <p class="text-light bg-dark">.text-light</p>
      <p class="text-dark">.text-dark</p>
      <p class="text-muted">.text-muted</p>
      <p class="text-white bg-light">.text-white</p>
    </div>
  </div>
</div>


<script src="../../../../structure/js/color_data.js"></script>
<script>
  $(document).ready(function() {

    $('.copyColor').on('click', function() {
      var copyText = $(this).attr('class');
      copyText = copyText.replace("copyColor mr-10px ", " ")
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val(copyText).select();
      document.execCommand("copy");
      $temp.remove();
      w.notification("success", 'Copy ' + copyText);
    });
  });
</script>
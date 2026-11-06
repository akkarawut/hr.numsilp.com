<div class="content-header-container">
  <h3 class="header-title">Live Img</h3>
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
      <h6 class="text-primary">Once Img</h6>
      <div calss="detail my-20px">
        <?php
        $bg_img = '../../_framework/modules/tiwform/img/bg1.png';
        $options = [
          'width' => '150px',
          'height' => '100%',
          'title' => 'COVER',
        ];
        TiwForm::liveImg('test_once_img', $bg_img);
        echo '<br>';
        TiwForm::liveImg('test_once_img_1', $bg_img, $options);
        ?>
      </div>
      <div class="code-container mt-10px">
        <pre><code data-language="php">$bg_img = '../../_framework/modules/tiwform/img/bg1.png'; //รูปพื้นหลังแสดงตอนไม่มีรูป
$code = 'test_once_img'; //ชื่อรูป
$options = [
  'width' => '200px', //ถ้าไม่ใส่จะเท่ากับ 200px
  'height' => '100%', //ความสูงต้องใส่เป็น เปอร์เซ็น เท่านั้น เช่น ถ้าใส่ 100% จะเท่ากับ 1:1 หรือ 200px * 200px หรือถ้าใส่ 50% จะเท่ากับ 1:2 หรือ 200px * 100px เป็นต้น
  'title' => 'ทดสอบ title', //title จะไปแสดงด้านล่างของรูป
  'is_btn' => 0, //ไม่เอาปุ่มทั้งหมด
  'is_view' => 1, //ไม่เอาปุ่ม view
  'is_delete' => 1, //ไม่เอาปุ่ม ลบ
]; //ใส่หรือไม่ใส่ก็ได้
TiwForm::liveImg($code, $bg_img, $options);</code></pre>
      </div>
    </div>

    <div class="col-12 pt-20px">
      <h6 class="text-primary">Multi Img</h6>
      <div calss="my-20px">

        <!-- <div class="live-multi-img-group">
          <div class="live-multi-upload-area">
            <div class="live-multi-upload upload_live_img_event" style="background-image: url(../../_framework/modules/tiwform/img/bg1.png);"></div>
            <form enctype="multipart/form-data">
              <input type="hidden" name="code" value="test_multi_img">
              <input type="file" name="multi_img" class="multi_img_event">
            </form>
          </div>
          <div class="live-multi-group live_multi_group_event">
            <div class="img-list">
              <button type="button" class="delete"><?= file_get_contents(F_ROOT_PHP . '/_framework/modules/tiwform/icon/delete.svg') ?></button>
              <img class="preview" src="http://178.128.83.30:8103/system/resource/file/test_once_img.gif?date=2021-03-02 20:31:06.1">
            </div>
          </div>
        </div> -->

      </div>
      <div class="code-container mt-10px">
        <pre><code data-language="php">Once Img</code></pre>
      </div>
    </div>

    <div class="col-12 pt-20px">
      <h6 class="text-primary">File Img</h6>
      <div calss="my-20px">

      </div>
      <div class="code-container mt-10px">
        <pre><code data-language="php">Base Img</code></pre>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $('.upload_live_img_event').click(function(e) {
      e.preventDefault();
      var ele = $(this).parents('.live-multi-img-group');
      ele.find('.multi_img_event').click();
    });

    <?php /*$('.multi_img_event').change(function (e) { 
      e.preventDefault();
      var ele = $(this).parents('.live-multi-img-group');
      
      var newImg = '<div class="img-list">';
      newImg += '<button type="button" class="delete"><?=file_get_contents(F_ROOT_PHP . '/_framework/modules/tiwform/icon/delete.svg')?></button>';
      newImg += '<img class="preview" src="http://178.128.83.30:8103/system/resource/file/test_once_img.gif?date=2021-03-02 20:31:06.1">';
      newImg += '</div>';

      ele.find('.live_multi_group_event').append(newImg);
      renderImg(ele.find('.preview'), this);
    });*/ ?>
  });
</script>
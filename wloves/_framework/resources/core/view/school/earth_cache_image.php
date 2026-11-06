<div class="content-header-container">
  <h3 class="header-title text-info">การทำให้รูปหรือไฟล์ไม่ติด Cache</h3>
</div>
<div class="content-body-container">
  <p class="text-info mt-3 mb-1">
    วิธีที่ 1 ทำ Javascript
  </p>
  <pre>
  <code data-language="php">
    function refreshAllImages() {
    // ดึงแท็ก <img> ทั้งหมดในหน้านั้น
    const images = document.querySelectorAll('img');

    // วนหารูปทั้งหมดในหน้า
    images.forEach(img => {
      // ยกเว้นไฟล์ .svg (เพราะการวาด SVG ไม่เหมือน Image ทั่วไป)
      if (img.src.endsWith('.svg')) return;

      // ดึงค่ารูปเดิมมาเก็บไว้
      const currentSrc = img.src;

      // เพิ่มวันเวลาตอนนั้นเพื่อทำให้รูปนั้น unique (มีคำภาษาไทยบอกได้) และไม่ติด cache
        img.src = currentSrc + '?version=' + new Date().getTime();
      });
    }
    // วิธีเรียกใช้
    refreshAllImages();
  </code>
  </pre>

  <p class="text-info mt-3 mb-1">
    วิธีที่ 2 ทำด้วย PHP
  </p>
  <pre>
  <code data-language="php">
  function refreshAllImages()
  {
    // หาแท็ก <img> ทั้งหมดในหน้านั้น
    $images = array_filter(
      $GLOBALS['_SERVER']['HTTP_REFERER']
    );

    // วนหารูปทั้งหมดในหน้า
    foreach ($images as &$img) {
      // ยกเว้นไฟล์ .svg
      if (substr($img, -4) == '.svg') continue;

      // Add the file modification time as a query string to the URL
      $img .= '?version=' . filemtime($img);
    }
  }

  // วิธีเรียกใช้
    refreshAllImages();
  </code>
  </pre>

</div>
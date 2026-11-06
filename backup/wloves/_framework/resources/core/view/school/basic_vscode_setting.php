<div class="content-header-container">
  <h3 class="header-title text-info"> Setting Vscode สำหรับ Wolves ใช้กันภายใน</h3>
</div>
<div class="content-body-container">
  <div class="body-group-text mb-30px">
    <p class="font-14px mb-0">มาเขียนเป็นทางเดียวกันเตอะ <span class="text-danger font-22px"> ไม่ทำไล่ออก!!!</span></p>
  </div>
  <div class="row">
    <div class="col-12">
      <h5 class="text-info">เปิด <span style="color:#6f42c1;"> Visual Studio Code </span> (vscode) ถ้าไม่มีก็ลง <a href="https://code.visualstudio.com/" target="_blank">Link</a> </h5>
      <h6 class="font-16px mt-3 text-info">Setting <span style="color:#6f42c1;">Vscode </span></h6>
      <p class="text-info">
        ลง Extensions
        <a href="https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client" target="_blank">
          PHP Intelephense
        </a>
      </p>
      <p class=" text-secondary">
        PHP Intelephense คือ ตัวช่วยในการจัดโค้ดที่เราเขียนให้อยู่ในรูปแบบที่ถูกต้อง
      </p>
      <a href="#extensions" class="text-warning ">Extensions ที่ควรมี</a>
      <p class=" text-secondary mt-3">
        ภาพตัวอย่าง
      </p>
      <img src="structure/image/vscode_set.png" class='w-100 mb-3'>
      <p class=" text-secondary">
        ลงเสร็จในเปิดไฟล์ <span class="text-warning"> settings.json </span>แล้วก๊อปโค้ดไปลง
      </p>
      <p class=" text-secondary">
        เปิดยังไง ?? <span class="text-success"> กด F1 > พิมพ์ "Open Settings" > Enter</span>
      </p>
      <div class="code-container mt-10px">
        <pre>
        <code  data-language="json">    
  "editor.formatOnSave": true,
  "editor.tabSize": 2,
  "intelephense.diagnostics.undefinedTypes": false,
  "intelephense.telemetry.enabled": false,
  "intelephense.diagnostics.run": "onSave",
  "intelephense.diagnostics.unusedSymbols": false,
  "intelephense.diagnostics.duplicateSymbols": false,</code>
      </pre>
      </div>
    </div>
    <div class="col-12">
      <p class="text-info">ลง Snippet ของ Wolves</p>
      <p class=" text-secondary">
        เปิดไฟล์ <span class="text-warning"> php.json </span>แล้วก๊อปโค้ดไปลง
      </p>
      <p class=" text-secondary">
        เปิดยังไง ?? <span class="text-success"> กด F1 > พิมพ์ "User Snippets" > เลือก "php.json" > Enter</span>
      </p>
      <div class="code-container mt-10px">
        <pre>
        <code  data-language="json">    
{
  // SystemZaa snipets
  "Aww Display": {
    "prefix": "zDisplay",
    "body": [
    "W_Main::display(${1:\\$data});"
    ],
    "description": "Print Formated Array or Object."
  },
  "Aww Console": {
    "prefix": "zConsole",
    "body": [
    "W_Main::console(${1:\\$data});"
    ],
    "description": "Console Log Formated Array or Object."
  },
  "Aww Format Date": {
    "prefix": "zDate",
    "body": [
    "W_Main::formatDate(${1:\\$data}, 'd/m/Y H:i:s');"
    ],
    "description": "Format Date."
  },
  "Aww Redirect": {
    "prefix": "zRedirect",
    "body": [
    "W_Main::redirect('');"
    ],
    "description": "Redirect."
  },
  "Aww Console Table": {
    "prefix": "zTable",
    "body": [
    "W_Main::consoleTable(${1:\\$data});"
    ],
    "description": "Console Log Formated Array or Object in Table."
  },
  "System Transform Array": {
    "prefix": "zTransArray",
    "body": [
    "W_Main::transformArray(${1:\\$array}, 'id');",
    "$2"
    ],
    "description": "Transform index array to key,value array."
  },
  "System Call API Origin": {
    "prefix": "zCallAPIOrigin",
    "body": [
    "W_Main::callAPIOrigin('${1:code}', '${2:action}', \\$data, false);"
    ],
    "description": "Call api with all server output."
  },
  "System Handle Notification": {
    "prefix": "zHandleNotification",
    "body": [
    "W_Main::handle_notification();"
    ],
    "description": "Load and clear notification pool."
  },
  "System Create Pagination": {
    "prefix": "zPagination",
    "body": [
    "W_Main::createPagination(${1:\\$current_page}, ${2:\\$total_page})"
    ],
    "description": "Create normal pagination."
  },
  "System Create Pagination Async": {
    "prefix": "zPaginationAsync",
    "body": [
    "W_Main::createPaginationAsync(${1:\\$current_page}, ${2:\\$total_page}, 'target_async_content_id', \\$data);"
    ],
    "description": "Create async pagination."
  },
  "System Convert Date Thai": {
    "prefix": "zConvertDateTH",
    "body": [
    "W_Main::convertDateTH(${1:\\$datetime}, false);"
    ],
    "description": "Convert time passed to string."
  },
  "System Convert Date Thai Short": {
    "prefix": "zConvertDateTHShort",
    "body": [
    "W_Main::convertDateTHShort(${1:\\$datetime}, false);"
    ],
    "description": "Convert time passed to short format string."
  },
  "Short if": {
    "prefix": "zIf",
    "body": [
    "(${1:\\$data}) ? ${2:\\$data} : '-';"
    ],
    "description": "Echo Short If"
  },
}
</code>
      </pre>
      </div>
    </div>
  </div>
</div>
<div class="content-body-container" id="extensions">
  <div class="body-group-text mb-30px">
    <h5 class="text-info">Extensions <span class=" text-warning"> ที่ควรมี</span></h5>
    <p class=" text-secondary mb-0 font-14px">ถ้ามี Extensions แนะนำมาเพิ่มได้นะ </p>
  </div>
  <p class="text-info">
    <a href="https://marketplace.visualstudio.com/items?itemName=eamodio.gitlens" target="_blank" class="font-18px text-info">
      GitLens — Git supercharged
    </a>
  </p>
  <img src="structure/image/ex01.png" class="mb-3">
  <p class="text-info">
    <a href="https://marketplace.visualstudio.com/items?itemName=ritwickdey.live-sass" target="_blank" class="font-18px text-info">
      Live Sass Compiler
    </a>
  </p>
  <img src="structure/image/ex02.png" class="mb-3">
</div>
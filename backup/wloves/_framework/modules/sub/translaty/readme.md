# A เกี่ยวกับไฟล์

 ## A.1. อยู่ในโฟลเดอร์ _framework/module_main

 ## A.2 ไฟล์ pagination.php ในฟังชั่น createHomepagify

  + ### A.2.1 เพื่ม param ของฟังชั่น

  เปลี่ยนจาก
```php
  public static function createHomepagify($id = 'table', $send_param = '', $active = '', $header_name = '', $page_no = 1, $page_size = 10, $customButton = false)
```
  เป็น

```php
  public static function createHomepagify($id = 'table', $send_param = '', $active = '', $header_name = '', $prefix = '', $page_no = 1, $page_size = 10, $customButton = false, $name_states = '')
```
---
  + ### A.2.2 ทำให้edit url ให้เรียก table บน module ได้

  เปลี่ยนจาก

```php
    echo 'data-url="ajax/table_' . $id . '.php' . $get . '"';
```
  เป็น 

```php
 echo 'data-url=' . '"' . $prefix . 'ajax/table_' . $id . '.php' . $get . '"';
```
---
## A.3 อัพเดตโฟลเดอร์ detiw
  ### A.3.1 อัพเดตโฟลเดอร์ detiw โดยนำโฟลเดอร์จาก wlovesx3 มา
  
---
## ~~A.4 ไฟล์ structure.php~~ (ยกเลิก)

  ### ~~A.4.1 ในฟังชั่น loadMeta~~

  ในลูป 
  ``` php 
   foreach ($module_main_array as $module_main) {
      include $prefix . '_framework/module_main/' . $module_main . '/config.php';
  ```
  เปลี่ยนเป็น 
  ```php
  foreach ($module_main_array as $module_main) {
       // ? protect include same file more than one.
        $path = F_ROOT_PHP . '/' . '_framework/module_main/' . $module_main . '/config.php';
        $include_list = get_included_files();
        if (!in_array($path, $include_list) || !isLocalhost()) {
          include $prefix . '_framework/module_main/' . $module_main . '/config.php';
          .
          .
          .
        }

  ```

# B การใช้งาน Translaty

 ## A.1. การใช่ Modal

  
 
  
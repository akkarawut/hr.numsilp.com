<?php
class Email {
  private static $conf = [
    'description' => 'คลาสสำหรับใช้งาน Email ทั้งหมด'
  ];

  /**
  | Group | Email
  | Description | 1. เช็คชื่ออีเมลว่าอยู่ใน format ที่ถูกต้องไหม
  | $email | ชื่ออีเมล
   */
  public static function checkEmail($email) {
    $return = false;

    // Validate
    if ($email) {
      if ($filtered_email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
      }
    }

    return $return;
  }

  /**
  | Group | Email
  | Description | 2.โหลดไฟล์ พร้อมกับใส่ ตัวแปรเข้าไปได้ ส่งกลับเป็น HTML string เลย
  | $template_path | พาทไฟล์เต็มๆที่เซิพเวอร์ ใช้ ROOT_PHP กับ DIRECTORY_SEPARATOR ได้
  | $params | ตัวแปร ใช้ Array นี้เลย ประกาศไว้ในไฟล์ template.php
   */
  public static function getHtmlTemplate($template_path, $params = []) {
    $return = '';

    // $sample_template_path = ROOT_PHP.DIRECTORY_SEPARATOR.'module'.DIRECTORY_SEPARATOR.'specific'.DIRECTORY_SEPARATOR.'aerobic_mileage'.DIRECTORY_SEPARATOR.'template_change_password.php';

    // Validate
    if (!$template_path) {
      return Amst::repError('E0001', 'Template file path');
    }

    // DOWORK
    if (file_exists($template_path)) {
      ob_start();
      include $template_path;
      $return = ob_get_clean();
    }

    // [FIX_BUG][20220301] เวลาส่งภาษาไทยยาวๆแล้ว แสดงที่อีเมล์เป็นภาษาต่างดาว
    // keyword unicode utf8 ภาษาต่างดาว
    // Apparently I needed to convert the template to HTML-ENTITIES before sending:
    // $message = mb_convert_encoding($message, 'HTML-ENTITIES', "UTF-8");
    // UPDATE 2
    // And the reason I was having to use mb_convert_encoding was due to a BOM (byte order mark) in my HTML template file. After removing this I no longer have to convert the template.
    $return = mb_convert_encoding($return, 'HTML-ENTITIES', "UTF-8");

    return $return;
  }

  /**
  | Group | Email
  | Description | 3.ส่งเมล ในรูปแบบของ HTML
  | $target_email | อีเมลผู้รับ
  | $sender_email | เมลผู้ส่ง จะเป็นอะไรก็ได้
  | $sender_name | ชื่อผู้ส่ง
  | $subject | ชื่อหัวข้อ
  | $html | HTML String
  | $files_attachment | ไฟล์แนบ **ยังไม่ได้ ส่งแบบไหนดี sys_file หรือ file_input
   */
  public static function sendHtmlMail($target_email, $sender_email, $sender_name, $subject, $html, $files_attachment = []) {
    $rep = [
      'message' => 'ERROR'
    ];

    // Validate
    if (!$target_email) {
      return Amst::repError('E0001', 'Customer Email');
    }
    if (!$sender_email) {
      return Amst::repError('E0001', 'Sender Email');
    }
    if (!$subject) {
      return Amst::repError('E0001', 'Subject');
    }
    if (!$html) {
      return Amst::repError('E0001', 'Message');
    }

    // error_reporting(E_ALL);
    // ini_set('display_errors', true);
    // ini_set('display_startup_errors', true);

    // CONFIG
    if (!defined('Config::phpmailer_gmail_account') || (!Config::phpmailer_gmail_account) || !defined('Config::phpmailer_gmail_password') || (!Config::phpmailer_gmail_password)) {
      Log::add(3, 'Class Email.sendHtmlMail ERROR : Config not set', 0, 'backend');
      return Amst::repError('E0000', 'Config not set');
    }

    // PATH == /var/www/html/system/library/PHPMailer/src/
    $library_path = ROOT_PHP.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
    if (!file_exists($library_path.'PHPMailer.php')) {
      Log::add(3, 'Class Email.sendHtmlMail ERROR : Library Class file not found, $library_path='.$library_path, 0, 'backend');
      return Amst::repError('E0000', 'Library not found');
    }

    if ($html) {
      require_once $library_path.'PHPMailer.php';
      require_once $library_path.'SMTP.php';
      require_once $library_path.'Exception.php';

      // ERROR HANDLER / TROUHBLE SHOOTER
      // Status: IMAP is disabled
      // 1.Check IMAP
      // https://mail.google.com/mail/u/0/?tab=rm&ogbl#settings/fwdandpop
      // IMAP access: (access Gmail from other clients using IMAP)
      // turn IMAP == ON

      // on SMTP connect() failed PHPmailer
      // 1. go => https://www.google.com/settings/security/lesssecureapps
      // 2. go => http://www.google.com/accounts/DisplayUnlockCaptcha and resent mail in 10 minute to enable app
      $mail = new PHPMailer\PHPMailer\PHPMailer;
      $mail->isSMTP();
      $mail->SMTPDebug   = 0; // 4 for fully debug
      $mail->Debugoutput = 'html';
      $mail->Host        = "smtp.gmail.com";
      $mail->Port        = 587;
      $mail->SMTPSecure  = 'tls';
      $mail->SMTPAuth    = true;
      $mail->SMTPAutoTLS = false;
      $mail->Username    = Config::phpmailer_gmail_account; // เมลที่ขอรหัส
      $mail->Password    = Config::phpmailer_gmail_password; // รหัส
      $mail->SetFrom($sender_email, $sender_name);
      // [LATER] many mail ? receiver name ? $mail->AddAddress('dev.rerobicmileage@gmail.com', 'receiver name');
      $mail->AddAddress($target_email);
      // [LATER] CC
      // if ($is_cc) {
      //   $mail->AddCC($send_from_email, $send_from_name);
      // }
      // // [LATER] Provide file path and name of the attachments
      // foreach ($path_file as $key => $path) {
      //   $root_path = ROOT_PHP.'/'.$path['path'];
      //   $mail->addAttachment($root_path, $path['file_name']);
      // }
      $mail->Subject = $subject;
      $mail->msgHTML($html);

      if (!$mail->send()) {
        Log::add(2, $mail->ErrorInfo, 0, 'backend');
        return Amst::repError('E0000', 'ERROR on mail System');
      } else {
        // SUCCESS
        $rep['message'] = 'Change password Successfully';
      }
    } else {
      Log::add(2, ['Invalid Input :', $html, $html], 0, 'backend');
      return Amst::repError('E0000', 'ERROR on mail System');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Send Mail
  | Description | ส่งเมลเล้ออ *แบบเก่า ค่อยเอาออกทีหลัง
   */
  static function sendMailer($send_from_email, $send_from_name, $send_to, $title, $message, $path_file = [], $is_cc = false) {
    require ROOT_PHP.'/library/PHPMailer/src/Exception.php';
    require ROOT_PHP.'/library/PHPMailer/src/PHPMailer.php';
    require ROOT_PHP.'/library/PHPMailer/src/SMTP.php';
    //Create a new PHPMailer instance.
    $mail          = new PHPMailer\PHPMailer\PHPMailer;
    $mail->CharSet = 'UTF-8';
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
    //Set the hostname of the mail server
    $mail->Host = "smtp.gmail.com";
    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication
    $mail->Username = "";
    //Password to use for SMTP authentication
    $mail->Password = "";
    //Set who the message is to be sent from
    $mail->setFrom($send_from_email, $send_from_name);
    //Set who the message is to be sent to
    foreach ($send_to as $key => $email) {
      $mail->addAddress($email, $email);
    }
    //เพิ่ม CC

    if ($is_cc) {
      $mail->AddCC($send_from_email, $send_from_name);
    }
    //Provide file path and name of the attachments
    foreach ($path_file as $key => $path) {
      $root_path = ROOT_PHP.'/'.$path['path'];
      $mail->addAttachment($root_path, $path['file_name']);
    }
    //Set the subject line
    $mail->Subject = $title;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('content.html'), dirname(__FILE__));
    $mail->msgHTML($message);

    //send the message, check for errors
    if (!$mail->send()) {
      return "Mailer Error: ".$mail->ErrorInfo;
    } else {
      return "Message sent!";
    }
  }
}

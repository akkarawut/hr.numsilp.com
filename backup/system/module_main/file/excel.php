<?php
class Excel {
  private static $conf = [
    'description' => 'คลาสสำหรับใช้งาน Excel ทั้งหมด',
  ];

  /**
  | Group | IMPORT
  | Description | 1.Import file Excel อ่านทุกช่องในรูปแบบ A,B,C,AA,AB
  | $file_path | Path ไฟล์ ถ้าไฟล์อัพโหลด เป็น $file['tmp_path']
  | $sheet_index | index ของ sheet เริ่มที่ 0
  | $start_row | เริ่มอ่านที่แถว
  | $map_array | array = ['ชื่อช่อง' => 'A','ชื่อช่อง' => 'B']
   */
  public static function importMappingExcel($file_path, $sheet_index = 0, $start_row = 0, $map_array = []) {
    $return = [];

    $class_path = ROOT_PHP . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';
    if (!file_exists($class_path)) {
      $debug = [
        'file_path'   => $file_path,
        'sheet_index' => $sheet_index,
        'start_row'   => $start_row,
        'map_array'   => $map_array,
      ];
      Log::logBackendShouldKnow('ERROR for importMappingExcel, Upload Library/PHPExcel', $debug);
      return Amst::repError('E0000', 'Upload Excel Library first');
    }
    include ROOT_PHP . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';

    // $debug = [
    //   'REQUEST'     => $_REQUEST,
    //   'FIEL'        => $_FILES,
    //   'file_path'   => $file_path,
    //   'sheet_index' => $sheet_index,
    //   'start_row'   => $start_row,
    //   'map_array'   => $map_array
    // ];
    // Log::logBackendShouldKnow('DEBUG importMappingExcel', $debug);

    // AUTO convert
    if (isset($file_path['tmp_name'])) {
      $file_path = $file_path['tmp_name'];
    }

    // set_time_limit(120);
    $inputFileType = PHPExcel_IOFactory::identify($file_path);
    $objReader     = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($file_path);

    $objWorksheet  = $objPHPExcel->setActiveSheetIndex($sheet_index);
    $highestRow    = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
    for ($row = 1; $row <= $highestRow; ++$row) {
      $dataRow      = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
      $return[$row] = $dataRow[$row];
    }

    // First Row
    if ($start_row > 0) {
      foreach (range(0, $start_row) as $i_skip) {
        if (isset($return[$i_skip])) {
          unset($return[$i_skip]);
        }
      }
    }

    // Map data
    if ($map_array && is_array($map_array)) {
      // $map_array = [
      //   "class_code1" => 'B',
      //   "chin_name2"  => 'A',
      //   "eng_name3"   => 'C'
      // ];
      $return = array_map(function ($i_row) use ($map_array) {
        // TODO
        $fn_return = [];
        foreach ($map_array as $i_key => $i_col) {
          $fn_return[$i_key] = ($i_row[$i_col]) ? $i_row[$i_col] : '';
        }
        // return [
        //   "class_code" => ($i_row['A']) ? $i_row['A'] : '',
        //   "chin_name"  => ($i_row['B']) ? $i_row['B'] : '',
        //   "eng_name"   => ($i_row['C']) ? $i_row['C'] : ''
        // ];
        return $fn_return;
      }, $return);
    }

    return $return;
  }

  static function exportTable($filename, $code, $fieldlist, $where) {
    $return = null;

    $data = Amst::select($code, $fieldlist, $where);
    if ('*' == $fieldlist) {
      $field_tb  = Amst::query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'func_" . $code . "'");
      $fieldlist = [];
      foreach ($field_tb as $key => $value) {
        array_push($fieldlist, $value['COLUMN_NAME']);
      }
    }
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    date_default_timezone_set('Europe/London');

    if (PHP_SAPI == 'cli') {
      die('This example should only be run from a Web Browser');
    }

    /** Include PHPExcel */
    // require_once  realpath(__DIR__."/../../../../") . '/optional_library/PHPExcel/PHPExcel.php';
    require_once ROOT_PHP . '/library/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
      ->setLastModifiedBy("Maarten Balliauw")
      ->setTitle("Office 2007 XLSX Document")
      ->setSubject("Office 2007 XLSX Document")
      ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("Result file");
    $row = 2;
    $col = 0;
    foreach ($fieldlist as $key => $field) {
      $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension("A")
        ->setAutoSize(true);

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow($col, 1, $field);
      $col++;
    }

    foreach ($data as $header => $list) {
      $col = 0;
      foreach ($fieldlist as $key => $field) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $list[$field]);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($col)->setAutoSize(true);
        $col++;
      }
      $row++;
    }
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($filename);

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit();
  }

  static function exportArray($filename, $data) {
    $return = null;

    error_reporting(0);
    // ini_set('display_errors', true);
    // ini_set('display_startup_errors', true);
    // date_default_timezone_set('Europe/London');

    if (PHP_SAPI == 'cli') {
      die('This example should only be run from a Web Browser');
    }

    /** Include PHPExcel */
    //require_once  realpath(__DIR__."/../../../../").'/optional_library/PHPExcel/PHPExcel.php';
    require_once ROOT_PHP . '/library/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
      ->setLastModifiedBy("Maarten Balliauw")
      ->setTitle("Office 2007 XLSX Document")
      ->setSubject("Office 2007 XLSX Document")
      ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("Result file");

    $row = 2;
    $col = 0;

    $fieldlist = [];
    $fieldlist = array_keys($data[0]);
    foreach ($fieldlist as $key => $field) {
      $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension("A")
        ->setAutoSize(true);

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow($col, 1, $field);
      $col++;
    }

    foreach ($data as $key => $list) {
      $col = 0;
      foreach ($fieldlist as $key => $field) {
        //$list[$field] = preg_replace('/([^a-zA-Z0-9ก-๙เ])/', ' ', $list[$field]);
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, '="' . $list[$field] . '"');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $list[$field]);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($col)->setAutoSize(true);
        $col++;
      }
      $row++;
    }
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($filename);

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if (Util::endsWith($filename, '.csv')) {
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
    } else {
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    }
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    if (ob_get_contents() || ob_get_length()) {
      ob_end_clean(); //or ob_end_flush();
    }

    //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    //Log::add(1, $filename,0, 'backend');
    // echo $filename;
    exit();
    // return $data;
  }

  static function import($file, $start_row = 1) {
    date_default_timezone_set('Europe/London');
    if (PHP_SAPI == 'cli') {
      die('This example should only be run from a Web Browser');
    }

    /** Include PHPExcel */
    // require_once  realpath(__DIR__."/../../../../").'/optional_library/PHPExcel/PHPExcel.php';
    require_once ROOT_PHP . '/library/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $excelReader   = PHPExcel_IOFactory::createReaderForFile($file);
    $excelObj      = $excelReader->load($file);
    $worksheet     = $excelObj->getSheet(0);
    $lastRow       = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $i_data        = [];
    $fields        = [];
    for ($col = 0; $col < $highestColumn; $col++) {
      $data = $worksheet->getCellByColumnAndRow($col, $start_row)->getValue();
      array_push($fields, $data);
    }
    $wrap = [];
    for ($row = 2; $row <= $lastRow; $row++) {
      $col  = 0;
      $data = [];
      foreach ($fields as $key => $field) {
        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
        // fix error log
        $data["{$field}"] = $value;
        $col++;
      }
      array_push($wrap, $data);
    }
    return $wrap;
  }

  static function importExcel($file, $start_row = 1) {
    $return = [];

    date_default_timezone_set('Europe/London');
    if (PHP_SAPI == 'cli') {
      die('This example should only be run from a Web Browser');
    }

    /** Include PHPExcel */
    // require_once  realpath(__DIR__."/../../../../").'/optional_library/PHPExcel/PHPExcel.php';
    require_once ROOT_PHP . '/library/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $excelReader   = PHPExcel_IOFactory::createReaderForFile($file);
    $excelObj      = $excelReader->load($file);
    $worksheet     = $excelObj->getSheet(0);
    $lastRow       = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $i_data        = [];
    $fields        = [];
    for ($col = 0; $col < $highestColumn; $col++) {
      $data = $worksheet->getCellByColumnAndRow($col, $start_row)->getValue();
      array_push($fields, $data);
    }

    for ($row = 2; $row <= $lastRow; $row++) {
      $col  = 0;
      $data = [];
      foreach ($fields as $key => $field) {
        $value = $worksheet->getCellByColumnAndRow($col, $row)->getFormattedValue(); // Fix bugs date time as numeric
        // fix error log
        $data["{$field}"] = $value;
        $col++;
      }
      array_push($return, $data);
    }
    return $return;
  }

  static function importCsv($file, $has_header = true) {
    $rep = [
      'file'   => $file,
      'output' => false,
    ];
    if ($file) {
      error_reporting(E_ALL);
      ini_set('display_errors', true);
      ini_set('display_startup_errors', true);
      date_default_timezone_set('Europe/London');
      // *'application/vnd.ms-excel', Cant handle ERROR
      $mimes = ['text/plain', 'text/csv', 'text/tsv'];
      if (in_array($file['type'], $mimes)) {
        // Set our own error handler; we will restore the default one afterwards.
        // Our new error handler need only handle E_WARNING and E_NOTICE, as per
        // the documentation of getimagesize().
        set_error_handler("Excel::my_error_handler", E_WARNING | E_NOTICE);

        // No error has occured yet; it is the responsibility of my_error_handler
        // to set $error_occurred to true if an error occurs.
        $error_occurred = false;

        // Call getimagesize; use operator @ to have errors not be generated
        // However, your error handler WILL STILL BE CALLED, as the documentation
        // for set_error_handler() states.
        $result = @array_map('str_getcsv', file($file['tmp_name']));

        // At this point, my_error_handler will have run if an error occurred, and
        // $error_occurred will be true. Before doing anything with it, restore the
        // previous error handler
        restore_error_handler();

        if ($error_occurred) {
          return Amst::repError('restore_error_handler', $error_occurred);
        } else {
          // no error; $size holds information we can use
          $rep['output'] = array_map('Excel::trimRow', $result);
        }
      } else {
        return Amst::repError('E0000', "Unsupported File type ({$file['type']})");
      }
    } else {
      return Amst::repError('E0000', 'No File');
    }

    return Amst::repSuccess($rep);
  }

  static function trimRow($value) {
    if (is_array($value)) {
      return array_map('Excel::trimRow', $value);
    }
    return preg_replace("/\xEF\xBB\xBF/", "", (trim($value)));
  }

  static function my_error_handler($errno, $errstr, $file, $line) {
    global $error_occurred;

    // If the code is written as above, then we KNOW that an error
    // here was caused by getimagesize(). We also know what error it was:
    switch ($errno) {
    case E_WARNING:
      Log::add(3, [$errno, $errstr, $file, $line], 0, 'backend');
      break;
    case E_NOTICE:
      Log::add(3, [$errno, $errstr, $file, $line], 0, 'backend');
      break;
    }

    // We could also check what $file is and maybe do something based on that,
    // if this error handler is used from multiple places. However, I would not
    // recommend that. If you need more functionality, just package all of this
    // into a class and use the objects of that class to store more state.

    $error_occurred = true;
    return true; // Do not let PHP's default error handler handle this after us
  }

  static function importExcelMultiSheets($file, $start_row = 1) {
    $return = [];

    date_default_timezone_set('Europe/London');
    if (PHP_SAPI == 'cli') {
      die('This example should only be run from a Web Browser');
    }

    /** Include PHPExcel */
    // require_once  realpath(__DIR__."/../../../../").'/optional_library/PHPExcel/PHPExcel.php';
    require_once ROOT_PHP . '/library/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $excelReader = PHPExcel_IOFactory::createReaderForFile($file);
    $excelObj    = $excelReader->load($file);

    $sheetname = $excelObj->getSheetNames();

    foreach ($sheetname as $sheet_index => $sheet_name) {
      if (trim($sheet_name)) {
        $worksheet     = $excelObj->getSheet($sheet_index);
        $lastRow       = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $i_data        = [];
        $fields        = [];
        for ($col = 0; $col < $highestColumn; $col++) {
          $data = $worksheet->getCellByColumnAndRow($col, $start_row)->getValue();
          array_push($fields, $data);
        }

        for ($row = 2; $row <= $lastRow; $row++) {
          $col  = 0;
          $data = [];
          foreach ($fields as $key => $field) {
            $value = $worksheet->getCellByColumnAndRow($col, $row)->getFormattedValue(); // Fix bugs date time as numeric
            // fix error log
            $data["{$field}"] = $value;
            $col++;
          }
          $return[$sheet_name][] = $data;
        }
      }
    }

    return $return;
  }

  static function exportArrayMultiSheets($filename, $data) {
    error_reporting(0);
    $ex_data = [
      'sheet_name1' => [
        [
          'data_1' => 'data_1',
        ],
      ],
      'sheet_name2' => [
        [
          'data_2' => 'data_2',
        ],
      ],
    ];

    if (!trim($filename)) {
      $filename = 'export';
    }
    if (PHP_SAPI == 'cli') {
      die('This example should only be run from a Web Browser');
    }

    /** Include PHPExcel */
    //require_once  realpath(__DIR__."/../../../../").'/optional_library/PHPExcel/PHPExcel.php';
    require_once ROOT_PHP . '/library/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Wolves")
      ->setLastModifiedBy("Wolves")
      ->setTitle("Office 2007 XLSX Document")
      ->setSubject("Office 2007 XLSX Document")
      ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("Result file");

    $objPHPExcel->getActiveSheet();
    $sheet_index = 0;
    foreach ($data as $sheet_name => $sheet_data) {
      if ($sheet_name && is_array($sheet_data) && count($sheet_data) > 0) {
        $row        = 2;
        $col        = 0;
        $sheet_name = $sheet_name;
        // $sheet_data   = $sheet['data'];
        $fieldlist    = [];
        $fieldlist    = array_keys($sheet_data[0]);
        $objWorkSheet = $objPHPExcel->createSheet($sheet_index);
        $objWorkSheet->setTitle($sheet_name);
        foreach ($fieldlist as $key1 => $field) {
          $objPHPExcel->setActiveSheetIndex($sheet_index)
            ->getColumnDimension("A")
            ->setAutoSize(true);

          $objPHPExcel->setActiveSheetIndex($sheet_index)
            ->setCellValueByColumnAndRow($col, 1, $field);
          $col++;
        }

        foreach ($sheet_data as $key2 => $list) {
          $col = 0;
          foreach ($fieldlist as $key3 => $field) {
            $objPHPExcel->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col, $row, $list[$field]);
            $objPHPExcel->setActiveSheetIndex($sheet_index)->getColumnDimensionByColumn($col)->setAutoSize(true);
            $col++;
          }
          $row++;
        }
        $sheet_index++;
      }
    }
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->removeSheetByIndex(
      $objPHPExcel->getIndex(
        $objPHPExcel->getSheetByName('Worksheet')
      )
    );

    // Redirect output to a client’s web browser (Excel2007)
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if (Util::endsWith($filename, '.csv')) {
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
    } else {
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    }
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    if (ob_get_contents() || ob_get_length()) {
      ob_end_clean(); //or ob_end_flush();
    }

    //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    //Log::add(1, $filename,0, 'backend');
    // echo $filename;
    exit();
    // return $data;
  }
}

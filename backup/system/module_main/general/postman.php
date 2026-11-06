<?php
class Postman {
  private static $conf = [
    'description'  => 'ใช้ Export API ในรูปแบบของ POSTMAN',
  ];

  private static function testPostManRaw($code) {
    $fn = [
      "code"     => "frwna",
      "api_key"  => "42674ea8d0399f6adbd1c9f8209afcea",
      "function" => "getProject",
      "class"    => "Bce_Project",
      "params"   => [
        "frwna",
        [
          "with_img"  => "2",
          "page_no"   => 1,
          "page_size" => 2
        ]
      ]
    ];

    $collection = [
      "info"                    => [
        "_postman_id" => "99dc1991-7276-450e-a375-76faf62a21f6",
        "name"        => "Bce_Project",
        "schema"      => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
      ],
      "item"                    => [
        [
          "name"                    => "getInformationByID",
          "description"             => "TEST",
          "protocolProfileBehavior" => [
            "disableBodyPruning" => true
          ],
          "request"                 => [
            "method" => "GET",
            "header" => [
            ],
            "body"   => [
              "mode"    => "raw",
              "raw"     => json_encode($fn),
              "options" => [
                "raw" => [
                  "language" => "json"
                ]
              ]
            ],
            "url"    => [
              "raw"      => "https://localhost.com/system/api/call.php",
              "protocol" => "https",
              "host"     => [
                "localhost",
                "com"
              ],
              "path"     => [
                "system",
                "api",
                "call.php"
              ]
            ]
          ],
          "response"                => [
          ]
        ]
      ],
      "protocolProfileBehavior" => []
    ];

    return $collection;
  }

  private static function testPostManMin($code) {
    $url = [
      "raw"      => "https://localhost.com/system/api/call.php",
      "protocol" => "https",
      "host"     => [
        "localhost",
        "com"
      ],
      "path"     => [
        "system",
        "api",
        "call.php"
      ]
    ];
    $fn = [
      "code"     => "frwna",
      "api_key"  => "42674ea8d0399f6adbd1c9f8209afcea",
      "class"    => "Bce_Project",
      "function" => "getProject",
      "params"   => [
        "frwna",
        [
          "with_img"  => "2",
          "page_no"   => 1,
          "page_size" => 2
        ]
      ]
    ];

    $collection = [
      "info" => [
        "name"   => "Bce_Project",
        "schema" => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
      ],
      "item" => [
        [
          "name"                    => "getInformationByID",
          "description"             => "TEST",
          "protocolProfileBehavior" => [
            "disableBodyPruning" => true // จำเป็น
          ],
          "request"                 => [
            "url"    => $url,
            "method" => "GET",
            "header" => [
            ],
            "body"   => [
              "mode"    => "raw",
              "raw"     => json_encode($fn),
              "options" => [
                "raw" => [
                  "language" => "json"
                ]
              ]
            ]
          ]
        ]
      ]
    ];

    return $collection;
  }

  /**
  | Group | Export
  | Description | โหลดข้อมูล Class เป็น Collection
  | $code | โค้ดโมดูล
  | $api_key | ดีย์
  | $class_name | ชื่อคลาส
  | $options | ไม่มี
   */
  public static function getCollection($code, $api_key, $class_name, $options = []) {
    $colections = [];

    $class_list = [$class_name];
    foreach ($class_list as $i_class_name) {
      $ex_class = static::selectClassFunction($i_class_name);
      if (isset($ex_class['function_list'])) {
        $fn_list = [];
        foreach ($ex_class['function_list'] as $i_fn) {
          $i_params = [$code];

          if (Util::startsWith($i_fn['function'], 'get') && Util::endsWith($i_fn['function'], 'ByID') || Util::startsWith($i_fn['function'], 'update') || Util::startsWith($i_fn['function'], 'delete')) {
            $i_params[] = 1; // ID as second Params
          }
          // Select/Get
          if (isset($i_fn['params_where'])) {
            $i_params[] = $i_fn['params_where'];
          }
          if (isset($i_fn['params_option'])) {
            $i_params[] = $i_fn['params_option'];
          }
          // New/Update
          if (isset($i_fn['params_data']) && $i_fn['params_data']) {
            $i_params[] = $i_fn['params_data'];
          }

          $fn_list[] = [
            'name'   => $i_fn['function'],
            'method' => static::getMethodByName($i_fn['function']),
            'desc'   => implode("\r\n", $i_fn['description']), // เอากลับเหมือนเดิม 555+
            'raw'    => [
              "code"     => $code,
              "api_key"  => $api_key,
              "class"    => $ex_class['class_name'],
              "function" => $i_fn['function'],
              "params"   => $i_params
            ]
          ];
        }

        if ($fn_list) {
          $colections[] = static::outputPostman($ex_class['class_name'], $fn_list);
        }
      }
    }

    return isset($colections[0]) ? $colections[0] : []; // 0
  }

  /**
  | Group | Export
  | Description | โหลดข้อมูล Class เป็น Collection
  | $code | โค้ดโมดูล
  | $api_key | ดีย์
  | $class_name | ชื่อคลาส
  | $options | ไม่มี
   */
  public static function debugCollection($code, $api_key, $class_name, $options = []) {
    $rep = [
      'code'       => $code,
      'api_key'    => $api_key,
      'class_name' => $class_name,
      'options'    => $options,
      'ex_class'   => []
    ];
    $colections = [];

    $class_list = [$class_name];
    foreach ($class_list as $i_class_name) {
      $ex_class = static::selectClassFunction($i_class_name);
      $rep['ex_class'] = $ex_class;
      if (isset($ex_class['function_list'])) {
        $fn_list = [];
        foreach ($ex_class['function_list'] as $i_fn) {
          $i_params = [$code];

          if (Util::startsWith($i_fn['function'], 'get') && Util::endsWith($i_fn['function'], 'ByID') || Util::startsWith($i_fn['function'], 'update') || Util::startsWith($i_fn['function'], 'delete')) {
            $i_params[] = 1; // ID as second Params
          }
          // Select/Get
          if (isset($i_fn['params_where'])) {
            $i_params[] = $i_fn['params_where'];
          }
          if (isset($i_fn['params_option'])) {
            $i_params[] = $i_fn['params_option'];
          }
          // New/Update
          if (isset($i_fn['params_data'])) {
            $i_params[] = $i_fn['params_data'];
          }

          $fn_list[] = [
            'name' => $i_fn['function'],
            'desc' => implode("\r\n", $i_fn['description']), // เอากลับเหมือนเดิม 555+
            'raw'  => [
              "code"     => $code,
              "api_key"  => $api_key,
              "class"    => $ex_class['class_name'],
              "function" => $i_fn['function'],
              "params"   => $i_params
            ]
          ];
        }

        if ($fn_list) {
          $colections[] = static::outputPostman($ex_class['class_name'], $fn_list);
        }
      }
    }

    $rep['colections'] = $colections;

    //return isset($colections[0]) ? $colections[0] : []; // 0
    return Amst::repSuccess($rep);
  }

  private static function testexportPostman($code, $api_key, $class_name) {
    $project = [
      'version'       => 1,
      'collections'   => [],
      'environments'  => [],
      'headerPresets' => [],
      'globals'       => []
    ];

    // todo setting..
    $module_name = 'BCEvent';
    $project_name = 'BCEvent';

    // AMSTX
    $amst_fn_list[] = [
      'name' => 'addNewModule',
      'desc' => 'Create new Project',
      'raw'  => [
        "code"     => $code,
        "api_key"  => $api_key,
        "class"    => "Func",
        "function" => "add",
        "params"   => [
          $module_name,
          $project_name
        ]
      ]
    ];
    $amst_fn_list[] = [
      'name' => 'regenerateSQLTable',
      'desc' => 'Create/Update DATABASE',
      'raw'  => [
        "code"     => $code,
        "api_key"  => $api_key,
        "class"    => "DB_Init",
        "function" => "regenerateSQLTable",
        "params"   => []
      ]
    ];
    $project['collections'][] = static::outputPostman('AMST', $amst_fn_list);

    // Ex.
    $fn_list[] = [
      'name' => 'getProject',
      'desc' => 'call fn getProject',
      'raw'  => [
        "code"     => $code,
        "api_key"  => $api_key,
        "class"    => "Bce_Project",
        "function" => "getProject",
        "params"   => [
          "frwna",
          [
            "with_img"  => "2",
            "page_no"   => 1,
            "page_size" => 2
          ]
        ]]
    ];
    $fn_list[] = [
      'name' => 'updateProject',
      'desc' => 'call fn updateProject',
      'raw'  => [
        "code"     => $code,
        "api_key"  => $api_key,
        "class"    => "Bce_Project",
        "function" => "updateProject",
        "params"   => [
          "frwna",
          [
            "name"           => "ตูมตามeeee",
            "description"    => "ตูมตามeeee",
            "main_color"     => "#FFFFFFF",
            "start_datetime" => "2021-12-01",
            "end_datetime"   => "2021-12-31"
          ]
        ]]
    ];

    $class_list = [
      'Bce_Agenda',
      'BCE_Exhibition',
      'Bce_Information',
      'BCE_News',
      'BCE_Project',
      'BCE_Sponser'
    ];
    foreach ($class_list as $i_class_name) {
      $ex_class = static::selectClassFunction($i_class_name);
      if (isset($ex_class['function_list'])) {
        $fn_list = [];
        foreach ($ex_class['function_list'] as $i_fn) {
          $i_params = [$code];
          if (isset($i_fn['params_data'])) {
            $i_params[] = $i_fn['params_data'];
          }

          $fn_list[] = [
            'name'   => $i_fn['function'],
            'method' => static::getMethodByName($i_fn['function']),
            'desc'   => implode("\r\n", $i_fn['description']), // เอากลับเหมือนเดิม 555+
            'raw'    => [
              "code"     => $code,
              "api_key"  => $api_key,
              "class"    => $ex_class['class_name'],
              "function" => $i_fn['function'],
              "params"   => $i_params
            ]
          ];
        }

        if ($fn_list) {
          $project['collections'][] = static::outputPostman($ex_class['class_name'], $fn_list);
        }
      }
    }

    return $project;
  }

  private static function exportPostman($col_name, $fn_list) {
    //TODO

    // "id": "04c75694-4c18-406c-b935-2ca006e6aee9",
    // "name": "Report",
    // "description": null,
    // "auth": null,
    // "events": null,
    // "variables": [],
    // "order": [
    //   "b0829c55-ad40-4212-99a9-3864b5e8f047",
    //   "b4f734ea-aadf-4d0d-8235-1a6e7440780d",
    //   "1d8c8052-0508-4c23-8503-cfadc3a5d0fe",
    //   "9541d2dd-e1b7-45b4-98e5-a8ac0e770e45"
    // ],
    // "folders_order": [],
    // "protocolProfileBehavior": {},
    // "folders": [],
    // "requests": [

    return [];
  }

  private static function outputPostman($col_name, $fn_list) {

    // $i_params[] = $_SERVER['HTTPS'];
    // $i_params[] = $_SERVER['HTTP_USESSL'];
    // $i_params[] = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on')) {
      $protocol = 'https';
    }
    if (($protocol == 'http') && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
      $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    }

    $url = [
      // "raw" => "https://localhost.com/system/api/call.php",
      //"protocol" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"), //"https",
      "protocol" => $protocol,
      "host"     => explode('.', $_SERVER['HTTP_HOST']),
      "path"     => explode('/', $_SERVER['REQUEST_URI'])
    ];
    $collection = [
      "info" => [
        "name"   => $col_name,
        "schema" => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
      ],
      "item" => [
        // [
        //   "name" => "getInformationByID",
        //   "description" => "TEST",
        //   "protocolProfileBehavior" => [
        //     "disableBodyPruning" => true // จำเป็น
        //   ],
        //   "request" => [
        //     "url" => $url,
        //     "method" => "GET",
        //     "header" => [
        //     ],
        //     "body" => [
        //       "mode" => "raw",
        //       "raw" => json_encode($fn),
        //       "options" => [
        //         "raw" => [
        //           "language" => "json"
        //         ]
        //       ]
        //     ]
        //   ]
        // ]
      ]
    ];

    foreach ($fn_list as $fn) {
      $collection['item'][] = [
        "name"                    => $fn['name'],
        "protocolProfileBehavior" => [
          "disableBodyPruning" => true // จำเป็น
        ],
        "request"                 => [
          "url"         => $url,
          "description" => $fn['desc'],
          "method"      => (isset($fn['method']) && ($fn['method'])) ? $fn['method'] : "GET",
          "header"      => [
          ],
          "body"        => [
            "mode"    => "raw",
            "raw"     => json_encode($fn['raw'], JSON_UNESCAPED_UNICODE), // รองรับภาษาไทย
            "options" => [
              "raw" => [
                "language" => "json"
              ]
            ]
          ]
        ]
      ];
    }

    return $collection;
  }

  private static function selectClassFunction($class_name) {
    if (!$class_name) {
      return false;
    }

    $rep['class_name'] = $class_name;
    $rep['class_exists'] = class_exists($class_name);

    $class_functions = get_class_methods($class_name);
    $rep['class_functions'] = $class_functions;

    if ($class_functions) {
      $ref_class = new ReflectionClass($class_name);
      $rep['class_path'] = $ref_class->getFileName();
      $rep['text'] = file($ref_class->getFileName());
      foreach ($class_functions as $class_function) {
        $extract_data = self::extractDoc($class_name, $class_function);
        if ($extract_data) {
          $extract_data['text'] = [];
          // Extract Conf
          if ($extract_data['start_line'] && $extract_data['end_line']) {
            foreach (range($extract_data['start_line'], $extract_data['end_line']) as $i) {
              $extract_data['text'][] = str_replace("\r\n", '', $rep['text'][$i - 1]); // offset-1
            }
          }

          // [SUPPORT_FUNTION_NAME] CREATE DATA/BODY
          if (Util::startsWith($extract_data['function'], 'addNew')) {
            $has_start_line = false;
            foreach ($extract_data['text'] as $line) {
              $i_line = trim($line);

              if (!$has_start_line) {
                if ($i_line == '$options[\'insert_fields\'] = [') {
                  $has_start_line = true;
                }
              }
              if ($has_start_line) {
                if (strpos($i_line, '=>') && (Util::startsWith($i_line, '\'') || Util::startsWith($i_line, '"'))) {
                  $i_var = explode('=>', $i_line);
                  $var_name = static::_stripFieldKey($i_var[0]);
                  if ($var_name) {
                    $extract_data['params_data'][$var_name] = static::_stripFieldValue($i_var[1], $var_name);
                  }
                }
                if (trim($line) == "];") {
                  $has_start_line = false;
                }
              }
            }
          } else if (Util::startsWith($extract_data['function'], 'update')) {
            $has_start_line = false;
            foreach ($extract_data['text'] as $line) {
              $i_line = trim($line);

              if (!$has_start_line) {
                if ($i_line == '$options[\'insert_fields\'] = [') {
                  $has_start_line = true;
                }
              }
              if ($has_start_line) {
                if (strpos($i_line, '=>') && (Util::startsWith($i_line, '\'') || Util::startsWith($i_line, '"'))) {
                  $i_var = explode('=>', $i_line);
                  $var_name = static::_stripFieldKey($i_var[0]);
                  if ($var_name) {
                    $extract_data['params_data'][$var_name] = static::_stripFieldValue($i_var[1], $var_name);
                  }
                }
                if (trim($line) == "];") {
                  $has_start_line = false;
                }
              }
            }
          } else if (Util::startsWith($extract_data['function'], 'count') || Util::startsWith($extract_data['function'], 'select') || Util::startsWith($extract_data['function'], 'get')) {
            // WHEREs
            $has_start_line = false;
            foreach ($extract_data['description'] as $i_desc) {
              if (!$has_start_line) {
                if (Util::startsWith($i_desc, '<h5>$where =')) {
                  $has_start_line = true;
                }
              }
              if ($has_start_line) {
                if (Util::startsWith($i_desc, '<li>') && strpos($i_desc, '=>')) {
                  $i_var = explode('=>', $i_desc);
                  $var_name = static::_stripCommentKey($i_var[0]);
                  if ($var_name) {
                    $extract_data['params_where'][$var_name] = static::_stripCommentValue($i_var[1]);
                  }
                }
                if ($i_desc == "</ul>") {
                  $has_start_line = false;
                }
              }
            }
            // OPTIONs
            $has_start_line = false;
            foreach ($extract_data['description'] as $i_desc) {
              if (!$has_start_line) {
                if (Util::startsWith($i_desc, '<h5>$options =')) {
                  $has_start_line = true;
                }
              }
              if ($has_start_line) {
                if (Util::startsWith($i_desc, '<li>') && strpos($i_desc, '=>')) {
                  $i_var = explode('=>', $i_desc);
                  $var_name = static::_stripCommentKey($i_var[0]);
                  if ($var_name) {
                    $extract_data['params_option'][$var_name] = static::_stripCommentValue($i_var[1]);
                  }
                }
                if ($i_desc == "</ul>") {
                  $has_start_line = false;
                }
              }
            }
          }

          // [DOC 2.0] [TODO]
          // foreach ($extract_data['parameter'] as $i_param) {
          //   $parameter_list[$i_param->name] = $i_param->name;
          // }
          foreach ($extract_data['parameter_detail'] as $var_name => $line) {
            if (Util::startsWith($line, '[') && Util::endsWith($line, ']')) {
              foreach (explode(',', substr($line, 1, strlen($line) - 2)) as $i_param) {
                $i_name = Util::trimRuleName(trim($i_param));
                $i_comment = "{$i_name}";
                $extract_data['params_data'][$i_name] = $i_comment;
              }
            } else {
              $i_name = (substr($var_name, 1));
              $extract_data['params_data'][$i_name] = $line;
            }
          }
          $rep['function_list'][] = $extract_data;
        }
      }
    }

    return $rep;
  }

  public static function extractDoc($class, $function) {
    $result = false;

    $reflection = new ReflectionMethod($class, $function);
    $comments = $reflection->getDocComment();

    // no comment no show
    if (!$comments) {
      return $result;
    }

    // operate
    $comments = str_replace('/**', '', $comments);
    $comments = str_replace('*/', '', $comments);
    $comments = trim($comments);
    $comments = explode('| ', $comments); // so you can use | in desp NOW.

    if ($comments && (count($comments) >= 4)) {
      $result = [
        'group'            => trim($comments[2]),
        'class'            => $class,
        'function'         => $function,
        'parameter'        => $reflection->getParameters(),
        //'description' => explode("\r\n", trim($comments[4])),
        'description'      => preg_split('/(\s*,*\s*)*\r\n+(\s*,*\s*)*/', trim($comments[4])),
        'parameter_detail' => [],
        'getFileName'      => $reflection->getFileName(),
        'start_line'       => $reflection->getStartLine(),
        'end_line'         => $reflection->getEndLine(),
        'return_type'      => $reflection->getReturnType(),
        'has_return_type'  => $reflection->hasReturnType()
      ];
    }

    // [DOC 2.0]
    $parameter_list = [];
    foreach ($result['parameter'] as $i_param) {
      $parameter_list[$i_param->name] = $i_param->name;
    }

    foreach ($comments as $seq => $block) {
      $i_block_name = strtolower(trim($block));
      $i_param_name = (substr($i_block_name, 1));
      if (Util::startsWith($i_block_name, '$') && isset($parameter_list[$i_param_name]) && isset($comments[$seq + 1])) {
        $result['parameter_detail'][$i_block_name] = strtolower(trim($comments[$seq + 1]));
      }
    }

    return $result;
  }

  private static function _stripFieldKey($line) {
    $line = trim(str_replace('\'', '', $line));

    return $line;
  }

  private static function _stripFieldValue($line, $f_name) {
    $line = (str_replace('|INPUT|', '', $line));
    $line = (str_replace('|REACTION|', '', $line));
    $line = (str_replace('|Fz|', '', $line));
    if (strpos($line, 'ddatetime cdatetime')) {
      // DATETIME
      if (strpos($f_name, 'start') !== false) {
        $line = date('Y-01-01 00:00:00');
      } else if (strpos($f_name, 'end') !== false) {
        $line = date('Y-12-31 23:59:59');
      } else {
        $line = Util::getSystemDate();
      }
    } else {
      // VARCHAR + etc
      $eol = strpos($line, "',");
      if ($eol !== false) {
        $line = trim(str_replace(" '", '', (substr($line, 0, $eol))));
      }
      // Last Array
      $eol = strpos($line, "' //");
      if ($eol !== false) {
        $line = trim(str_replace(" '", '', (substr($line, 0, $eol))));
      }
    }
    return $line;
  }

  private static function _stripCommentKey($line) {
    // if (count($i_vars) == 2) {
    //   $var_name = trim(str_replace('\'', '', $i_vars[0]));
    //   $var_name = (str_replace('<li>', '', $var_name));
    //   $var_val = (str_replace(' ', '', $i_vars[1]));
    //   $var_val = substr($var_val, 0, strpos($var_val, ','));
    //   // revmoved after , == $var_val = (str_replace('</li>', '', $var_val));
    // } else {
    //   // Invalid Line
    //   $var_name = isset($extract_data['params_option']) ? count($extract_data['params_option']) : 0;
    //   $var_val = $i_desc;
    // }

    $line = trim(str_replace('\'', '', $line));
    $line = trim(str_replace('<li>', '', $line));

    return $line;
  }

  private static function _stripCommentValue($line) {
    $line = trim(str_replace(' ', '', $line));
    $line = substr($line, 0, strpos($line, ',')); // removed any after , == $line = (str_replace('</li>', '', $line));

    $line = trim(str_replace('\'', '', $line));

    if ($line == 'true') {
      return true;
    }
    if ($line == 'false') {
      return false;
    }

    // if (Util::startsWith($line, '\'')) {

    // }
    // if (Util::endsWith($line, '\'')) {

    // }

    return $line;
  }

  private static function getMethodByName($fn_name) {
    $get_list = [
      'count',
      'select',
      'get'
    ];

    foreach ($get_list as $i_name) {
      if (Util::startsWith($fn_name, $i_name)) {
        return 'GET';
      }
    }
    return 'POST';
  }

  /**
  | Group | Export
  | Description | โหลดข้อมูล Class เป็น Interface
  | $class_name | ชื่อคลาส
   */
  public static function getInterface($class_name) {
    $return = [];

    if (!$class_name) {
      return false;
    }

    $return['class_name'] = $class_name;
    $return['class_exists'] = class_exists($class_name);

    $class_functions = get_class_methods($class_name);
    $return['class_functions'] = $class_functions;
    if ($class_functions) {
      $ref_class = new ReflectionClass($class_name);
      // $return['class_path'] = $ref_class->getFileName();
      $file_text = file($ref_class->getFileName());
      foreach ($class_functions as $class_function) {
        $extract_data = self::extractInterface($class_name, $class_function);
        if ($extract_data) {
          $extract_data['text'] = [];
          // Extract Conf
          if ($extract_data['start_line'] && $extract_data['end_line']) {
            $i_return_type = '';
            foreach (range($extract_data['start_line'], $extract_data['end_line']) as $i) {
              if ($i == ($extract_data['start_line'])) {
                // NAME
                $extract_data['text'][] = str_replace("\r\n", '', $file_text[$i - 1]); // offset-1
              } else if ($i == ($extract_data['start_line'] + 1)) {
                // RETURN TYPE
                $i_return_type_str = trim(str_replace("\r\n", '', $file_text[$i - 1]));
                if (Util::startsWith($i_return_type_str, '$rep')) {
                  $i_return_type = '    return Amst::repSuccess();';
                } else if (Util::startsWith($i_return_type_str, '$return')) {
                  $i_return_type = '    return [];'; // [TODO] numeric or else
                } else {
                  $i_return_type = '    return TRUE;';
                }
              } else if ($i == $extract_data['end_line']) {
                // RETURN
                $extract_data['text'][] = $i_return_type;
                $extract_data['text'][] = '  }';
              }
            }
          }

          $return['function_list'][] = $extract_data;
        }
      }
    }

    return $return;
  }

  public static function extractInterface($class, $function) {
    $result = false;

    $reflection = new ReflectionMethod($class, $function);
    $full_comments = $reflection->getDocComment();

    // no comment no show
    if (!$full_comments) {
      $full_comments = '';
    }

    // operate
    $comments = str_replace('/**', '', $full_comments);
    $comments = str_replace('*/', '', $comments);
    $comments = trim($comments);
    $comments = explode('| ', $comments); // so you can use | in desp NOW.

    if (true) {
      $result = [
        'group'            => 'INTERFACE',
        'class'            => $class,
        'function'         => $function,
        'parameter'        => $reflection->getParameters(),
        'full_comments'    => $full_comments,
        // 'description'      => preg_split('/(\s*,*\s*)*\r\n+(\s*,*\s*)*/', trim($comments[4])),
        'parameter_detail' => [],
        'getFileName'      => $reflection->getFileName(),
        'start_line'       => $reflection->getStartLine(),
        'end_line'         => $reflection->getEndLine(),
        'return_type'      => $reflection->getReturnType(),
        'has_return_type'  => $reflection->hasReturnType()
      ];
    }

    return $result;
  }
}

<?php
class Debug {
  static $debug_log = [];

  public function __call($function, $args) {
    $start_time = microtime(true);
    call_user_func_array([$this, $function], $args);
    $execution_time = (microtime(true) - $start_time);

    echo 'Execution time : '.$execution_time.' sec<br />';
    die();
  }

  static function saveRecord($class, $function) {
    self::$debug_log[] = [
      'class'    => $class,
      'function' => $function
    ];

    header('Server-Timing: missedCache');
    header('Server-Timing: cpu;dur=2.4');
    header('Server-Timing: cache;desc="Cache Read";dur=23.2');
    header('Server-Timing: 1;dur=53;desc="User::select()", User_register;dur=47.2;desc="User::register()", total;dur=100');
    header('Trailer: Server-Timing');
  }
}

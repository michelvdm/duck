<?php
define('START_TIME', microtime(true));
define('BASE', getcwd());
define('DEBUG', true);

ini_set('display_errors', DEBUG?1:0); 
error_reporting(DEBUG?E_ALL:0);

function debug($val, $label='Debug'){
	echo '<h2>', $label, '</h2><pre class="debug">'; 
	var_dump($val);
	echo '---', PHP_EOL, 'Backtrace: ', PHP_EOL; 
	debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); 
	echo '</pre>', PHP_EOL; 
}

spl_autoload_register(function($class, $data=null){require_once(str_replace('\\', '/', BASE.'/sys/class_'.strtolower($class).'.php'));});

new AppController();

<?php
define('START_TIME', microtime(true));
define('DEBUG', true);
define('BASE', getcwd());

ini_set('display_errors', DEBUG?1:0); 
error_reporting(DEBUG?E_ALL:0);

define('ROOT', dirname($_SERVER['PHP_SELF']));
define('METHOD', strtolower($_SERVER['REQUEST_METHOD']));
define('REQUEST', explode('/', (isset($_GET['url'])?$_GET['url']:'index').'//////////'));

function out($val){echo $val, PHP_EOL;}

function tag($tag, $val=''){echo '<', $tag, '>', $val, '</', explode(' ',$tag)[0], '>', PHP_EOL;}

function debug($val, $label='Debug', $backtrace=false){
	echo '<h2>', $label, '</h2><pre class="debug">'; 
	var_dump($val);
	if($backtrace){
		echo '---', PHP_EOL, 'Backtrace: ', PHP_EOL; 
		debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); 
	}
	echo '</pre>', PHP_EOL; 
}

spl_autoload_register(function($class, $data=null){require_once(str_replace('\\', '/', BASE.'/sys/class_'.strtolower($class).'.php'));});

$_GET=filter_var_array($_GET, FILTER_SANITIZE_STRIPPED);
foreach($_POST as $key=>$value) if($key!='body' && !is_array($value))$_POST[$key]=filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRIPPED);
session_set_cookie_params(60*60*24*365);
session_start();

new AppController();

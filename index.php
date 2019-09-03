<?php
define('START_TIME', microtime(true));
define('BASE', getcwd());
define('ROOT', dirname($_SERVER['PHP_SELF']));
define('DEBUG', true);

ini_set('display_errors', DEBUG?1:0); 
error_reporting(DEBUG?E_ALL:0);

spl_autoload_register(function($class, $data=null){require_once(str_replace('\\', '/', BASE.'/sys/class_'.strtolower($class).'.php'));});

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

function ifSet(&$value, $default){return isset($value)?$value:$default;}

$request=explode('/', ifSet($_GET['url'], '').'//////////');
$app=$request[0]=='admin'?'AdminController':'AppController';
new $app($request);

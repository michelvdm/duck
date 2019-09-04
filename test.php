<?php
define('BASE', getcwd());

function debug($val, $label='Debug'){
	echo '<h2>', $label, '</h2><pre class="debug">'; 
	var_dump($val);
	echo '</pre>', PHP_EOL; 
}

$file=BASE.'/media/home01-fields.png';

// Send file headers
header('Content-type: $type');
header('Content-Disposition: attachment;filename='.$file);
header("Content-Transfer-Encoding: binary");
header('Pragma: no-cache');
header('Expires: 0');
// Send the file contents.
set_time_limit(0);
readfile($file);


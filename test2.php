<?php
define('BASE', getcwd());
define('ROOT', dirname($_SERVER['PHP_SELF']));

function debug($val, $label='Debug'){
	echo '<h2>', $label, '</h2><pre class="debug">'; 
	var_dump($val);
	echo '</pre>', PHP_EOL; 
}

echo '<img src="'.ROOT.'/test.php" alt="">';

<?php defined('BASE') or die("Access denied");

class AppController {

	function __construct(){
		$_GET=filter_var_array($_GET, FILTER_SANITIZE_STRIPPED);
		foreach($_POST as $key=>$value) if($key!='body' && !is_array($value))$_POST[$key]=filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRIPPED);
		$this->config=require(BASE.'/config/app-settings.php');
		$this->model=new AppModel(require(BASE.'/config/app-config.php'));
		$this->method=strtolower($_SERVER['REQUEST_METHOD']);
		$this->request=explode('/', (isset($_GET['url'])?$_GET['url']:'index').'//////////');
		date_default_timezone_set($this->config['timezone']);
		session_set_cookie_params(60*60*24*365);
		session_start();
		$this->tables=$this->model->getTables();
		debug($this);
	}
	
	function setData($arr){foreach($arr as $key=>$value) $this->data[$key]=$value;}
	function setMessage($text, $type='normal'){$_SESSION['message']=['text'=>$text, 'type'=>$type];}
	function goAndSay($link, $text, $type=null){$this->setMessage($text, $type); die(header('Location: '.ROOT.$link));}

/* login/logout */
	function getLogin(){$this->setData(['type'=>'login', 'title'=>'Log in']);}

	function postLogin(){
		extract($_POST);
		$user=$this->model->getUserNamed($userName);
		if($user && password_verify($password, $user['password'])) {
			$_SESSION['userName']=$userName;
			$_SESSION['access']=$user['accesslevel'];
			$this->goAndSay('/', 'You are now logged in as '.$userName);
		} else {
			$this->goAndSay('/login', 'Log in failed.', 'error');
		}
	}

	function getLogout(){
		session_destroy(); 
		session_start(); 
		$this->goAndSay('/', 'You are logged out.');
	}

}

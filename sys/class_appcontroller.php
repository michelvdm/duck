<?php defined('BASE') or die("Access denied");

class AppController {

	function __construct(){
		$this->data=require(BASE.'/config/app-config.php');
		extract($this->data);
		unset($this->data['db']);
		date_default_timezone_set($timezone);
		$this->model=new AppModel($db);
		$this->handleRequest();
	}
	
	function setData($arr){
		foreach($arr as $key=>$value) $this->data[$key]=$value;
	}

	function setMessage($text, $type='normal'){
		$_SESSION['message']=['text'=>$text, 'type'=>$type];
	}

	function goAndSay($link, $text, $type=null){
		$this->setMessage($text, $type); 
		die(header('Location: '.ROOT.$link));
	}

/* login/logout */
	function getLogin(){
		$this->setData(['type'=>'login', 'title'=>'Log in']);
	}

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

	function checkAccess($table){}

	function handleRequest(){
		$top=REQUEST[0];
		if(in_array($top, $this->config['tables'])){
			if($this->checkAccess($top)) out('user has access');
			else out('access denied');
		}
	}

}

<?php defined('BASE') or die("Access denied");

class BaseController {

	function __construct(){
		$this->dbConfig=require(BASE.'/config/app-config.php');
		$_GET=filter_var_array($_GET, FILTER_SANITIZE_STRIPPED);
		foreach($_POST as $key=>$value) if($key!='body' && !is_array($value))$_POST[$key]=filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRIPPED);
		session_set_cookie_params(60*60*24*365);
		session_start();
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

	function handleRequest($request){
		out('handleRequestBASE');
		$method=strtolower($_SERVER['REQUEST_METHOD']);
		if($request[0]=='')$request[0]='index';
		$fn=$method.ucfirst($request[0]);
		if(method_exists( $this, $fn)) {
			call_user_func(array($this, $fn));
			if($method=='get') $this->renderView();
		}	else {
			$this->setData([
				'type'=>'static', 
				'errCode'=>500, 
				'title'=>'Error in '.__CLASS__, 
				'body'=>'<p>Method does not exist: '.$fn.'</p>'
			]);
			if(DEBUG) $this->renderView();
			else die("Invalid request");
		}
	}

}

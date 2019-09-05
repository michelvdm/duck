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
		$user=$this->model->getUserByName($userName);
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

	function checkTableAccess($table){
		$userAccess=isset($_SESSION['access'])?$_SESSION['access']:0;
		$accessNeeded=$this->data['tables'][$table]['access'];
		return $userAccess>=$accessNeeded;
	}

	function crud($table){
		if(!$this->checkTableAccess($table)) die("Access denied.");
		if($this->method=='post'){
			out('no rule for posts');
		} else {
			switch($this->request[1]){
				case '': $page=1; break;
				case 'item': 
					$unid=$this->request[2]; 
					break;
				case 'new': 
					$this->setData([
						'title'=>'New '.substr($table, 0, -1),
						'type'=>'new',
						'action'=>'create',
						'table'=>$table,
						'structure'=>$this->model->getStructure($table)
					]);
					new AppView($this->data);
					break;
				default: $page=$this->request[1]*1;
			}
			out('view: '.$table.' - page: '.$page);			
		}
	}

	function handleRequest(){
		$method=$this->method=strtolower($_SERVER['REQUEST_METHOD']);
		$request=$this->request=explode('/', (isset($_GET['url'])?$_GET['url']:'index').'//////////');
		$top=$request[0];
		if(array_key_exists($top, $this->data['tables'])) die($this->crud($top));
		$fn=$method.ucfirst($top);
		if(method_exists( $this, $fn)) die(call_user_func(array($this, $fn)));
		$this->handleError(500, 'Method does not exist: '.$fn);
	}

}

<?php defined('BASE') or die("Access denied");

class AdminController extends BaseController {

	function __construct($request){
		parent::__construct();
		$this->model=new AdminModel($this->dbConfig);
		$this->data=$this->model->getConfig();
		date_default_timezone_set($this->data['timezone']);
		array_shift($request);
		if($request[0]=='')$request[0]='index';
		$this->request=$request;
		$this->handleRequest($request);
	}
	
	function getIndex(){
		$this->setData([
			'type'=>'static', 
			'title'=>'Index',
			'body'=>''
		]);
	}

	function getSettings(){
		$this->setData([
			'type'=>'view', 
			'title'=>'Settings',
			'linkBase'=>ROOT.'/admin/settings/item/',
			'items'=>$this->model->getView('settings')
		]);
	}

	function getUsers(){
			$this->setData([
				'type'=>'view', 
				'title'=>'Users',
				'linkBase'=>ROOT.'/admin/users/item/',
				'items'=>$this->model->getView('users')
			]);
		}


	function getNav(){
		$tables=$this->model->getTables();
		$nav=[
			['link'=>'/', 'key'=>'', 'label'=>'Back to site'],
			['link'=>'/admin', 'key'=>'index', 'label'=>'Dashboard'],
		];
		foreach ($tables as $item) {
			$nav[]=['link'=>'/admin/'.$item, 'key'=>$item, 'label'=>ucfirst($item)];
		}

		$this->setData(['navItems'=>$nav, 'navActive'=>$this->request[0]]);
	}

	function renderView(){
		$this->getNav();
		new AdminView($this->data);
	}

}

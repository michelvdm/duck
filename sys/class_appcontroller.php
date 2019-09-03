<?php defined('BASE') or die("Access denied");

class AppController extends BaseController {

	function __construct($request){
		parent::__construct();
		$this->model=new AppModel($this->dbConfig);
		$this->data=$this->model->getConfig();
		date_default_timezone_set($this->data['timezone']);
		
		$this->handleRequest($request);
	}
	
	function getIndex(){
		$this->setData([
			'type'=>'static', 
			'title'=>'Index',
			'body'=>''
		]);
	}

	function renderView(){
		new AppView($this->data);
	}

}

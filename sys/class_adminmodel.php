<?php defined('BASE') or die("Access denied");

class AdminModel extends BaseModel {

	function __construct($db){
		parent::__construct($db);
	}

	function getTables(){
		return $this->db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
	}

	function getView($name) {
		$q=$this->db->query("SELECT * FROM $name");
		return $q->fetchAll(PDO::FETCH_ASSOC);
	}

}
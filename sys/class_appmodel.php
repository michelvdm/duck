<?php defined('BASE') or die("Access denied");

class AppModel {

	function __construct($db){
		extract($db);
		try{$db=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);}
		catch(PDOException $e){die('Error: '.$e->getMessage());}
		if(DEBUG)$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->db=$db;
	}
		
}
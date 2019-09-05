<?php defined('BASE') or die("Access denied");

class AppModel {

	function __construct($db){
		extract($db);
		try{$db=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);}
		catch(PDOException $e){die('Error: '.$e->getMessage());}
		if(DEBUG)$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->db=$db;
	}

	function getUserByName($name) {
		$req=$this->db->prepare('SELECT * FROM users WHERE LOWER(name)=:name');
		$req->execute([':name'=>strtolower($name)]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	function getStructure($table){
		return $this->db->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
	}

}
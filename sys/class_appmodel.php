<?php defined('BASE') or die("Access denied");

class AppModel {

	function __construct($db){
		extract($db);
		try{$db=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);}
		catch(PDOException $e){die('Error: '.$e->getMessage());}
		if(DEBUG)$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->db=$db;
	}

	function getTables(){
		return $this->db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
	}

	function getConfig(){
		return $this->db->query("SELECT name, value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	function getUserNamed($name) {
		$req=$this->db->prepare('SELECT * FROM users WHERE LOWER(name)=:name');
		$req->execute([':name'=>strtolower($name)]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

}
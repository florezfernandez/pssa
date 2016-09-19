<?php

class UserDAO {
	private $id;
	private $name;
	private $institution;
	private $country;
	private $mail;
	
	private $userDAO;
	private $conection;

	function UserDAO($i = "", $nam = "", $ins = "", $cou = "", $mai = "") {
		$this -> id = $i;
		$this -> name = $nam;
		$this -> institution = $ins;
		$this -> country = $cou;
		$this -> mail = $mai;
	}

	function select() {
		return "select *
				from user
				where iduser = " . $this -> id;
	}
	
	function selectAll() {
		return "select *
				from user";
	}
	
	function insert() {
		return "insert 
				into user(name,institution,country,mail,date)
				values('" . $this -> name . "','" . $this -> institution . "','" . $this -> country . "','" . $this -> mail . "','" . date("Y-m-d") . "')";
	}
}
?>
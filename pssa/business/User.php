<?php
require_once ("persistence/UserDAO.php");

class User {
	private $id;
	private $name;
	private $institution;
	private $country;
	private $mail;
	private $date;
	
	private $userDAO;
	private $conection;

	function User($i = "", $nam = "", $ins = "", $cou = "", $mai = "", $dat = "") {
		$this -> id = $i;
		$this -> name = $nam;
		$this -> institution = $ins;
		$this -> country = $cou;
		$this -> mail = $mai;
		$this -> date = $dat;
		$this -> userDAO = new UserDAO($this -> id, $this -> name, $this->institution, $this->country, $this->mail, $this->date);
		$this -> conection = new Conection();
	}

	function getId() {
		return $this -> id;
	}

	function getName() {
		return $this -> name;
	}

	function getInstitution() {
		return $this -> institution;
	}
	
	function getCountry() {
		return $this -> country;
	}

	function getMail() {
		return $this -> mail;
	}
	
	function getDate() {
		return $this -> date;
	}
	
	function select() {
		$this -> conection -> run($this -> userDAO -> select());
		$result = $this -> conection -> fetch();
		$this -> name = $result[1];
		$this -> institution = $result[2];
		$this -> country = $result[3];
		$this -> mail = $result[4];
		$this -> date = $result[5];
	}

	function selectAll() {
		$this -> conection -> run($this -> userDAO -> selectAll());
		$results = array();
		$numRows = 0;
		while ($result = $this -> conection -> fetch()) {
			$results[$numRows] = new Gene($result[0], $result[1], $result[2], $result[3], $result[4], $result[5]);
			$numRows++;
		}
		return $results;
	}
	
	function insert() {
		$this -> conection -> run($this -> userDAO -> insert());
	}


}
?>
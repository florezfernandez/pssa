<?php
require_once("persistence/MicroorganismDAO.php");

class Microorganism {
	private $id;
	private $idType;	
	private $name;
	private $description;
	
	private $microorganismDAO;
	private $conection;

	function Microorganism($i = "", $idt = "", $nam = "", $des = "") {
		$this -> id = $i;
		$this -> idType = $idt;
		$this -> name = $nam;
		$this -> description = $des;
		$this -> microorganismDAO = new MicroorganismDAO($this -> id, $this -> idType, $this -> name, $this->description);
		$this -> conection = new Conection();
	}

	function getId() {
		return $this -> id;
	}

	function getIdType() {
		return $this -> idType;
	}
	
	function getName() {
		return $this -> name;
	}

	function getDescription() {
		return $this -> description;
	}
	
	function select() {
		$this -> conection -> run($this -> microorganismDAO -> select());
		$result = $this -> conection -> fetch();
		$this -> idType = $result[1];
		$this -> name = $result[2];
		$this -> description = $result[3];
	}

	function selectByType() {
		$this -> conection -> run($this -> microorganismDAO -> selectByType());
		$results = array();
		$numRows = 0;
		while ($result = $this -> conection -> fetch()) {
			$results[$numRows] = new Microorganism($result[0], $result[1], $result[2], $result[3]);
			$numRows++;
		}
		return $results;
	}
}
?>
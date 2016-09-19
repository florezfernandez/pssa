<?php
require_once ("persistence/TypeDAO.php");

class Type {
	private $id;
	private $name;
	
	private $typeDAO;
	private $conection;

	function Type($i = "", $nam = "") {
		$this -> id = $i;
		$this -> name = $nam;
		$this -> typeDAO = new TypeDAO($this -> id, $this -> name);
		$this -> conection = new Conection();
	}

	function getId() {
		return $this -> id;
	}

	function getName() {
		return $this -> name;
	}

	function select() {
		$this -> conection -> run($this -> typeDAO -> select());
		$result = $this -> conection -> fetch();
		$this -> name = $result[1];
	}
	
	function selectAll() {
		$this -> conection -> run($this -> typeDAO -> selectAll());
		$results = array();
		$numRows = 0;
		while ($result = $this -> conection -> fetch()) {
			$results[$numRows] = new Type($result[0], $result[1]);
			$numRows++;
		}
		return $results;
	}

}
?>
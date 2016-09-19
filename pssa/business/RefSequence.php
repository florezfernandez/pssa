<?php
require_once ("persistence/RefSequenceDAO.php");

class RefSequence {
	private $id;
	private $idGene;	
	private $idCountry;	
	private $name;
	private $year;
	private $genotype;
	private $sequence;
	
	private $refSequenceDAO;
	private $conection;

	function RefSequence($i = "", $idg = "", $idc = "", $nam = "", $yea = "", $gen = "", $seq = "") {
		$this -> id = $i;
		$this -> idGene = $idg;
		$this -> idCountry = $idc;
		$this -> name= $nam;
		$this -> year = $yea;		
		$this -> genotype = $gen;
		$this -> sequence = $seq;
		$this -> refSequenceDAO = new RefSequenceDAO($this -> id, $this -> idGene, $this->idCountry, $this->name, $this->year, $this -> genotype, $this->sequence);
		$this -> conection = new Conection();
	}

	function getId() {
		return $this -> id;
	}

	function getIdGene() {
		return $this -> idGene;
	}
	
	function getIdCountry() {
		return $this -> idCountry;
	}
	
	function getName() {
		return $this -> name;
	}
	
	function getYear() {
		return $this -> year;
	}
	
	function getGenotype() {
		return $this -> genotype;
	}

	function getSequence() {
		return $this -> sequence;
	}
	
	function select() {
		$this -> conection -> run($this -> refSequenceDAO -> select());
		$result = $this -> conection -> fetch();
		$this -> name = $result[3];
		$this -> year = $result[4];
		$this -> genotype = $result[5];
		$this -> sequence = $result[6];
	}

/*	function selectAll() {
		$this -> conection -> run($this -> refSequenceDAO -> selectAll());
		$results = array();
		$numRows = 0;
		while ($result = $this -> conection -> fetch()) {
			$results[$numRows] = new RefSequence($result[0], $result[1], $result[2], $result[3]);
			$numRows++;
		}
		return $results;
	}
*/
	function selectByGene($idGene) {
		$this -> conection -> run($this -> refSequenceDAO -> selectByGene($idGene));
		$results = array();
		$numRows = 0;
		while ($result = $this -> conection -> fetch()) {
			$results[$numRows] = new RefSequence($result[0], $result[1], $result[9]." (".$result[12].")", $result[3], $result[4], $result[5], $result[6]);
			$numRows++;
		}
		return $results;
	}
	
}
?>
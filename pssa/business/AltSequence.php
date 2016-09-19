<?php
require_once ("persistence/AltSequenceDAO.php");

class AltSequence {
	private $id;
	private $idGene;	
	private $idCountry;	
	private $name;
	private $genotype;
	private $year;
	private $sequence;
	
	private $altSequenceDAO;
	private $conection;

	function AltSequence($i = "", $idg = "", $idc = "", $nam = "", $gen = "", $yea = "", $seq = "") {
		$this -> id = $i;
		$this -> idGene = $idg;
		$this -> idCountry = $idc;
		$this -> name= $nam;
		$this -> genotype = $gen;
		$this -> year = $yea;		
		$this -> sequence = $seq;
		$this -> altSequenceDAO = new AltSequenceDAO($this -> id, $this -> idGene, $this->idCountry, $this->name, $this -> genotype, $this->year, $this->sequence);
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
		$this -> conection -> run($this -> altSequenceDAO -> select());
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
		$this -> conection -> run($this -> altSequenceDAO -> selectByGene($idGene));
		$results = array();
		$numRows = 0;
		while ($result = $this -> conection -> fetch()) {
			$results[$numRows] = new AltSequence($result[0], $result[1], $result[9]." (".$result[12].")", $result[3], $result[4], $result[5], $result[6]);
			$numRows++;
		}
		return $results;
	}
	
}
?>
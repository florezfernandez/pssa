<?php
class GeneDAO {
	private $id;
	private $idMicroorganism;	
	private $name;
	private $description;
	
	function GeneDAO($i = "", $idm = "", $nam = "", $des = "") {
		$this -> id = $i;
		$this -> idMicroorganism = $idm;
		$this -> name = $nam;
		$this -> description = $des;
	}
	
	function select() {
		return "select * 
				from gene 
				where idgene = " . $this -> id;
	}

	function selectAll() {
		return "select *  
				from gene"; 
	}		

	function selectByMicroorganism() {
		return "select *
				from gene
				where microorganism_idmicroorganism = " . $this -> idMicroorganism;
	}
	
}
?>
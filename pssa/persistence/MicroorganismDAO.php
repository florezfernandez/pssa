<?php
class MicroorganismDAO {
	private $id;
	private $idType;	
	private $name;
	private $description;
	
	function MicroorganismDAO($i = "", $idt = "", $nam = "", $des = "") {
		$this -> id = $i;
		$this -> idType = $idt;
		$this -> name = $nam;
		$this -> description = $des;
	}
	
	function select() {
		return "select * 
				from microorganism 
				where idmicroorganism = " . $this -> id;
	}

	function selectByType() {
		return "select *  
				from microorganism
				where 	type_idtype = '" . $this->idType . "'"; 
	}		
}
?>
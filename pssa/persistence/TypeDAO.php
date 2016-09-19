<?php
class TypeDAO {
	private $id;
	private $name;
	
	function TypeDAO($i = "", $nam = "") {
		$this -> id = $i;
		$this -> name = $nam;
	}
	
	function select() {
		return "select *  
				from type
				where idType = " . $this->id; 
	}		
	
	function selectAll() {
		return "select *  
				from type"; 
	}		
}
?>
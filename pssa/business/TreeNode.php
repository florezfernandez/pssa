<?php

class TreeNode {
	private $name;
	private $changes;
	private $nodes = array();	
	
	function TreeNode($nam = "", $cha = "") {
		$this -> name = $nam;
		$this -> changes = $cha;
	}
	
	
	function getName() {
		return $this -> name;
	}

	function getChanges() {
		return $this -> changes;
	}
	
	function getNodes() {
		return $this -> nodes;
	}
	
	function addNode($node) {
		return $this -> nodes[count($this -> nodes)]=$node;
	}

	function findNodeToAdd($value) {
		for($i=0; $i<count($this->nodes); $i++){
			$dif=$this->nodes[$i]->getChanges()-$value;
			if($dif>5){
				return $this->nodes[$i];
			}else{
				return $this->nodes[$i]->findNodeToAdd($value);
			}
		}
	}
	
	function toStream(){
		if(count($this->nodes)==0){
			return "{\"name\": \"".$this->name."\"}";
		}else{
			$stream = "{\n\"name\": \"".$this->name."\",\n";
			$stream.="\"children\": [ \n";
			for($i=0; $i<count($this->nodes); $i++){
				$stream.=$this->nodes[$i]->toStream();
				if($i<count($this->nodes)-1){
					$stream.=",\n";
				}
			}
			$stream.="\n]\n}";
			return $stream;
		}
		
	}
}
?>
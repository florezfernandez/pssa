<?php
class AltSequenceDAO {
	private $id;
	private $idGene;	
	private $idCountry;	
	private $name;
	private $year;
	private $genotype;
	private $sequence;
		
	function AltSequenceDAO($i = "", $idg = "", $idc = "", $nam = "", $yea = "", $gen = "", $seq = "") {
		$this -> id = $i;
		$this -> idGene = $idg;
		$this -> idCountry = $idc;
		$this -> name= $nam;
		$this -> year = $yea;		
		$this -> genotype = $gen;
		$this -> sequence = $seq;
			}
	
	function select() {
		return "select * 
				from alt_sequence 
				where idalt_sequence = " . $this -> id;
	}

	function selectAll() {
		return "select *  
				from alt_sequence"; 
	}		

	function selectByGene($idGene) {
		return "select ase.*, cou.*, con.*
				from alt_sequence ase, country cou, continent con
				where ase.gene_idgene = " . $idGene . " and cou.idcountry = ase.country_idcountry and con.idcontinent = cou.continent_idcontinent
				order by ase.idalt_sequence";
	}
	
}
?>
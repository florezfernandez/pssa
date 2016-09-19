<?php
class RefSequenceDAO {
	private $id;
	private $idGene;	
	private $idCountry;	
	private $name;
	private $year;
	private $genotype;
	private $sequence;
		
	function RefSequenceDAO($i = "", $idg = "", $idc = "", $nam = "", $yea = "", $gen = "", $seq = "") {
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
				from ref_sequence 
				where idref_sequence = " . $this -> id;
	}

	function selectAll() {
		return "select *  
				from ref_sequence"; 
	}		

	function selectByGene($idGene) {
		return "select rse.*, cou.*, con.*
				from ref_sequence rse, country cou, continent con
				where rse.gene_idgene = " . $idGene . " and cou.idcountry = rse.country_idcountry and con.idcontinent = cou.continent_idcontinent
				order by rse.idref_sequence";
	}
	
}
?>
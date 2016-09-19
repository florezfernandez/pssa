<?php
require_once("business/Gene.php");
require_once("business/RefSequence.php");
require_once("business/AltSequence.php");
require_once("persistence/Conection.php");
$idGene = $_GET ['idGene'];
$gene = new Gene($idGene);
$gene->select();
$idSequence=$_GET['idSequence'];
if($_GET['type']=="ref"){
	$sequence = new RefSequence($idSequence);	
}else{
	$sequence = new AltSequence($idSequence);
}

$sequence->select();
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title"><?php echo $gene->getName()." - ".$sequence->getName(); ?></h4>
</div>
<div class="modal-body">
	<textarea style="resize:none" class="form-control" rows="15"><?php echo $sequence->getSequence(); ?></textarea>
</div>
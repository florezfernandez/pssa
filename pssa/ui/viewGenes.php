<?php 
$idMicroorganism=$_GET['idMicroorganism'];
$microorganism = new Microorganism($idMicroorganism);
$microorganism -> select();
$type = new Type($microorganism->getIdType());
$type->select();

?>

<div class="container">
	<div class="row">
		<div class="col-md-12 text-left">
			<h3><?php echo $type->getName() . " " . $microorganism->getName() ?></h3>
			<p><?php echo $microorganism->getDescription() ?></p>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-<?php echo $color?>">
				<div class="panel-heading">
					<h3 class="panel-title">Genes of <?php echo $microorganism->getName() ?></h3>
				</div>
				<div class="panel-body">					
					<table class="table table-striped table-hover">
						<tr>
							<td><strong>Name</strong></td>
							<td><strong>Description</strong></td>
							<td></td>
							</tr>
							<?php 
								$gene = new Gene("",$idMicroorganism);
								$genes = $gene->selectByMicroorganism();
								for ($i = 0; $i < count($genes); $i++) {
									echo "<tr>";
									echo "<td>", $genes[$i]->getName(), "</td>";
									echo "<td>", $genes[$i]->getDescription(), "</td>";
									echo "<td class='text-center' nowrap>
									<a href='index.php?pid=".base64_encode("ui/viewRefSequences.php")."&idGene=".$genes[$i]->getId()."&nameMicroorganism=".$microorganism->getName()."&nameType=".$type->getName()."' ><img src='img/view1.png' width='30' data-toggle='tooltip' data-placement='left' data-original-title='View Reference Sequences'/></a>						
									<a href='index.php?pid=".base64_encode("ui/viewAltSequences.php")."&idGene=".$genes[$i]->getId()."&nameMicroorganism=".$microorganism->getName()."&nameType=".$type->getName()."' ><img src='img/view2.png' width='30' data-toggle='tooltip' data-placement='left' data-original-title='View Alternative Sequences'/></a>						
									</td>";
									echo "</tr>\n";
								}
								echo "<tr><td colspan='10'><strong>" . count($genes) . " registries<strong></td></tr>";											
							?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

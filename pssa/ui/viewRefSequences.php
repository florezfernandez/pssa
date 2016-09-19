<?php 
$idGene=$_GET['idGene'];
$nameMicroorganism=$_GET['nameMicroorganism'];
$nameType=$_GET['nameType'];
$gene = new Gene($idGene);
$gene->select();
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-<?php echo $color?>">
				<div class="panel-heading">
					<h3 class="panel-title">Reference Sequences</h3>
				</div>
				<div class="panel-body">
					<form id="form" role="form" method="post" action="<?php echo "index.php?pid=".base64_encode("ui/selectPatients.php")."&idGene=".$idGene."&nameMicroorganism=".$nameMicroorganism."&nameType=".$nameType ?>" >
						<div class='alert alert-success' role='alert'>
							<strong>Selected <?php echo $nameType ?>:</strong> <?php echo $nameMicroorganism ?><br>
							<strong>Selected Gene:</strong> <?php echo $gene->getName() ?>.
						</div>
						<table id="sequencesTable" class="table table-striped table-hover">
							<tr>
								<td><strong>Name</strong></td>
								<td><strong>Genotype</strong></td>
								<td><strong>Year</strong></td>
								<td><strong>Country</strong></td>
								<td><strong>Sequence</strong></td>
								<td></td>
								</tr>
								<?php 
									$refSequence = new RefSequence();
									$refSequences = $refSequence->selectByGene($idGene);
									for ($i = 0; $i < count($refSequences); $i++) {
										echo "<tr>";
										echo "<td>", $refSequences[$i]->getName(), "</td>";
										echo "<td>", $refSequences[$i]->getGenotype(), "</td>";
										echo "<td>", $refSequences[$i]->getYear(), "</td>";
										echo "<td>", $refSequences[$i]->getIdCountry(), "</td>";
										echo "<td><textarea rows='4' cols='50' readonly>", $refSequences[$i]->getSequence(), "</textarea></td>";										
										echo "<td class='text-center' nowrap>
											<a href='index.php?pid=".base64_encode("ui/selectPatientsRefAnalysis.php")."&idRefSequence=".$refSequences[$i]->getId()."&idGene=".$idGene."&nameMicroorganism=".$nameMicroorganism."&nameType=".$nameType."' ><img src='img/view1.png' width='30' data-toggle='tooltip' data-placement='left' data-original-title='Select Patients for Analysis'/></a>						
										</td>";
										echo "</tr>\n";
									}
									echo "<tr><td colspan='10'><strong>" . count($refSequences) . " registries<strong></td></tr>";											
								?>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

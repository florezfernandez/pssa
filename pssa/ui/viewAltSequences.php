<?php 
$idGene=$_GET['idGene'];
$nameMicroorganism=$_GET['nameMicroorganism'];
$nameType=$_GET['nameType'];
$gene = new Gene($idGene);
$gene->select();
$refSequence = new RefSequence();
$refSequences = $refSequence->selectByGene($idGene);

?>
<script type="text/javascript">
$('body').on('show.bs.modal', '.modal', function (e) {
	var link = $(e.relatedTarget);
	$(this).find(".modal-content").load(link.attr("href"));
});
$('body').on('hidden.bs.modal', '.modal', function () {
	document.getElementById("modalContent").innerHTML="";
});
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="modalContent">
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-<?php echo $color?>">
				<div class="panel-heading">
					<h3 class="panel-title">Alternative Sequences</h3>
				</div>
				<div class="panel-body">
					<form id="form" role="form" method="post" action="<?php echo "index.php?pid=".base64_encode("ui/selectPatientsAltAnalysis.php")."&idGene=".$idGene."&nameMicroorganism=".$nameMicroorganism."&nameType=".$nameType ?>" >
						<div class='alert alert-success' role='alert'>
							<strong>Selected <?php echo $nameType ?>:</strong> <?php echo $nameMicroorganism ?>.<br>
							<strong>Selected Gene:</strong> <?php echo $gene->getName() ?>.<br>
							<strong>Reference Sequences:</strong> 
							<?php 
								for($i=0; $i<count($refSequences); $i++){
									echo $refSequences[$i]->getName();
									echo "<a href='sequenceViewer.php?idGene=".$idGene."&idSequence=".$refSequences[$i]->getId()."&type=alt' data-toggle='modal' data-target='#myModal' ><img src='img/view3.png' width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Sequence'/></a> ";
								}							
							?> 
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
									$altSequence = new AltSequence();
									$altSequences = $altSequence->selectByGene($idGene);
									for ($i = 0; $i < count($altSequences); $i++) {
										echo "<tr>";
										echo "<td>", $altSequences[$i]->getName(), "</td>";
										echo "<td>", $altSequences[$i]->getYear(), "</td>";
										echo "<td>", $altSequences[$i]->getGenotype(), "</td>";
										echo "<td>", $altSequences[$i]->getIdCountry(), "</td>";
										echo "<td><textarea rows='4' cols='50' readonly>", $altSequences[$i]->getSequence(), "</textarea></td>";										
										echo "<td class='text-center' nowrap>
																
										</td>";
										echo "</tr>\n";
									}
									echo "<tr><td colspan='10'><strong>" . count($altSequences) . " registries<strong></td></tr>";											
								?>
						</table>
  						<button type="submit" class="btn btn-<?php echo $color?>" id="submit">Continue</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

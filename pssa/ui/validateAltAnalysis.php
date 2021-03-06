<?php 
require("functions/fileHelper.php");
require("functions/arrayHelper.php");
$idGene=$_GET['idGene'];
$nameMicroorganism=$_GET['nameMicroorganism'];
$nameType=$_GET['nameType'];
$gene = new Gene($idGene);
$gene->select();
$patientsFiles=$_FILES['patientsFiles'];
$refSequence = new RefSequence();
$refSequences = $refSequence->selectByGene($idGene);
$altSequence = new AltSequence();
$altSequences = $altSequence->selectByGene($idGene);
$lenErrors=false;
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
					<h3 class="panel-title">Validate Information for Analysis</h3>
				</div>
				<div class="panel-body">
					<form id="form" role="form" method="post" action="<?php echo "index.php?pid=".base64_encode("ui/resultsAltAnalysis.php")."&idGene=".$idGene."&nameMicroorganism=".$nameMicroorganism."&nameType=".$nameType ?>" >
						<div class='alert alert-success' role='alert'>
							<strong>Selected <?php echo $nameType ?>:</strong> <?php echo $nameMicroorganism ?><br>
							<strong>Selected Gene:</strong> <?php echo $gene->getName() ?>.<br>
							<strong>Reference Sequences:</strong> 
							<?php 
								for($i=0; $i<count($refSequences); $i++){
									echo $refSequences[$i]->getName();
									echo "<a href='sequenceViewer.php?idGene=".$idGene."&idSequence=".$refSequences[$i]->getId()."&type=alt' data-toggle='modal' data-target='#myModal' ><img src='img/view3.png' width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Sequence'/></a> ";
								}							
							?><br> 
							<strong>Alternative Sequences:</strong> 
							<?php 
								for($i=0; $i<count($altSequences); $i++){
									echo $altSequences[$i]->getName();
									echo "<a href='sequenceViewer.php?idGene=".$idGene."&idSequence=".$altSequences[$i]->getId()."&type=alt' data-toggle='modal' data-target='#myModal' ><img src='img/view3.png' width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Sequence'/></a> ";
								}							
							?> 
						</div>
						<div class="panel panel-<?php echo $color?>">
							<div class="panel-heading">
								<h3 class="panel-title">Selected Patients</h3>
							</div>
							<div class="panel-body">
								<table class="table table-striped table-hover">
									<tr>
										<td></td>
										<td><strong>File Name</strong></td>
										<td><strong>Sequences</strong></td>
										<td><strong>Sequence Details</strong></td>
									</tr>
									<?php 
										for($i=0; $i<count($patientsFiles['name']); $i++){
											$patientsContent=getFileContent($patientsFiles['tmp_name'][$i]);
											$patientData = split(">", $patientsContent);
											echo "<input type='hidden' name='patientsFilesName[]' value='".$patientsFiles['name'][$i]."'>";
											echo "<input type='hidden' name='patientsContents[]' value='".$patientsContent."'>";												
											$num=$i+1;
											echo "<tr>";
											echo "<td>".$num."</td>";
											echo "<td>".$patientsFiles['name'][$i]."</td>";
											echo "<td>".(count($patientData)-1)."</td>";
											echo "<td><table  class='table table-striped table-hover'>";
											echo "<tr><td></td><td><strong>Name</strong></td><td><strong>Nucleotides</strong></td><td><strong>Sequence</strong></td><td><strong>Observations</strong></td></tr>";
											for($j=1; $j<count($patientData); $j++){
												$cloneData = split("\n", $patientData[$j]);
												$sequence=strtoupper(mergeArrayInfo($cloneData,1));
												echo "<tr>";
												echo "<td>".$j."</td>";
												echo "<td>".$cloneData[0]."</td>";
												echo "<td>".strlen($sequence)."</td>";
												echo "<td><textarea rows='4' cols='50' readonly>".$sequence."</textarea></td>";
												$sequenceValidation=$gene->validatePatientSequence($altSequences[0]->getSequence(), $sequence);
												if($sequenceValidation==0){
													echo "<td><div class='alert alert-success' role='alert'>Correct sequence</div></td>";
												}else if($sequenceValidation==1){
													echo "<td><div class='alert alert-danger' role='alert'>Incorrect sequence length</div></td>";
													$lenErrors=true;														
												}else if($sequenceValidation==2){
													echo "<td><div class='alert alert-danger' role='alert'>Incorrect sequence. Characters accepted: A, C, G, T, and -</div></td>";
													$lenErrors=true;														
												}else if($sequenceValidation==3){
													echo "<td><div class='alert alert-danger' role='alert'>Incorrect sequence. Character '-' is just accepted before the first letter or after the last letter</div></td>";
													$lenErrors=true;														
												}
												echo "</tr>";
											}
											echo "</table></td>";												
											echo "</tr>";
										}
									?> 
										
								</table>											
							</div>
						</div>											
						<?php 
							if(!$lenErrors){
								echo "<button type='submit' class='btn btn-".$color."' id='submit'>Calculate Mutations</button>";		
							}else{
								echo "<div class='alert alert-danger' role='alert'>Please, check errors and try again</div>";
							}
						?>
						
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

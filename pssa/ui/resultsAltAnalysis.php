<?php 
include("functions/fileHelper.php");
include("functions/mutationHelper.php");
include("functions/arrayHelper.php");
include("pdf/class.ezpdf.php");

$startTime = microtime_float();

removeOldFiles("graphFiles/","json");
removeOldFiles("reportFiles/","pdf");

$idGene=$_GET['idGene'];
$nameMicroorganism=$_GET['nameMicroorganism'];
$nameType=$_GET['nameType'];
$gene = new Gene($idGene);
$gene->select();
$refSequence = new RefSequence();
$refSequences = $refSequence->selectByGene($idGene);
$altSequence = new AltSequence();
$altSequences = $altSequence->selectByGene($idGene);

$patientsFilesName=$_POST['patientsFilesName'];
$patientsContents=$_POST['patientsContents'];

$baseFileName=date("YmdHis");
$treeFile=$baseFileName.".json";
$reportFile=$baseFileName.".pdf";
$treeFilePath="treeFiles/".$treeFile;
$reportFilePath="reportFiles/".$reportFile;
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
					<h3 class="panel-title">Results</h3>
				</div>
				<div class="panel-body">
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
						<br>
						<strong>Selected Patients:</strong> 
						<?php 
							for($i=0; $i<count($patientsFilesName); $i++){
								echo $patientsFilesName[$i].". ";
							}
						?>
						<br>
						View Force-Direct Graph: <a href='<?php echo "index.php?pid=".base64_encode("ui/treeViewer.php")."&treeFile=".$treeFile; ?>' target="_blank"><img src="img/atom.ico" width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Graph'/></a><br>
						Results report: <a href='<?php echo "index.php?pid=".base64_encode("ui/sendMail.php")."&reportFile=".$reportFile; ?>' target="_blank"><img src="img/pdf.ico" width='20' data-toggle='tooltip' data-placement='top' data-original-title='Get report in a pdf file'/></a>
					</div>
					<div id="timeAlert" class="hide"></div>
				<?php 			
						$seqRefChanges4Tree = array();
						$seqAltChanges4Tree = array();
						$seqPatChanges4Tree = array();
						for($i=0; $i<count($patientsFilesName); $i++){
							echo "
							<div class='panel panel-".$color."'>
								<div class='panel-heading'>
									<h3 class='panel-title'>Patient: ".$patientsFilesName[$i]."</h3>
								</div>
							<div class='panel-body'>";
							echo "<pre>";
							echo "Main Reference Sequence: <strong>".$refSequences[0]->getName()." (".$refSequences[0]->getGenotype().")</strong> <br>".$refSequences[0]->getSequence()."<br><br>";
							
							for($j=1; $j<count($refSequences); $j++){
								list($seqChanged, $changes) = calculateNucleotideChanges($refSequences[0]->getSequence(), $refSequences[$j]->getSequence());
								$seqRefChanges4Tree[$j-1][0]=$refSequences[$j]->getName();
								$seqRefChanges4Tree[$j-1][1]=$changes;
								echo "Reference Sequence: <strong>".$refSequences[$j]->getName()." (".$refSequences[$j]->getGenotype().")</strong> (Changes:".count($changes).")<br>".$seqChanged."<br>";
								for($k=0; $k<count($changes); $k++){
									echo $changes[$k][0].":".$changes[$k][1]." => ".$changes[$k][2]."; ";
								}
								echo "<br><br>";
							}
							echo "<hr>";

							for($j=0; $j<count($altSequences); $j++){
								list($seqChanged, $changes) = calculateNucleotideChanges($refSequences[0]->getSequence(), $altSequences[$j]->getSequence());
								$seqAltChanges4Tree[$j][0]=$altSequences[$j]->getName();
								$seqAltChanges4Tree[$j][1]=$changes;
								echo "Altertative Sequence: <strong>".$altSequences[$j]->getName()." (".$altSequences[$j]->getGenotype().")</strong> (Changes:".count($changes).")<br>".$seqChanged."<br>";
								for($k=0; $k<count($changes); $k++){
									echo $changes[$k][0].":".$changes[$k][1]." => ".$changes[$k][2]."; ";
								}
								echo "<br><br>";
							}
							echo "<hr>";
							
							$patientData = split(">", $patientsContents[$i]);
							for($j=0; $j<count($patientData); $j++){
								$patientDataArray = split("\n", $patientData[$j]);
								$patienSequence=strtoupper(mergeArrayInfo($patientDataArray,1));
								list($seqChanged, $changes) = calculateNucleotideChanges($refSequences[0]->getSequence(), $patienSequence);
								$seqPatChanges4Tree[$j][0]=trim($patientDataArray[0]);
								$seqPatChanges4Tree[$j][1]=$changes;
								echo "Patient Sequence: <strong>".trim($patientDataArray[0])."</strong> (Changes:".count($changes).")<br>".$seqChanged."<br>";
								for($k=0; $k<count($changes); $k++){
									echo $changes[$k][0].":".$changes[$k][1]." => ".$changes[$k][2]."; ";
								}
								echo "<br><br>";								
							}	
							echo "</pre>";
							echo "</div></div>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$fileStream=treeStream($refSequences[0]->getName(),$seqRefChanges4Tree,$seqAltChanges4Tree,$seqPatChanges4Tree);
setFileContent($treeFilePath, $fileStream);

//createPDF($reportFilePath, $gene->getName(), $patientsFilesName, $reportSummary, $reportNucleotide, $reportAminoacid);

$endTime = microtime_float();
$time = round($endTime - $startTime,3);	
?>
<script>
var time = <?php echo json_encode($time); ?>;
$('#timeAlert').removeClass().addClass('alert alert-warning');			
$('#timeAlert').text("Analysis Time: "+time+" seconds");
</script>
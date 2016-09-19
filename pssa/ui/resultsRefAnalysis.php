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
$idRefSequence=$_GET['idRefSequence'];
$refSequence = new RefSequence($idRefSequence);
$refSequence->select();

$patientsFilesName=$_POST['patientsFilesName'];
$patientsContents=$_POST['patientsContents'];

$baseFileName=date("YmdHis");
$graphFile=$baseFileName.".json";
$reportFile=$baseFileName.".pdf";
$graphFilePath="graphFiles/".$graphFile;
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
						<strong>Selected Sequence:</strong> <?php echo $refSequence->getName() ?>. <a href='<?php echo "sequenceViewer.php?idGene=".$idGene."&idSequence=".$idRefSequence."&type=ref" ?>' data-toggle='modal' data-target='#myModal' ><img src='img/view3.png' width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Sequence'/></a><br>
						<strong>Selected Patients:</strong> 
						<?php 
							for($i=0; $i<count($patientsFilesName); $i++){
								echo $patientsFilesName[$i].". ";
							}
						?>
						<br>
						View Force-Direct Graph: <a href='<?php echo "index.php?pid=".base64_encode("ui/graphViewer.php")."&graphFile=".$graphFile; ?>' target="_blank"><img src="img/atom.ico" width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Graph'/></a><br>
						Results report: <a href='<?php echo "index.php?pid=".base64_encode("ui/sendMail.php")."&reportFile=".$reportFile; ?>' target="_blank"><img src="img/pdf.ico" width='20' data-toggle='tooltip' data-placement='top' data-original-title='Get report in a pdf file'/></a>
					</div>
					<div id="timeAlert" class="hide"></div>
				<?php 						
						for($i=0; $i<count($patientsFilesName); $i++){
							echo "
							<div class='panel panel-".$color."'>
								<div class='panel-heading'>
									<h3 class='panel-title'>Patient: ".$patientsFilesName[$i]."</h3>
								</div>
							<div class='panel-body'>";
							$patientData = split(">", $patientsContents[$i]);
							for($j=1; $j<count($patientData); $j++){
								$patientDataArray = split("\n", $patientData[$j]);
								echo "<h4 class='text-center'>".trim($patientDataArray[0])."</h4>";								
								$secuenceChanges[$i][$j][0]=$patientsFilesName[$i].". ".trim($patientDataArray[0]);
								list($changes, $changesCount, $equChanges, $equChangesConf)=calculateTripletsChanges($refSequence->getSequence(), strtoupper(mergeArrayInfo($patientDataArray,1)));
								echo "<table class='table table-striped table-hover'>";
								echo "<tr><td width='50%'><strong>Nucleotides</strong></td><td width='50%'><strong>Amino Acid</strong></td></tr>";								
								echo "<tr><td><pre>";
								$reportSummary[$i][$j][0]=$patientDataArray[0];
								$reportSummary[$i][$j][1]=0;								
								$reportSummary[$i][$j][2]=0;															
								for($k=0; $k<count($changes); $k++){
									if($changesCount[$k]!=0){
										echo $changes[$k]." = ".$changesCount[$k]."<br>";
										$reportSummary[$i][$j][1]+=$changesCount[$k];
										$reportNucleotide[$i][$j][$k]=$changes[$k]." = ".$changesCount[$k];										
									}else{
										$reportNucleotide[$i][$j][$k]="";										
									}
								}
								echo "</pre></td><td><pre>";
								$secuenceChangesCount=0;
								$secuenceChangesDescription="";
								for($k=0; $k<count($equChanges); $k++){
									$reportAminoacid[$i][$j][$k][0]=$equChanges[$k];
									$reportAminoacid[$i][$j][$k][1]=$equChangesConf[$k];
									if($equChangesConf[$k]=="yes"){
										echo "<div class='bg-danger'>".$equChanges[$k]."</div>";
										$secuenceChangesCount++;
										$secuenceChangesDescription.=$equChanges[$k].". ";		
									}else{
										echo $equChanges[$k]."<br>";
									}
								}
								$secuenceChanges[$i][$j][1]=$secuenceChangesCount;
								$secuenceChanges[$i][$j][2]=$secuenceChangesDescription;								
								$reportSummary[$i][$j][2]=$secuenceChangesCount;
								echo "</pre></td></tr></table>";
							}
							echo "</div></div>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$fileStream=graphStream($gene->getName(), $secuenceChanges);
setFileContent($graphFilePath, $fileStream);
createPDF($reportFilePath, $gene->getName(), $patientsFilesName, $reportSummary, $reportNucleotide, $reportAminoacid);

$endTime = microtime_float();
$time = round($endTime - $startTime,3);	
?>
<script>
var time = <?php echo json_encode($time); ?>;
$('#timeAlert').removeClass().addClass('alert alert-warning');			
$('#timeAlert').text("Analysis Time: "+time+" seconds");
</script>
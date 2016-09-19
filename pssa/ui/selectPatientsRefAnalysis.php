<?php 
$idGene=$_GET['idGene'];
$nameMicroorganism=$_GET['nameMicroorganism'];
$nameType=$_GET['nameType'];
$gene = new Gene($idGene);
$gene->select();
$idRefSequence=$_GET['idRefSequence'];
$refSequence = new RefSequence($idRefSequence);
$refSequence->select();

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
					<h3 class="panel-title">Select Patients</h3>
				</div>
				<div class="panel-body">
					<form id="form" role="form" method="post" action="<?php echo "index.php?pid=".base64_encode("ui/validateRefAnalysis.php")."&idRefSequence=".$idRefSequence."&idGene=".$idGene."&nameMicroorganism=".$nameMicroorganism."&nameType=".$nameType ?>" enctype="multipart/form-data">
						<div class='alert alert-success' role='alert'>
							<strong>Selected <?php echo $nameType ?>:</strong> <?php echo $nameMicroorganism ?><br>
							<strong>Selected Gene:</strong> <?php echo $gene->getName() ?>.<br>
							<strong>Selected Sequence:</strong> <?php echo $refSequence->getName() ?>. <a href='<?php echo "sequenceViewer.php?idGene=".$idGene."&idSequence=".$idRefSequence."&type=ref" ?>' data-toggle='modal' data-target='#myModal' ><img src='img/view3.png' width='20' data-toggle='tooltip' data-placement='top' data-original-title='View Sequence'/></a><br>
						</div>
						<div class='alert alert-warning' role='alert'>
							The sequences of patients must be included by plain text files that conforms to FASTA format. Each file represents one patient. One patient can have several sequences. Each sequence must include its name. <a href="files/<?php echo $nameMicroorganism."/".$gene->getName() ?>.txt" target="_blank"><img src="img/file.png" width='20' data-toggle='tooltip' data-placement='left' data-original-title='Example Patient File'></a><br>We provide you a test dataset, for using SSA. <a href="files/<?php echo $nameMicroorganism."/".$gene->getName() ?>.zip" ><img src="img/zip.png" width='20' data-toggle='tooltip' data-placement='left' data-original-title='Dataset'></a>.
						</div>
						<div class='alert alert-warning' role='alert'>
							SSA do not store your patients information.
						</div>
						<div class="form-group">
    						<label for="exampleInputFile">File input</label>
    						<input type="file" name="patientsFiles[]" multiple="multiple">
    						<p class="help-block">Select the patients' files.</p>
  						</div>
  						<button type="submit" class="btn btn-<?php echo $color?>" id="submit">Continue</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
function getFileContent($filePath){
	$file = fopen($filePath, "r") or die("Unable to open file!");
	$fileData=fread($file,filesize($filePath));
	fclose($file);
	return $fileData; 
}

function setFileContent($filePath, $fileStream){
	$file = fopen($filePath,"w");
	fwrite($file,$fileStream);
	fclose($file);	
}

function removeOldFiles($path,$ext){
	$year=date("Y")+0;
	$month=date("m")+0;
	$day=date("d")-15;
	$deleteDate=$year*365+$month*30+$day;
	
	$dir= opendir($path);
	$items = array();
	$numItems=0;
	while($content = readdir($dir)){
		$fileExt=substr($content,strpos($content,".")+1);
		if($fileExt==$ext){
			$fileYear=substr($content,0,4)+0;
			$fileMonth=substr($content,4,2)+0;
			$fileDay=substr($content,6,2)+0;
			$fileDate=$fileYear*365+$fileMonth*30+$day;
			if($fileDate<$deleteDate){
				unlink($path.$content);
			}
		}
	}
}

function createPDF($reportFilePath, $selectedReferenceName, $patientsFilesName, $reportSummary, $reportNucleotide, $reportAminoacid){
	$pdf = new Cezpdf('LETTER');
	$pdf -> selectFont('pdf/fonts/Helvetica.afm');
	$pdf -> ezSetCmMargins(3, 3, 3, 3);
	$pdf -> ezStartPageNumbers(500,50,12,'','',1);
	$size = 14;
	$pdf -> ezText("<b>SEQUENCE SIGNATURE ANALYSIS</b>", $size, array('justification' => 'center'));
	$pdf -> ezText("ANALYSIS REPORT", $size, array('justification' => 'center'));
	$pdf -> addJpegFromFile("img/logo.jpg", 80, 650, 80, 80);
	$pdf -> ezText("Date of Analysis: ".date("Y-m-d"), $size, array('justification' => 'center'));
	$pdf -> ezText("Gene: ".$selectedReferenceName, $size, array('justification' => 'center'));
	$size = 10;
	
	$summaryTableOptions = array(
			'showLines' => 1,
			'shaded' => 0,
			'xOrientation' => 'center',
			'fontSize' => $size,
			'cols' => array(
					'patienFileName' => array('justification' => 'center', 'width' => 100),
					'clones' => array('justification' => 'center', 'width' => 70),
					'nucleotideChanges' => array('justification' => 'center', 'width' => 70),
					'aminoacidChanges' => array('justification' => 'center', 'width' => 70)));
	$summaryTableTitles = array(
			'patienFileName' => '<b>Patient File Name</b>',
			'sequences' => '<b>Sequences</b>',
			'nucleotideChanges' => '<b>Nucleotide Changes</b>',
			'aminoacidChanges' => '<b>Amino Acid Changes</b>');
	$summaryTableData = array();
	$pdf -> ezText("", $size, array('justification' => 'left'));
	$pdf -> ezText("<b>SUMMARY</b>", $size, array('justification' => 'left'));
	$pdf -> ezText("", $size, array('justification' => 'left'));
	$row=0;
	for($i=0; $i<count($patientsFilesName); $i++){
		//$pdf -> ezText("Patient File: ".$patientsFilesName[$i], $size, array('justification' => 'left'));
		$summaryTableData[$row]["patienFileName"]=$patientsFilesName[$i];
		
		$countTotal=0;
		$group=$i+2;
		for($j=1; $j<=count($reportSummary[$i]); $j++){
			if($j!=1){
				$summaryTableData[$row]["patienFileName"]="";
			}
			$summaryTableData[$row]["sequences"]=$reportSummary[$i][$j][0];
			$summaryTableData[$row]["nucleotideChanges"]=$reportSummary[$i][$j][1];
			$summaryTableData[$row]["aminoacidChanges"]=$reportSummary[$i][$j][2];
			$row++;
		}
	}
	$pdf -> ezTable($summaryTableData, $summaryTableTitles, '', $summaryTableOptions);
	
	for($i=0; $i<count($patientsFilesName); $i++){
		$pdf->ezNewPage();  
		$pdf -> ezText("<b>DETAILED REPORT</b>", $size, array('justification' => 'center'));
		$pdf -> ezText("<b>PATIENT: ".$patientsFilesName[$i]."</b>", $size, array('justification' => 'center'));
		for($j=1; $j<=count($reportNucleotide[$i]); $j++){
			$pdf -> ezText("", $size, array('justification' => 'left'));
			$pdf -> selectFont('pdf/fonts/Helvetica.afm');
			$pdf -> ezText("<b>SEQUENCE: ".$reportSummary[$i][$j][0]."</b>", $size, array('justification' => 'left'));
			$pdf -> ezText("<b>Nucleotides</b>", $size, array('justification' => 'left'));
			$pdf -> selectFont('pdf/fonts/Courier.afm');
			for($k=0; $k<count($reportNucleotide[$i][$j]); $k++){
				$pdf -> ezText($reportNucleotide[$i][$j][$k], $size, array('justification' => 'left'));
			}				
			$pdf -> selectFont('pdf/fonts/Helvetica.afm');
			$pdf -> ezText("<b>Amino Acid</b>", $size, array('justification' => 'left'));
			$pdf -> selectFont('pdf/fonts/Courier.afm');
			for($k=0; $k<count($reportAminoacid[$i][$j]); $k++){
				if($reportAminoacid[$i][$j][$k][1]=="no"){
					$pdf -> ezText($reportAminoacid[$i][$j][$k][0], $size, array('justification' => 'left'));
				}else{
					$pdf -> ezText($reportAminoacid[$i][$j][$k][0]." **Changed**", $size, array('justification' => 'left'));
				}
			}
		}
	}
	
	
	
	$pdfcode = $pdf->ezOutput();
	$fp=fopen($reportFilePath,'wb');
	fwrite($fp,$pdfcode);
	fclose($fp);
}
?>
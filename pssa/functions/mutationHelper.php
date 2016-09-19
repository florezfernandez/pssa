<?php

function getItems($dirPath,$nivel,$selectorType,$selectorName){
	$dir= opendir($dirPath);
	$items = array();
	$numItems=0;
	if($nivel==1){
		echo "<div class='tree well'>";
	}	
	while($content = readdir($dir)){
		if($content!="." && $content!=".." && $content!="Thumbs.db"){
			if(is_dir($dirPath."/".$content)){
				echo "<ul>";				
				echo "<li>
			    <span><img src='../img/folder.ico' width='20' />".$content."</span>
			    <ul>";
				getItems($dirPath."/".$content,$nivel+1,$selectorType,$selectorName);
				echo "</ul>
				</li>
				</ul>";
			}else{
				echo "<li><span>
				<input type='".$selectorType."' name='".$selectorName."' value='".$dirPath."/".$content."'> ".$content."
		        <a href='viewFile.php?filePath=" . base64_encode($dirPath."/".$content) . "' target='_blank' ><img src='../img/viewFile.ico' width='20' data-toggle='tooltip' data-placement='top' data-original-title='View File'/></a>
		        </span>
		        </li>";							
			}
		}					
	}
	if($nivel==1){
		echo "</div>";	
	}
}

function calculateTripletsChanges($referenceString, $patientString){		
	//echo "<pre>".$referenceString."</pre>";
	//echo "<pre>".$patientString."</pre>";
	$equivalencies = getEquivalencies();
	$changes = array();
	$changesCount = array();
	$equChanges = array();
	$equChangesConf = array();
	$changesFound=false;
	$equChangesFound=false;
	for($i=0; $i<strlen($referenceString)/3; $i++){
		$pos = $i*3;
		$referenceTrip=substr($referenceString, $pos, 3);
		$patientTrip=substr($patientString, $pos, 3);
		if($patientTrip!="---"){
			if($referenceTrip != $patientTrip){
				$changesFound=true;
				$changeFound=$referenceTrip." => ".$patientTrip;
				$changed=false;
				for($j=0; $j<count($changes); $j++){
					if($changes[$j]==$changeFound){
						$changesCount[$j]++;
						$changed=true;
					}
				}
				if(!$changed){
					$changes[count($changes)]=$changeFound;
					$changesCount[count($changesCount)]=1;
				}
			}
			if($referenceTrip != $patientTrip){
				$equChangesFound=true;
				$equChanges[count($equChanges)]=($i+1).": ".$referenceTrip." (".$equivalencies[$referenceTrip].") => ".$patientTrip." (".$equivalencies[$patientTrip].")";
				if($equivalencies[$referenceTrip]!=$equivalencies[$patientTrip]){
					$equChangesConf[count($equChangesConf)]="yes";
				}else{
					$equChangesConf[count($equChangesConf)]="no";
				}
			}
		}
	}
	if(!$changesFound){
		$changes[count($changes)]="";
		$changesCount[count($changesCount)]=0;		
	}
	if(!$equChangesFound){
		$equChanges[count($equChanges)]="";
		$equChangesConf[count($equChangesConf)]="no";
	}
	
	return array($changes, $changesCount, $equChanges, $equChangesConf);
}

function calculateNucleotideChanges($refString, $comString){
	$pos=0;
	$changes=array();
	$seqChanged="";
	for($i=0; $i<strlen($refString); $i++){
		$letterAltString=substr($refString, $i, 1);
		$letterPatString=substr($comString, $i, 1);
		if(strcmp($letterAltString, $letterPatString)){
			$changes[$pos][0]=$i+1;
			$changes[$pos][1]=substr($refString, $i, 1);
			$changes[$pos][2]=substr($comString, $i, 1);
			$seqChanged.="<span data-toggle='tooltip' data-placement='top' data-original-title='".$changes[$pos][0].": ".$changes[$pos][1]." => ".$changes[$pos][2]."'>".substr($comString, $i, 1)."</span>";
			$pos++;
		}else{
			$seqChanged.=".";
		}
	}
	return array($seqChanged, $changes);
}

/*
function calculateNucleotideChanges($refString, $comString){
	$pos=0;
	$changes=array();
	for($i=0; $i<strlen($refString); $i++){
		$letterAltString=substr($refString, $i, 1);
		$letterPatString=substr($comString, $i, 1);
		if(strcmp($letterAltString, $letterPatString)){
			$changes[$pos][0]=$i+1;
			$changes[$pos][1]=substr($refString, $i, 1);
			$changes[$pos][2]=substr($comString, $i, 1);
			$pos++;
		}		
	}
	return $changes;	
}
*/

function getEquivalencies(){
	return array(
			'TTT' => 'F','TTC' => 'F',
			'TTA' => 'L','TTG' => 'L','CTT' => 'L','CTC' => 'L','CTA' => 'L','CTG' => 'L',
			'TCT' => 'S','TCC' => 'S','TCA' => 'S','TCG' => 'S','AGT' => 'S','AGC' => 'S',
			'TAT' => 'Y','TAC' => 'Y',
			'TGT' => 'C','TGC' => 'C',
			'TGG' => 'W',
			'CCT' => 'P','CCC' => 'P','CCA' => 'P','CCG' => 'P',
			'CAT' => 'H','CAC' => 'H',
			'CAA' => 'Q','CAG' => 'Q',
			'CGT' => 'R','CGC' => 'R','CGA' => 'R','CGG' => 'R','AGA' => 'R','AGG' => 'R',
			'ATT' => 'I','ATC' => 'I','ATA' => 'I',
			'ATG' => 'M',
			'ACT' => 'T','ACC' => 'T','ACA' => 'T','ACG' => 'T',
			'AAT' => 'N','AAC' => 'N',
			'AAA' => 'K','AAG' => 'K',
			'GTT' => 'V','GTC' => 'V','GTA' => 'V','GTG' => 'V',
			'GCT' => 'A','GCC' => 'A','GCA' => 'A','GCG' => 'A',
			'GAT' => 'D','GAC' => 'D',
			'GAA' => 'E','GAG' => 'E',
			'GGT' => 'G','GGC' => 'G','GGA' => 'G','GGG' => 'G',
			'TAA' => '*','NTC' => '*','ACN' => '*'
	);
	
}

function microtime_float(){
	list($useg, $seg) = explode(" ", microtime());
	return ((float)$useg + (float)$seg);
}

function graphStream($selectedReferenceName, $secuenceChanges){
$fileStream="
{
  \"nodes\":[
    {\"name\":\"".$selectedReferenceName."\",\"group\":1},
    ";
$countTotal=0;
for($i=0; $i<count($secuenceChanges); $i++){
	$group=$i+2;
	for($j=1; $j<=count($secuenceChanges[$i]); $j++){
		$pos=$countTotal+1;
		$fileStream.="{\"name\":\"".$secuenceChanges[$i][$j][0]." (".$secuenceChanges[$i][$j][1]."). ".$secuenceChanges[$i][$j][2]."\",\"group\":".$group."},
		";
		$nodes[$countTotal][0]=$group;
		$nodes[$countTotal][1]=$secuenceChanges[$i][$j][1];
		$countTotal++;
	}
}
$lastIndexComma=strrpos($fileStream, ",", -1);
$fileStream=substr($fileStream, 0, $lastIndexComma);
$fileStream.="
  ],
  \"links\":[
  ";  
for($i=0; $i<count($nodes); $i++){
	$target=$i+1;
	$value=$nodes[$i][1]+1;
	$fileStream.="{\"source\":0,\"target\":".$target.",\"value\":". $value."},
	";
}

for($i=0; $i<count($nodes)-1; $i++){
	for($j=$i+1; $j<count($nodes); $j++){
		if($nodes[$i][0] == $nodes[$j][0] && $nodes[$i][1] == $nodes[$j][1]){
			$source=$i+1;
			$target=$j+1;
			$value=$nodes[$i][1]+1;
			$fileStream.="{\"source\":".$source.",\"target\":".$target.",\"value\":". $value."},
			";			
		}
	}
}
$lastIndexComma=strrpos($fileStream, ",", -1);
$fileStream=substr($fileStream, 0, $lastIndexComma);
$fileStream.="
  ]
}";	
return $fileStream;
}

function treeStream($selectedReferenceName, $sequenceRefChanges, $sequenceAltChanges, $sequencePatChanges){	
	for($i=0; $i<count($sequenceAltChanges)-1; $i++){
		for($j=$i+1; $j<count($sequenceAltChanges); $j++){
			if($sequenceAltChanges[$i][1]>$sequenceAltChanges[$j][1]){
				$temp=$sequenceAltChanges[$i];
				$sequenceAltChanges[$i]=$sequenceAltChanges[$j];
				$sequenceAltChanges[$j]=$temp;
			}
		}
	}

	for($i=0; $i<count($sequencePatChanges)-1; $i++){
		for($j=$i+1; $j<count($sequencePatChanges); $j++){
			if($sequencePatChanges[$i][1]>$sequencePatChanges[$j][1]){
				$temp=$sequencePatChanges[$i];
				$sequencePatChanges[$i]=$sequencePatChanges[$j];
				$sequencePatChanges[$j]=$temp;
			}
		}
	}

	$mainNode = new TreeNode($selectedReferenceName, 0);
	
	for($i=0; $i<count($sequenceRefChanges); $i++){
		$mainNode->addNode(new TreeNode($sequenceRefChanges[$i][0],count($sequenceRefChanges[$i][1])));
	}
	
/*		
	for($i=0; $i<count($sequenceAltChanges); $i++){
		$node = $mainNode->findNodeToAdd(count($sequenceRefChanges[$i][1]));
		$node->addNode(new TreeNode($sequenceAltChanges[$i][0],count($sequenceAltChanges[$i][1])));
	}	*/
	$stream=$mainNode->toStream();
	return $stream;
}


?>
<?php
function getPositionElement($array, $element){
	for($i=0; $i<count($array); $i++){
		if($array[$i]==$element){
			return $i;
		}
	}
	return count($array);
}

function mergeArrayInfo($array, $start){
	$result="";
	for($i=$start; $i<count($array); $i++){
		$result.=trim($array[$i]);
	}
	return $result;	
}

?>
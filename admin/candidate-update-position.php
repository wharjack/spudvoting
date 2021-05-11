<?php
require_once('../includes/dbOperations.php');

$position_id = 0;
if ($_POST['position'] == "P") {
	$position_id = 1;
}else if($_POST['position'] == "VP"){
	$position_id = 2;
}else if($_POST['position'] == "G"){
	$position_id = 3;
}else if($_POST['position'] == "VG"){
	$position_id = 4;
}else if($_POST['position'] == "C"){
	$position_id = 5;
}
$updateCandidatePosition = (new dbOperations())->updateCandidatePosition($_POST['candidate_id'],$position_id);

?>
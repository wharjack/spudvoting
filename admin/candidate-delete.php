<?php

require_once('../includes/dbOperations.php');

$data = json_decode(stripslashes($_POST['data']));

  // here i would like use foreach:

  foreach($data as $d){
      $deleteCandidate = (new dbOperations())->deleteCandidate($d);
  }



?>
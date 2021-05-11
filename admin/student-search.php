<?php

require_once('../includes/dbOperations.php');
function displayImage($student_image){
	if (empty($student_image)) {
		echo "../images/default.jpg";
	}else {
		echo "../uploads/".$student_image;
	}
}
function candidateRunningPosition($position_code){
	if ($position_code == "1") {
		echo "Running for Presidency";
	}elseif ($position_code == "2") {
		echo "Running for Vice President";
	}elseif ($position_code == "3") {
		echo "Running for Governor";
	}elseif ($position_code == "4") {
		echo "Running for Vice Governor";
	}elseif ($position_code == "5") {
		echo "Running for Congress";
	}elseif ($position_code == ""|| empty($position_code)) {
		echo "-";
	}
}
if (!empty($_POST["keyword"])) {
$getStudentInfo = (new dbOperations())->getStudentInfo($_POST["keyword"]);
$count = $getStudentInfo->num_rows;	

?>

	<?php if($count > 0){?>
		
			<style type="text/css">
				body {
						color: #666666;
					margin: 0;
					font-family: 'Open Sans', sans-serif;
				}
				#table tr:hover {
					background-color: #f0f0f0;
				}
			</style>
			<table id="table" style="border-collapse: collapse; font-size: 14px; text-align: left;" width="100%">
				
				<?php while ($row = $getStudentInfo->fetch_assoc()) { ?>
				<tr onClick="selectStudent('<?php echo $row["student_number"]." ".$row["student_fname"]." ".$row["student_mname"]." ".$row["student_lname"]; ?>');" style="cursor:pointer; border-bottom: 1px solid #DDDDDD;" >
					<td width="70px"><div style="margin: 5px; width: 40px; height: 40px; border-radius: 90px; overflow: hidden;"><img src="<?php echo displayImage($row['student_image']);?>" width="100%" height="100%"></div></td>
					<td><?php echo $row['student_number'];?></td>
					<td><?php echo $row['student_fname'];?></td>
					<td><?php echo $row['student_mname'];?></td>
					<td><?php echo $row['student_lname'];?></td>
					<td width="200px"><?php candidateRunningPosition($getCandidatePosition = (new dbOperations())->getCandidatePosition($row['student_id']));?></td>
				</tr>
				<?php }?>
			</table>
		
	<?php }else { ?>
		<table id="table" style="border-collapse: collapse; font-size: 14px; text-align: left;" width="100%">
			<td colspan="6" style="padding: 7px; color: #666666;">No results found...</td>
		</table>
	<?php }?>
<?php }  ?>

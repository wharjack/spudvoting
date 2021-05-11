<?php
require_once('../includes/dbOperations.php');
$getElectionStatus = (new dbOperations())->getElectionStatus();
if (isset($_POST['keyword']) && $_POST['keyword'] != "" && !empty($_POST['keyword'])) {
	$indexCandidates = (new dbOperations())->getCandidateInfo($_POST['keyword']);
}else {
	$indexCandidates = (new dbOperations())->indexCandidates();
}
function candidatePosition($candidate_position_id,$position_id){
	if ($candidate_position_id == $position_id) {
		echo "selected style='background-color: #2FB47E; color: white;'" ;
	}
}
function displayImage($student_image){
	if (empty($student_image)) {
		echo "../images/default.jpg";
	}else {
		echo "../uploads/".$student_image;
	}
}
$noOfCandidates = $indexCandidates->num_rows;
?>

<script>
		$(document).ready(function(){
			$("#check-all-candidates").click(function(){
				$('.candidate-checkbox:checkbox').not(this).prop('checked', this.checked);
			});
			$(".candidate-position").change(function(){
				var selected_position = $(this).children("option:selected").attr("value");
				var candidate_id = $(this).attr("id");
       			 
       			 $.ajax({
					type: "POST",
					url: "candidate-update-position.php",
					data: {position : selected_position,
						   candidate_id : candidate_id,},
					success: function(data){
						 
						window.location.reload();
					},
					error: function(data){
						alert("Some error occured.");
						
					}
				});
			});

		});
	</script>
	
<table width="100%">
					<tr>
						<th width="10px">
							<?php if($getElectionStatus == "new"){?>
								<input id="check-all-candidates" type="checkbox" name="">
							<?php } ?>
						</th>
						<th width="60px"></th>
						<th width="150px">ID</th>
						<th>FIRST NAME</th>
						<th>MIDDLE NAME</th>
						<th>LAST NAME</th>
						<th width="100px">DEPARTMENT</th>
						<th width="75px">COURSE</th>
						<th width="135px">POSITION</th>
					</tr>
					<?php if($noOfCandidates > 0){?>
						<?php while ($row = $indexCandidates ->fetch_assoc()) { ?>
							<tr>
								<td>
									<?php if($getElectionStatus == "new"){?>
										<input class="candidate-checkbox" type="checkbox" value="<?php echo $row['candidate_id'];?>">
									<?php } ?>
								</td>
								<td><div class="candidate-image"><img src="<?php echo displayImage($row['student_image']);?>" width="100%" height="100%"></div></td>
								<td><?php echo $row['student_number'];?></td>
								<td><?php echo $row['student_fname'];?></td>
								<td><?php echo $row['student_mname'];?></td>
								<td><?php echo $row['student_lname'];?></td>
								<td><?php echo $row['department_abbr'];?></td>
								<td><?php echo $row['course_abbr'];?></td>
								<td>
									<?php if($getElectionStatus == "new"){?>
										<select class="candidate-position" id="<?php echo $row['candidate_id'];?>">
											<option <?php candidatePosition($row['position_id'],1);?>  value="P">President</option>
											<option <?php candidatePosition($row['position_id'],2);?>  value="VP">Vice President</option>
											<option <?php candidatePosition($row['position_id'],3);?>  value="G">Governor</option>
											<option <?php candidatePosition($row['position_id'],4);?>  value="VG">Vice Govenor</option>
											<option <?php candidatePosition($row['position_id'],5);?>  value="C">Congress</option>
										</select>
									<?php }else{ ?>
										<?php echo $row['position_name'];?>
									<?php }?>
								</td>
							</tr>
						<?php }?>
					<?php } else {?>
						<tr>
							<td colspan="9">No results found...</td>
						</tr>
					<?php }?>
				</table>
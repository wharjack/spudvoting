<?php
require_once('../includes/dbOperations.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
	header("Location: login.php");
}
if (isset($_POST['add-candidate'])) {
	$explode = explode(" ",$_POST['student-id']);
	$count = count($explode);
	if (!empty($_POST['student-id']) || $_POST['student-id'] != "") {
		if ($count <= 4) {
			if (is_numeric($explode[0]) && $explode[3] != "") {
				if ($_POST['student-position'] != "") {

					$message = $addCandidates = (new dbOperations())->addCandidates($explode[0],$explode[1],$explode[2],$explode[3],$_POST['student-position']);
						
				}else {
					$message =  "Select a Position.";
				}
			}else {
				$message =  "Error Student value.";
			}
		}else {
			$message =  "Error Student value.";
		}
		
	}else {
		$message =  "Required fields are missing.";
	}
}
if (isset($_POST['new-election'])) {
	$updateElectionStatus = (new dbOperations())->updateElectionStatus("new");
	$deleteVotes = (new dbOperations())->deleteVotes(); 
	$deleteCandidates = (new dbOperations())->deleteCandidates(); 
	header("Refresh:0");

}elseif(isset($_POST['start-election'])) {
	$updateElectionStatus = (new dbOperations())->updateElectionStatus("start");
	header("Refresh:0");
}elseif (isset($_POST['end-election'])) {
	$updateElectionStatus = (new dbOperations())->updateElectionStatus("end");
	header("Refresh:0");
}
$getElectionStatus = (new dbOperations())->getElectionStatus();


#functions-----------------------------
function displayImage($student_image){
	if (empty($student_image)) {
		echo "../images/default.jpg";
	}else {
		echo "../uploads/".$student_image;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>SPUD Admin | Election</title>
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/election.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#load-candidates").load('candidate-search.php');

			$("#button-validate-start-election").click(function(){
				$("#md-validate-start-election").css("display","block");
			});
			$(document).click(function(event) {
				if (!$(event.target).closest(".md-panel, #button-validate-start-election").length) {
					$("body").find("#md-validate-start-election").css('display','none');
				}
			});

			$("#button-validate-end-election").click(function(){
				$("#md-validate-end-election").css("display","block");
			});
			$(document).click(function(event) {
				if (!$(event.target).closest(".md-panel, #button-validate-end-election").length) {
					$("body").find("#md-validate-end-election").css('display','none');
				}
			});
			
			$("#button-validate-new-election").click(function(){
				$("#md-validate-new-election").css("display","block");
			});
			$(document).click(function(event) {
				if (!$(event.target).closest(".md-panel, #button-validate-new-election").length) {
					$("body").find("#md-validate-new-election").css('display','none');
				}
			});

			$("#check-all-candidates").click(function(){
				$('.candidate-checkbox:checkbox').not(this).prop('checked', this.checked);
			});

			$("#button-delete-candidate").click(function(){
				$("#md-validate-delete-candidate").css("display","block");
			});
			$(document).click(function(event) {
				if (!$(event.target).closest(".md-panel, #button-delete-candidate").length) {
					$("body").find("#md-validate-delete-candidate").css('display','none');
				}
			});
			$("#md-submit-delete-candidate").click(function(){

				var checkedArray = [];
				 $(".candidate-checkbox:checked").each(function() {
		            checkedArray.push($(this).val());
		        });
		     	
				var jsonString = JSON.stringify(checkedArray);

				 $.ajax({
					type: "POST",
					url: "candidate-delete.php",
					data:{data : jsonString},
					success: function(data){
						$("#load-candidates").html(	);
						
					},
					error: function(data){
						alert("Some error occured.");
						
					}
				});
			});

			$("#search-student-for-candidate").keyup(function(){
				$.ajax({
					type: "POST",
					url: "student-search.php",
					data:'keyword='+$(this).val(),
					success: function(data){
			
						$("#student-result-box").show();
						$("#student-result-box").html(data);
						$("#student-result-box").css('display','block');
						if (data == "") {
							$("#student-result-box").css('display','none');
						}
					},error: function(data){
						alert("Some error occured.");
						
					}
				});
			});
			$("#search-candidate").keyup(function(){
			
				$.ajax({
					type: "POST",
					url: "candidate-search.php",
					data:'keyword='+$(this).val(),
					success: function(data){
						$("#load-candidates").html(data);
					
					},error: function(data){
						alert("Some error occured.");
						
					}
				});
			});
		});
		function selectStudent(val) {
			$("#search-student-for-candidate").val(val);
			$("#student-result-box").hide();
		}
	</script>
</head>
<body>
	<header>
		<div class="header-section inline-block">
			<div id="company-logo" class="inline-block"><img src="../images/spud-logo.png" width="100%" height="100%"></div>
			<div id="company-name" class="inline-block">SPUD Voting | Admin Panel</div>
		</div>
		<div class="header-section inline-block">
			<div id="header-nav">
				<ul>
					<li><a href="dashboard.php">DASHBOARD</a></li>
					<li style="color: #4f4d4c;"><a href="election.php">ELECTION</a></li>
					<li><a href="students.php">STUDENTS</a></li>
					<li><a href="admins.php">ADMINS</a></li>
					<li><a href="logout.php">LOG OUT</a></li>
					<li style="margin-right: 15px;"><a href="#" style="padding: 28px 0px  ;">|</a></li>
					<li><a href="#" style="padding: 28px 0px ;"><?php echo $_SESSION['admin_name'];?></a></li>
				</ul>
			</div>
		</div>
	</header>
	<main>
		<div id="first-section-con">
			<div class="search-student section inline-block">
				<form method="post">
					<input autocomplete="off"type="text" id="search-student-for-candidate" name="student-id" placeholder="Search Student">
					<div id="student-result-box"></div>
					<select name="student-position">
						<option value="">Position</option>
						<option value="P">President</option>
						<option value="VP">Vice President</option>
						<option value="G">Governor</option>
						<option value="VG">Vice Governor</option>
						<option value="C">Congress</option>
					</select>
					<input type="submit" name="add-candidate" value="ADD">
				</form>
				<?php if(isset($message)){?>
						<span style="color: red; "><?php echo $message;?></span>
				<?php }?>
			</div>
			<div class="election-buttons section inline-block">
				<?php if($getElectionStatus == "end"){?>
				<div id="button-validate-new-election" class="election-button inline-block">NEW ELECTION</div>
				<?php }else if($getElectionStatus == "new"){?>
				<div id="button-validate-start-election" class="election-button inline-block">START ELECTION</div>
				<?php }else if($getElectionStatus == "start"){?>
				<div id="button-validate-end-election" class="election-button inline-block">END ELECTION</div>
				<?php }?>
			</div>
		</div>
		<div id="second-section-con">
			<div class="delete-candidate section inline-block">
				<?php if($getElectionStatus == "new"){?>
				<button id="button-delete-candidate">DELETE</button>
				<?php }?>
				
			</div>
			<div class="search-candidate section inline-block">
					<input type="text" id="search-candidate" placeholder="Search candidate" autocomplete="off">
			</div>
		</div>

		<div id="candidates-con">
			<div id="candidate-list">
				<div id="load-candidates"></div>
			</div>
		</div>
	</main>

	<div class="md-bg" id="md-validate-new-election">
		<div class="md-panel md-election">
			<div class="md-title">New Election</div>
			<div class="md-content">
				<form method="post">
					By creating new Election, all data will be wiped out, this action can't be undone.
					<br/><br/>
					<input type="checkbox" required /> I'm aware of this condition.<br/>
					<input type="checkbox" required/> The election is/was and we will start another.
					<br/><br/>
					<button id="md-submit-button-new-election" type="submit" name="new-election">CREATE</button>
				</form>
			</div>
		</div>
	</div>

	<div class="md-bg" id="md-validate-start-election">
		<div class="md-panel md-election">
			<div class="md-title">Start Election</div>
			<div class="md-content">
				<form method="post">
					By starting the election, you can't add/edit candidates anymore, this action can't be undone.
					<br/><br/>
					<input type="checkbox" required/> I'm aware of this condition.<br/>
					<input type="checkbox" required/> The candidates are complete and/or finalized.
					<br/><br/>
					<button id="md-submit-button-start-election" type="submit" name="start-election">START</button>
				</form>
			</div>
		</div>
	</div>

	<div class="md-bg" id="md-validate-end-election">
		<div class="md-panel md-election">
			<div class="md-title">End Election</div>
			<div class="md-content">
				<form method="post">
					By ending the election, votes can't be added anymore, this action can't be undone.
					<br/><br/>
					<input type="checkbox" required /> I'm aware of this condition.<br/>
					<input type="checkbox" required/> The election is/was done.
					<br/><br/>
					<button id="md-submit-button-end-election" type="submit" name="end-election">END</button>
				</form>
			</div>
		</div>
	</div>


	<div class="md-bg" id="md-validate-delete-candidate">
		<div class="md-panel md-candidate">
			<div class="md-title">Delete Candidate</div>
			<div class="md-content">
				
					You are about to delete Candidate(s).
					<br/><br/>
					
					<br/><br/>
					<button id="md-submit-delete-candidate" name="">DELETE</button>
				
			</div>
		</div>
	</div>
</body>
</html>
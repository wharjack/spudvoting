<?php
require_once('../includes/dbOperations.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
	header("Location: login.php");
}
date_default_timezone_set('Asia/Manila');
$getStudentInfo = "";
if (isset($_POST['import-students'])) {
	$allowed_file= array('csv');
	$file_name = $_FILES['file_name']['name'];
	$file_path = realpath($_FILES["file_name"]["tmp_name"]);
	$extension = pathinfo($file_name, PATHINFO_EXTENSION);
	$file_open = fopen($file_path,"r");

	if (!in_array($extension, $allowed_file)) {
		echo 'error';
	}else {	
		$rows = 0 ;

		
		$report_array = array();
		fgetcsv($file_open);
		$num = 0;
		while(($student_data = fgetcsv($file_open, 1000, ",")) !== false)
		{
			
			$addStudent = (new dbOperations())->addStudent(trim($student_data[0]),trim($student_data[1]),trim($student_data[2]),trim($student_data[3]),trim($student_data[4]),trim($student_data[5]),trim($student_data[6])); 

			$explode = explode(',', $addStudent);
			$report_array[$num]['ID'] = $explode[0];
			$report_array[$num]['FIRST NAME'] = $explode[1];
			$report_array[$num]['MIDDLE NAME'] = $explode[2];
			$report_array[$num]['LAST NAME'] = $explode[3];
			$report_array[$num]['REPORT'] =  $explode[4];
			$num++;
			
		}
		
		exportReportToCSV($report_array);
		
	}
	
}
if (isset($_POST['student-keywords'])) {
	if (!empty($_POST['student-keywords']) || $_POST['student-keywords'] != "") {
		$getStudentInfo = (new dbOperations())->getStudentInfo($_POST['student-keywords']); 
	}
	
}


#functions-----------------------------
function displayImage($student_image){
	if (empty($student_image)) {
		echo "../images/default.jpg";
	}else {
		echo "../uploads/".$student_image;
	}
}
function exportReportToCSV($array){

	$filename = "Import Student Report @ ".date("M.d.Y h.i A").'.csv';         
	header("Content-type: text/csv");       
	header("Content-Disposition: attachment; filename=$filename");   
	ob_end_clean();    
	$output = fopen("php://output", "w");       
	$header = array_keys($array[0]);       
	fputcsv($output, $header);       
	foreach($array as $row)       
	{  
		fputcsv($output, $row);  
	}       
	fclose($output); 
	exit;
	
	
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>SPUD Admin | Students</title>
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/students.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#button-import-students").click(function(){
				$("#md-import-students").css("display","block");
			});
			$(document).click(function(event) {
				if (!$(event.target).closest(".md-panel, #button-import-students").length) {
					$("body").find("#md-import-students").css('display','none');
				}
			});
			
			
		});
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
					<li><a href="election.php">ELECTION</a></li>
					<li style="color: #4f4d4c;"><a href="students.php">STUDENTS</a></li>
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
					<input type="text" name="student-keywords" placeholder="Search Student" autocomplete="off">
					<input type="submit" name="submit-search-student" value="SEARCH">
				</form>
			</div>
			<div class="import-students section inline-block">
				<div id="button-import-students" class="import-students-button inline-block">IMPORT</div>
			</div>
		</div>

		<div id="students-con">
			<div id="student-list">
				<table width="100%">
					<tr>
						
						<th width="60px"></th>
						<th width="150px">ID</th>
						<th>LAST NAME</th>
						<th>FIRST NAME</th>
						<th>MIDDLE NAME</th>
						<th width="100px">DEPARTMENT</th>
						<th width="85px">COURSE</th>
						<th width="50px"></th>
					</tr>
					<?php if($getStudentInfo){?>
						<?php while($row = $getStudentInfo->fetch_assoc()){?>
							<tr>
								<td><div class="student-image"><img src="<?php echo displayImage($row['student_image'])?>" width="100%" height="100%"></div></td>	
								<td><?php echo$row['student_number'];?></td>
								<td><?php echo$row['student_fname'];?></td>
								<td><?php echo$row['student_mname'];?></td>
								<td><?php echo$row['student_lname'];?></td>
								<td><?php echo$row['department_abbr'];?></td>
								<td><?php echo$row['course_abbr'];?></td>
								<td>
									<form method="get" action="student.php">
										
										<input type="hidden" name="studentId" value="<?php echo $row['student_number'];?>">
										<button type="submit">EDIT</button>
									</form>
								</td>
							</tr>
						<?php }?>
					<?php }else{?>
						<tr>
							<td><div class="student-image"><img src="../images/default.jpg" width="100%" height="100%;"></div></td>	
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
						</tr>
					<?php }?>
				</table>
			</div>
		</div>
		
	</main>

	<div class="md-bg" id="md-import-students">
		<div class="md-panel md-import-students">
			<div class="md-title">Import Students</div>
			<div class="md-content">
				<form method="post" enctype="multipart/form-data">
					<label>Only accepts .csv</label>
					<br/>
					<input id="md-import-csv" type="file" name="file_name" accept=".csv" required/>
					<br/>
					<button id="md-submit-import" type="submit" name="import-students">SUBMIT</button>
					
				</form>
			</div>
		</div>
	</div>
</body>
</html>
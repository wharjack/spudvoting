<?php
require_once('../includes/dbOperations.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
	header("Location: login.php");
}
$student_id = $_GET['studentId'];
$getStudentInfoByNumber = (new dbOperations())->getStudentInfoByNumber($student_id); 
if ($getStudentInfoByNumber->num_rows > 0) {
	# code...
}else {
	header("Location: students.php");
}

function displayImage ($student_image){
	if (empty($student_image)) {
		echo "../images/default.jpg";
	}else {
		echo "../uploads/".$student_image;
	}
}
if (isset($_POST['submit-update-image'])) {
	$target_dir = "../uploads/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	
		if (empty($_FILES['image']["name"])) {
			$message =  "No image to upload.";
		}else {
			$file_parts = pathinfo($_FILES["image"]["name"]);
			$file_parts['extension'];
			$image_extensions = Array('jpg','png','jpeg');
			if (in_array($file_parts['extension'],$image_extensions,true)){
				  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

				  	$updateStudentImage = (new dbOperations())->updateStudentImage($_FILES["image"]["name"],$student_id);
				 	if (!empty($_POST['current_image'])) {
				 		 unlink("../uploads/".$_POST['current_image']);
				 	}
			   	 	$message = "The file ". basename( $_FILES["image"]["name"]). " has been uploaded. Reloading...";
			   	 	header("Refresh:2");
				  } else {

				    $message =  "Sorry, there was an error uploading your file.";
				  }
			  } else {
				 $message =  "Sorry, file not supported.";
			  }
		}
	
	
}
if (isset($_POST['submit-update'])) {

	$updateStudentInfo = (new dbOperations())->updateStudentInfo($_POST['student_fname'],$_POST['student_mname'],$_POST['student_lname'],$student_id);
	$message =  "Information sucessfully updated. Reloading...";
	header("Refresh:2");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>SPUD Admin | Students</title>
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/students.css">
	
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

		<div id="student-info-con">
			<?php while($row = $getStudentInfoByNumber->fetch_assoc()){ ?>
				
				<div class="student-image-wrapper inline-block">
					<form method="post" enctype="multipart/form-data">
						<div class="student-info-image">
							<img src="<?php echo displayImage($row['student_image']);?>" width="100%" height="100%">
						</div>
						<br/>
						<label>Upload Image</label>
						<input type="hidden" name="current_image" value="<?php echo $row['student_image']; ?>">
						<input type="file" name="image" accept=".jpg,.jpg,.png">
						<input type="submit" name="submit-update-image" value="SUBMIT">
						<div style="color: red"></div>
					</form>
				</div>
			
				
				<div class="student-info-wrapper inline-block">
					<form method="post">
						<label>First Name</label>
						<input type="text" name="student_fname" value="<?php echo $row['student_fname'];?>"  autocomplete="off">
						<label>Middle Name</label>
						<input type="text" name="student_mname" value="<?php echo $row['student_mname'];?>" autocomplete="off">
						<label>Last Name</label>
						<input type="text" name="student_lname" value="<?php echo $row['student_lname'];?>" autocomplete="off">
						<label>Department</label>
						<input type="text" value="<?php echo $row['department_name'];?>" readonly/>
						<label>Course</label>
						<input type="text" value="<?php echo $row['course_name'];?>" readonly/>
						<label>Status</label>
						<input type="text" value="<?php echo $row['student_status'];?>" readonly/>
						<input type="submit" name="submit-update" value="SUBMIT">
					</form>
				</div>
				
			<?php }?>
			<?php if(isset($message)){?>
				<span style="color: red; "><?php echo $message;?></span>
			<?php }?>
		</div>
		
	</main>

	
</body>
</html>
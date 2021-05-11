<?php
require_once('../includes/dbOperations.php');
date_default_timezone_set('Asia/Manila');
session_start();
if (!isset($_SESSION['admin_id'])) {
	header("Location: login.php");
}
if (isset($_POST['new-admin'])) {

	if ($_POST['password'] == $_POST['cpassword']) {
		$addAdmin = (new dbOperations())->addAdmin($_POST['name'],$_POST['username'],$_POST['password'],"Admin"); 
		$message = $addAdmin;
	}else {
		$message = "Password did not matched.";
	}

}
$indexAdmin = (new dbOperations())->indexAdmin();
$countAdmin = $indexAdmin->num_rows;
?>
<!DOCTYPE html>
<html>
<head>
	<title>SPUD Admin | Admins</title>
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/admins.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#button-new-admin").click(function(){
				$("#md-new-admin").css("display","block");
			});
			$(document).click(function(event) {
				if (!$(event.target).closest(".md-panel, #button-new-admin").length) {
					$("body").find("#md-new-admin").css('display','none');
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
					<li><a href="students.php">STUDENTS</a></li>
					<li style="color: #4f4d4c;"><a href="admins.php">ADMINS</a></li>
					<li><a href="logout.php">LOG OUT</a></li>
					<li style="margin-right: 15px;"><a href="#" style="padding: 28px 0px  ;">|</a></li>
					<li><a href="#" style="padding: 28px 0px ;"><?php echo $_SESSION['admin_name'];?></a></li>
				</ul>
			</div>
		</div>
	</header>
	<main>
		<div id="first-section-con">
			<div class="new-admin section">
				<div id="button-new-admin" class="inline-block">NEW ADMIN</div>
			</div>
		</div>

		<div id="students-con">

			<?php if(isset($message)){?>
				<span style="color: red; "><?php echo $message;?></span>
			<?php }?>
			<div id="student-list">
				<table width="100%">
					<tr>
						<th>ID</th>
						<th>NAME</th>
						<th>USERNAME</th>
						<th width="85px">TYPE</th>
					</tr>
					<?php if($countAdmin > 0){?>
						<?php while($row = $indexAdmin->fetch_assoc()){?>
							<tr>
								<td><?php echo $row['admin_id'];?></td>
								<td><?php echo $row['admin_name'];?></td>
								<td><?php echo $row['admin_username'];?></td>
								<td><?php echo $row['admin_type'];?></td>
							</tr>
						<?php }?>
					<?php }else{?>
						<tr>
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

	<div class="md-bg" id="md-new-admin">
		<div class="md-panel md-new-admin">
			<div class="md-title">New Admin</div>
			<div class="md-content">
				<form method="post" enctype="multipart/form-data">
					
					<label>Username</label>
					<input type="text" name="username">
					<label>Password</label>
					<input type="password" name="password">
					<label>Confirm Password</label>
					<input type="password" name="cpassword">
					<label>Name</label>
					<input type="" name="name">
					
					<button id="md-submit-import" type="submit" name="new-admin">CREATE</button>
					
				</form>
			</div>
		</div>
	</div>
</body>
</html>
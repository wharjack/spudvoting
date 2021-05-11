<?php
require_once('../includes/dbOperations.php');
session_start();
if (isset($_POST['login'])) {
	
	if (empty($_POST['username']) || empty($_POST['password'])) {
		$message = "Required fields are missing.";
	}else {
		$adminLogin = (new dbOperations())->adminLogin($_POST['username'],$_POST['password']);
		$checkLogin = $adminLogin->num_rows;
		if ($checkLogin > 0) {
			while ($row = $adminLogin->fetch_assoc()) {
				$_SESSION['admin_id'] = $row['admin_id'];
				$_SESSION['admin_name'] = $row['admin_name'];
				
				header("Location: dashboard.php");
			}
		}else {
			$message = "Wrong ID or Password.";
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>SPUD Admin | Login</title>
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	
</head>
<body>
	<header>
		<div class="header-section inline-block">
			<div id="company-logo" class="inline-block"><img src="../assets/img/spud-logo.png" width="100%" height="100%"></div>
			<div id="company-name" class="inline-block">SPUD Voting | Admin Panel</div>
		</div>
		<div class="header-section inline-block">
			<div id="header-nav">
				<ul>
					<li style="color: #4f4d4c;"><a href="login.php">LOGIN</a></li>
				</ul>
			</div>
		</div>
	</header>
	
	<main>
		
		<div id="login-con">
			<div id="login-panel">
				<div id="spud-logo"><img src="../assets/img/spud-logo.png" width="100%" height="100%"></div>
				<div id="login-text">Login to SPUD Voting System Admin Panel</div>
				<form method="post">
					<div class="form-group">
						<label>Username</label>
						<input type="text" name="username">
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" name="password"  autocomplete="off">
					</div>
					<?php if(isset($message)){?>
						<div style="color: red; margin-bottom: 10px;"><?php echo $message;?></div>
					<?php } ?>
					<div class="form-group">

						<input type="submit" name="login" value="LOGIN">
					</div>
				</form>
			</div>

		</div>
	</main>
</body>
</html>
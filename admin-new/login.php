<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SPUD Voting | Login</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<div class="login-container">	
		<div class="login login-container__panel">
			<div class="login__logo"><img src="../assets/img/spud-logo.png" width="100%"></div>
			<br/><br/>
			<h3 class="login__heading">Admin</h3>
			<h4 class="login__subheading">SPUD Voting System</h4>
			<br/>
			<form action="dashboard.php" method="post" class="form login__form">
				<input type="text" name="username" class="input form__input-text form__input-text--focus" placeholder="ID No." autofocus="true" autocomplete="off">
				<input type="password" name="password" class="input form__input-password form__input-password--focus" placeholder="Password">
				<button type="submit" class="button form__button form__button--focus ">LOGIN</button>
			</form>
		</div>
	</div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SPUD Voting | Home</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<?php include_once("header.php");?>
	<main>
		
		<h3 class="heading section-heading">Presidential</h3>
		<section class="cards">
				<?php for($a = 1; $a < 10; $a++){?>
				<div class="card cards__item">
					<div class="candidate card__content">
						<div class="candidate__department">CBIT</div>
						<br/>
						<div class="candidate__img">
							<img src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F558c0172e4b0425fd034f8ba%2F0x0.jpg%3Ffit%3Dscale%26background%3D000000" width="100%" alt="">
						</div>
						<br/>
						<div class="candidate__name">Leonardo DiCaprio</div>
						<div class="candidate__course">Information Technology</div>
						<div class="text candidate__footer">
							<div class="text__vote-count">33</div>
						</div>
						<div class="candidate__text-votes">VOTES</div>
					</div>
				</div>
				<?php }?>
		</section>

	</main>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SPUD Voting | Vote</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<?php include_once("header.php");?>
	<main>
		
		<h3 class="heading section-heading">Presidential</h3>
		<section class="cards">
				<?php for($a = 1; $a < 10; $a++){?>
				<div class="card cards__item">
					<div id="<?php echo $a."ID"?>" class="candidate js-presidential card__content card__content--vote-cards ">
						<div class="candidate__department">CBIT</div>
						<br/>
						<div class="candidate__img">
							<img src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F558c0172e4b0425fd034f8ba%2F0x0.jpg%3Ffit%3Dscale%26background%3D000000" width="100%" alt="">
						</div>
						<br/>
						<div class="candidate__name">Leonardo DiCaprio</div>
						<div class="candidate__course">Information Technology</div>
						<div class="text candidate__footer">
							<div class="text__icon text__icon--disabled">&#10004;</div>
						</div>
					</div>
				</div>
				<?php }?>
		</section>
		
		<h3 class="heading section-heading">Vice Presidential</h3>
		<section class="cards">
				<?php for($a = 1; $a < 10; $a++){?>
				<div class="card cards__item">
					<div id="<?php echo $a."ID"?>" class="candidate js-vice-presidential card__content card__content--vote-cards">
						<div class="candidate__department">CBIT</div>
						<br/>
						<div class="candidate__img">
							<img src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F558c0172e4b0425fd034f8ba%2F0x0.jpg%3Ffit%3Dscale%26background%3D000000" width="100%" alt="">
						</div>
						<br/>
						<div class="candidate__name">Leonardo DiCaprio</div>
						<div class="candidate__course">Information Technology</div>
						<div class="text candidate__footer">
							<div class="text__icon text__icon--disabled">&#10004;</div>
						</div>
					</div>
				</div>
				<?php }?>
		</section>
		<button id="aw" onclick="awtest()">SUBMIT VOTE</button>
	</main>
</body>
<script type="text/javascript">

	var $presidential = document.getElementsByClassName('js-presidential');
	var $vice_presidential = document.getElementsByClassName('js-vice-presidential');

	function selectPresident(){
		//Remove last selected President
		for (var i = 0; i < $presidential.length; i++){
			$presidential[i].classList.remove("js-card__content--is-selected");			
		}
		//Check the selected President
		this.classList.add("js-card__content--is-selected");

			
	}
		
	// Assign a click event handler to every Presidential Candidate
	for(var i = 0; i < $presidential.length; i++) {
		$presidential[i].onclick = selectPresident;

	}

	function selectVicePresident(){
		//Remove last selected President
		for (var i = 0; i < $vice_presidential.length; i++){
			$vice_presidential[i].classList.remove("js-card__content--is-selected");			
		}
		//Check the selected President
		this.classList.add("js-card__content--is-selected");

			
	}
		
	// Assign a click event handler to every Presidential Candidate
	for(var i = 0; i < $vice_presidential.length; i++) {
		$vice_presidential[i].onclick = selectVicePresident;

	}

	var $aw = document.getElementById('aw');
		function awtest(){
			var $awaw = document.getElementsByClassName("js-card__content--is-selected");
			

			for(var i=0; i<$awaw.length; i++) {
			  console.log($awaw[i].getAttribute("id"));

			}
			
		}
</script>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin | Dashboard</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<?php include_once("header.php");?>
	<main>
		<div class="query voted-query">
			<span class="query__heading">Voted | </span><span class="query__count">43 of 102</span>
			<div class="bar-graph query__graph">
				<div class="bar-graph__value bar-graph__value--red">100%</div>
			</div>
		</div>
		<br/>
		<div class="query inline-query">
			<div class="query__inline-items">
				<span class="query__heading">CASE | </span><span class="query__count">43 of 102</span>
				<div class="bar-graph query__graph">
					<div class="bar-graph__value bar-graph__value--blue">100%</div>
				</div>
			</div>
			<div class="query__inline-items">
				<span class="query__heading">CBIT | </span><span class="query__count">43 of 102</span>
				<div class="bar-graph query__graph">
					<div class="bar-graph__value bar-graph__value--green">100%</div>
				</div>
			</div>
			<div class="query__inline-items">
				<span class="query__heading">Voted | </span><span class="query__count">43 of 102</span>
				<div class="bar-graph query__graph">
					<div class="bar-graph__value bar-graph__value--orange">100%</div>
				</div>
			</div>
		</div>
		<br/>
		<div class="query by-department-query">
			<span class="query__heading">Votes by Department | </span>
			<span class="department query__subheading">
				<div class="department__icon department__icon--blue"></div>
				<span class="department__name">&nbsp;College of Arts, Sciences and Education</span>
				<div class="department__icon department__icon--green"></div>
				<span class="department__name">&nbsp;College of Business and Information Technology</span>
				<div class="department__icon department__icon--orange"></div>
				<span class="department__name">&nbsp;College of Nursing</span>
			</span>
			<div class="bar-graph query__graph query__graph--inline-graph-value">
				<div class="bar-graph__value bar-graph__value--blue">100%</div>
				<div class="bar-graph__value bar-graph__value--green">100%</div>
				<div class="bar-graph__value bar-graph__value--orange">100%</div>
			</div>
		</div>
		<br/>
	</main>
</body>
</html>
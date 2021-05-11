<?php
require_once('../includes/dbOperations.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
	header("Location: login.php");
}
 $getStudentWhoVoted = (new dbOperations())->getStudentWhoVoted();
 $getStudentWhoDidNotVote = (new dbOperations())->getStudentWhoDidNotVote();

function getPercentage($a,$b){
	return $percentage = ($a / $b) * 100;

}
function displayImage($student_image){
	if (empty($student_image)) {
		echo "../images/default.jpg";
	}else {
		echo "../uploads/".$student_image;
	}
}
$votedPercentage = getPercentage($getStudentWhoVoted,$getStudentWhoDidNotVote);

$votedCASE = (new dbOperations())->getStudentWhoVotedPerDepartment("CASE");
$votedCBIT = (new dbOperations())->getStudentWhoVotedPerDepartment("CBIT");
$votedCON = (new dbOperations())->getStudentWhoVotedPerDepartment("CON");

$didNotvoteCASE = (new dbOperations())->getStudentWhoDidNotVotePerDepartment("CASE");
$didNotvoteCBIT = (new dbOperations())->getStudentWhoDidNotVotePerDepartment("CBIT");
$didNotvoteCON = (new dbOperations())->getStudentWhoDidNotVotePerDepartment("CON");

$votedPercentageCASE = getPercentage($votedCASE,$didNotvoteCASE);
$votedPercentageCBIT = getPercentage($votedCBIT,$didNotvoteCBIT);
$votedPercentageCON = getPercentage($votedCON,$didNotvoteCON);

$votedPercentageTotalCASE = getPercentage($votedCASE,$getStudentWhoDidNotVote); 
$votedPercentageTotalCBIT = getPercentage($votedCBIT,$getStudentWhoDidNotVote); 
$votedPercentageTotalCON = getPercentage($votedCON,$getStudentWhoDidNotVote); 


$getHighestVotePresident = (new dbOperations())->getHighestVotePresident();
$getHighestVoteVicePresident = (new dbOperations())->getHighestVoteVicePresident();

$getHighestVoteGovernorCASE = (new dbOperations())->getHighestVoteGovernor("CASE");
$getHighestVoteGovernorCBIT = (new dbOperations())->getHighestVoteGovernor("CBIT");
$getHighestVoteGovernorCON = (new dbOperations())->getHighestVoteGovernor("CON");

$getHighestVoteViceGovernorCASE = (new dbOperations())->getHighestVoteViceGovernor("CASE");
$getHighestVoteViceGovernorCBIT = (new dbOperations())->getHighestVoteViceGovernor("CBIT");
$getHighestVoteViceGovernorCON = (new dbOperations())->getHighestVoteViceGovernor("CON");

$getHighestVoteCongressBEED = (new dbOperations())->getHighestVoteCongress("BEED");
$getHighestVoteCongressBPED = (new dbOperations())->getHighestVoteCongress("BPED");
$getHighestVoteCongressBSP = (new dbOperations())->getHighestVoteCongress("BSP");
$getHighestVoteCongressBSED = (new dbOperations())->getHighestVoteCongress("BSED");
$getHighestVoteCongressBSNED= (new dbOperations())->getHighestVoteCongress("BSNED");
$getHighestVoteCongressBSNEDGEN = (new dbOperations())->getHighestVoteCongress("BSNEDGEN");

$getHighestVoteCongressBSA = (new dbOperations())->getHighestVoteCongress("BSA");
$getHighestVoteCongressBSAIS = (new dbOperations())->getHighestVoteCongress("BSAIS");
$getHighestVoteCongressBSBA = (new dbOperations())->getHighestVoteCongress("BSBA");
$getHighestVoteCongressBSE = (new dbOperations())->getHighestVoteCongress("BSE");
$getHighestVoteCongressBSHM = (new dbOperations())->getHighestVoteCongress("BSHM");
$getHighestVoteCongressBSIT = (new dbOperations())->getHighestVoteCongress("BSIT");
$getHighestVoteCongressBSMA = (new dbOperations())->getHighestVoteCongress("BSMA");
$getHighestVoteCongressBSTM = (new dbOperations())->getHighestVoteCongress("BSTM");

$getHighestVoteCongressBSN = (new dbOperations())->getHighestVoteCongress("BSN");


?>
<!DOCTYPE html>
<html>
<head>
	<title>SPUD Admin | Dashboard</title>
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/dashboard.css">
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
					<li style="color: #4f4d4c;"><a href="dashboard.php">DASHBOARD</a></li>
					<li><a href="election.php">ELECTION</a></li>
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
		<div class="all-vote query-wrap">
			<div class="query-name inline-block">Voted</div>
			<div class="query-count inline-block">| <?php echo  $getStudentWhoVoted; ?> of <?php echo  $getStudentWhoDidNotVote; ?> </div>
			<div class="query-progress-bar">
				<div class="query-bar">
					<div style="background-color: #f44336; width: <?php echo $votedPercentage."%;";?>" class="query-progress">
						<?php echo $showPercentage = ($votedPercentage >= 3) ? round($votedPercentage)."%" : "<br/>";?>
					</div>
				</div>
			</div>
		</div>
		<div id="department-votes-con">
			<div class="case department-votes query-wrap inline-block">
				<div class="query-name inline-block">CASE</div>
				<div class="query-count inline-block">| <?php echo  $votedCASE; ?> of <?php echo  $didNotvoteCASE; ?> </div> 
				<div class="query-progress-bar">
					<div class="query-bar">
						<div style="background-color: #3297D3; width: <?php echo $votedPercentageCASE."%;";?>" class="query-progress">
						<?php echo $showPercentageCASE = ($votedPercentageCASE >= 6) ? round($votedPercentageCASE)."%" : "<br/>";?>
						</div>
					</div>
				</div>
			</div>
			<div class="cbit department-votes query-wrap inline-block">
				<div class="query-name inline-block">CBIT</div>
				<div class="query-count inline-block">| <?php echo  $votedCBIT; ?> of <?php echo  $didNotvoteCBIT; ?></div>
				<div class="query-progress-bar">
					<div class="query-bar">
						<div style="background-color: #2FB47E; width: <?php echo $votedPercentageCBIT."%;";?>" class="query-progress">
							<?php echo $showPercentageCBIT = ($votedPercentageCBIT >= 6) ? round($votedPercentageCBIT)."%" : "<br/>";?>
						</div>
					</div>
				</div>
			</div>
			<div class="con department-votes query-wrap inline-block">
				<div class="query-name inline-block">CON</div>
				<div class="query-count inline-block">| <?php echo  $votedCON; ?> of <?php echo  $didNotvoteCON; ?></div>
				<div class="query-progress-bar">
					<div class="query-bar">
						<div style="background-color: #E39F48; width: <?php echo $votedPercentageCON."%;";?>"class="query-progress">
							<?php echo $showPercentageCON = ($votedPercentageCON >= 6) ? round($votedPercentageCON)."%" : "<br/>";?>
						</div>
					</div>
				</div>
			</div>

			<div class="votes-by-deparment query-wrap">
				<div class="query-name inline-block">Votes by Department</div>
				<div class="query-count inline-block"> | 
					<div style="background-color: #3297D3;" class="colored-box inline-block"></div> College of Arts, Sciences and Education
					<div style="background-color: #2FB47E;" class="colored-box inline-block"></div> College of Business and Information Technology
					<div style="background-color: #E39F48;" class="colored-box inline-block"></div> College of Nursing
				</div>
				<div class="query-progress-bar">
					<div class="query-bar">
						<div style="background-color: #3297D3; width: <?php echo $votedPercentageTotalCASE."%;";?>" class="query-progress inline-block">
							<?php echo $showPercentageTotalCASE = ($votedPercentageTotalCASE >= 3) ? round($votedPercentageTotalCASE)."%" : "<br/>";?>
						</div>
						<div style="background-color: #2FB47E; width: <?php echo $votedPercentageTotalCBIT."%;";?>" class="query-progress inline-block">
							<?php echo $showPercentageTotalCBIT = ($votedPercentageTotalCBIT >= 3) ? round($votedPercentageTotalCBIT)."%" : "<br/>";?>
						</div>
						<div style="background-color: #E39F48; width: <?php echo $votedPercentageTotalCON."%;";?>" class="query-progress inline-block">
								<?php echo $showPercentageTotalCON = ($votedPercentageTotalCON >= 3) ? round($votedPercentageTotalCON)."%" : "<br/>";?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="most-votes-con">
			<?php while($rowWinnerPresident = $getHighestVotePresident ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerPresident['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerPresident['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerPresident['student_fname']." ".mb_substr($rowWinnerPresident['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerPresident['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerPresident['department_abbr'];?> - <?php echo $rowWinnerPresident['course_abbr'];?></div>
					<div class="candidate-position">President</div>
				</div>
			<?php }?>
			<?php while($rowWinnerVicePresident = $getHighestVoteVicePresident ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerVicePresident['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerVicePresident['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerVicePresident['student_fname']." ".mb_substr($rowWinnerVicePresident['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerVicePresident['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerVicePresident['department_abbr'];?> - <?php echo $rowWinnerVicePresident['course_abbr'];?></div>
					<div class="candidate-position">Vice President</div>
				</div>
			<?php }?>
			
			<?php while($rowWinnerGovernorCASE = $getHighestVoteGovernorCASE ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerGovernorCASE['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerGovernorCASE['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerGovernorCASE['student_fname']." ".mb_substr($rowWinnerGovernorCASE['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerGovernorCASE['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerGovernorCASE['department_abbr'];?> - <?php echo $rowWinnerGovernorCASE['course_abbr'];?></div>
					<div class="candidate-position">Governor</div>
				</div>
			<?php }?>
			<?php while($rowWinnerGovernorCBIT = $getHighestVoteGovernorCBIT ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerGovernorCBIT['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerGovernorCBIT['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerGovernorCBIT['student_fname']." ".mb_substr($rowWinnerGovernorCBIT['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerGovernorCBIT['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerGovernorCBIT['department_abbr'];?> - <?php echo $rowWinnerGovernorCBIT['course_abbr'];?></div>
					<div class="candidate-position">Governor</div>
				</div>
			<?php }?>
			<?php while($rowWinnerGovernorCON = $getHighestVoteGovernorCON ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerGovernorCON['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerGovernorCON['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerGovernorCON['student_fname']." ".mb_substr($rowWinnerGovernorCON['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerGovernorCON['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerGovernorCON['department_abbr'];?> - <?php echo $rowWinnerGovernorCON['course_abbr'];?></div>
					<div class="candidate-position">Governor</div>
				</div>
			<?php }?>
			
			<?php while($rowWinnerViceGovernorCASE = $getHighestVoteViceGovernorCASE ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerViceGovernorCASE['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerViceGovernorCASE['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerViceGovernorCASE['student_fname']." ".mb_substr($rowWinnerViceGovernorCASE['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerViceGovernorCASE['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerViceGovernorCASE['department_abbr'];?> - <?php echo $rowWinnerViceGovernorCASE['course_abbr'];?></div>
					<div class="candidate-position">Vice Governor</div>
				</div>
			<?php }?>
			<?php while($rowWinnerViceGovernorCBIT = $getHighestVoteViceGovernorCBIT ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerViceGovernorCBIT['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerViceGovernorCBIT['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerViceGovernorCBIT['student_fname']." ".mb_substr($rowWinnerViceGovernorCBIT['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerViceGovernorCBIT['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerViceGovernorCBIT['department_abbr'];?> - <?php echo $rowWinnerViceGovernorCBIT['course_abbr'];?></div>
					<div class="candidate-position">Vice Governor</div>
				</div>
			<?php }?>
			<?php while($rowWinnerViceGovernorCON = $getHighestVoteViceGovernorCON ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerViceGovernorCON['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerViceGovernorCON['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerViceGovernorCON['student_fname']." ".mb_substr($rowWinnerViceGovernorCON['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerViceGovernorCON['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerViceGovernorCON['department_abbr'];?> - <?php echo $rowWinnerViceGovernorCON['course_abbr'];?></div>
					<div class="candidate-position">Vice Governor</div>
				</div>
			<?php }?>

			<?php while($rowWinnerCongressBEED = $getHighestVoteCongressBEED ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBEED['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBEED['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBEED['student_fname']." ".mb_substr($rowWinnerCongressBEED['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBEED['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBEED['department_abbr'];?> - <?php echo $rowWinnerCongressBEED['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBPED = $getHighestVoteCongressBPED ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBPED['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBPED['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBPED['student_fname']." ".mb_substr($rowWinnerCongressBPED['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBPED['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBPED['department_abbr'];?> - <?php echo $rowWinnerCongressBPED['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSP = $getHighestVoteCongressBSP ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSP['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSP['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSP['student_fname']." ".mb_substr($rowWinnerCongressBSP['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSP['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSP['department_abbr'];?> - <?php echo $rowWinnerCongressBSP['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSED = $getHighestVoteCongressBSED ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSED['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSED['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSED['student_fname']." ".mb_substr($rowWinnerCongressBSED['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSED['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSED['department_abbr'];?> - <?php echo $rowWinnerCongressBSED['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSNED = $getHighestVoteCongressBSNED ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSNED['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSNED['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSNED['student_fname']." ".mb_substr($rowWinnerCongressBSNED['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSNED['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSNED['department_abbr'];?> - <?php echo $rowWinnerCongressBSNED['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSA = $getHighestVoteCongressBSA ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSA['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSA['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSA['student_fname']." ".mb_substr($rowWinnerCongressBSA['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSA['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSA['department_abbr'];?> - <?php echo $rowWinnerCongressBSA['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSAIS = $getHighestVoteCongressBSAIS ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSAIS['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSAIS['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSAIS['student_fname']." ".mb_substr($rowWinnerCongressBSAIS['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSAIS['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSAIS['department_abbr'];?> - <?php echo $rowWinnerCongressBSAIS['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSA = $getHighestVoteCongressBSA ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSA['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSA['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSA['student_fname']." ".mb_substr($rowWinnerCongressBSA['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSA['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSA['department_abbr'];?> - <?php echo $rowWinnerCongressBSA['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSE = $getHighestVoteCongressBSE ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSE['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSE['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSE['student_fname']." ".mb_substr($rowWinnerCongressBSE['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSE['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSE['department_abbr'];?> - <?php echo $rowWinnerCongressBSE['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSHM = $getHighestVoteCongressBSHM ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSHM['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSHM['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSHM['student_fname']." ".mb_substr($rowWinnerCongressBSHM['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSHM['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSHM['department_abbr'];?> - <?php echo $rowWinnerCongressBSHM['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSIT = $getHighestVoteCongressBSIT ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSIT['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSIT['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSIT['student_fname']." ".mb_substr($rowWinnerCongressBSIT['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSIT['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSIT['department_abbr'];?> - <?php echo $rowWinnerCongressBSIT['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSMA = $getHighestVoteCongressBSMA ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSMA['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSMA['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSMA['student_fname']." ".mb_substr($rowWinnerCongressBSMA['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSMA['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSMA['department_abbr'];?> - <?php echo $rowWinnerCongressBSMA['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSTM = $getHighestVoteCongressBSTM ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSTM['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSTM['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSTM['student_fname']." ".mb_substr($rowWinnerCongressBSTM['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSTM['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSTM['department_abbr'];?> - <?php echo $rowWinnerCongressBSTM['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
			<?php while($rowWinnerCongressBSN = $getHighestVoteCongressBSN ->fetch_assoc()){?>
				<div class="most-votes-wrap inline-block">
					<div class="total-votes"><?php echo $rowWinnerCongressBSN['vote_count'];?></div>
					<div class="candidate-picture"><img src="<?php echo displayImage($rowWinnerCongressBSN['student_image']);?>" width="100%" height="100%"></div>
					<div class="candidate-name"><?php echo $rowWinnerCongressBSN['student_fname']." ".mb_substr($rowWinnerCongressBSN['student_mname'], 0, 1, 'utf-8').". ".$rowWinnerCongressBSN['student_lname'];?></div>
					<div class="candidate-college-course"><?php echo $rowWinnerCongressBSN['department_abbr'];?> - <?php echo $rowWinnerCongressBSN['course_abbr'];?></div>
					<div class="candidate-position">Congress</div>
				</div>
			<?php }?>
		</div>

	</main>
</body>
</html>
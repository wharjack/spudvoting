<?php
/**
 * 
 */
class dbOperations 
{
	private $con;
	function __construct()
	{
		require_once dirname(__FILE__).'/dbConnect.php';
		$db = new dbConnect();
		$this->con = $db->connect();
	}

	public function getElectionStatus (){
		$stmt = $this->con->prepare("SELECT election_status FROM tbl_elections WHERE election_id = 1");
		$stmt->execute();
		$stmt->bind_result($val);
		$stmt->fetch();
		
		return $val; 
	}
	public function updateElectionStatus($election_status){
		$stmt = $this->con->prepare("UPDATE  tbl_elections SET election_status = ? WHERE election_id = 1");
		$stmt->bind_param("s",$election_status);
		$stmt->execute();
		$this->con->close();

	}

	//Students PHP

	private function isDepartmentCodeExist($code){
		
		$stmt = $this->con->prepare("SELECT `department_code` FROM `tbl_departments` WHERE department_code = ?");
		$stmt->bind_param("s",$code);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
		$this->con->close();
	}
	private function isCourseCodeExist($code){
		
		$stmt = $this->con->prepare("SELECT `course_code` FROM `tbl_courses` WHERE course_code = ?");
		$stmt->bind_param("s",$code);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
		$this->con->close();
	}
	private function isStudentExist($student_number){
		$stmt = $this->con->prepare("SELECT `student_number` FROM `tbl_students` WHERE student_number = ?");
		$stmt->bind_param("s",$student_number);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
		$this->con->close();
	}
	private function getDepartmentIdByCode($code){
		
		$stmt = $this->con->prepare("SELECT `department_id` FROM `tbl_departments` WHERE department_code = ?");
		$stmt->bind_param("s",$code);
		$stmt->execute();
		$stmt->bind_result($val);
		$stmt->fetch();
		
		return $val; 
	}
	private function getCourseIdByCode($code){
		
		$stmt = $this->con->prepare("SELECT `course_id` FROM `tbl_courses` WHERE course_code = ?");
		$stmt->bind_param("s",$code);
		$stmt->execute();
		$stmt->bind_result($val);
		$stmt->fetch();
		return $val; 
	}
	

	public function addStudent($student_number,$student_fname,$student_mname,$student_lname,$department_code,$course_code,$student_status){
		
		if ($this->isStudentExist($student_number)) {
			return $student_number.",".$student_fname.",".$student_mname.",".$student_lname.","."Student Already Exist";
		}else {
			if ($this->isDepartmentCodeExist($department_code)) {
				if ($this->isCourseCodeExist($course_code)) {
					$department_id = $this->getDepartmentIdByCode($department_code);
					$course_id =  $this->getCourseIdByCode($course_code);

					$stmt = $this->con->prepare("INSERT INTO `tbl_students`(`student_number`, `student_fname`,  `student_mname`, `student_lname`,`department_id`, `course_id`, `student_status`, `student_password`) VALUES (?,?,?,?,$department_id,$course_id,?,'spudstudent')");

					$stmt->bind_param("sssss",$student_number,$student_fname,$student_mname,$student_lname,$student_status);
					
					if ($stmt->execute()) {
						return $student_number.",".$student_fname.",".$student_mname.",".$student_lname.","."Student Sucesfully Added";
					}else {
						return $student_number.",".$student_fname.",".$student_mname.",".$student_lname.","."Some Error Occured, Please try again!";
					}


					
				}else {
					return $student_number.",".$student_fname.",".$student_mname.",".$student_lname.","."Invalid Course Code";
				}
			}else {
				return $student_number.",".$student_fname.",".$student_mname.",".$student_lname.","."Invalid Department Code";
			}
		}

		
	}
	public function indexStudents(){
		$stmt = $this->con->prepare("SELECT * FROM `tbl_students`
			LEFT JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			LEFT JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id ");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();

	}
	public function getStudentInfo($search_value){
		$search = '%'.$search_value.'%';

		$stmt = $this->con->prepare("SELECT * FROM tbl_students 
			INNER JOIN tbl_departments ON tbl_departments.department_id = tbl_students.department_id
			INNER JOIN tbl_courses  ON tbl_courses.course_id = tbl_students.course_id
			WHERE tbl_students.student_number LIKE ? OR
			tbl_students.student_fname LIKE ? OR
			tbl_students.student_mname LIKE ? OR
			tbl_students.student_lname LIKE ? OR
			tbl_departments.department_name LIKE ? OR
			tbl_departments.department_abbr LIKE ? OR
			tbl_courses.course_name LIKE ? OR
			tbl_courses.course_abbr LIKE ? ");
		$stmt->bind_param("ssssssss",$search,$search,$search,$search,$search,$search,$search,$search);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function getStudentInfoByNumber($student_number){
		$stmt = $this->con->prepare("SELECT * FROM tbl_students 
			INNER JOIN tbl_departments ON tbl_departments.department_id = tbl_students.department_id
			INNER JOIN tbl_courses  ON tbl_courses.course_id = tbl_students.course_id
			WHERE tbl_students.student_number = ?");
		$stmt->bind_param("s",$student_number);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close(); 
	}
	public function updateStudentInfo($first_name,$middle_name,$last_name,$student_number){

		$stmt = $this->con->prepare("UPDATE  tbl_students SET student_fname = ?,student_mname = ?,student_lname = ? WHERE student_number = ?");
		$stmt->bind_param("sssi",$first_name,$middle_name,$last_name,$student_number);
		$stmt->execute();

		$this->con->close();
	}
	public function updateStudentImage($image,$student_number){

		$stmt = $this->con->prepare("UPDATE  tbl_students SET student_image = ? WHERE student_number = ?");
		$stmt->bind_param("si",$image,$student_number);
		$stmt->execute();

		$this->con->close();
	}
	//Students PHP -----------------------------------------------------------------------------

	//Election PHP
	public function addCandidates($student_number,$student_fname,$student_mname,$student_lname,$position_code){
		if ($this->isStudentExist($student_number)) {

			$student_id = $this->getStudentIdByNumber($student_number);

			if ($this->isCandidateExist($student_id)) {
				return $student_number.",".$student_fname." ".$student_mname." ".$student_lname.""."has already existed in the current election";
			}else {
				if ($this->isPositionCodeExist($position_code)) {
					$position_id = $this->getPositionIdByCode($position_code);

					$stmt = $this->con->prepare("INSERT INTO `tbl_candidates`(`election_id`, `student_id`, `position_id`) VALUES (1,$student_id,$position_id)");
					

					if ($stmt->execute()) {
						return $student_number.",".$student_fname." ".$student_mname." ".$student_lname.","." Sucesfully Added";
					}else {
						
						return $student_number.",".$student_fname." ".$student_mname." ".$student_lname.","."Some Error Occured, Please try again!";
					}

				}else {
					return $student_number.",".$student_fname." ".$student_mname." ".$student_lname.","."Position Code doesn't exist";
				}
			}
		}else {
			return $student_number.",".$student_fname." ".$student_mname." ".$student_lname.","." doesn't exist";
		}

	}
	private function isPositionCodeExist($position_code){
		$stmt = $this->con->prepare("SELECT `position_code` FROM `tbl_positions` WHERE position_code = ?");
		$stmt->bind_param("s",$position_code);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
		$this->con->close();
	}
	private function getPositionIdByCode($position_code){
		$stmt = $this->con->prepare("SELECT `position_id` FROM `tbl_positions` WHERE position_code = ?");
		$stmt->bind_param("s",$position_code);
		$stmt->execute();
		$stmt->bind_result($val);
		$stmt->fetch();
		return $val; 
	}
	private function getStudentIdByNumber($student_number){
		$stmt = $this->con->prepare("SELECT `student_id` FROM `tbl_students` WHERE student_number = ?");
		$stmt->bind_param("s",$student_number);
		$stmt->execute();
		$stmt->bind_result($val);
		$stmt->fetch();
		return $val; 
	}
	private function isCandidateExist($student_id){
		
		$stmt = $this->con->prepare("SELECT * FROM `tbl_candidates` WHERE election_id = 1 AND student_id = ?");
		$stmt->bind_param("i",$student_id);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
		$this->con->close();
	}
	public function indexCandidates(){
		$stmt = $this->con->prepare("SELECT * FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			WHERE tbl_candidates.election_id = 1");
		if ($stmt->execute()) {
			return $stmt->get_result();
		}else {
			return $stmt->error;
		}
		


		$this->con->close();

	}
	public function getCandidatePosition($student_id){
		$stmt = $this->con->prepare("SELECT position_id FROM tbl_candidates WHERE student_id = ?");
		$stmt->bind_param("i",$student_id);
		$stmt->execute();
		$stmt->bind_result($val);
		$stmt->fetch();
		
		return $val;

		$this->con->close();

	}
	public function getCandidateInfo($search_value){
		$search = '%'.$search_value.'%';

		$stmt = $this->con->prepare("SELECT * FROM tbl_candidates
			INNER JOIN tbl_positions ON tbl_positions.position_id = tbl_candidates.position_id
			INNER JOIN tbl_students ON tbl_students.student_id = tbl_candidates.student_id
			INNER JOIN tbl_departments ON tbl_departments.department_id = tbl_students.department_id
			INNER JOIN tbl_courses  ON tbl_courses.course_id = tbl_students.course_id
			WHERE tbl_students.student_number LIKE ? OR
			tbl_students.student_fname LIKE ? OR
			tbl_students.student_mname LIKE ? OR
			tbl_students.student_lname LIKE ? OR
			tbl_departments.department_name LIKE ? OR
			tbl_departments.department_abbr LIKE ? OR
			tbl_courses.course_name LIKE ? OR
			tbl_courses.course_abbr LIKE ? OR
			tbl_positions.position_name LIKE ? 
			AND tbl_candidates.election_id = 1");
		$stmt->bind_param("sssssssss",$search,$search,$search,$search,$search,$search,$search,$search,$search);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}

	public function deleteCandidate($candidate_id){
		$stmt = $this->con->prepare("DELETE FROM tbl_candidates WHERE candidate_id= ?");
		$stmt->bind_param("i",$candidate_id);
		$stmt->execute();

		$this->con->close();

	}
	public function updateCandidatePosition($candidate_id,$position_id){
		$stmt = $this->con->prepare("UPDATE  tbl_candidates SET position_id = ? WHERE candidate_id= ?");
		$stmt->bind_param("ii",$position_id,$candidate_id);
		$stmt->execute();

		$this->con->close();

	}

	//Election PHP -----------------------------------------------------------------------------


	//MAIN Election PHP -----------------------------------------------------------------------------

	public function indexPresidents(){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_positions.position_code = 'P' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexVicePresidents(){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_positions.position_code = 'VP' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexGovernor($department_code){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_departments.department_code = ? AND tbl_positions.position_code = 'G' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexViceGovernor($department_code){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_departments.department_code = ? AND tbl_positions.position_code = 'VG' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexCongress($course_code){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_courses.course_abbr = ? AND tbl_positions.position_code = 'C' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->bind_param("s",$course_code);
		$stmt->execute();
		return $stmt->get_result();


		
		$this->con->close();
	}

	public function indexPresidentsForVote(){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			LEFT JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_positions.position_code = 'P' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexVicePresidentsForVote(){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_positions.position_code = 'VP' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexGovernorForVote($department_code){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_departments.department_code = ? AND tbl_positions.position_code = 'G' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");

		
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexViceGovernorForVote($department_code){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_departments.department_code = ? AND tbl_positions.position_code = 'VG' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");

		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function indexCongressForVote($course_code){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_courses.course_abbr = ? AND tbl_positions.position_code = 'C' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC");

		$stmt->bind_param("s",$course_code);
		$stmt->execute();
		return $stmt->get_result();


		
		$this->con->close();
	}
	//Election PHP -----------------------------------------------------------------------------
	public function loginStudent($student_number,$password){
		$stmt = $this->con->prepare("SELECT * FROM `tbl_students`
			LEFT JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			LEFT JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id WHERE tbl_students.student_number = ? AND tbl_students.student_password = ?");
		$stmt->bind_param("ss",$student_number,$password);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function getCandidateData($candidate_id){
		$stmt = $this->con->prepare("SELECT * FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			WHERE tbl_candidates.candidate_id = ? ");
		$stmt->bind_param("i",$candidate_id);
		$stmt->execute();
		return $stmt->get_result();

		$this->con->close();
	}
	public function submitVote($candidate_id,$student_id){
		$stmt = $this->con->prepare("INSERT INTO `tbl_votes`(`candidate_id`, `student_id`,`election_id`) VALUES (?,?,1)");
		$stmt->bind_param("ii",$candidate_id,$student_id);
		$stmt->execute();
		$this->con->close();
	}
	
	public function getStudentVotes($student_id){
		
		$stmt = $this->con->prepare("SELECT * FROM `tbl_votes` WHERE election_id = 1  AND student_id = ?");
		$stmt->bind_param("i",$student_id);
		$stmt->execute();
		return 	$stmt->get_result();
		$this->con->close();
	}
	public function resetPassword($password,$student_id){
		$stmt = $this->con->prepare("UPDATE `tbl_students` SET student_password = ? WHERE student_id = ?");
		$stmt->bind_param("si",$password,$student_id);
		$stmt->execute();
		$this->con->close();
	}
	public function getStudentWhoVotedPerDepartment($department_code){
		$stmt = $this->con->prepare("SELECT * FROM `tbl_votes`
			LEFT JOIN tbl_students ON tbl_votes.student_id = tbl_students.student_id
			LEFT JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			WHERE tbl_votes.election_id = 1 AND tbl_departments.department_code = ? GROUP BY tbl_votes.student_id");
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows;
		$this->con->close();
	}
	public function getStudentWhoDidNotVotePerDepartment($department_code){
		$stmt = $this->con->prepare("SELECT * FROM tbl_students LEFT JOIN tbl_departments
			ON tbl_students.department_id = tbl_departments.department_id WHERE tbl_students.student_id NOT IN (SELECT student_id FROM tbl_votes WHERE election_id = 1) AND tbl_students.student_status = 'Studying' AND tbl_departments.department_code = ? ");
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows;
		$this->con->close();
	}
	public function getStudentWhoVoted (){
		$stmt = $this->con->prepare("SELECT * FROM `tbl_votes`
			LEFT JOIN tbl_students ON tbl_votes.student_id = tbl_students.student_id
			WHERE tbl_votes.election_id = 1 AND tbl_students.student_status = 'Studying'  GROUP BY tbl_votes.student_id");
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows;
		$this->con->close();
	}
	public function getStudentWhoDidNotVote (){
		$stmt = $this->con->prepare("SELECT * FROM tbl_students WHERE student_id NOT IN (SELECT student_id FROM tbl_votes WHERE election_id = 1) AND student_status = 'Studying'");
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows;
		$this->con->close();
	}
	public function getHighestVotePresident(){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			LEFT JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_positions.position_code = 'P' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC LIMIT 1");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function getHighestVoteVicePresident(){
		$stmt = $this->con->prepare("SELECT *,tbl_candidates.candidate_id,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			LEFT JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_positions.position_code = 'VP' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC LIMIT 1");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}

	public function getHighestVoteGovernor($department_code){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_departments.department_code = ? AND tbl_positions.position_code = 'G' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC LIMIT 1");
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function getHighestVoteViceGovernor($department_code){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_departments.department_code = ? AND tbl_positions.position_code = 'VG' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC LIMIT 1");
		$stmt->bind_param("s",$department_code);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
	public function getHighestVoteCongress($course_code){
		$stmt = $this->con->prepare("SELECT *,COUNT(tbl_votes.candidate_id) AS vote_count FROM tbl_candidates
			INNER JOIN `tbl_students` ON tbl_candidates.student_id = tbl_students.student_id
			INNER JOIN tbl_courses ON tbl_students.course_id = tbl_courses.course_id
			INNER JOIN tbl_departments ON tbl_students.department_id = tbl_departments.department_id
			INNER JOIN tbl_positions ON tbl_candidates.position_id = tbl_positions.position_id 
			LEFT JOIN tbl_votes ON tbl_candidates.candidate_id = tbl_votes.candidate_id 
			WHERE tbl_candidates.election_id = 1 AND tbl_courses.course_abbr = ? AND tbl_positions.position_code = 'C' GROUP BY tbl_candidates.candidate_id ORDER BY vote_count DESC LIMIT 1");
		$stmt->bind_param("s",$course_code);
		$stmt->execute();
		return $stmt->get_result();


		
		$this->con->close();
	}
	public function deleteCandidates(){
		$stmt = $this->con->prepare("DELETE FROM tbl_candidates");
		$stmt->execute();
	}
	public function deleteVotes(){
		$stmt = $this->con->prepare("DELETE FROM tbl_votes");
		$stmt->execute();
	}
	public function addAdmin($admin_name,$admin_username,$admin_password,$admin_type){
		if ($this->IsAdminExist($admin_username)) {
			return "Username already existed.";
		}else {
			$stmt = $this->con->prepare("INSERT INTO `tbl_admins`(`admin_name`, `admin_username`, `admin_password`, `admin_type`) VALUES (?,?,?,?)");	
			$stmt->bind_param("ssss",$admin_name,$admin_username,$admin_password,$admin_type);
			$stmt->execute();
			$this->con->close();
			return "Sucessfully created.";
		}
	}

	private function IsAdminExist($admin_username){
		$stmt = $this->con->prepare("SELECT `admin_username` FROM `tbl_admins` WHERE admin_username = ?");
		$stmt->bind_param("s",$admin_username);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
		$this->con->close();
	}
	public function indexAdmin(){
		$stmt = $this->con->prepare("SELECT * FROM `tbl_admins");
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();

	}
	public function adminLogin($admin_username,$admin_password){
		$stmt = $this->con->prepare("SELECT * FROM `tbl_admins` WHERE admin_username = ? AND admin_password = ? ");
		$stmt->bind_param("ss",$admin_username,$admin_password);
		$stmt->execute();
		return $stmt->get_result();
		$this->con->close();
	}
}	
?>
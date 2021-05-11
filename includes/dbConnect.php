<?php

class dbConnect
{
	private $con;
	function __construct()
	{
		
	}
	function connect(){
		include_once dirname(__FILE__).'/config.php';
		$this->con = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) {
			echo "Failed to connecto to the Database".mysqli_connect_errno();
		}
		return $this->con;
	}
}

?>
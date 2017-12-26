<?php

/*

	This function returns in json an array containing the last event, with its ID, time, text, event_type, and current time :
	
	Should return something like this:
	
	{"recordsTemp":"40","recordsDetection":"2017-12-17 13:09:46"}
	
	
	
			
	NB : json validate : https://jsonlint.com/

*/

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	$myhost = "192.168.0.147"; 			
	include 'connect-db.php';
	
	//	echo $_SERVER['REQUEST_URI'];
	$defaultTimeZone='UTC';
	if(date_default_timezone_get()!=$defaultTimeZone) date_default_timezone_set($defaultTimeZone);
	
	function _date($format="r", $timestamp=false, $timezone=false)
	{
		$userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'GMT');
		$gmtTimezone = new DateTimeZone('GMT');
		$myDateTime = new DateTime(($timestamp!=false?date("r",(int)$timestamp):date("r")), $gmtTimezone);
		$offset = $userTimezone->getOffset($myDateTime);
		return date($format, ($timestamp!=false?(int)$timestamp:$myDateTime->format('U')) + $offset);
	}
	
	class Event {
		public $id;
		public $time;
		public $text;
		public $type;
		
		// Assigning the values
		public function __construct($id, $time, $text, $type) {
		  $this->id = $id;
		  $this->time = $time;
		  $this->text = $text;
		  $this->type = $type;
		}
		
		// Creating a method (function tied to an object)
		public function test() {
		  return "Hello, this is this event : " . $this->id . " " . $this->time . " !";
		}
	}


	function getLastEvent($myhost) {
		
		$query = "
		SELECT event_id, event_text, event_time, event_type
		FROM event
		ORDER BY event_id DESC
		LIMIT 1
		";
		//			echo $query;
		
		
		global $myhost;
		include 'connect-db.php';
		$conn = new mysqli($dbhost,$dbuser,$dbpass,$mydb);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		if (!$conn->set_charset("utf8")) {
			printf("Error with charset utf8 : %s\n", $conn->error);
			exit();
		} 

		$result = $conn->query($query);
		if (!$result) {
			die ("problem : ".$conn->error);
		}
		$row = $result->fetch_assoc();
		$time = $row["event_time"];
		$conn->close();
		return $time; 
	}


	function getLastUpdateTemp($myhost) {
		
		global $myhost;
		
		$query = "
		SELECT temp_id, temp_time, temp_temp 
		FROM temp
		ORDER BY temp_id DESC
		LIMIT 1
		";
		//			echo $query;
		
		
		
		include 'connect-db.php';
		$conn = new mysqli($dbhost,$dbuser,$dbpass,$mydb);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		if (!$conn->set_charset("utf8")) {
			printf("Error with charset utf8 : %s\n", $conn->error);
			exit();
		} 

		$result = $conn->query($query);
		if (!$result) {
			die ("problem : ".$conn->error);
		}
		$row = $result->fetch_assoc();
		$time = $row["temp_time"];
		$conn->close();
		return $time; 
	}



	//==========================================================================================================================
	//==========================================================================================================================
	//==========================================================================================================================
	
	$currTime = _date("Y-m-d H:i:s", false, 'Europe/Paris');
	
	//$myhost = "localhost";
	$myhost = "192.168.0.147";
	
	if(isset($_GET['myhost'])) { $myhost = $_GET['myhost']; }
	
	
/*	
	$outpTemp = getLastUpdateTemp($myhost);
	$lastTempTime) = getLastUpdateDetection($myhost);
	$outp ='{"recordsTemp":['.$outpTemp.']';
	$outp = $outp.',"recordsDetection":['.$outpDetection.']';
	$outp = $outp.',"currTime":"'.$currTime.'"';
//	$outp = $outp.',"lastTempTime":"'.$lastTempTime.'"';
*/

	$lastTempTime = getLastUpdateTemp($myhost);
	$outpDetection = getLastUpdateDetection($myhost);

	$outp ='{"recordsTemp":"'.$lastTempTime.'"';
	$outp = $outp.',"recordsDetection":"'.$outpDetection.'"';
	$outp = $outp.',"currTime":"'.$currTime.'"';
/*
*/
	$outp = $outp.'}';

	echo($outp);

	
	?>


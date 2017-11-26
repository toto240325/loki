<?php

/*

adds a test record in DB : 
http://192.168.0.2/loki/test_add_temp.php


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
	
	
	function getLastUpdateDetectionOld($myhost) {
		
		$outp = "";

		$query = "
		SELECT event_id, event_txt, event_time, event_temp 
		FROM logs
		ORDER BY event_id DESC
		LIMIT 1
		";
		//			echo $query;



		$conn = mysql_connect($myhost, $dbuser, $dbpass);
		mysql_set_charset('utf8',$conn);
		$db_selected = mysql_select_db($mydb, $conn);
		if (!$db_selected) { die ('Database access error : ' . mysql_error());}
		$result = mysql_query($query, $conn);
		if(!$result) {
			die("Database query failed: " . mysql_error());
		}
		while ($rs = mysql_fetch_array($result)) {			
			if ($outp != "") {$outp .= ",";}
			
			$time = $rs["event_time"];
			$txt = $rs["event_txt"];
			$temp = $rs["event_temp"];
			$outp .= '{';
			$outp .= '"time":"'.$time.'",';
			$outp .= '"txt":'.json_encode($txt). ',';
			$outp .= '"temp":"'. $temp.'"'; 
			$outp .= '}';
		}
		mysql_close($conn);
		return $outp;				
	}
	
	function getLastUpdateDetection($myhost) {
		
		$query = "
		SELECT event_id, event_text, event_time
		FROM event
		ORDER BY event_id DESC
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
		$time = $row["event_time"];
		$conn->close();
		return $time; 
	}


	function getLastUpdateTemp($myhost) {
		
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


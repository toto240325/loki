<?php

/*

adds a test record in DB : 
http://192.168.0.2/loki/test_add_temp.php


Note : mysql_* deprecated ! use MySQLi instead

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
	
	
	function getLast5Temp($myhost) {
		
		$outp = "";

		$sql = "
		SELECT temp_id, temp_time, temp_temp
		FROM temp
		ORDER BY temp_id DESC
		LIMIT 5
		";
		//			echo $query;

/*
$conn = new mysqli("example.com", "user", "password", "database");
$result = $conn->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = $result->fetch_assoc();
echo htmlentities($row['_message']);
*/

		include 'connect-db.php';
		$conn = new mysqli($dbhost,$dbuser,$dbpass,$mydb);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$result = $conn->query($sql);
		
		if (!$result) {
			die ("problem : ".$conn->error);
		}
		if ($result->num_rows > 0) {
			// output data of each row
				
			while($row = $result->fetch_assoc()) {
				
				
				if ($outp != "") {$outp .= ",";}	
				$id = $row["temp_id"];
				$time = $row["temp_time"];
				$temp = $row["temp_temp"];
				$outp .= '{';
				$outp .= '"id":'.json_encode($id). ',';
				$outp .= '"time":"'.$time.'",';
				$outp .= '"temp":"'. $temp.'"'; 
				$outp .= '}';
				
				//echo "id: " . $row["temp_id"]. " - time: " . $row["temp_time"]. "<br>";
				$temp_time = $row['temp_time'];
				echo htmlentities($row['temp_time'])."\n";
				}
		} else {
			echo "0 results";
		}
		$conn->close();
		return $outp;				
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

	$outp = getLast5Temp($myhost);

	$outp = '{'.$outp.'}';

	echo($outp);

	
	?>


<?php

	/*

	adds a test record in DB : 
	http://192.168.0.2/loki/getEvent.php?type="backup p702"

	To test : 
	http://192.168.0.2/loki/getEvent.php?type="backup p702"&type="test"

	Output example : 
	{"lastEvent":"{"id":"63","time":"2017-11-22 22:07:56","text":"backup p702 to googleDrive via mypc3","type":"backup p702"}"}

	Note : mysql_* deprecated ! use MySQLi instead

	*/

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

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
	
	function getLastEvent() {
		$outp = "";

		$sql = "
		SELECT event_id, event_time, event_text, event_type
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
		$result = $conn->query($sql);

		if (!$result) {
			die ("problem : ".$conn->error);
		}
		
		if ($result->num_rows > 0) {
			// output data of each row
				
			while($row = $result->fetch_assoc()) {
				
				if ($outp != "") {$outp .= ",";}	
				$id = $row["event_id"];
				$time = $row["event_time"];
				$text = $row["event_text"];
				$type = $row["event_type"];
				$outp .= '{';
				$outp .= '"id":'.json_encode($id). ',';
				$outp .= '"time":"'.$time.'",';
				$outp .= '"text":"'. $text.'",'; 
				$outp .= '"type":"'. $type.'"'; 
				$outp .= '}';
				
				//echo "id: " . $row["event_id"]. " - time: " . $row["event_time"]. "<br>";
				$event_time = $row['event_time'];
				//echo htmlentities($row['event_time'])."\n";
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
	$type = "backup p702";
	
	if(isset($_GET['myhost'])) { $myhost = $_GET['myhost']; }
	if(isset($_GET['type'])) { $myhost = $_GET['type']; }


	
	if ($type = "test") {
		$lastEventJs = '{"id":"63","time":"2017-11-22 22:07:56","text":"backup p702 to googleDrive via mypc3","type":"backup p702"}';
	} else {
		$lastEventJs = getLastEvent();
	}
	
	$lastEventJs = getLastEvent();

	$outp ='{'.
	$outp = '"lastEvent":'.$lastEventJs;
	$outp = $outp.'}';

	echo($outp);
	
	?>


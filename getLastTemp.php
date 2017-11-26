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
	
	
	function getLastTemp($myhost) {
		
		$outp = "";

		$sql = "
		SELECT temp_time 
		FROM temp
		ORDER BY temp_id DESC
		LIMIT 1
		";
		//			echo $query;

/*
$mysqli = new mysqli("example.com", "user", "password", "database");
$result = $mysqli->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = $result->fetch_assoc();
echo htmlentities($row['_message']);
*/

		include 'connect-db.php';
		$mysqli = new mysqli($dbhost,$dbuser,$dbpass,$mydb);
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$temp_time = $row['temp_time'];
		echo htmlentities($row['temp_time'])."\n<br>";

		
/*		
		$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$mydb);
		if(! $conn )
		{
			echo "test2 : $dbhost $dbuser & dbpass<p>";
			die('Erreur de connexion : ' . mysqli_connect_error());
			die('Could not connect: ' . mysqli_error($conn));
		}
		#mysqli_select_db($mydb);

		echo "before retval\n<br>";
		$retval = mysqli_query( $conn, $sql );
		echo "after retval : $retval<br>";

		if(! $retval )
		{
		  die('Could not enter data: ' . mysqli_error($conn));
		}
		echo "Entered data successfully\n";
		mysqli_close($conn);
*/
		
/*
		$conn = mysql_connect($myhost, $dbuser, $dbpass);
		mysql_set_charset('utf8',$conn);
		$db_selected = mysql_select_db($mydb, $conn);
		if (!$db_selected) { die ('Database access error : ' . mysql_error());}
		$result = mysql_query($query, $conn);
		if(!$result) {
			die("Database query failed: " . mysql_error());
		}
*/

/*
		while ($rs = mysql_fetch_array($result)) {			
			if ($outp != "") {$outp .= ",";}
			
			$time = $rs["log_time"];
			$txt = $rs["log_txt"];
			$temp = $rs["log_temp"];
			$outp .= '{';
			$outp .= '"time":"'.$time.'",';
			$outp .= '"txt":'.json_encode($txt). ',';
			$outp .= '"temp":"'. $temp.'"'; 
			$outp .= '}';
		}
*/

		$mysqli->close();
		return $temp_time;				
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

	$lastTempTime = getLastTemp($myhost);

	
	$outp ='{"recordsTemp":"'.$lastTempTime.'"';
	$outp = $outp.',"recordsDetection":['.$lastTempTime.']';
	$outp = $outp.',"currTime":"'.$currTime.'"';
/*
*/
	$outp = $outp.'}';

	echo($outp);

	
	?>


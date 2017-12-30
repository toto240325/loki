<?php
	
	/*
		This function returns in json an array containing all the detection times between two dates
		exemple : http://192.168.0.2/loki/getDetectionTimes.php?myFunc=2&from='2017-10-31'&to='2019-12-31'
		
		test it with : http://192.168.0.2/loki/getDetectionTimes.php
		
		result format :
		{"records":["2017-10-31 20:07:06","2017-11-04 19:09:04","2017-11-18 19:06:59"],"from":"'2017-10-31'","to":"'2017-12-31'"}
		
	*/
	
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	function getRecords($from,$to) {
		
		$query = "
		SELECT event_time 
		FROM event
		WHERE 
		event_time >=".$from." and 
		event_time <=".$to."
		ORDER by event_time
		";
		//$conn = mysql_connect('localhost', 'toto', 'Toto!');
		$conn = mysql_connect('192.168.0.147', 'toto', 'Toto!');
		mysql_set_charset('utf8',$conn);
		$db_selected = mysql_select_db('loki', $conn);
		if (!$db_selected) { die ('Database access error : ' . mysql_error());}
		$result = mysql_query($query, $conn);
		if(!$result) {
			die("Database query failed: " . mysql_error());
		}
		$outp = "";
		while ($rs = mysql_fetch_array($result)) {			
			if ($outp != "") {$outp .= ",";}
			$time = $rs["event_time"];
			
			$outp .= '{';
			$outp .= '"time":"'.$time.'"';
			$outp .= '}';
		}
		mysql_close($conn);
		return $outp;				
	}
	
	
	function getRecords2($from,$to) {
		
		$query = "
		SELECT event_time 
		FROM event
		WHERE 
		event_time >=".$from." and 
		event_time <=".$to."
		ORDER by event_time
		";
		//$conn = mysql_connect('localhost', 'root', 'Toto!');
		$conn = mysql_connect('192.168.0.147', 'toto', 'Toto!');
		mysql_set_charset('utf8',$conn);
		$db_selected = mysql_select_db('loki', $conn);
		if (!$db_selected) { die ('Database access error : ' . mysql_error());}
		$result = mysql_query($query, $conn);
		if(!$result) {
			die("Database query failed: " . mysql_error());
		}
		$timesArray = array();
		while ($rs = mysql_fetch_array($result)) {			
			$time = $rs["event_time"];
			$timesArray[] = $time;
		}
		mysql_close($conn);
		return json_encode($timesArray);				
	}
	
	//======================================================================================
	//======================================================================================

	
	$from = "'2016-08-26'";
	if(isset($_GET['from'])) { $from = $_GET['from']; }
	
	$to = "'2019-12-31'";
	if(isset($_GET['to'])) { $to = $_GET['to']; }
	
	$myFunc = "1";
	if(isset($_GET['myFunc'])) { $myFunc = $_GET['myFunc']; }
	
	
	//checkCharSet($conn);
	
	if ($myFunc == "9") {	
		$d1 = "'2017-10-31'";
		$d2 = "'2017-12-31'";
		$outp = '{"records":["2017-10-31 20:07:06","2017-11-04 19:09:04","2017-11-18 19:06:59"],"from":"'.$d1.'","to":"'.$d2.'"}';
		//echo "\noutp : \n<br>"; var_dump($outp);
		
		
	} else {
	
		if ($myFunc == "2") {	
			$outp = getRecords2($from,$to);
			} else {
			$outp = getRecords($from,$to);
		}
		$outp ='{"records":['.$outp.']';
		$outp = $outp.',"from":"'.$from.'"';
		$outp = $outp.',"to":"'.$to.'"';
		$outp = $outp.'}';
	}
		
	echo($outp);
?>

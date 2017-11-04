<html>
	<head>
		<title>Adding Record</title>
	</head>
	<body>
		<?php
			if(isset($_POST['add']))
			{ 
				
				#$hosting = 'local';
				include "connect-db.php";
				$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$mydb);
				
				if(! $conn )
				{
					die('Could not connect: ' . mysqli_error($conn));
				}
				
				if(! get_magic_quotes_gpc() )
				{
					$log_txt = addslashes ($_POST['log_txt']);
					$log_time = addslashes ($_POST['log_time']);
					$log_temp = addslashes ($_POST['log_temp']);
				}
				else
				{
					$log_txt = $_POST['log_txt'];
					$log_time = $_POST['log_time'];
					$log_temp = $_POST['log_temp'];
				}
				
				$myNow = date("Y-m-d H:i:s");
				
				$sql = "INSERT INTO logs ".
				"(log_time,log_txt,log_temp) ".
				"VALUES ".
				"('$log_time','$log_txt','$log_temp')";
				$retval = mysqli_query( $conn,$sql );
				if(! $retval )
				{
					die('Could not enter data: ' . mysqli_error($conn));
				}
				echo "Entered log successfully on $myNow\n";
				mysqli_close($conn);
			}
			else
			{
			?>
			<form method="post" action="<?php $_PHP_SELF ?>">
				<table width="600" border="0" cellspacing="1" cellpadding="2">
					<tr>
						<td width="250">Time</td>
						<td>
							<input name="log_time" type="text" id="log_time" value="2016-04-05 10:00:00">
						</td>
					</tr>
					<tr>
					<td width="250">Txt</td>
					<td>
					<input name="log_txt" type="text" id="log_txt">
					</td>
					</tr>
					<tr>
					<td width="250"> </td>
					</tr>
					<td> </td>
					<tr>
					<td width="250"> </td>
					<td>
					<input name="add" type="submit" id="add" value="Add log">
					</td>
					</tr>
					</table>
					</form>
					<?php
					}
					?>
					</body>
					</html>
					
										
<html>
	<head>
		<title>Add New Record in MySQL Database</title>
	</head>
	<body>
		<?php
			if(isset($_POST['add']))
			{
				
				
				
				#$hosting = 'freehostingeu';
				include "connect-db.php";
				$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $mydb);
				
				if(! $conn )
				{
					die('Could not connect: ' . mysqli_error($conn));
				}
				
				if(! get_magic_quotes_gpc() )
				{
					$temp_time = addslashes ($_POST['temp_time']);
					$temp_temp = addslashes ($_POST['temp_temp']);
				}
				else
				{
					$temp_time = $_POST['temp_time'];
					$temp_temp = $_POST['temp_temp'];
				}
				
				#mysql_select_db($mydb);
				#mysqli_select_db($mydb);
				
				$myNow = date("Y-m-d H:i:s");
				
				$sql = "INSERT INTO temp ".
				"(temp_time,temp_temp) ".
				"VALUES ".
				"('$temp_time','$temp_temp')";
				
				$retval = mysqli_query( $conn, $sql );
				
				if(! $retval )
				{
					die('Could not enter data: ' . mysqli_error($conn));
				}
				
				echo "Entered temp successfully on $myNow\n";
				mysqli_close($conn);
			}
			else
			{
				$a = "test"
				
			?>
			<form method="post" action="<?php $_PHP_SELF ?>">
				<table width="600" border="0" cellspacing="1" cellpadding="2">
					<tr>
						<td width="250">Time</td>
						<td>
							<!-- <input name="temp_time" type="text" id="temp_time" value="2016-04-05 10:00:00"> !-->
							<input name="temp_time" type="text" id="temp_time" value="<?php echo date("Y-m-d H:i:s");?>"/> 
						</td>
					</tr>
					<tr>
						<td width="250">Temp</td>
						<td>
							<input name="temp_temp" type="text" id="temp_temp">
						</td>
					</tr>
					<tr>
						<td width="250"> </td>
						<td> </td>
					</tr>
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


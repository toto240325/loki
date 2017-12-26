<html>
	<head>
		<title>Add New Record in MySQL Database</title>
	</head>
	<body>
		<?php
			if(isset($_POST['add']))  
			{				
				$myhost = "192.168.0.147"; 			
				include "connect-db.php";
				$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $mydb);
				
				if(! $conn )
				{
					die('Could not connect: ' . mysqli_error($conn));
				}
				
				if(! get_magic_quotes_gpc() )
				{
					#$log_time = addslashes ($_POST['log_time']);
					$log_txt = addslashes ($_POST['log_txt']);
				}
				else
				{
					#$log_time = $_POST['log_time'];
					$log_txt = $_POST['log_txt'];
				}
				
				#mysql_select_db($mydb);
				#mysqli_select_db($mydb);
				
				$myNow = date("Y-m-d H:i:s");
				
				$sql = "INSERT INTO log ".
				"(log_time,log_txt) ".
				"VALUES ".
				"('$myNow','$log_txt')";
				
				$retval = mysqli_query( $conn, $sql );
				
				if(! $retval )
				{
					die('Could not enter data: ' . mysqli_error($conn));
				}
				
				echo "Entered log successfully on $myNow\n";
				mysqli_close($conn);
			}
			else
			{
				$a = "test"
				
			?>
			<form method="post" action="<?php $_PHP_SELF ?>">
				<table width="600" border="0" cellspacing="1" cellpadding="2">
					<!--
					<tr>
						<td width="250">Time</td>
						<td>
							<input name="log_time" type="text" id="log_time" value="2016-04-05 10:00:00">
							<input log_time" type="text" id="log_time" value="<?php echo date("Y-m-d H:i:s");?>"/> 
						</td>
					</tr>
					-->
					<tr>
						<td width="250">Txt</td>
						<td>
							<input name="log_txt" type="text" id="log_txt">
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


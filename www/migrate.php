<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
	header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>  
<?php require("header3.php"); ?>

<h3><a href="admin.php">Back To Admin Page</a> | <a href="migrate.php">Refresh This Page</a> </h3>

<?php
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sensors = array();
$result = $conn->query("SELECT sid, sensor_id, sensor_name, sensor_type FROM sensors_info ORDER BY sid ASC");
while ($row = $result->fetch_assoc()) {
	$sid = $row['sid'];
	$sensor_id = $row['sensor_id'];
	$sensor_name = $row['sensor_name'];
	$sensor_type = $row['sensor_type'];

	$sensors[$sid] = array($sensor_id, $sensor_name, $sensor_type);
}
?>

<table align="center" border=1 cellpadding='5' cellspacing='5'>
<form id="migrate_data" name="migrate_data" action="migrate_really.php" method="POST">
<tr><td width="60%" align="right" valign="center">
	<table>
	<tr><td><b><font size=5pt>Source:</font></b></td>
	<td><select name="sensor_source">
	<?php
	foreach ($sensors as $sid => $sensor_info) {
		$sensor_name= $sensor_info[1] ? $sensor_info[1] : $sensor_info[0];
		echo '<option value="'.$sid.'">'.$sensor_name.'</option>';
	}
	?>
	</select></td></tr>

	<tr><td><b><font size=5pt>Destination:</font></b></td>
	<td><select name="sensor_dest">
	<?php
	foreach ($sensors as $sid => $sensor_info) {
		$sensor_name= $sensor_info[1] ? $sensor_info[1] : $sensor_info[0];
		echo '<option value="'.$sid.'">'.$sensor_name.'</option>';
	}
	?>
	</select></td></tr>
	</table>
</td>
<td>
<table>
<tr><td><input type="submit" name="MigrateData" value="Migrate Sensor Data" /></td></tr>
</table>
</td></tr>
</form>
</table>

<?php require("footer.php"); ?>  

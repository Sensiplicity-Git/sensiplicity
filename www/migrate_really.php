<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
	header("location: login.php");
  }
?>
<?php require("header3.php"); ?>
<?php require("header_admin.php"); ?>  

<?php
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST["sensor_source"]) || !isset($_POST["sensor_dest"])) {
	header("Location: migrate.php");
}

$source_sid = $_POST["sensor_source"];
$dest_sid = $_POST["sensor_dest"];

// Query database for sensor id and name of each of the sensors
$source_esc = $conn->real_escape_string($source_sid);
$dest_esc = $conn->real_escape_string($dest_sid);
$source_result = $conn->query("SELECT sensor_id, sensor_name FROM sensors_info WHERE sid = $source_esc");
$dest_result = $conn->query("SELECT sensor_id, sensor_name FROM sensors_info WHERE sid = $dest_esc");
$source_row = $source_result->fetch_assoc();
$dest_row = $dest_result->fetch_assoc();

if (!$source_row || !$dest_row) {
	die("Invalid sid(s): $source_sid, $dest_sid");
}

$source_name = $source_row['sensor_name'] ? $source_row['sensor_name'] : $source_row['sensor_id'];
$dest_name = $dest_row['sensor_name'] ? $dest_row['sensor_name'] : $dest_row['sensor_id'];

?>

<h3><a href="migrate.php">Back to Migrate Page</a></h3>
<br />
<br />
<h1>Migration summary:</h1>
<h2><?php echo htmlspecialchars($source_name); ?> -> <?php echo htmlspecialchars($dest_name); ?></h2>
<br />
<table align="center" border=1 cellpadding='5' cellspacing='5'>
<tr><td width="90%">
	<h1>Data migration is NOT a reversable operation. Do you really want to perform this migration?</h1>
</td>
<td width="10%">
	<form id="migrate_data" name="migrate_data" action="migrate_data.php" method="POST">
	<input type="hidden" name="sensor_source" value="<?php echo htmlspecialchars($source_sid); ?>" />
	<input type="hidden" name="sensor_dest" value="<?php echo htmlspecialchars($dest_sid); ?>" />
	<input type="submit" name="Really Migrate" value="Migrate Data" />
	</form>
</td></tr>
</table>

<?php require("footer.php"); ?>

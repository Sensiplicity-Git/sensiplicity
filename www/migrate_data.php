<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
	header("location: login.php");
  }
?>
<?php require("header3.php"); ?>
<?php require("header_admin.php"); ?>  

<h3><a href="admin.php">Back To Admin Page</a></h3>
<br />

<?php
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST["sensor_source"]) || !isset($_POST["sensor_dest"])) {
	header("Location: migrate.php");
}

// Escape sid fields
$source_sid = $conn->real_escape_string($_POST['sensor_source']);
$dest_sid = $conn->real_escape_string($_POST['sensor_dest']);

// Make sure target sensors exist in the database
$source_result = $conn->query("SELECT * FROM sensors_info WHERE sid = $source_sid");
$dest_result = $conn->query("SELECT * FROM sensors_info WHERE sid = $dest_sid");
$source_row = $source_result->fetch_assoc();
$dest_row = $dest_result->fetch_assoc();

if (!$source_row || !$dest_row) {
	die("Invalid sid(s): $sensor_source_sid, $sensor_dest_sid");
}

// Update the sensors_data table with new sid
$update_result = $conn->query("UPDATE sensors_data SET sid = $dest_sid WHERE sid = $source_sid");

if ($update_result) {  // Successful migration
	echo "<h1>Migration successful</h1>";
}
else {  // Failure
	echo "<h1>Migration failed</h1><br />";
	echo "<h3>Error:</h3><br />";
	echo "<p>".htmlspecialchars($conn->error)."</p>";
}
?>

<?php require("footer.php"); ?>

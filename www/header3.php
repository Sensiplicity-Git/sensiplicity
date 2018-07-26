<?php
$ini_array = parse_ini_file("/opt/sensiplicity/etc/SS-L1.conf");
$config_file = '/opt/sensiplicity/etc/SS-L1.conf';
$config_backup = '/opt/sensiplicity/etc/SS-L1.conf';

$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT value FROM sensors_system WHERE name = 'server_timezone'");
if ($result) {  // Success! If query was unsuccessful then the default (UTC) will be used
	$row = $result->fetch_assoc();
	$tz = $row['value'];

	if (!date_default_timezone_set($tz)) {
		// If the timezone isn't valid, we set the timezone to UTC,
		// otherwise we will get an E_NOTICE every time we use it.
		date_default_timezone_set("UTC");
	}
}

$conn->close();
?>

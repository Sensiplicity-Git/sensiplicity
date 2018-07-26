<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }


require("header3.php");

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sid_sql = "SELECT * FROM sensors_info WHERE sensor_type = 'Alarm Switch'";
$result = $conn->query($sid_sql);
$set_sensor_on = '';
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sensor_limits = json_decode($row["sensor_limits"]);
	$sensor_id = $row["sensor_id"];
	$sensor_state = $row["sensor_state"];
	$sensor_interval = $row["sensor_period"];
	$sensor_status = $row["sensor_limit4"];
	if ($sensor_interval == 0) {
		$sensor_interval = 5;
	}
	if ($sensor_status == "") {
		$sensor_status = "close";
	}
	$set_sensor_on = "".$sensor_id." ".$sensor_status." ".$sensor_interval." ";

	shell_exec("sudo systemctl stop sensiplicity-alarms.service");

	if($sensor_state == "on") {
		#echo "set_sensor_on = ".$set_sensor_on."<br>";
        	shell_exec("ps -aef | grep $sensor_id | awk '{print $2}' | xargs -i++ kill -9 ++");
        	shell_exec("/opt/sensiplicity/bin/alarm-switch-daemon $set_sensor_on >/dev/null 2>/dev/null &");
	} else {
		#echo "set_sensor_off = ".$set_sensor_on."<br>";
        	shell_exec("ps -aef | grep $sensor_id | awk '{print $2}' | xargs -i++ kill -9 ++");
	}
    }
}
$conn->close();


header("Location: list_sensors.php");

?> 


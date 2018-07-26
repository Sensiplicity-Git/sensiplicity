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

$SetAllOn = isset($_REQUEST["SetAllOn"]) ? $_REQUEST["SetAllOn"] : "";
$SetAllOff = isset($_REQUEST["SetAllOff"]) ? $_REQUEST["SetAllOff"] : "";
$ReSetAll = isset($_REQUEST["ReSetAll"]) ? $_REQUEST["ReSetAll"] : "";

if ($ResetSensor == "Reset Sensor") {
	$sql  = "UPDATE sensors_info SET sensor_name = '', sensor_state = 'off', sensor_setup = 'no', ";
	$sql .= "sensor_type = '', sensor_geotag = '', sensor_limits = NULL, sensor_period = NULL, ";
	$sql .= "sensor_email = NULL, sensor_texting = NULL, sensor_comment = '', sensor_limit1 = '', ";
	$sql .= "sensor_limit2 = '', sensor_limit3 = '', sensor_limit4 = ''";
}

if ($SetAllOff == "Set All Off") {
        $sql  = "UPDATE sensors_info SET sensor_state = 'off', sensor_setup = 'no'";
}

if ($SetAllOn == "Set All On") {
        $sql  = "UPDATE sensors_info SET sensor_state = 'on', sensor_setup = 'yes'";
}


$sql .= " WHERE sid != ''";
$conn->query($sql);

$conn->close();

header("Location: update_alarm.php");

?>



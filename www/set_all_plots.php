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

if ($SetAllOn == "Set All On") {
        $sql  = "UPDATE sensors_info SET sensor_state = 'on', sensor_setup = 'yes', sensor_plot = 'on'";
}

if ($SetAllOff == "Set All Off") {
        $sql  = "UPDATE sensors_info SET sensor_plot = 'off'";
}

$sql .= " WHERE sid != ''";


#echo "sql = ".$sql."<br>";

if ($conn->query($sql) === TRUE) {
    #echo "Records added successfully.";
} else {
    #echo "Could not find the entry your requested.";
}

$conn->close();

header("Location: manage_plots.php");
#header("Location: list_sensors.php");

?>



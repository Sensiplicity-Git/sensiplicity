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

$sid = $_REQUEST["sid"];
$sensor_id = $_REQUEST["sensor_id"];
$UpdateSensor = $_REQUEST["Update"];
$sensor_plot = isset($_REQUEST["sensor_plot"]) ? $_REQUEST["sensor_plot"] : "";


$sql  = "UPDATE sensors_info SET ";
$sql .= "sensor_setup = 'yes' ";

if($sensor_plot != "") {
	$sql .= ", sensor_plot = '".$sensor_plot."' ";
}

$sql .= " WHERE sid = ".$sid." ";

#echo "sql = ".$sql."<br>";

if ($conn->query($sql) === TRUE) {
    #echo "Records added successfully.";
} else {
    #echo "Could not find the entry your requested.";
}

$conn->close();

header("Location: manage_plots.php");

?> 


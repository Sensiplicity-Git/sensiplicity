<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }

$ResetSensor = $_GET['ResetAllSensors'];

// echo "ResetSensor = $ResetSensor<br>";

if ($ResetSensor != '') {
	$reset_sensors = "/opt/sensiplicity/bin/reset_sensors";
	exec($reset_sensors);
}

header("Location: list_sensors.php");

?> 


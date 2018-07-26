<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
if($value == "") {
	header("location: login.php");
}

exec('/opt/sensiplicity/bin/find_sensors');
header("Location: list_sensors.php");

?>

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

$UpdateServer = $_REQUEST["UpdateServer"];
$server_hostname = isset($_REQUEST["server_hostname"]) ? $_REQUEST["server_hostname"] : "";
$server_temperature = isset($_REQUEST["server_temperature"]) ? $_REQUEST["server_temperature"] : "";
$server_hightemp = isset($_REQUEST["server_hightemp"]) ? $_REQUEST["server_hightemp"] : "";
$server_lowtemp = isset($_REQUEST["server_lowtemp"]) ? $_REQUEST["server_lowtemp"] : "";
$server_timezone = isset($_REQUEST["server_timezone"]) ? $_REQUEST["server_timezone"] : "";


if($server_hostname != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$server_hostname."' ";
	$sql .= "WHERE name = 'server_hostname' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($server_temperature != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$server_temperature."' ";
        $sql .= "WHERE name = 'server_temperature' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

if($server_lowtemp != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$server_lowtemp."' ";
        $sql .= "WHERE name = 'server_lowtemp' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

if($server_hightemp != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$server_hightemp."' ";
        $sql .= "WHERE name = 'server_hightemp' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

if($server_timezone != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$server_timezone."' ";
	$sql .= "WHERE name = 'server_timezone' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}


#echo "sql = ".$sql."<br>";

$conn->close();

header("Location: admin.php");

?> 


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

$USBDrive = $_REQUEST["USBDrive"];
$usbdrive = isset($_REQUEST["usbdrive"]) ? $_REQUEST["usbdrive"] : "";
$usb_folder = isset($_REQUEST["usb_folder"]) ? $_REQUEST["usb_folder"] : "";
$usb_state = isset($_REQUEST["usb_state"]) ? $_REQUEST["usb_state"] : "";


if($usb_folder != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$usb_folder."' ";
	$sql .= "WHERE name = 'usb_folder' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($usb_state != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$usb_state."' ";
	$sql .= "WHERE name = 'usb_state' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

$conn->close();

header("Location: manage_remote.php");

?> 


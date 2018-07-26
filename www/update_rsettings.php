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

#$UpdateRemoteSetting = $_REQUEST["UpdateRemoteSetting"];
$syncdatanow = isset($_REQUEST["SyncRemoteDataNow"]) ? $_REQUEST["SyncRemoteDataNow"] : "";
$rserver_store = isset($_REQUEST["DataStorage"]) ? $_REQUEST["DataStorage"] : "";
$rserver_sync = isset($_REQUEST["SyncStorage"]) ? $_REQUEST["SyncStorage"] : "";
$rserver_state = isset($_REQUEST["SyncStoreState"]) ? $_REQUEST["SyncStoreState"] : "";


if($rserver_store != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$rserver_store."' ";
	$sql .= "WHERE name = 'rserver_store' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($rserver_state != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$rserver_state."' ";
	$sql .= "WHERE name = 'rserver_state' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($rserver_sync != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$rserver_sync."' ";
        $sql .= "WHERE name = 'rserver_sync' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

$conn->close();

if($syncdatanow == "Syncronize Data Now"){
        exec('/opt/sensiplicity/bin/remote_ssh_server.py > /dev/null &');
}



header("Location: manage_remote.php");

?> 


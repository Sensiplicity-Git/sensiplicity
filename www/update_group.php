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

$AddGroup = isset($_REQUEST["AddGroup"]) ? $_REQUEST["AddGroup"] : "";
$group_add = isset($_REQUEST["group_add"]) ? $_REQUEST["group_add"] : "";
$remove_gid = isset($_REQUEST["remove_gid"]) ? $_REQUEST["remove_gid"] : "";

print "AddGroup = $AddGroup<br>";
print "group_add = $group_add<br>";
print "remove_gid = $remove_gid<br>";

if($group_add != "") {
	$sql  = "INSERT INTO `sensors_groups` ";
	$sql .= "(`gid`, `group_name`) VALUES (NULL, '".$group_add."')";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($remove_gid != "") {
	$sql1 = "SELECT * FROM `sensors_info` WHERE `sensor_group` = '".$remove_gid."'";
	$result1 = $conn->query($sql1);
	if ($result1->num_rows > 0) {
		$error = "Can not remove this group because there are still sensors within this group.<br>";
		$_SESSION['post_error'] = $error;
	} else {
		$sql2  = "DELETE FROM `sensors_groups` WHERE ";
		$sql2 .= "`gid` = '".$remove_gid."'";
		if ($conn->query($sql2) === TRUE) {
		    #echo "Records added successfully.";
		} else {
		    #echo "Could not find the entry your requested.";
		}
	}
}


#echo "sql = ".$sql."<br>";

$conn->close();

header("Location: manage_groups.php");

?> 


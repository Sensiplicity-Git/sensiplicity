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
$ssh_state = isset($_REQUEST["SyncSSHState"]) ? $_REQUEST["SyncSSHState"] : "";
$rserver_hostname = isset($_REQUEST["rserver_hostname"]) ? $_REQUEST["rserver_hostname"] : "";
$rserver_port = isset($_REQUEST["rserver_port"]) ? $_REQUEST["rserver_port"] : "";
$rserver_user = isset($_REQUEST["rserver_user"]) ? $_REQUEST["rserver_user"] : "";
$rserver_pass = isset($_REQUEST["rserver_pass"]) ? $_REQUEST["rserver_pass"] : "";
$rserver_path = isset($_REQUEST["rserver_path"]) ? $_REQUEST["rserver_path"] : "";

$pass_string = $rserver_pass;
$secret_key = "53n51Pl1C1ty_R0ck5";
// Create the initialization vector for added security.
$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
// Encrypt $string
$encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secret_key, $pass_string, MCRYPT_MODE_CBC, $iv);
// Decrypt $string
$decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secret_key, $encrypted_string, MCRYPT_MODE_CBC, $iv);

if($ssh_state != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$ssh_state."' ";
	$sql .= "WHERE name = 'ssh_state' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($rserver_hostname != "") {
	$sql  = "UPDATE sensors_system SET ";
	$sql .= "value = '".$rserver_hostname."' ";
	$sql .= "WHERE name = 'rserver_hostname' ";
	if ($conn->query($sql) === TRUE) {
	    #echo "Records added successfully.";
	} else {
	    #echo "Could not find the entry your requested.";
	}
}

if($rserver_port != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$rserver_port."' ";
        $sql .= "WHERE name = 'rserver_port' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

if($rserver_path != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$rserver_path."' ";
        $sql .= "WHERE name = 'rserver_path' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

if($rserver_user != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$rserver_user."' ";
        $sql .= "WHERE name = 'rserver_user' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

if($rserver_pass != "") {
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$iv."' ";
        $sql .= "WHERE name = 'iv' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
        $sql  = "UPDATE sensors_system SET ";
        $sql .= "value = '".$rserver_pass."' ";
        $sql .= "WHERE name = 'rserver_pass' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}

#echo "sql = ".$sql."<br>";

$conn->close();

header("Location: manage_remote.php");

?> 


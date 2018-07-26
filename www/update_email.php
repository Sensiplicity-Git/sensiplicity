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


$Update = $_GET['Update'];
$TestEmail = $_GET['TestEmail'];
$TestSMS = $_GET['TestSMS'];
$smtp_server = isset($_GET['smtp_server']) ? $_GET['smtp_server'] : $ini_array['smtp_server'];
$smtp_port = isset($_GET['smtp_port']) ? $_GET['smtp_port'] : $ini_array['smtp_port'];
$smtp_login = isset($_GET['smtp_login']) ? $_GET['smtp_login'] : $ini_array['smtp_login'];
$smtp_pass = isset($_GET['smtp_pass']) ? $_GET['smtp_pass'] : $ini_array['smtp_pass'];
$smtp_state = isset($_GET['smtp_state']) ? $_GET['smtp_state'] : $ini_array['smtp_state'];
$sms_state = isset($_GET['sms_state']) ? $_GET['sms_state'] : $ini_array['sms_state'];
$send_address = isset($_GET['send_address']) ? $_GET['send_address'] : $ini_array['send_address'];
$recv_address = isset($_GET['recv_address']) ? $_GET['recv_address'] : $ini_array['recv_address'];
$sms_address = isset($_GET['sms_address']) ? $_GET['sms_address'] : $ini_array['sms_address'];

$password = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $smtp_pass);

$date = system('/bin/date +%s');

$config_backup = $config_backup.".".$date;

if ($Update != '' || $TestSMS != '' || $TestEmail != '') {
	if (!rename($config_file, $config_backup)) {
	    echo "Failed to rename $config_file...<br>";
	}
    	$f = fopen($config_file, 'w') or die("can't open file");

	$conf_data  = "##########################################\n";
	$conf_data .= "# Temperature_Configuration		#\n";
	$conf_data .= "##########################################\n";
	$conf_data .= "low = \"".$ini_array['low']."\"\n";
	$conf_data .= "high = \"".$ini_array['high']."\"\n";
	$conf_data .= "\n";
	$conf_data .= "##########################################\n";
	$conf_data .= "# MySQL_Configuration			#\n";
	$conf_data .= "##########################################\n";
	$conf_data .= "servername = \"".$ini_array['servername']."\"\n";
	$conf_data .= "username = \"".$ini_array['username']."\"\n";
	$conf_data .= "password = \"".$ini_array['password']."\"\n";
	$conf_data .= "dbname = \"".$ini_array['dbname']."\"\n";
	$conf_data .= "\n";
	$conf_data .= "##########################################\n";
	$conf_data .= "# Email_Configuration			#\n";
	$conf_data .= "##########################################\n";
	$conf_data .= "send_address = \"".$send_address."\"\n";
	$conf_data .= "recv_address = \"".$recv_address."\"\n";
	$conf_data .= "sms_address = \"".$sms_address."\"\n";
	$conf_data .= "smtp_login = \"".$smtp_login."\"\n";
	$conf_data .= "smtp_pass = \"".$password."\"\n";
	$conf_data .= "smtp_server = \"".$smtp_server."\"\n";
	$conf_data .= "smtp_port = \"".$smtp_port."\"\n";
	$conf_data .= "\n";

    	fwrite($f, $conf_data);
    	fclose($f);

	if($smtp_state != "") {
	        $sql  = "UPDATE sensors_system SET ";
        	$sql .= "value = '".$smtp_state."' ";
        	$sql .= "WHERE name = 'smtp_state' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }

	if($sms_state != "") {
	        $sql  = "UPDATE sensors_system SET ";
        	$sql .= "value = '".$sms_state."' ";
        	$sql .= "WHERE name = 'sms_state' ";
        if ($conn->query($sql) === TRUE) {
            #echo "Records added successfully.";
        } else {
            #echo "Could not find the entry your requested.";
        }
}


}



}
if ($TestEmail != '') {
	$email_test = "/opt/sensiplicity/bin/email_test ".$smtp_server." ".$smtp_port."  ".$smtp_login." ".$password." ".$send_address." ".$recv_address."";
	exec($email_test);
}
if ($TestSMS != '') {
	$sms_test = "/opt/sensiplicity/bin/sms_test ".$smtp_server." ".$smtp_port."  ".$smtp_login." ".$password." ".$send_address." ".$sms_address."";
	exec($sms_test);
}

header("Location: manage_email.php");

?> 


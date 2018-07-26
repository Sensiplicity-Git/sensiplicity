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


$sid            = $conn->escape_string($_GET["sid"]);
$UpdateSensor   = $conn->escape_string(isset($_GET["Update"])         ? $_GET["Update"]         : "");
$ResetSensor    = $conn->escape_string(isset($_GET["Reset"])          ? $_GET["Reset"]          : "");
$RemoveSensor   = $conn->escape_string(isset($_GET["Remove"])         ? $_GET["Remove"]         : "");
$ToggleSensor   = $conn->escape_string(isset($_GET["Toggle"])         ? $_GET["Toggle"]         : "");
$sensor_name    = $conn->escape_string(isset($_GET["sensor_name"])    ? $_GET["sensor_name"]    : "");
$sensor_state   = $conn->escape_string(isset($_GET["sensor_state"])   ? $_GET["sensor_state"]   : "");
$sensor_setup   = $conn->escape_string(isset($_GET["sensor_setup"])   ? $_GET["sensor_setup"]   : "");
$sensor_type    = $conn->escape_string(isset($_GET["sensor_type"])    ? $_GET["sensor_type"]    : "");
$sensor_error   = $conn->escape_string(isset($_GET["sensor_error"])   ? $_GET["sensor_error"]   : "");
$sensor_geotag  = $conn->escape_string(isset($_GET["sensor_geotag"])  ? $_GET["sensor_geotag"]  : "");
$sensor_group   = $conn->escape_string(isset($_GET["sensor_group"])   ? $_GET["sensor_group"]   : "");
$sensor_period  = $conn->escape_string(isset($_GET["sensor_period"])  ? $_GET["sensor_period"]  : "");
$sensor_email   = $conn->escape_string(isset($_GET["sensor_email"])   ? $_GET["sensor_email"]   : "");
$sensor_texting = $conn->escape_string(isset($_GET["sensor_texting"]) ? $_GET["sensor_texting"] : "");
$sensor_comment = $conn->escape_string(isset($_GET["sensor_comment"]) ? $_GET["sensor_comment"] : "");
$sensor_limit1  = $conn->escape_string(isset($_GET["sensor_limit1"])  ? $_GET["sensor_limit1"]  : "");
$sensor_limit2  = $conn->escape_string(isset($_GET["sensor_limit2"])   ? $_GET["sensor_limit2"]   : "");
$sensor_limit3  = $conn->escape_string(isset($_GET["sensor_limit3"]) ? $_GET["sensor_limit3"] : "");
$sensor_limit4  = $conn->escape_string(isset($_GET["sensor_limit4"]) ? $_GET["sensor_limit4"] : "");

$sensor_id = "";
$sensor_type_db = "";
$geotag = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $sensor_geotag);

if ($sensor_type == "Temperature") {
	$json_array = array();
	$json_array["temp_low"]  = $conn->escape_string($_GET["temp_limit_low"]);
	$json_array["temp_high"] = $conn->escape_string($_GET["temp_limit_high"]);

	$sensor_limits = json_encode($json_array); 
} else if ($sensor_type == "Humidity") {
	$json_array = array();
	$json_array["rh_low"]    = $conn->escape_string($_GET["rh_limit_low"]);
	$json_array["rh_high"]   = $conn->escape_string($_GET["rh_limit_high"]);
	$json_array["temp_low"]  = $conn->escape_string($_GET["temp_limit_low"]);
	$json_array["temp_high"] = $conn->escape_string($_GET["temp_limit_high"]);

	$sensor_limits = json_encode($json_array);
} else if ($sensor_type == "Soil Sensor") {
	$json_array = array();
	$json_array["temp_low"]    = $conn->escape_string($_GET["temp_limit_low"]);
	$json_array["temp_high"]   = $conn->escape_string($_GET["temp_limit_high"]);
	$json_array["rh_low"]      = $conn->escape_string($_GET["rh_limit_low"]);
	$json_array["rh_high"]     = $conn->escape_string($_GET["rh_limit_high"]);
	$json_array["light_low"]   = $conn->escape_string($_GET["light_limit_low"]);
	$json_array["light_high"]  = $conn->escape_string($_GET["light_limit_high"]);
	$json_array["temp0_low"]   = $conn->escape_string($_GET["temp0_limit_low"]);
	$json_array["temp0_high"]  = $conn->escape_string($_GET["temp0_limit_high"]);
	$json_array["temp1_low"]   = $conn->escape_string($_GET["temp1_limit_low"]);
	$json_array["temp1_high"]  = $conn->escape_string($_GET["temp1_limit_high"]);
	$json_array["temp2_low"]   = $conn->escape_string($_GET["temp2_limit_low"]);
	$json_array["temp2_high"]  = $conn->escape_string($_GET["temp2_limit_high"]);
	$json_array["temp3_low"]   = $conn->escape_string($_GET["temp3_limit_low"]);
	$json_array["temp3_high"]  = $conn->escape_string($_GET["temp3_limit_high"]);
	$json_array["moist0_low"]  = $conn->escape_string($_GET["moist0_limit_low"]);
	$json_array["moist0_high"] = $conn->escape_string($_GET["moist0_limit_high"]);
	$json_array["moist1_low"]  = $conn->escape_string($_GET["moist1_limit_low"]);
	$json_array["moist1_high"] = $conn->escape_string($_GET["moist1_limit_high"]);
	$json_array["moist2_low"]  = $conn->escape_string($_GET["moist2_limit_low"]);
	$json_array["moist2_high"] = $conn->escape_string($_GET["moist2_limit_high"]);
	$json_array["moist3_low"]  = $conn->escape_string($_GET["moist3_limit_low"]);
	$json_array["moist3_high"] = $conn->escape_string($_GET["moist3_limit_high"]);

	$sensor_limits = json_encode($json_array);
} else if ($sensor_type == "Emergency Button" || $sensor_type == "Alarm Switch") {
	$json_array = array();
	$json_array["alert_condition"] = $conn->escape_string($_GET["alert_condition"]);

	$sensor_limits = json_encode($json_array);
}

if ($ResetSensor) {
	$sql  = "UPDATE sensors_info SET sensor_name = '', sensor_state = 'off', sensor_setup = 'no', ";
	$sql .= "sensor_geotag = '', sensor_limits = NULL, sensor_period = NULL, ";
	$sql .= "sensor_email = NULL, sensor_texting = NULL, sensor_comment = ''";

	// Legacy emergency button limits fields
	if ($sensor_type == "Emergency Button" || $sensor_type == "Alarm Switch") {
		$sql .= ", sensor_limit1 = '', sensor_limit2 = '', sensor_limit3 = '', sensor_limit4 = ''";
	}
} else if ($RemoveSensor) {
	$sql  = "DELETE FROM sensors_info";
} else if ($UpdateSensor) {
	$sql  = "UPDATE sensors_info SET sensor_name = '".$sensor_name."', sensor_state = '".$sensor_state."', ";
	$sql .= "sensor_setup = 'yes', sensor_type = '".$sensor_type."', sensor_geotag = '".$geotag."', ";
	$sql .= "sensor_limits = '".$sensor_limits."', sensor_period = '".$sensor_period."', ";
	$sql .= "sensor_email = '".$sensor_email."', sensor_texting = '".$sensor_texting."', ";
	$sql .= "sensor_group = '".$sensor_group."', sensor_comment = '".$sensor_comment."'";

	// Legacy emergency button limits fields
	if ($sensor_type == "Emergency Button" || $sensor_type == "Alarm Switch") {
		$sql .= ", sensor_limit1 = '".$sensor_limit1."', sensor_limit2 = '".$sensor_limit2."', ";
		$sql .= "sensor_limit3 = '".$sensor_limit3."', sensor_limit4 = '".$sensor_limit4."'";
	}
} else if ($ToggleSensor) {
	$result = $conn->query("SELECT sensor_state FROM sensors_info WHERE sid = ".$sid.";");
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$state = $row["sensor_state"];

		if ($state == "on") $next_state = "off";
		else $next_state = "on";

		$sql = "UPDATE sensors_info SET sensor_state = '".$next_state."'";
	}
}

$sql .= " WHERE sid = ".$sid.";";

$conn->query($sql);
$conn->close();

header("Location: update_alarm.php");

?> 


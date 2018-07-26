<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>  
<?php require("header3.php"); ?>

<h3><a href="list_sensors.php">Back To List Sensors Page</a></h3>
<form id="sensors_update" name="sensor_update" action="update_sensor.php" method="GET">
<center>
<h3>
<table border=1 cellpadding='5' cellspacing='5'>
<?php

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sid = $conn->escape_string($_GET['sid']);
$sensor_type_select = isset($_REQUEST['sensor_type_select']) ? $_REQUEST['sensor_type_select'] : '';


$temp_type = "Fahrenheit";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_temperature'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["value"] == "Celsius") {
                $temp_type = "&#176;C";
        }
        else {
                $temp_type = "&#176;F";
        }
    }
}

$temp_low = "";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_lowtemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["value"] != "") {
                $temp_low = $row["value"];
        }
        else {
                $temp_low = "-300";
        }
    }
}

$temp_high = "";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_hightemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["value"] != "") {
                $temp_high = $row["value"];
        }
        else {
                $temp_high = "3000";
        }
    }
}





$sql = "SELECT * FROM sensors_info WHERE sid = ".$sid."";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td align='center'>SID</td><td align='center'>" . $row["sid"]. "</td></tr>";
	echo "<tr><td align='center'>Sensor ID</td><td align='center'>" . $row["sensor_id"]. "</td></tr>";

        echo "<tr><td align='center'>Type</td>";
	echo "<td align='center'>";
        echo "<select id='sensor_type' name='sensor_type' onchange='changetype(this)'>";
        echo "<option value=''></option>";
	if($row["sensor_type"] != "") {
		$sensor_type_select = $row["sensor_type"];
	}
	else if (isset($_GET["sensor_type"])) {
		$sensor_type_select = $_GET["sensor_type"];
	}
        echo "<option value='Temperature'"; if ($sensor_type_select == "Temperature") { echo " selected='selected'";} echo ">Temperature</option>";
        echo "<option value='Humidity'"; if ($sensor_type_select == "Humidity") { echo "selected='selected'";} echo ">Temperature/Humidity</option>";
        echo "<option value='Soil Sensor'"; if ($sensor_type_select == "Soil Sensor") { echo "selected='selected'";} echo ">Soil Sensor</option>";
        echo "<option value='Alarm Switch'"; if ($sensor_type_select == "Alarm Switch") { echo "selected='selected'";} echo ">Open/Close Alarm</option>";
        echo "</select></td></tr>";

	echo "<tr><td align='center'>Name</td><td align='center'><input type='text' value='" . $row["sensor_name"]. "' name='sensor_name'></td></tr>";

	echo "<tr><td align='center'>Group</td>";
	echo "<td align='center'>";

	$sql2 = "SELECT * FROM sensors_groups";
	$result2 = $conn->query($sql2);
	if ($result2->num_rows > 0) {
	    echo "<select id='sensor_group' name='sensor_group'>";
	    while($row2 = $result2->fetch_assoc()) {
		if ($row["sensor_group"] == $row2["gid"]) {
			echo "<option value='".$row2["gid"]."' selected='selected'>".$row2["group_name"]."</option>";
		} else {
			echo "<option value='".$row2["gid"]."'>".$row2["group_name"]."</option>";
		}
	   }
	   echo "</select></td></tr>";
	}



	echo "<tr><td align='center'>State</td>";
	echo "<td align='center'>";
	echo "<select id='sensor_state' name='sensor_state'>";
	if ($row["sensor_state"] == "on") {
		echo "<option value='on' selected='selected'>On</option>";
		echo "<option value='off'>Off</option>";
	} else {
		echo "<option value='off' selected='selected'>Off</option>";
		echo "<option value='on'>On</option>";
	}
	echo "</select></td></tr>";


        echo "<tr><td align='center'>Geo-Tag</td><td align='center'><input type='text' value='" . $row["sensor_geotag"]. "' name='sensor_geotag'></td></tr>";

	// Decode JSON data in sensor_limits fields into an assoc array with max depth 2
	$sensor_limits = json_decode($row["sensor_limits"], true, 2);

	if ($sensor_type_select == "Temperature") {
		$temp_limit_low  = isset($sensor_limits["temp_low"])  ? $sensor_limits["temp_low"]  : "";
		$temp_limit_high = isset($sensor_limits["temp_high"]) ? $sensor_limits["temp_high"] : "";

		echo "<tr><td align='center'>Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp_limit_low."' name='temp_limit_low'>".$temp_type;
		echo "</td></tr>";

		echo "<tr><td align='center'>High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp_limit_high."' name='temp_limit_high'>".$temp_type;
		echo "</td></tr>";
	} else if ($sensor_type_select == "Humidity") {
		$temp_limit_low  = isset($sensor_limits["temp_low"])  ? $sensor_limits["temp_low"]  : "";
		$temp_limit_high = isset($sensor_limits["temp_high"]) ? $sensor_limits["temp_high"] : "";
		$rh_limit_low    = isset($sensor_limits["rh_low"])    ? $sensor_limits["rh_low"]    : "";
		$rh_limit_high   = isset($sensor_limits["rh_high"])   ? $sensor_limits["rh_high"]   : "";

		echo "<tr><td align='center'>Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp_limit_low."' name='temp_limit_low'>".$temp_type;
		echo "</td></tr>";

		echo "<tr><td align='center'>High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp_limit_high."' name='temp_limit_high'>".$temp_type;
		echo "</td></tr>";

		echo "<tr><td align='center'>Low Humidity Limit</td>";
		echo "<td align='center'><input type='text' value='".$rh_limit_low."' name='rh_limit_low'>%";
		echo "</td></tr>";

		echo "<tr><td align='center'>High Humidity Limit</td>";
		echo "<td align='center'><input type='text' value='".$rh_limit_high."' name='rh_limit_high'>%";
		echo "</td></tr>";
	} else if ($sensor_type_select == "Soil Sensor") {
		$temp_limit_low    = isset($sensor_limits["temp_low"])    ? $sensor_limits["temp_low"]    : "";
		$temp_limit_high   = isset($sensor_limits["temp_high"])   ? $sensor_limits["temp_high"]   : "";
		$rh_limit_low      = isset($sensor_limits["rh_low"])      ? $sensor_limits["rh_low"]      : "";
		$rh_limit_high     = isset($sensor_limits["rh_high"])     ? $sensor_limits["rh_high"]     : "";
		$light_limit_low   = isset($sensor_limits["light_low"])   ? $sensor_limits["light_low"]   : "";
		$light_limit_high  = isset($sensor_limits["light_high"])  ? $sensor_limits["light_high"]  : "";
		$temp0_limit_low   = isset($sensor_limits["temp0_low"])   ? $sensor_limits["temp0_low"]   : "";
		$temp0_limit_high  = isset($sensor_limits["temp0_high"])  ? $sensor_limits["temp0_high"]  : "";
		$temp1_limit_low   = isset($sensor_limits["temp1_low"])   ? $sensor_limits["temp1_low"]   : "";
		$temp1_limit_high  = isset($sensor_limits["temp1_high"])  ? $sensor_limits["temp1_high"]  : "";
		$temp2_limit_low   = isset($sensor_limits["temp2_low"])   ? $sensor_limits["temp2_low"]   : "";
		$temp2_limit_high  = isset($sensor_limits["temp2_high"])  ? $sensor_limits["temp2_high"]  : "";
		$temp3_limit_low   = isset($sensor_limits["temp3_low"])   ? $sensor_limits["temp3_low"]   : "";
		$temp3_limit_high  = isset($sensor_limits["temp3_high"])  ? $sensor_limits["temp3_high"]  : "";
		$moist0_limit_low  = isset($sensor_limits["moist0_low"])  ? $sensor_limits["moist0_low"]  : "";
		$moist0_limit_high = isset($sensor_limits["moist0_high"]) ? $sensor_limits["moist0_high"] : "";
		$moist1_limit_low  = isset($sensor_limits["moist1_low"])  ? $sensor_limits["moist1_low"]  : "";
		$moist1_limit_high = isset($sensor_limits["moist1_high"]) ? $sensor_limits["moist1_high"] : "";
		$moist2_limit_low  = isset($sensor_limits["moist2_low"])  ? $sensor_limits["moist2_low"]  : "";
		$moist2_limit_high = isset($sensor_limits["moist2_high"]) ? $sensor_limits["moist2_high"] : "";
		$moist3_limit_low  = isset($sensor_limits["moist3_low"])  ? $sensor_limits["moist3_low"]  : "";
		$moist3_limit_high = isset($sensor_limits["moist3_high"]) ? $sensor_limits["moist3_high"] : "";

		echo "<tr><td align='center'>Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp_limit_low."' name='temp_limit_low'>".$temp_type;
		echo "</td></tr>";

		echo "<tr><td align='center'>High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp_limit_high."' name='temp_limit_high'>".$temp_type;
		echo "</td></tr>";

		echo "<tr><td align='center'>Low Humidity Limit</td>";
		echo "<td align='center'><input type='text' value='".$rh_limit_low."' name='rh_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>High Humidity Limit</td>";
		echo "<td align='center'><input type='text' value='".$rh_limit_high."' name='rh_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Low Light Limit</td>";
		echo "<td align='center'><input type='text' value='".$light_limit_low."' name='light_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>High Light Limit</td>";
		echo "<td align='center'><input type='text' value='".$light_limit_high."' name='light_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 1 Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp0_limit_low."' name='temp0_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 1 High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp0_limit_high."' name='temp0_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 2 Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp1_limit_low."' name='temp1_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 2 High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp1_limit_high."' name='temp1_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 3 Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp2_limit_low."' name='temp2_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 3 High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp2_limit_high."' name='temp2_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 4 Low Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp3_limit_low."' name='temp3_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 4 High Temp Limit</td>";
		echo "<td align='center'><input type='text' value='".$temp3_limit_high."' name='temp3_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 1 Low Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist0_limit_low."' name='moist0_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 1 High Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist0_limit_high."' name='moist0_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 2 Low Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist1_limit_low."' name='moist1_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 2 High Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist1_limit_high."' name='moist1_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 3 Low Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist2_limit_low."' name='moist2_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 3 High Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist2_limit_high."' name='moist2_limit_high'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 4 Low Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist3_limit_low."' name='moist3_limit_low'>";
		echo "</td></tr>";

		echo "<tr><td align='center'>Zone 4 High Moisture Limit</td>";
		echo "<td align='center'><input type='text' value='".$moist3_limit_high."' name='moist3_limit_high'>";
		echo "</td></tr>";
	} else if ($sensor_type_select == "Alarm Switch") {
		$switch_alert_condition = isset($sensor_limits["sensor_limit4"]) ? $sensor_limits["sensor_limit4"] : "closed";

		echo "<tr><td align='center'>Alarm Switch Alert Condition</td>";
		echo "<td align='center'><select name='sensor_limit4'>";
		if ($switch_alert_condition == "closed") {
			echo "<option value='close' selected='selected'>Closed</option>";
			echo "<option value='open'>Open</option>";
		} else {
			echo "<option value='close'>Closed</option>";
			echo "<option value='open' selected='selected'>Open</option>";
		}
		echo "</select></td></tr>";
	}

	$sensor_period = $row["sensor_period"] ? $row["sensor_period"] : "";
	echo "<tr><td align='center'>Email/SMS Alert Time Period (minutes)</td><td align='center'><input type='text' value='".$sensor_period."' name='sensor_period'></td></tr>";
	echo "<tr><td align='center'>Email Address</td><td align='center'><input type='text' value='".$row["sensor_email"]."' name='sensor_email'></td></tr>";
	echo "<tr><td align='center'>Texting Email Address</td><td align='center'><input type='text' value='".$row["sensor_texting"]."' name='sensor_texting'></td></tr>";

        echo "<tr><td align='center'>Comment</td><td align='center'><textarea name='sensor_comment'>".$row["sensor_comment"]."</textarea></td></tr>";
        echo '<script type="text/javascript">function changetype (select) { var selectedOption = select.options[select.selectedIndex]; document.sensor_update.action = \'manage_sensor.php?sid='.$sid.'&sensor_type_select=\' + selectedOption.value; document.sensor_update.submit(); return; }</script>';
  }
} else {
    echo "Could not find the entry you requested.<br><br>";
}
$conn->close();
?> 
</table>
</h3>
<table>
<tr>
<td></td>
<td>
  <input type="hidden" name='sid' id='sid' value='<?php echo "" . $sid. ""; ?>' />
  <input type="submit" name='Update' value="Update Sensor" />
  <input type="submit" name='Reset' value="Reset Sensor" />
  <input type="submit" name='Remove' value="Remove Sensor" />
</td>
</table>
</center>
</form>
<?php require("footer.php"); ?>  


<?php
include('lock.php');
?>
<?php require("header1.php"); ?>
<meta http-equiv="REFRESH" content="300;url=rawdata_humidity.php"></head>
<?php require("header2.php"); ?>
<?php require("header3.php"); ?>


<?php
// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$temp_type = "Fahrenheit";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_temperature'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	if ($row["value"] == "Celsius") {
		$temp_type = "Celsius";
	}
	else {
		$temp_type = "Fahrenheit";
	}
    }
}

$temp_high = "";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_hightemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $temp_high = $row["value"];
    }
}

$temp_low = "";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_lowtemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $temp_low = $row["value"];
    }
}


$sql = "SELECT * FROM sensors_info WHERE sensor_state = 'on' AND sensor_type = 'Humidity'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row

	echo '<h3><a href="/rawdata.php">Back To Raw Data Page</a> | <a href="/rawdata_humidity.php">Refresh This Page</a> </h3>';
	echo '<center><h3>';
	echo '<table border=1 cellpadding=2>';
	echo '<tr>';
	echo '<td>Sensor Name / ID</td>';
	echo '<td>Status</td>';
	echo '<td>State</td>';
	echo '<td>Humidity (%)</td>';
	echo '<td>Temperature &deg;F/&deg;C</td>';

    while($row = $result->fetch_assoc()) {
	$sensor_id = escapeshellarg($row["sensor_id"]);

	$sensor_detect = exec("/opt/sensiplicity/bin/sn-util-rpi enum_w1 | grep ".$sensor_id);
        if($sensor_detect){
                $status = "<center><img src=\"/images/good.gif\"></center>";
        }
        else {
                $status = "<center><img src=\"/images/bad.png\"></center>";
        }

	$humidity_low = 0;
	$humidity_high = 100;

        $sensor_limits = json_decode($row["sensor_limits"], true, 2);
        $sensor_low  = isset($sensor_limits["temp_low"])  ? $sensor_limits["temp_low"]  : "";
        $sensor_high = isset($sensor_limits["temp_high"]) ? $sensor_limits["temp_high"] : "";

        if(($sensor_low != "")) {
                $temp_low = $sensor_low;
        }
        if(($sensor_high != "")) {
                $temp_high = $sensor_high;
        }


	$sensor_state = htmlspecialchars($row["sensor_state"]);

        if ($row["sensor_name"] != "") {
        	$sensor_name  = htmlspecialchars($row["sensor_name"]);
        }
	else {
		$sensor_name = htmlspecialchars($row["sensor_id"]);
	}

        $raw_humidity = exec('/opt/sensiplicity/bin/sn-util-rpi humidity '.$sensor_id.' | grep '.$sensor_id);
	$humidity_data = explode(",", $raw_humidity);

	$humidity_value = round($humidity_data[1], 1, PHP_ROUND_HALF_UP);

	if ($temp_type == "Celsius") {
		$temp_value = $humidity_data[2];
		$temp_units = "&deg;C";
	}
	else {
		$temp_value = ($humidity_data[2] * (9.0/5.0)) + 32;
		$temp_units = "&deg;F";
	}
	$temp_value = round($temp_value, 1, PHP_ROUND_HALF_UP);

	$sensor_status = "";
	if ($temp_value > $temp_high ) {
		$sensor_status .= '<font color="red">HIGH TEMP</font><br />';
	}
	else if ($temp_value < $temp_low ) {
		$sensor_status .= '<font color="blue">LOW TEMP</font><br />';
	}

	if ($humidity_value > $humidity_high) {
		$sensor_status .= '<font color="red">HIGH HUMIDITY</font>';
	}
	else if ($humidity_value < $humidity_low) {
		$sensor_status .= '<font color="blue">LOW HUMIDITY</font>';
	}

	if (!$sensor_status) {
		$sensor_status = '<font color="green">GOOD</font>';
	}

	echo '
		</tr>
		<tr>
		<td align="center" valign="middle">'.$sensor_name.'</td>
		<td align="center" valign="middle">'.$sensor_state.'</td>
		<td align="center" valign="middle">'.$sensor_status.'</td>
		<td align="center" valign="middle">'.$humidity_value.'%</td>
		<td align="center" valign="middle">'.$temp_value.$temp_units.'</td>
                </tr>
	';

    }


                echo '</table>';
}
else {
	echo '<h2>There are no sensors turned on under the admin section. If you want to view raw data you will need to enable some sensors.</h2>';
}
?>

<?php require("footer.php"); ?>

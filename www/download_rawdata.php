<?php
include('lock.php');

require("header3.php");

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the server temperature units
$temp_result = $conn->query('SELECT value FROM sensors_system WHERE name = "server_temperature"');
if ($temp_result == "Fahrenheit")
	$temp_units = "F";
else
	$temp_units = "C";

// Select the output format
$DownloadTXT = isset($_GET["TXT"]) ? true : "";  // Text format
$DownloadXML = isset($_GET["XML"]) ? true : "";  // XML format
$DownloadCSV = isset($_GET["CSV"]) ? true : "";  // CSV format
$DownloadSEN = isset($_GET["SEN"]) ? true : "";  // Sensatronics Text format

// Get the list of currently connected sensors
$connected_sensors = array();  // sensor_id => sensor_bus

exec("/opt/sensiplicity/bin/sn-util-rpi enum_w1 | tail -n +2", $w1_sensors);
foreach ($w1_sensors as $w1_sensor) {
	$sensor_split = array_map("trim", str_getcsv($w1_sensor));
	$connected_sensors[$sensor_split[0]] = "1-wire";
}

for ($bus = 1; $bus <= 3; $bus++) {
	unset($sn_sensors);
	exec("/opt/sensiplicity/bin/sn-util-rpi enum ".$bus." | tail -n +2", $sn_sensors);
	foreach ($sn_sensors as $sn_sensor) {
		$sensor_split = array_map("trim", str_getcsv($sn_sensor));
		$connected_sensors[$sensor_split[3]] = $bus;
	}
}

// Get sensor data
$sensors = $conn->query('SELECT sensor_id, sensor_name, sensor_bus, sensor_type FROM sensors_info WHERE sensor_state = "on"');
$sensors_data = array();
foreach ($sensors as $sensor) {
	// Detect if sensor is connected
	if (!array_key_exists($sensor["sensor_id"], $connected_sensors))
		continue;

	// Get data from sensor

	if ($sensor["sensor_type"] == "Temperature") {
		$sensor_id = escapeshellarg($sensor["sensor_id"]);
		$cmd_output = exec("/opt/sensiplicity/bin/sn-util-rpi temp ".$sensor_id." | tail -n 1");
		$cmd_split = array_map("trim", str_getcsv($cmd_output));

		$temp_value = floatval($cmd_split[1]);

		if ($temp_units == "F")
			$temp_value = ($temp_value * (9.0/5.0)) + 32;

		$sensor_name = $sensor["sensor_id"];
		if ($sensor["sensor_name"] != "") {
			$sensor_name = $sensor["sensor_name"];
		}

		$sensors_data[$sensor_name] = array(
			"temp" => $temp_value.$temp_units,
		);
	}
	else if ($sensor["sensor_type"] == "Humidity") {
		$sensor_id = escapeshellarg($sensor["sensor_id"]);
		$cmd_output = exec("/opt/sensiplicity/bin/sn-util-rpi humidity ".$sensor_id." | tail -n 1");
		$cmd_split = array_map("trim", str_getcsv($cmd_output));
		
		$rh_value = floatval($cmd_split[1]);
		$temp_value = floatval($cmd_split[2]);

		if ($temp_units == "F")
			$temp_value = ($temp_value * (9.0/5.0)) + 32;

		$sensor_name = $sensor["sensor_id"];
		if ($sensor["sensor_name"] != "") {
			$sensor_name = $sensor["sensor_name"];
		}


		$sensors_data[$sensor_name] = array(
			"rh"   => $rh_value,
			"temp" => $temp_value.$temp_units,
		);
	}
	else if ($sensor["sensor_type"] == "Soil Sensor") {
		$bus = escapeshellarg($connected_sensors[$sensor["sensor_id"]]);
		$sensor_id = escapeshellarg($sensor["sensor_id"]);
		$cmd_output = exec("/opt/sensiplicity/bin/sn-util-rpi get 1 ".$sensor_id." | tail -n 1");
		$cmd_split = array_map("trim", str_getcsv($cmd_output));

		$temp_value = floatval($cmd_split[5]);
		$rh_value = floatval($cmd_split[4]);
		$light_value = intval($cmd_split[10]);
		$zone0_temp_value = floatval($cmd_split[6]);
		$zone1_temp_value = floatval($cmd_split[7]);
		$zone2_temp_value = floatval($cmd_split[8]);
		$zone3_temp_value = floatval($cmd_split[9]);
		$zone0_moisture_value = floatval($cmd_split[15]);
		$zone1_moisture_value = floatval($cmd_split[16]);
		$zone2_moisture_value = floatval($cmd_split[17]);
		$zone3_moisture_value = floatval($cmd_split[18]);

		if ($temp_units == "F") {
			$temp_value = ($temp_value * (9.0/5.0)) + 32;
			$zone0_temp_value = ($zone0_temp_value * (9.0/5.0)) + 32;
			$zone1_temp_value = ($zone1_temp_value * (9.0/5.0)) + 32;
			$zone2_temp_value = ($zone2_temp_value * (9.0/5.0)) + 32;
			$zone3_temp_value = ($zone3_temp_value * (9.0/5.0)) + 32;
		}

		$sensor_name = $sensor["sensor_id"];
		if ($sensor["sensor_name"] != "") {
			$sensor_name = $sensor["sensor_name"];
		}


		$sensors_data[$sensor_name] = array(
			"amb_temp" => $temp_value.$temp_units,
			"rh"   => $rh_value,
			"light" => $light_value,
			"zone0_temp" => $zone0_temp_value.$temp_units,
			"zone1_temp" => $zone1_temp_value.$temp_units,
			"zone2_temp" => $zone2_temp_value.$temp_units,
			"zone3_temp" => $zone3_temp_value.$temp_units,
			"zone0_moisture" => $zone0_moisture_value,
			"zone1_moisture" => $zone1_moisture_value,
			"zone2_moisture" => $zone2_moisture_value,
			"zone3_moisture" => $zone3_moisture_value,
		);
	}
}

// Output sensor data in selected format TODO
if ($DownloadTXT) {
	//OUTPUT HEADERS
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=rawdata.txt;" );
	header("Content-Transfer-Encoding: binary");
	header("refresh:5; url=download.php");

	foreach ($sensors_data as $sensor_name => $sensor_data) {
		echo "'".$sensor_name."';";
		foreach ($sensor_data as $name => $value) {
			echo $name.'='.$value.';';
		}
		echo "\n";
	}
}
else if ($DownloadXML) {
	//OUTPUT HEADERS
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=rawdata.xml;" );
	header("Content-Transfer-Encoding: binary");

	echo "<?xml version=\"1.0\"\?&gt;\n";
	echo "<sensors>\n";
	foreach ($sensors_data as $sensor_name => $sensor_data) {
		echo "\t".'<sensor name="'.$sensor_name.'">'."\n";
		foreach ($sensor_data as $name => $value) {
			echo "\t\t".'<data type="'.$name.'">';
			echo $value;
			echo "</data>\n";
		}
		echo "\t</sensor>\n";
	}
	echo "</sensors>\n";
}
else if ($DownloadCSV) {
	//OUTPUT HEADERS
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=rawdata.csv;" );
	header("Content-Transfer-Encoding: binary");

	echo "\"Sensor Name\",\"Sensor Data\"\r\n";
	foreach ($sensors_data as $sensor_name => $sensor_data) {
		echo "\"".$sensor_name."\",";
		foreach ($sensor_data as $name => $value) {
			echo "\"".$name."=".$value."\",";
		}
		echo "\r\n";
	}
}
else if ($DownloadSEN) {
}

?>

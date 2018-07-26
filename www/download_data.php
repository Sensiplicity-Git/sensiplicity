<?php
include('lock.php');

require("header3.php");

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$DownloadDataXML = isset($_GET["DownloadDataXML"]) ? $_GET["DownloadDataXML"] : "";
$DownloadDataCSV = isset($_GET["DownloadDataCSV"]) ? $_GET["DownloadDataCSV"] : "";
$SensorID = isset($_GET["SensorID"]) ? $_GET["SensorID"] : "";
$month = isset($_GET["month"]) ? $_GET["month"] : "";
$day = isset($_GET["day"]) ? $_GET["day"] : "";
$year = isset($_GET["year"]) ? $_GET["year"] : "";
$hour = isset($_GET["hour"]) ? $_GET["hour"] : "";
$minute = isset($_GET["minute"]) ? $_GET["minute"] : "";
$name = $SensorID;

$sql = "SELECT * FROM sensors_info WHERE sensor_id = '".$SensorID."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["sensor_name"] != "") {
        	$name  = $row["sensor_name"];
		}
        if ($row["sensor_type"] != "") {
        	$sensor_type  = $row["sensor_type"];
		}
		if ($row["sid"] != "") {
			$sid = $row["sid"];
		}
    }
}

$start_date = new DateTime($year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':00');
$start_timestamp = $start_date->getTimestamp();
$result = $conn->query('SELECT sid, timestamp, value FROM sensors_data WHERE sid = '.$sid.' AND timestamp >= '.$start_timestamp.' ORDER BY timestamp DESC');

$csv_data = "\"Sensor ID\",\"Type\",\"Date\",\"Data\"\n";
$xml_data = "<?xml version=\"1.0\"?>\n<?xml-stylesheet type=\"text/xsl\" href=\"soil_sensor.xsl\"?>\n<temperatures>\n";
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$datetime = new DateTime('@'.$row['timestamp']);
		$formatted_time = $datetime->format('D M j H:i:s T Y');
		$values = explode(":", $row['value']);
		if ($sensor_type == 'Soil Sensor') {
			$fields = array('atemp', 'humidity', 'light', 'stemp1', 'stemp2', 'stemp3', 'stemp4', 'moist1', 'moist2', 'moist3', 'moist4');
		}
		else if ($sensor_type == 'Temperature') {
			$fields = array('temp');
		}
		else if ($sensor_type == 'Humidity') {
			$fields = array('humidity', 'temp');
		}
		else {
			$fields = array();
		}

		for ($i = 0; $i < sizeof($fields); $i++) {
			$csv_data .= '"'.$name.'","'.$fields[$i].'","'.$formatted_time.'",'.$values[$i]."\n";
			$xml_data .= "<avgdata Sensor=\"".$name."\" Type=\"".$fields[$i]."\" Time=\"".$formatted_time."\" Data=\"".$values[$i]."\" />\n";
		}
	}
}
$xml_data .= "</temperatures>\n";

if ($DownloadDataCSV != "") {
	//OUPUT HEADERS
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"$name.csv\";" );
	header("Content-Transfer-Encoding: binary");
	echo($csv_data); 
}
else if ($DownloadDataXML != "") {
	//OUPUT HEADERS
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"$name.xml\";" );
	header("Content-Transfer-Encoding: binary");
	echo($xml_data); 
}


?> 


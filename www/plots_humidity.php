<?php
include('lock.php');
?>

<!-- <?php require("header1.php"); ?>  -->
<!-- <meta http-equiv="REFRESH" content="60;url=plots.php"></head> -->
<script type="text/javascript" src="moment.min.js"></script>
<script type="text/javascript" src="moment-timezone-with-data.min.js"></script>
<script type="text/javascript" src="Chart.min.js"></script>
<!--
<?php require("header2.php"); ?>  
<?php require("header3.php"); ?>
-->

<?php
// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sensors_info WHERE sensor_plot LIKE '%on%' AND sensor_state = 'on' AND sensor_type = 'Humidity' ORDER BY sid ASC";
$result = $conn->query($sql);

?>

<?php
$_SESSION['curPlot'] = 'TEMP_HUMIDITY';
$html_id = "";
$html_name = "";
if ($result->num_rows > 0) {

	echo '<form name="PlotForm" method="GET" action="plots.php">'."\n";
	echo '<B>Sensor:</B>&nbsp;&nbsp;'."\n";
	echo '<SELECT NAME="SensorID" >'."\n";

	if (!isset($_GET['SensorID'])) {
		// echo '<option disabled selected value> --Select a Sensor-- </option>'."\n";
	}
	else{
		echo '<option disabled value> --Select a Sensor-- </option>'."\n";
	}

	while($row = $result->fetch_assoc()) {
		if ($row["sensor_state"] == "on") {

			$sensor_id = $row["sensor_id"];
			if ($html_id == "") {
				$html_id = $row["sensor_id"];
			}
			$name = $row["sensor_id"];
			#$html_name = $row["sensor_id"];
			if ($row["sensor_name"] != "") {
				$name  = $row["sensor_name"];
				if ($html_name == "") {
					$html_name = $name;
				}
			}
			else if ($html_name == "") {
				$html_name = $row["sensor_id"];
			}
			echo '        <OPTION VALUE="'.$sensor_id.'"';
			if (isset($_GET['SensorID'])) {
				if ($_GET['SensorID'] == $row["sensor_id"]) {
					echo " SELECTED";
				}
			}
			echo '>'.$name.'</OPTION>'."\n";

		}
	}

	echo '</SELECT> <br />'."\n";


	echo '<B>Plot Data For:</B>&nbsp;&nbsp;'."\n";
	echo '<SELECT NAME="SubSensor" >';

	if (!isset($_GET['SubSensor'])) {
		// echo '<option disabled selected value> --Select a Sensor Value-- </option>'."\n";
	}
	else {
		echo '<option disabled value> --Select a Sensor Value-- </option>'."\n";
	}

	echo '<OPTION VALUE="humidity"';
	if (isset($_GET['SubSensor'])) {
		if ($_GET['SubSensor'] == "humidity") {
			echo " SELECTED";
		}
	}
	echo '> Humidity </OPTION>'."\n";

	echo '<OPTION VALUE="temp"';
	if (isset($_GET['SubSensor'])) {
		if ($_GET['SubSensor'] == "temp") {
			echo " SELECTED";
		}
	}
	echo '> Temperature </OPTION>'."\n";

	echo '</SELECT> <br />';


	echo '<B>Plot Time Line:</B>&nbsp;&nbsp;'."\n";
	echo'<SELECT NAME="TimeLine" >'."\n";

	if (!isset($_GET['TimeLine'])) {
		// echo '<option disabled selected value> --Select a Timeline-- </option>'."\n";
	}
	else {
		echo '<option disabled value> --Select a Timeline-- </option>'."\n";
	}

	echo '<OPTION VALUE="1h"';
	if (isset($_GET['TimeLine'])) {
		if ($_GET['TimeLine'] == "1h") {
			echo "SELECTED";
		}
	}
	echo '> hour </OPTION>'."\n";
	echo '<OPTION VALUE="1d"';
	if (isset($_GET['TimeLine'])) {
		if ($_GET['TimeLine'] == "1d") {
			echo "SELECTED";
		}
	}
	echo '> day </OPTION>'."\n";
	echo '<OPTION VALUE="1w"';
	if (isset($_GET['TimeLine'])) {
		if ($_GET['TimeLine'] == "1w") {
			echo "SELECTED";
		}
	}
	echo '> week </OPTION>'."\n";
	echo '<OPTION VALUE="1m"';
	if (isset($_GET['TimeLine'])) {
		if ($_GET['TimeLine'] == "1m") {
			echo "SELECTED";
		}
	}
	echo '> month </OPTION>'."\n";
	echo '<OPTION VALUE="1y"';
	if (isset($_GET['TimeLine'])) {
		if ($_GET['TimeLine'] == "1y") {
			echo "SELECTED";
		}
	}
	echo '> year </OPTION>'."\n";
	echo '</SELECT> <br />'."\n";

	echo '<input type="submit" value="Submit">';
	echo '</form>';

}

?>

<?php


if(isset($_GET['SensorID']))
{
	$sql = "SELECT * FROM sensors_info WHERE sensor_id = '".$_GET['SensorID']."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if ($row["sensor_name"] != "") {
				$html_id = $_GET['SensorID'];
				$sid = $row["sid"];
				$html_name = $row["sensor_name"];
			}
			else {
				$html_id = $_GET['SensorID'];
				$sid = $row["sid"];
				$html_name = $row["sensor_id"];
			}
		}
	}
}

echo '<script>';
if(isset($_GET['TimeLine']))
{
	if ($_GET['TimeLine'] == "1h") {
		$timeline = "Hour";
		$time_interval = new DateInterval('PT1H');
		$granularity = 1;
		echo 'var unit = "minute"; var step = 2; var interval = moment.duration(1, "h");';
	}
	else if ($_GET['TimeLine'] == "1d") {
		$timeline = "Day";
		$time_interval = new DateInterval('P1D');
		$granularity = 5;
		echo 'var unit = "hour"; var step = 0.5; var interval = moment.duration(1, "d");';
	}
	else if ($_GET['TimeLine'] == "1w") {
		$timeline = "Week";
		$time_interval = new DateInterval('P1W');
		$granularity = 20;
		echo 'var unit = "day"; var step = 0.25; var interval = moment.duration(1, "w");';
	}
	else if ($_GET['TimeLine'] == "1m") {
		$timeline = "Month";
		$time_interval = new DateInterval('P1M');
		$granularity = 80;
		echo 'var unit = "day"; var step = 1; var interval = moment.duration(1, "M");';
	}
	else if ($_GET['TimeLine'] == "1y") {
		$timeline = "Year";
		$time_interval = new DateInterval('P1Y');
		$granularity = 400;
		echo 'var unit = "month"; var step = 1; var interval = moment.duration(1, "y");';
	}

	$now = new DateTime('NOW');
	$min_time = $now->sub($time_interval)->getTimestamp();
}
echo '</script>';

/* Set y-axis scale and units */
$result = $conn->query('SELECT * from sensors_system WHERE name = "server_lowtemp" OR name = "server_hightemp" OR name = "server_temperature" ORDER BY name');
if ($result->num_rows == 3) {
	echo "<script>\n";
	foreach ($result as $row) {
		if ($row['name'] == 'server_lowtemp') {
			echo 'var temp_low = '.$row['value'].';';
		}
		else if ($row['name'] == 'server_hightemp') {
			echo 'var temp_high = '.$row['value'].';';
		}
		else if ($row['name'] == 'server_temperature') {
			$temp_units = $row['value'];
			echo 'var temp_units = "'.$row['value'].'";';
		}
	}
	echo "</script>\n";
}

if (isset($_GET['SubSensor'])) {
	echo "<script>\n";
	if ($_GET['SubSensor'] == "humidity") {
		$subsensor = "Relative Humidity";
		$units = "%";
		echo 'var val_low = 0, val_high = 50;';
	}
	else if ($_GET['SubSensor'] == "temp") {
		$subsensor = "Temperature";
		$units = $temp_units;
		echo 'var val_low = temp_low, val_high = temp_high;';
	}
	echo "</script>\n";
}

if (isset($timeline) && isset($sid) && isset($subsensor)) {
	$result = $conn->query('SELECT timestamp, value FROM sensors.sensors_data WHERE sid = '.$sid.' AND timestamp >= '.$min_time.' ORDER BY timestamp ASC');
	if ($result->num_rows > 0) {
		echo "<script>\n";
		echo "var data = [";
		$counter = 0;
		while ($row = $result->fetch_assoc()) {
			$counter += 1;
			if (isset($granularity) && $counter % $granularity != 0)
				continue;

			$values = explode(':', $row['value']);
			if ($subsensor == 'Relative Humidity') $value = $values[0];
			if ($subsensor == 'Temperature') $value = $values[1];

			if (substr($value, -1, 1) == 'F' && $units == "Celsius") {
				$value = (intval(substr($value, 0, -1)) - 32) * (5.0/9.0);
			}
			else if (substr($value, -1, 1) == 'C' && $units == "Fahrenheit") {
				$value = (intval(substr($value, 0, -1)) * (9.0/5.0)) + 32;
			}
			else if ($units == $temp_units) {
				$value = intval(substr($value, 0, -1));
			}

			/* TODO TEMPORARY UNTIL SN-UTIL ISSUE IS FIXED */
			if ($subsensor == 'Relative Humidity' && $value > 100) continue;
			if ($subsensor == 'Temperature' && $units == 'Celsius' && $value > 100) continue;
			if ($subsensor == 'Temperature' && $units == 'Fahrenheit' && $value > 200) continue;

			echo "{x: '".date(DATE_ISO8601, $row['timestamp'])."', y: ".$value."},";
		}
		echo "];\n";
		echo "</script>\n";
	}
	
	echo "<canvas id='temp_chart' width=575 height=400></canvas>\n";
	echo '<script>
	var timezone = "'.date_default_timezone_get().'";

	var data_min = data[0]["x"];
	if (moment(data[0]["x"]).diff(moment().subtract(interval), "seconds") >= 60) {
		data_min = moment().subtract(interval).tz(timezone).format();
	}

	var ctx = document.getElementById("temp_chart");
	var temp_chart = new Chart(ctx, {
		type: "line",
		data: {
			datasets: [{
				label: "'.$subsensor.' ('.$units.')",
				backgroundColor: "#03C03C33",
				data: data,
				fill: "start",
			}],
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: "'.$subsensor.' chart for '.$html_name.'",
			},
			scales: {
				xAxes: [{
					type: "time",
					display: true,
					time: {
						min: data_min,
						max: data[data.length - 1]["x"],
						unit: unit,
						unitStepSize: step,
						tooltipFormat: "YYYY-MM-DD HH:mm:ss",
					},
					ticks: {
						autoSkip: true,
						// UGLY HACK BECAUSE Chart.js DOES NOT
						// PROPERLY SUPPORT TIMEZONES
						callback: function(value, index, values) {
							if (!values[index]) return;
							return moment.utc(values[index]["_d"]).tz(timezone).format("YYYY-MM-DD HH:mm:ss");
						}
					},
					scaleLabel: {
						display: true,
						labelString: "Date/Time",
					},
				}],
				yAxes: [{
					display: true,
					ticks: {
						beginAtZero: true,
						suggestedMin: val_low,
						suggestedMax: val_high,
					},
					scaleLabel: {
						display: true,
						labelString: "'.$subsensor.' ('.$units.')",
					},
				}]
			}
		}
	});
	</script>';
}
else {
	echo '<h2>Select a plot and a timeline. If there are no plots available then enable them in the Admin section.</h2>';
}
?>

<?php require("footer.php"); ?>  


<?php
include('lock.php');
?>
<!--
<?php require("header1.php"); ?>
<meta http-equiv="REFRESH" content="300;url=rawdata_temp.php"></head>
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

$date_tz = "UTC";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_timezone'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $date_tz = $row["value"];
    }
}

$date = exec('TZ='.$date_tz.' /bin/date');
$info = "No problems with this device";


$sql = "SELECT * FROM sensors_info WHERE sensor_state = 'on' AND sensor_type = 'Alarm Switch'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row

	echo '<h3><a href="/rawdata.php">Refresh This Page</a> </h3>';
	echo '<center><h3>';
	echo '<table border=1 cellpadding=2>';
	echo '<tr>';
	echo '<td>Alarm Sensor Name / ID</td>';
	echo '<td>Status</td>';
	echo '<td>State</td>';
	echo '<td>Information</td>';

    while($row = $result->fetch_assoc()) {
	$file = $row["sensor_id"];
        $sensor_dir = "/sys/bus/w1/devices/".$file."";
        if(file_exists($sensor_dir)){
                $status = "<center><img src=\"/images/good.gif\"></center>";
        }
        else {
                $status = "<center><img src=\"/images/bad.png\"></center>";
        }

	$sensor_error = $row["sensor_error"];

        $state = 'on';
        if ($row["sensor_state"] == "on"){
                $state = 'off';
        }

        $name = $row["sensor_id"];
        if ($row["sensor_name"] != "") {
        	$name  = $row["sensor_name"];
        }

	if ($sensor_error == "error" || $sensor_error == "triggered" || $sensor_error == "missing") {
		$state_of_sensor = '<font color="red">Alarm State</font>';
		$info = "Device is in <font color='red'>".$sensor_error."</font> state";
	}
	else {
		$state_of_sensor = '<font color="green">No Alarms</font>';
	}

	echo '
		</tr>
		<tr>
		<td align="center" valign="middle">'.$name.'</td>
		<td align="center" valign="middle">'.$status.'</td>
		<td align="center" valign="middle">'.$state_of_sensor.'</td>
		<td align="center" valign="middle">'.$info.'</td>
                </tr>
	';

    }


                echo '</table>';
		echo '</h3>Last Update:'.$date.'<br></center>';
}
else {
	echo '<h2>There are no sensors turned on under the admin section. If you want to view raw data you will need to enable some sensors.</h2>';
}
?>

<?php require("footer.php"); ?>

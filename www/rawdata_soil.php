<?php
include('lock.php');
?>
<?php require("header1.php"); ?>
<meta http-equiv="REFRESH" content="120;url=rawdata_soil.php"></head>
<?php require("header2.php"); ?>
<?php require("header3.php"); ?>


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


$temp_type = "Fahrenheit";
$deg = "F";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_temperature'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	if ($row["value"] == "Celsius") {
		$temp_type = "Celsius";
		$deg = "C";
	}
	else {
		$temp_type = "Fahrenheit";
		$deg = "F";
	}
    }
}


$sql = "SELECT * FROM sensors_info WHERE sensor_state = 'on' AND sensor_type = 'Soil Sensor'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row

	echo '<h3><a href="/rawdata.php">Back To Raw Data Page</a> | <a href="/rawdata_soil.php">Refresh This Page</a> </h3>';
	#echo '<center><h4>'; 
	echo '<h5>';
	echo '<table border=1 cellpadding=2>';
	echo '<tr>';
	echo '<td>Sensor ID</td>';
	echo '<td>Status</td>';
	echo '<td>State</td>';
	echo '<td>Air Temp</td>';
	echo '<td>Humidity (%)</td>';
	echo '<td>Light (nW/(cm)^2)</td>';
	echo '<td>Soil Temp 1</td>';
	echo '<td>Soil Temp 2</td>';
	echo '<td>Soil Temp 3</td>';
	echo '<td>Soil Temp 4</td>';
	echo '<td>Moisture 1</td>';
	echo '<td>Moisture 2</td>';
	echo '<td>Moisture 3</td>';
	echo '<td>Moisture 4</td>';

    while($row = $result->fetch_assoc()) {
        $soil_sensor = exec('/opt/sensiplicity/bin/sn-util-rpi get '.$row["sensor_bus"].' '.$row["sensor_id"].' | grep '.$row["sensor_id"].'');
        if ($soil_sensor) {
                $status = "<center><img src=\"/images/good.gif\"></center>";
        }
        else {
                $soil_sensor1 = exec('/opt/sensiplicity/bin/sn-util-rpi get 1 '.$row["sensor_id"].' | grep '.$row["sensor_id"].'');
                $soil_sensor2 = exec('/opt/sensiplicity/bin/sn-util-rpi get 2 '.$row["sensor_id"].' | grep '.$row["sensor_id"].'');
                $soil_sensor3 = exec('/opt/sensiplicity/bin/sn-util-rpi get 3 '.$row["sensor_id"].' | grep '.$row["sensor_id"].'');
                if ($soil_sensor1) {
                        $status = "<center><img src=\"/images/good.gif\"></center>";
                        $sql_bus = "UPDATE sensors_info SET sensor_bus = '1' WHERE `sensor_id` ='".$row["sensor_id"]."'";
                        $result_bus = $conn->query($sql_bus);
                        $row["sensor_bus"] = 1;
                }
                else if ($soil_sensor2) {
                        $status = "<center><img src=\"/images/good.gif\"></center>";
                        $sql_bus = "UPDATE sensors_info SET sensor_bus = '3' WHERE `sensor_id` ='".$row["sensor_id"]."'";
                        $result_bus = $conn->query($sql_bus);
                        $row["sensor_bus"] = 2;
                }
                else if ($soil_sensor3) {
                        $status = "<center><img src=\"/images/good.gif\"></center>";
                        $sql_bus = "UPDATE sensors_info SET sensor_bus = '3' WHERE `sensor_id` ='".$row["sensor_id"]."'";
                        $result_bus = $conn->query($sql_bus);
                        $row["sensor_bus"] = 3;
                }
                else {
                        $status = "<center><img src=\"/images/bad.png\"></center>";
                }
        }

	$low = $ini_array['low'];
	$high = $ini_array['high'];
	if(($row["sensor_limit1"] != "") && ($row["sensor_limit2"] != "")) {
		$low = $row["sensor_limit1"];
		$high = $row["sensor_limit2"];
	}
	else if(($row["sensor_limit3"] != "") && ($row["sensor_limit4"] != "")) {
		$low = $row["sensor_limit3"];
                $high = $row["sensor_limit4"];
	}


        $state = 'on';
        if ($row["sensor_state"] == "on"){
                $state = 'off';
        }

        $name = $row["sensor_id"];
        if ($row["sensor_name"] != "") {
        	$name  = $row["sensor_name"];
        }


        $raw_soil = exec('/opt/sensiplicity/bin/sn-util-rpi get '.$row["sensor_bus"].' '.$row["sensor_id"].' | grep '.$row["sensor_id"].' | grep -v Node ');
	$soil_data = explode(",", $raw_soil);

	$temp_value_air = "";
	$temp_valueC_air = $soil_data[6];
	$temp_valueC_soil0 = $soil_data[7];
	$temp_valueC_soil1 = $soil_data[8];
	$temp_valueC_soil2 = $soil_data[9];
	$temp_valueC_soil3 = $soil_data[10];
	$temp_valueF_air = round(($soil_data[6] * (9/5) + 32), 1);
	$temp_valueF_soil0 = round(($soil_data[7] * (9/5) + 32) ,1);
	$temp_valueF_soil1 = round(($soil_data[8] * (9/5) + 32) ,1);
	$temp_valueF_soil2 = round(($soil_data[9] * (9/5) + 32) ,1);
	$temp_valueF_soil3 = round(($soil_data[10] * (9/5) + 32) ,1);

	if ($temp_type == "Celsius") {
		$temp_value_soil0 = $temp_valueC_soil0;
		$temp_value_soil1 = $temp_valueC_soil1;
		$temp_value_soil2 = $temp_valueC_soil2;
		$temp_value_soil3 = $temp_valueC_soil3;
		$temp_value_air = $temp_valueC_air;
	}
	else {
		$temp_value_soil0 = $temp_valueF_soil0;
		$temp_value_soil1 = $temp_valueF_soil1;
		$temp_value_soil2 = $temp_valueF_soil2;
		$temp_value_soil3 = $temp_valueF_soil3;
		$temp_value_air = $temp_valueF_air;
	}

	$temp_values_soil0 = explode(".", $temp_value_soil0);
	if ($temp_values_soil0[0] > $high ) {
		$state_of_sensor = '<font color="red">HIGH TEMP</font>';
	}
	elseif ($temp_values_soil0[0] < $low ) {
		$state_of_sensor = '<font color="blue">LOW TEMP</font>';
	}
	else {
		$state_of_sensor = '<font color="green">GOOD</font>';
	}
        $temp_values_soil1 = explode(".", $temp_value_soil1);
        if ($temp_values_soil1[0] > $high ) {
                $state_of_sensor = '<font color="red">HIGH TEMP</font>';
        }
        elseif ($temp_values_soil1[0] < $low ) {
                $state_of_sensor = '<font color="blue">LOW TEMP</font>';
        }
        else {
                $state_of_sensor = '<font color="green">GOOD</font>';
        }
        $temp_values_soil2 = explode(".", $temp_value_soil2);
        if ($temp_values_soil2[0] > $high ) {
                $state_of_sensor = '<font color="red">HIGH TEMP</font>';
        }
        elseif ($temp_values_soil2[0] < $low ) {
                $state_of_sensor = '<font color="blue">LOW TEMP</font>';
        }
        else {
                $state_of_sensor = '<font color="green">GOOD</font>';
        }
        $temp_values_soil3 = explode(".", $temp_value_soil3);
        if ($temp_values_soil3[0] > $high ) {
                $state_of_sensor = '<font color="red">HIGH TEMP</font>';
        }
        elseif ($temp_values_soil3[0] < $low ) {
                $state_of_sensor = '<font color="blue">LOW TEMP</font>';
        }
        else {
                $state_of_sensor = '<font color="green">GOOD</font>';
        }

	$temp_values_air = explode(".", $temp_value_air);
	if ($temp_values_air[0] > $high ) {
		$state_of_sensor = '<font color="red">HIGH TEMP</font>';
	}
	elseif ($temp_values_air[0] < $low ) {
		$state_of_sensor = '<font color="blue">LOW TEMP</font>';
	}
	else {
		$state_of_sensor = '<font color="green">GOOD</font>';
	}
	$moisture1 = 0;
	$moisture2 = 0;
	$moisture3 = 0;
	$moisture4 = 0;
	if ($soil_data[16]) {
		$moisture1 = $soil_data[16];
		#$moisture1 = ((-0.00078 * $soil_data[16]) + 3.41);
		#$moisture1 = number_format((float)$moisture1, 2, '.', '');
		#if ($moisture1 < 0 ) {
		#	$moisture1 = 0.01;
		#}
	}
	if ($soil_data[17]) {
		$moisture2 = $soil_data[17];
		#$moisture2 = ((-0.078 * $soil_data[17]) + 3.41);
	}
	if ($soil_data[18]) {
		$moisture3 = $soil_data[18];
		#$moisture3 = ((-0.078 * $soil_data[18]) + 3.41);
	}
	if ($soil_data[19]) {
		$moisture4 = $soil_data[19];
		#$moisture4 = ((-0.078 * $soil_data[19]) + 3.41);
	}


	echo '
		</tr>
		<tr>
		<td align="center" valign="middle">'.$name.'</td>
		<td align="center" valign="middle">'.$status.'</td>
		<td align="center" valign="middle">'.$state_of_sensor.'</td>
		<td align="center" valign="middle">'.$temp_value_air.'&deg;'.$deg.'</td>
		<td align="center" valign="middle">'.$soil_data[5].'%</td>
		<td align="center" valign="middle">'.$soil_data[11].'</td>
		<td align="center" valign="middle">'.$temp_value_soil0.'&deg;'.$deg.'</td>
		<td align="center" valign="middle">'.$temp_value_soil1.'&deg;'.$deg.'</td>
		<td align="center" valign="middle">'.$temp_value_soil2.'&deg;'.$deg.'</td>
		<td align="center" valign="middle">'.$temp_value_soil3.'&deg;'.$deg.'</td>
		<td align="center" valign="middle">'.$moisture1.'</td>
		<td align="center" valign="middle">'.$moisture2.'</td>
		<td align="center" valign="middle">'.$moisture3.'</td>
		<td align="center" valign="middle">'.$moisture4.'</td>
                </tr>
	';

    }


                echo '</table>';
		#echo '</h4>Last Update:'.$date.'<br></center>';
		echo '</h5>Last Update:'.$date.'<br>';
}
else {
	echo '<h2>There are no sensors turned on under the admin section. If you want to view raw data you will need to enable some sensors.</h2>';
}
?>

<?php require("footer.php"); ?>

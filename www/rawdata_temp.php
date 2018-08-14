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

$high = "";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_hightemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$high = $row["value"];
    }
}

$low = "";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_lowtemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$low = $row["value"];
    }
}




$sql = "SELECT * FROM sensors_info WHERE sensor_state = 'on' AND sensor_type = 'Temperature'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row

	echo '<h3><a href="/rawdata.php">Refresh This Page</a> </h3>';
	echo '<center><h3>';
	echo '<table border=1 cellpadding=2>';
	echo '<tr>';
	echo '<td>Sensor Name / ID</td>';
	echo '<td>Temperature &deg;F/&deg;C</td>';
	echo '<td>Status</td>';
	echo '<td>State</td>';
	echo '<td></td>';

    while($row = $result->fetch_assoc()) {
	$file = $row["sensor_id"];
        $sensor_dir = "/sys/bus/w1/devices/".$file."";
        if(file_exists($sensor_dir)){
                $status = "<center><img src=\"/images/good.gif\"></center>";
        }
        else {
                $status = "<center><img src=\"/images/bad.png\"></center>";
        }

	$sensor_limits = json_decode($row["sensor_limits"], true, 2);
        $sensor_low  = isset($sensor_limits["temp_low"])  ? $sensor_limits["temp_low"]  : "";
        $sensor_high = isset($sensor_limits["temp_high"]) ? $sensor_limits["temp_high"] : "";

	if(($sensor_low != "")) {
		$low = $sensor_low;
	}
	if(($sensor_high != "")) {
                $high = $sensor_high;
	}


        $state = 'on';
        if ($row["sensor_state"] == "on"){
                $state = 'off';
        }

        $name = $row["sensor_id"];
        if ($row["sensor_name"] != "") {
        	$name  = $row["sensor_name"];
        }

	$cmdC = "cat $sensor_dir/w1_slave  | grep 't=' | awk -F= '{printf\"scale=1;(%s/1000 + 0)\\n\", $2}' | bc";
	$temp_value = "";

	$temp_valueC = exec($cmdC);
	$temp_valueF = ($temp_valueC * (9/5) + 32);
	if ($temp_type == "Celsius") {
		$temp_value = $temp_valueC;
	}
	else {
		$temp_value = $temp_valueF;
	}
	$temp_value = round($temp_value, 1, PHP_ROUND_HALF_UP);
	$temp_values = explode(".", $temp_value);
	$file_values = explode("/", $file);
	if ($temp_values[0] > $high ) {
		$state_of_sensor = '<font color="red">HIGH TEMP</font>';
	}
	elseif ($temp_values[0] < $low ) {
		$state_of_sensor = '<font color="blue">LOW TEMP</font>';
	}
	else {
		$state_of_sensor = '<font color="green">GOOD</font>';
	}

	echo '
		</tr>
		<tr>
		<td align="center" valign="middle">'.$name.'</td>
		<td align="center" valign="middle">'.$temp_valueF.' &deg;F / '.$temp_valueC.' &deg;C</td>
		<td align="center" valign="middle">'.$status.'</td>
		<td align="center" valign="middle">'.$state_of_sensor.'</td>
		<td align="center" valign="middle">
		<div class="container">
	    	   <div class="de">
	              <div class="den">
	      		  <div class="dene">
	            	    <div class="denem">
	              	       <div class="deneme">
	                 	'.$temp_values[0].'<span>.'.$temp_values[1].'</span><strong>&deg;</strong>
	    		       </div>
	            	    </div>
	        	 </div>
		      </div>
		   </div>
		</div>
		</td>
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

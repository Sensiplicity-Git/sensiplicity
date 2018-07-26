<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
	header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>  
<?php require("header3.php"); ?>


<h3><a href="admin.php">Back To Admin Page</a> | <a href="list_sensors.php">Refresh This Page</a> </h3>

<table>
   <tr><td width="83%">
   </td>
   <td width="17%">
	<form id="find_sensors" name="find_sensors" action="find_sensors.php" method="post">
	  <input type="submit" name='Find New Sensors' value="FindSensors" />
	</form>
   </td></tr>
</table>
<h4>
<table align="center" border=1 cellpadding='1' cellspacing='1'>
 <tr>
  <!-- <td align='center'>ID</td><td align='center'>Sensor ID</td><td align='center'>Bus ID</td><td align='center'>Name</td><td align='center'>State</td><td align='center'>Present</td><td align='center'>Type</td><td align='center'>Geo-Tag</td><td align='center'>Group</td><td align='center'>Manage</td> -->
  <td align='center'>ID</td><td align='center'>Sensor ID</td><td align='center'>Bus ID</td><td align='center'>Name</td><td align='center'>State</td><td align='center'>Present</td><td align='center'>Type</td><td align='center'>Group</td><td align='center'>Manage</td>
 </tr>
<?php
$ini_array = parse_ini_file("/opt/sensiplicity/etc/SS-L1.conf");

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM sensors_info";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	$sensor_id = $row["sensor_id"];

	$sensor_dir = "/sys/bus/w1/devices/".$row["sensor_id"]."";
	if(file_exists($sensor_dir)){
                $status = "<center><img src=\"/images/good.gif\"></center>";
        }
	else {
		$soil_sensor = array();
	    exec('/opt/sensiplicity/bin/sn-util-rpi get '.$row["sensor_bus"].' '.$row["sensor_id"].' | grep '.$row["sensor_id"].'', $soil_sensor);
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
			$sql_bus = "UPDATE sensors_info SET sensor_bus = '2' WHERE `sensor_id` ='".$row["sensor_id"]."'";
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
	}

	$state = 'on';
	if ($row["sensor_state"] == "on"){
		$state = 'off';
	}

	echo "<tr><td align='center'>" . $row["sid"]. "</td>";
	echo "<td align='center'><a href='manage_sensor.php?sid=".$row["sid"]."'>" . $row["sensor_id"]. "</a></td>";
	echo "<td align='center'>" . $row["sensor_bus"]. "</td>";
	echo "<td align='center'>" . $row["sensor_name"]. "</td>";
	echo "<td align='center'>" . $row["sensor_state"]. "</td>";
        echo "<td align='center'>" . $status. "</td>";
        echo "<td align='center'>" . $row["sensor_type"]. "</td>";
        #echo "<td align='center'>" . $row["sensor_geotag"]. "</td>";

	if ($row["sensor_group"] == 0) {
        	echo "<td align='center'></td>";
	}
	else {
        	$sql2 = "SELECT * FROM sensors_groups";
        	$result2 = $conn->query($sql2);
        	if ($result2->num_rows > 0) {
        	    while($row2 = $result2->fetch_assoc()) {
        	        if ($row["sensor_group"] == $row2["gid"]) {
        			echo "<td align='center'>" . $row2["group_name"]. "</td>";
        	        }
        	   }
        	}
	}

    	echo "<td align='center'><a href='manage_sensor.php?sid=".$row["sid"]."'>Edit Sensor</a>";
	echo "<br>-----<br>";
	echo "<a href='update_sensor.php?sid=".$row["sid"]."&Toggle=Toggle'>Set On/Off</a>";
	echo "<br>-----<br>";
	echo "<a href='update_sensor.php?sid=".$row["sid"]."&Reset=Reset'>Reset Sensor</a>";
	echo "</form>";

    }
} else {
    echo "There were no sensors found. Please run the FindSensors command below to find sensors.<br><br>";
}
$conn->close();
?> 
</table>
</h4>
<br>
<br>
<table>
  <form id="set_all" name="set_all" action="set_all_sensors.php" method="post">
   <tr><td width="45%">
   </td>
   <td width="17%">
	  <input type="submit" name='SetAllOn' value="Set All On" />
   </td>
   <td width="17%">
	  <input type="submit" name='SetAllOff' value="Set All Off" />
   </td>
   <td width="17%">
	  <input type="submit" name='ReSetAll' value="Reset All" />
   </td></tr>
  </form>
</table>
<?php require("footer.php"); ?>  


<?php
include('lock.php');
?>
<?php require("header1.php"); ?>      
<?php require("header2.php"); ?>      
<?php session_start();?>

    <center>

        <h3>
        <table cellpadding=6 cellspacing=0 border=0>
	<tr>
	    <td>
        	<table cellpadding=6 cellspacing=0 border=0>
		<tr><td><li><a href="rawdata_temp.php">Temperature Sensors Data</a></li></td></tr>
		<tr><td><li><a href="rawdata_humidity.php">Humidity Sensors Data</a></li></td></tr>
		<tr><td><li><a href="rawdata_soil.php">Soil Sensor System Data</a></li></td></tr>
		<tr><td><li><a href="rawdata_alarms.php">Alarm Switch / Open-Close Alarm Status</a></li></td></tr>
        	</table>
            </td>
            <td>
    		<img height=200 src="/images/sensiplicity_640hight.png"></a>
            </td>
        </tr>
        </table>
        </h3>


	<p>
	<h2>
	What Sensor Type would you like to see the status of?
	</h2>
	<form name="formSensorType" method="POST" action="rawdata.php">
	<select name="selectSensorType" onChange="document.formSensorType.submit()">
		<option value="">Select...</option>
		<option value="TEMP_ONLY">Temperature Sensor</option>
		<option value="TEMP_HUMIDITY">Temperature and Humidity Sensor</option>
		<option value="SOIL">Soil Sensor</option>
		<option value="ALARM">Alarm Sensor</option>
	</select>
	</form>
	</p>

	<?php
	if (isset($_POST['selectSensorType'])) {
		$varSensor = $_POST['selectSensorType'];
	} elseif (isset($_SESSION['curStatus'])) {
		$varSensor = $_SESSION['curStatus'];
	} 
	switch($varSensor) {
		case "TEMP_ONLY" : include("rawdata_temp.php"); $_SESSION['curStatus'] = 'TEMP_ONLY'; break;
		case "TEMP_HUMIDITY" : include("rawdata_humidity.php"); $_SESSION['curStatus'] = 'TEMP_HUMIDITY'; break;
		case "SOIL" : include("rawdata_soil.php"); $_SESSION['curStatus'] = 'SOIL'; break;
		case "ALARM" : include("rawdata_alarms.php"); $_SESSION['curStatus'] = 'ALARM'; break;
		default: echo("No selection"); require("footer.php"); break;
	}
	?>

   </center>


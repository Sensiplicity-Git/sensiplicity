<?php
include('lock.php');
?>
<?php require("header1.php"); ?>
<?php require("header2.php"); ?>
<?php session_start();?>



    <center>

	
	<p>
	<h2>
	What Sensor Type would you like to plot?
	</h2>
	<form name="formSensorType" method="POST" action="plots.php">
	<select name="selectSensorType" onChange="document.formSensorType.submit()">
		<option value="">Select...</option>
		<option value="TEMP_ONLY">Temperature Sensor</option>
		<option value="TEMP_HUMIDITY">Temperature and Humidity Sensor</option>
		<option value="SOIL">Soil Sensor</option>
	</select>
	</form>
	</p>

	<?php
	if (isset($_POST['selectSensorType'])) {
		$varSensor = $_POST['selectSensorType'];
	} elseif (isset($_SESSION['curPlot'])) {
		$varSensor = $_SESSION['curPlot'];
	} 
	switch($varSensor) {
		case "TEMP_ONLY" : include("plots_temp.php"); break;
		case "TEMP_HUMIDITY" : include("plots_humidity.php"); break;
		case "SOIL" : include("plots_soil.php"); break;
		default: echo("No selection"); require("footer.php"); break;
	}
	?>
	
   </center>


<?php
include('lock.php');
?>
<?php require("header1.php"); ?>
<?php require("header2.php"); ?>




    <center>

        <h3>
        <table cellpadding=6 cellspacing=0 border=0>
	<tr>
	    <td>
        	<table cellpadding=6 cellspacing=0 border=0>
		<tr><td><li><a href="plots_temp.php">Temperature Sensor Plots</a></li></td></tr>
		<tr><td><li><a href="plots_humidity.php">Temperature/Humidity Sensor Plots</a></li></td></tr>
		<tr><td><li><a href="plots_soil.php">Soil Sensor Plots</a></li></td></tr>
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
	if(isset($_POST)) {
	$varSensor = $_POST['selectSensorType'];
		switch($varSensor) {
			case "TEMP_ONLY" : include("plots_temp.php"); break;
			case "TEMP_HUMIDITY" : include("plots_humidity.php"); break;
			case "SOIL" : include("plots_soil.php"); break;
			default: echo("No selection"); break;
			//default: include("plots_soil.php"); break; //Testing to make sure the plots could be included
		}
	}
	?>
	
   </center>

<?php require("footer.php"); ?>

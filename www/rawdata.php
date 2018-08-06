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

   </center>

<?php require("footer.php"); ?>  

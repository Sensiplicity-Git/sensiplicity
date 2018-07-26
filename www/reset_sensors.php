<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>  

<h3><a href="admin.php">Back To Admin Page</a> </h3>
<br>
<br>
  <table align="center" border=1 cellpadding='5' cellspacing='5'>
   <tr><td width="90%">
	<h1>If you are sure you want to RESET ALL DATA AND SENSORS click the button the the right. Please be aware this will get rid of all plots and data for every sensor.</h1>
   </td>
   <td width="10%">
	<form id="reset_sensors" name="reset_sensors" action="reset_all_sensors.php" method="get">
  	<input type="submit" name='ResetAllSensors' value="Reset All Sensors" />
	</form>
   </td></tr>
  </table>
<?php require("footer.php"); ?>  


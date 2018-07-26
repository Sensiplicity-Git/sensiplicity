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
	<h1>If you are sure you want to TURN OFF THE SENSOR SYSTEM click the button the the right. Please be aware this will do a shutdown and leave the system ready to be unplugged.</h1>
   </td>
   <td width="10%">
	<form id="really_halt" name="really_halt" action="really_halt.php" method="get">
  	<input type="submit" name='ReallyShutDown' value="Shutdown The System" />
	</form>
   </td></tr>
  </table>
<?php require("footer.php"); ?>  


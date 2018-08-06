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
<<<<<<< HEAD
	<h2>Update Status:</h2>
   </td>
   <td><h3> <?php print `/opt/sensiplicity/bin/git_status.sh` ?> </h3></td>
   <td width="10%">
	<form id="update_device" name="update_device" action="adsfasdfadsfasdfasdfasdf.php" method="get">
  	<input type="submit" name='UpdateDevice' value="Update The System Device" />
	</form>
   </td></tr>
  </table>
<?php require("footer.php"); ?>  

=======
        <h2>Update Status:</h2>
   </td>
   <td><h3> <?php print `/opt/sensiplicity/bin/git_status.sh` ?> </h3></td>
   <td width="10%">
        <form id="update_device" name="update_device" action="adsfasdfadsfasdfasdfasdf.php" method="get">
        <input type="submit" name='UpdateDevice' value="Update The System Device" />
        </form>
   </td></tr>
  </table>
<?php require("footer.php"); ?>
>>>>>>> 04705645ce82d7d51f1d78c0c4c8f10f4b655290

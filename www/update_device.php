<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>  

<?php `sudo /usr/bin/git remote update`; ?>  
<?php $status = shell_exec('/opt/sensiplicity/bin/git_status.sh'); ?>

<h3><a href="admin.php">Back To Admin Page</a> </h3>
<br>
<br>
  <table align="center" border=1 cellpadding='5' cellspacing='5'>
   <tr><td width="90%">
	<h2>Update Status:</h2>
   </td>
   <td><h3> <?php print `/opt/sensiplicity/bin/git_status.sh` ?> </h3></td>
   <td width="10%">
	<form id="update_device" name="update_device" action="set_update.php" method="get">
	<input <?php if ($status != "Up-to-date\n") { echo " disabled='disabled'";}  ?> type="submit"name='UpdateDevice' value='Up date The System Device' />

	   </form>
   </td></tr>
  </table>
<?php require("footer.php"); ?>  


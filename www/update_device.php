<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>  

<?php
$host = 'git.com';      
$port = 80;
$waitTimeoutInSeconds = 1;
$ping_status = "offline";
if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){   
   $ping_status = "online";
   `sudo /usr/bin/git remote update`; 
   $git_status = shell_exec('/opt/sensiplicity/bin/git_status.sh');
   if ($git_status == "Up-to-date\n") {
   	$status = "<font color='green'>".$git_status."</font>"
   } else {
   	$status = "<font color='red'>".$git_status."</font>"
   }
} else {
   $status = "<font color='red'>System is not Online!</font>"
}
fclose($fp);
?>




<h3><a href="admin.php">Back To Admin Page</a> </h3>
<br>
<br>
  <table align="center" border=1 cellpadding='5' cellspacing='5'>
   <tr><td width="70%">
	<h1>Update Status:</h1>
	<h2>If the system needs to be updated the button to the right will become active. You can click the button and the system will pull the update from online after which you will need to reboot the system. Since the data is located online the device needs to be connected to the network to be updated.</h2>
   </td>
   <td width="20%"><h3> <?php print $status ?> </h3></td>
   <td width="10%">
	<form id="update_device" name="update_device" action="set_update.php" method="get">
	<input <?php if ($status == "Up-to-date\n") { echo " disabled='disabled'";}  ?> type="submit" name='UpdateDevice' value='Update Device' />
	   </form>
   </td></tr>
  </table>
<?php require("footer.php"); ?>  


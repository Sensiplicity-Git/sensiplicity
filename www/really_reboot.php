<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
if($value == "") {
        header("location: login.php");
	die();
}


$ReallyReboot = $_REQUEST["ReallyReboot"];

if ($ReallyReboot != "") {
	exec("sudo /sbin/reboot");
?>
	<?php require("header1.php"); ?>
	<?php require("header2.php"); ?>
	    <center>
	        <h3>The System is Rebooting Now!</h3>
	    	<img height=300 src="/images/sensiplicity_640hight.png"></a>
	    </center>
	<?php require("footer.php"); ?>
<?php 
}
else {
	header("Location: admin.php");
}

?> 




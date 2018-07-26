<?php
include('lock.php');
$value = isset($login_session) ? $login_session : "";
if($value == "") {
        header("location: login.php");
	die();
}
$ReallyShutDown = $_REQUEST["ReallyShutDown"];

if ($ReallyShutDown != "") {
	exec("sudo /sbin/shutdown -h now");
?>
	<?php require("header1.php"); ?>
	<?php require("header2.php"); ?>
	    <center>
		<h3>The System is Shutting Down Now!</h3>
		<img height=300 src="/images/sensiplicity_640hight.png"></a>
	    </center>
	<?php require("footer.php"); ?>
<?php 
}
else {
	header("Location: admin.php");
}

?> 




<?php
	include('lock.php');
	$value = isset($login_session) ? $login_session : '';
	if($value == "") {
		header("location: login.php");
	}
?>

<?php require("header_admin.php"); ?>

<?php `sudo sh -c 'echo "1" > ../update_set.up'`; ?>

<?php echo `sudo git clone https://github.com/Sensiplicity-Git/sensiplicity.git /opt/update/new`; ?>

<h3><a href="admin.php">Back To Admin Page</a> </h3>
<br>
<br>
<h2>Update Set for Next Reboot!</h2>

<?php require("footer.php"); ?>

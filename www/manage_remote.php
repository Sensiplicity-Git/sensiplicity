<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>
<?php
$active_process = '';
$active_process = exec("ps -aef | grep remote | head -1 | awk '{print \$9}'", $retval);
if ($active_process == "/opt/sensiplicity/bin/remote_ssh_server.py") {
	echo '<meta http-equiv="REFRESH" content="10;url=manage_remote.php">';
}
?>
<?php require("header3.php"); ?>


<h3><a href="admin.php">Back To Admin Page</a> | <a href="/manage_remote.php">Refresh This Page</a> </h3>

<?php
$type = isset($_SESSION['message']['type']) ? $_SESSION['message']['type'] : "";
$message = isset($_SESSION['message']['message']) ? $_SESSION['message']['message'] : "";

if ($message) {
	echo '<h3>'.$message.'<br></h3>';
	$_SESSION['message']['type'] = "";
	$_SESSION['message']['message'] = "";
}

?>


<h4>
<center>
<form id="remote_update" name="remote_update" action="update_rsettings.php" method="get">
 <h3>Storage and Sync Settings:</h3>
 You must enable this setting in order for the other options to become available<br>
 <table border=1 cellpadding='10' cellspacing='10'>

<?php
// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_store'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_store', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_store'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<tr><td align='center'>Data Storage Interval:</td><td align='center'>";
    echo "<select name='DataStorage'>";
    while($row = $result->fetch_assoc()) {
	echo "<option value='hours'"; if ($row['value'] == "hours") { echo " selected='selected'";} echo ">One Hour</option>";
	echo "<option value='days'"; if ($row['value'] == "days") { echo " selected='selected'";} echo ">One Day</option>";
	echo "<option value='weeks'"; if ($row['value'] == "weeks") { echo " selected='selected'";} echo ">One Week</option>";
	echo "<option value='months'"; if ($row['value'] == "months") { echo " selected='selected'";} echo ">One Month</option>";
	echo "<option value='years'"; if ($row['value'] == "years") { echo " selected='selected'";} echo ">One Year</option>";
    }
    echo "</select>";
    echo "</td></tr>";
}

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_sync'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_sync', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_sync'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<tr><td align='center'>How Offten to Sync Data:</td><td align='center'>";
    echo "<select name='SyncStorage'>";
    while($row = $result->fetch_assoc()) {
	echo "<option value='hours'"; if ($row['value'] == "hours") { echo " selected='selected'";} echo ">Each Hour</option>";
	echo "<option value='days'"; if ($row['value'] == "days") { echo " selected='selected'";} echo ">Each Day</option>";
	echo "<option value='weeks'"; if ($row['value'] == "weeks") { echo " selected='selected'";} echo ">Each Week</option>";
	echo "<option value='months'"; if ($row['value'] == "months") { echo " selected='selected'";} echo ">Each Month</option>";
	echo "<option value='years'"; if ($row['value'] == "years") { echo " selected='selected'";} echo ">Each Year</option>";
    }
    echo "</select>";
    echo "</td></tr>";
}

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_state'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_state', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_state'";
$result = $conn->query($sql);
$rserver_state = "";
if ($result->num_rows > 0) {
    echo "<tr><td align='center'>Storage / Sync Data State:</td><td align='center'>";
    echo "<select name='SyncStoreState'>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='disabled'"; if ($row['value'] == "disabled") { echo " selected='selected'";} echo ">Disabled</option>";
        echo "<option value='enabled'"; if ($row['value'] == "enabled") { echo " selected='selected'";} echo ">Enabled</option>";
	$rserver_state = $row['value'];
    }
    echo "</select>";
    echo "</td></tr>";
}


?>


<br>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input type="submit" name='UpdateRemoteSetting' value="Update Remote Server Settings" />
    <input <?php if ($rserver_state == "disabled" || $active_process == "/opt/sensiplicity/bin/remote_ssh_server.py") { echo " disabled='disabled'";}  ?> type="submit" name='SyncRemoteDataNow' <?php if ($active_process == "/opt/sensiplicity/bin/remote_ssh_server.py") { echo "value='Syncronizing'";} else { echo "value='Syncronize Data Now'"; }?> />
   </td>
 </table>

</form>

<br>
<br>
<br>

 <h3>USB Drive Sync:</h3>
<form id="usbdrive" name="usbdrive" action="update_usb.php" method="get">
 <table border=1 cellpadding='10' cellspacing='10'>
<?php
$sql = "SELECT value FROM sensors_system WHERE name = 'usb_folder'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('usb_folder', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'usb_folder'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	echo "<tr><td align='center'>Folder On USB Drive:</td><td align='center'><input "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " type='text' value='".$row['value']."' name='usb_folder'></td></tr>";
    }
}


$sql = "SELECT value FROM sensors_system WHERE name = 'usb_state'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('usb_state', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'usb_state'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<tr><td align='center'>State of USB Sync:</td><td align='center'>";
    echo "<select "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " name='usb_state'>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='disabled'"; if ($row['value'] == "disabled") { echo " selected='selected'";} echo ">Disabled</option>";
        echo "<option value='enabled'"; if ($row['value'] == "enabled") { echo " selected='selected'";} echo ">Enabled</option>";
    }
    echo "</select>";
    echo "</td></tr>";
}


?>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input <?php if ($rserver_state == "disabled") { echo " disabled='disabled'";}  ?> type="submit" name='USBDrive' value="Update USB Drive Information" />
   </td>
 </table>

</form>

<br>
<br>
<br>

<form id="ssh_update" name="ssh_update" action="update_remote.php" method="get">
 <h3>SCP/SFTP Remote Data Repositor:</h3>
 <table border=1 cellpadding='10' cellspacing='10'>

<?php   

$sensor_type_select = isset($_REQUEST['sensor_type_select']) ? $_REQUEST['sensor_type_select'] : '';

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_hostname'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_hostname', '')";
        $conn->query($sql); 
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_hostname'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
   	echo "<tr><td align='center'>Remote Server Hostname:</td><td align='center'><input "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " type='text' value='".$row['value']."' name='rserver_hostname'></td></tr>";
    }
}

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_port'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_port', '22')";
        $conn->query($sql); 
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_port'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
   	echo "<tr><td align='center'>Remote Server Port:</td><td align='center'><input "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " type='text' value='".$row['value']."' name='rserver_port'></td></tr>";
    }
}


$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_user'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_user', '')";
        $conn->query($sql); 
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td align='center'>Remote Server Login:</td><td align='center'><input "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " type='text' value='".$row['value']."' name='rserver_user'></td></tr>";
    }
}

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_pass'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('iv', '')";
        $conn->query($sql); 
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_pass', '')";
        $conn->query($sql); 
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_pass'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td align='center'>Remote Server Password:</td><td align='center'><input "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " type='password' value='".$row['value']."' name='rserver_pass'></td></tr>";
    }
}

$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_path'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('rserver_path', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'rserver_path'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td align='center'>Remote Server Folder:</td><td align='center'><input "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " type='text' value='".$row['value']."' name='rserver_path'></td></tr>";
    }
}


$sql = "SELECT value FROM sensors_system WHERE name = 'ssh_state'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
        $sql  = "INSERT INTO `sensors_system` (`name`, `value`) VALUES ('ssh_state', '')";
        $conn->query($sql);
}
$sql = "SELECT value FROM sensors_system WHERE name = 'ssh_state'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<tr><td align='center'>SCP/SFP State:</td><td align='center'>";
    echo "<select "; if ($rserver_state == 'disabled') { echo ' disabled="disabled"';} echo " name='SyncSSHState'>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='disabled'"; if ($row['value'] == "disabled") { echo " selected='selected'";} echo ">Disabled</option>";
        echo "<option value='enabled'"; if ($row['value'] == "enabled") { echo " selected='selected'";} echo ">Enabled</option>";
    }
    echo "</select>";
    echo "</td></tr>";
}



?>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input <?php if ($rserver_state == "disabled") { echo " disabled='disabled'";}  ?> type="submit" name='UpdateServer' value="Update SSH/SFTP Server Settings" />
   </td>
 </table>

</form>

<br>
<br>
<br>
<!-- 
 <h3>Google Drive Sync:</h3>
<form id="googledrive" name="googledrive" action="google_drive.php" method="get">
 <table border=1 cellpadding='10' cellspacing='10'>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input <?php if ($rserver_state == "disabled") { echo " disabled='disabled'";}  ?> type="submit" name='GoogleDrive' value="Update Google Drive Information" />
   </td>
 </table>

</form>

 <h3>Amazon Web Services (AWS) S3 Sync:</h3>
<form id="awss3" name="awss3" action="aws_s3.php" method="get">
 <table border=1 cellpadding='10' cellspacing='10'>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input <?php if ($rserver_state == "disabled") { echo " disabled='disabled'";}  ?> type="submit" name='AWS' value="Update AWS S3 Information" />
   </td>
 </table>

</form>
--!>


</center>
</h4>


<?php require("footer.php"); ?>

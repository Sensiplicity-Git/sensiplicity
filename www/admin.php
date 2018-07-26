<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>
<?php require("header3.php"); ?>

<?php
// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sensor_type_select = isset($_REQUEST['sensor_type_select']) ? $_REQUEST['sensor_type_select'] : '';

$sql = "SELECT value FROM sensors_system WHERE name = 'server_hostname'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<center><h1>".$row['value']."</h1></center>";
    }
}
?>


<center>
<table class="sensor_admin">
<tr><td valign="top">
<table><br><br><br>
<tr><td><a href="list_sensors.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage  / List All Sensors
</span></span></a></td></tr>
<tr><td><a href="manage_plots.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage Data / Plot Setting
</span></span></a></td><td></td></tr>
<tr><td><a href="manage_groups.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage Group Settings
</span></span></a></td><td></td></tr>
<tr><td><a href="manage_system.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage System / Main Setting</span></span></a></td><td></td></tr>
<tr><td><a href="manage_remote.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">File Storage / Remote Server Setting</span></span></a></td><td></td></tr>
<tr><td><a href="manage_network.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage Network Setting</span></span></a></td><td></td></tr>
<tr><td><a href="manage_email.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage Email Setting</span></span></a></td><td></td></tr>
<!-- <tr><td><a href="list_wifi.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Manage WiFi Setting</span></span></a></td><td></td></tr> -->
<!-- <tr><td><a href="/dbadmin/" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">MySQL Access (Login Required)</span></span></a></td><td></td></tr> -->
<tr><td><a href="migrate.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Migrate Sensor Data</span></span></a></td><td></td></tr>
<tr><td><a href="reset_sensors.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Delete All Sensors / Delete All Data</span></span></a></td><td></td></tr>
<tr><td><a href="reboot.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Reboot Sensor System</span></span></a></td><td></td></tr>
<tr><td><a href="halt.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Turn Off Sensor System</span></span></a></td><td></td></tr>
<tr><td><a href="logout.php" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t">Logout of Admin Section</span></span></a></td><td></td></tr>
</table>
</td>
<td align="right" valign="center">
<img height=300 src="/images/sensiplicity_640hight.png"></a>
</td></tr>
</table>
</center>


<?php require("footer.php"); ?>

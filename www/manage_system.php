<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>
<?php require("header3.php"); ?>


<h3><a href="admin.php">Back To Admin Page</a></h3>

<?php
$message1 = isset($_REQUEST["passwordchange"]) ? $_REQUEST["passwordchange"] : "";
$type = isset($_SESSION['message']['type']) ? $_SESSION['message']['type'] : "";
$message = isset($_SESSION['message']['message']) ? $_SESSION['message']['message'] : "";

if ($message) {
	echo '<h3>'.$message.'<br></h3>';
	$_SESSION['message']['type'] = "";
	$_SESSION['message']['message'] = "";
}

?>



<center>
<h3>
<form id="system_update" name="system_update" action="update_system.php" method="get">
 <h3>System Defaults:</h3>
 <table border=1 cellpadding='10' cellspacing='10'>

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
   	echo "<tr><td align='center'>Logger Hostname:</td><td align='center'><input type='text' value='".$row['value']."' name='server_hostname'></td></tr>";
    }
}

$sql = "SELECT value FROM sensors_system WHERE name = 'server_temperature'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	echo "<tr><td align='center'>Logger Temperature Type:</td><td align='center'><select id='server_temperature' name='server_temperature'>";
        if ($row["value"] == "Celsius") {
                echo "<option value='Celsius' selected='selected'>Celsius</option>";
                echo "<option value='Fahrenheit'>Fahrenheit</option>";
        } else {
                echo "<option value='Fahrenheit' selected='selected'>Fahrenheit</option>";
                echo "<option value='Celsius'>Celsius</option>";
        }
        echo "</select></td></tr>";
    }
}

$sql = "SELECT value FROM sensors_system WHERE name = 'server_lowtemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
   	echo "<tr><td align='center'>Logger Default Low Temp:</td><td align='center'><input type='text' value='".$row['value']."' name='server_lowtemp'></td></tr>";
    }
}

$sql = "SELECT value FROM sensors_system WHERE name = 'server_hightemp'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td align='center'>Logger Default High Temp:</td><td align='center'><input type='text' value='".$row['value']."' name='server_hightemp'></td></tr>";
    }
}

echo "<tr><td align='center'>Logger Time Zone:</td><td align='center'><select id='server_timezone' name='server_timezone'>";
$sql = "SELECT value FROM sensors_system WHERE name = 'server_timezone'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$zones = timezone_identifiers_list();
	foreach ($zones as $zone) {
    		//$zone = explode('/', $zone); // 0 => Continent, 1 => City
    		if ($row["value"] == $zone) {
    			echo "<option value='$zone' selected='selected'>$zone</option>";
    		} else {
    			echo "<option value='$zone'>$zone</option>";
    		}
    	}
 	  
    }
}
echo "</select></td></tr>";

?>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input type="submit" name='UpdateServer' value="Update Server Settings" />
   </td>
 </table>

</form>

<br>
<br>
<br>

 <h3>Change Password:</h3>
<form id="system_pass" name="system_pass" action="update_pass.php" method="get">
 <table border=1 cellpadding='10' cellspacing='10'>
   	<tr><td align='center'>Current Password:</td><td align='center'><input type='password' value='' name='pass_orig' size='20'></td></tr>
   	<tr><td align='center'>Change Password:</td><td align='center'><input type='password' value='' name='pass_new1' size='20'></td></tr>
   	<tr><td align='center'>Re-Type Password:</td><td align='center'><input type='password' value='' name='pass_new2' size='20'></td></tr>
 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input type="submit" name='UpdatePass' value="Update Password" />
   </td>
 </table>

</form>

</center>
</h3>


<?php require("footer.php"); ?>

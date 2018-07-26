<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }

require("header_admin.php");
require("header3.php");

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sid = $_GET['sid'];
$sensor_type_select = isset($_REQUEST['sensor_type_select']) ? $_REQUEST['sensor_type_select'] : '';

$smtp_state = "off";
$sql = "SELECT value FROM sensors_system WHERE name = 'smtp_state'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["value"] == "on") {
                $smtp_state = "on";
        }
    }
}

$sms_state = "off";
$sql = "SELECT value FROM sensors_system WHERE name = 'sms_state'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["value"] == "on") {
                $sms_state = "on";
        }
    }
}


?>
<h3><a href="admin.php">Back To Admin Page</a></h3>

<?php $password = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $ini_array["smtp_pass"]); ?>
<h3>
<center>
<form id="email_update" name="email_update" action="update_email.php" method="get">
 <table border=1 cellpadding='10' cellspacing='10'>
   <tr><td align='center'>SMTP Server Address:</td><td align='center'><input type='text' value='<?php echo "".$ini_array['smtp_server'].""; ?>' name='smtp_server'></td></tr>
   <tr><td align='center'>SMTP Server Port:</td><td align='center'><input type='text' value='<?php echo "".$ini_array['smtp_port'].""; ?>' name='smtp_port'></td></tr>
   <tr><td align='center'>SMTP Server Login:</td><td align='center'><input type='text' value='<?php echo "".$ini_array['smtp_login'].""; ?>' name='smtp_login'></td></tr>
   <tr><td align='center'>SMTP Server Password:</td><td align='center'><input type='password' value='<?php echo $password; ?>' name='smtp_pass'></td></tr>
   <tr><td align='center'>Sender Address:</td><td align='center'><input type='text' value='<?php echo "".$ini_array['send_address'].""; ?>' name='send_address'></td></tr>
   <tr><td align='center'>Default Receive Address:</td><td align='center'><input type='text' value='<?php echo "".$ini_array['recv_address'].""; ?>' name='recv_address'></td></tr>
   <tr><td align='center'>Default SMS Address:</td><td align='center'><input type='text' value='<?php echo "".$ini_array['sms_address'].""; ?>' name='sms_address'></td></tr>
<?php
        echo "<tr><td align='center'>Email State</td>";
        echo "<td align='center'>";
        echo "<select id='smtp_state' name='smtp_state'>";
        if ($smtp_state == "on") {
                echo "<option value='on' selected='selected'>On</option>";
                echo "<option value='off'>Off</option>";
        } else {
                echo "<option value='off' selected='selected'>Off</option>";
                echo "<option value='on'>On</option>";
        }
        echo "</select></td></tr>";

        echo "<tr><td align='center'>SMS State</td>";
        echo "<td align='center'>";
        echo "<select id='sms_state' name='sms_state'>";
        if ($sms_state == "on") {
                echo "<option value='on' selected='selected'>On</option>";
                echo "<option value='off'>Off</option>";
        } else {
                echo "<option value='off' selected='selected'>Off</option>";
                echo "<option value='on'>On</option>";
        }
        echo "</select></td></tr>";
?>

 </table>
<font size=2px><a href='https://en.wikipedia.org/wiki/SMS_gateway'>If you need help with the SMS settings click here and look at the section "Use with email clients".</a></font>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input type="submit" name='TestEmail' value="Test Email Settings" />
    <input type="submit" name='TestSMS' value="Test SMS Settings" />
    <input type="submit" name='Update' value="Update Email Settings" />
   </td>
 </table>

</form>
</center>
</h3>




<?php require("footer.php"); ?>

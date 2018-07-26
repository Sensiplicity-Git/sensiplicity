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

$post_error = isset($_SESSION['post_error']) ? $_SESSION['post_error'] : "";


?>

<h3><a href="admin.php">Back To Admin Page</a>
<center>
<?php
if($post_error != "") {
	echo "<font color='red'>$post_error</font>";
	$_SESSION['post_error'] = "";
}
?>
<table border=1 cellpadding='10' cellspacing='10'>
<tr><td align='center'>Group Name</td><td align='center'>Manage</td></tr>

<?php
$sql = "SELECT * FROM sensors_groups WHERE gid > 0";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	echo '<form id="group_remove_'.$row["gid"].'" name="group_remove_'.$row["gid"].'" action="update_group.php" method="post">';
	echo '<tr><td>'.$row["group_name"].'</td><td><input type="hidden" name="remove_gid" value="'.$row["gid"].'"><input name="Remove" type="submit" value="Remove"></td></tr>';
	echo '</form>';
    }
}
?>

  <form id="group_update" name="group_update" action="update_group.php" method="post">
  <tr><td align='center'><input type='text' name='group_add'></td><td><input type="submit" name='AddGroup' value="Add New Group" /></td></tr>
 <br>
 </table>
</form>
</center>




<?php require("footer.php"); ?>

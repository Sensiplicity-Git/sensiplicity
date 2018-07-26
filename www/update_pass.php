<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }


require("header3.php");

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_message) {
    die("Connection failed: " . $conn->connect_message);
}

$ses_sql=mysqli_query($db,"select username from sensors_users where username='$user_check' ");
$row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
$login_session=$row['username'];

$UpdatePass = $_REQUEST["UpdatePass"];
$pass_orig = isset($_REQUEST["pass_orig"]) ? $_REQUEST["pass_orig"] : "";
$pass_new1 = isset($_REQUEST["pass_new1"]) ? $_REQUEST["pass_new1"] : "";
$pass_new2 = isset($_REQUEST["pass_new2"]) ? $_REQUEST["pass_new2"] : "";
$md5password = md5($pass_orig);

$sql="SELECT uid FROM sensors_users WHERE username='$login_session' and passcode='$md5password'";
#print "sql = ".$sql."<br>";
$result=mysqli_query($db,$sql);
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
#$active=$row['active'];
$count=mysqli_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row
$message = "";
$type = "success";
if($count==1)
{

    if($pass_new1 == $pass_new2) {
        $sql  = "UPDATE sensors_users SET ";
        $sql .= "passcode = '".md5($pass_new1)."' ";
        $sql .= "WHERE username = '".$login_session."' ";
        if ($conn->query($sql) === TRUE) {
            $message = "Your Password Was Updated Successfully.";
	    $type = "SUCCESS";
        } else {
            $message = "Could Not Find The Entry To Update!";
	    $type = "ERROR";
        }
     }
     else {
	$message = "Your New Passwords Did Not Match!";
	$type = "ERROR";
     }

}
else
{
	$message="Your Login Name or Password is Invalid!";
	$type = "ERROR";
}


$_SESSION['message'] = array('type' => $type, 'message' => $message);;

$conn->close();

header("Location: manage_system.php");

?> 


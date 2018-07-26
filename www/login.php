<?php
include("config.php");
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
// username and password sent from Form
$myusername=mysqli_real_escape_string($db,$_POST['username']);
$mypassword=mysqli_real_escape_string($db,$_POST['password']);
$md5password = md5($mypassword);

$sql="SELECT uid FROM sensors_users WHERE username='$myusername' and passcode='$md5password'";
#print "sql = ".$sql."<br>";
$result=mysqli_query($db,$sql);
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
#$active=$row['active'];
$count=mysqli_num_rows($result);


// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1)
{
#session_register("myusername");
$_SESSION['login_user']=$myusername;

header("location: admin.php");
}
else
{
$error="Your Login Name or Password is invalid";
}
}
?>
<?php require("header1.php"); ?>  
<?php require("header2.php"); ?>  
   <form id="login" name="input" action="" method="post">

            Username: <input type="text" name="username" /><br />

            Password: <input type="password" name="password" />

            <input type="hidden" name='submitted' id='submitted' value='1' />
            <input type="submit" name='Submit' value="Submit" />

   </form>

<?php require("footer.php"); ?>  



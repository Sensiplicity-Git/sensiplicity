<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }
?>
<?php require("header_admin.php"); ?>
<?php require("header3.php"); ?>


<h3><a href="admin.php">Back To Admin Page</a> | <a href="/manage_network.php">Refresh This Page</a> </h3>

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
<form id="network_update" name="network_update" action="update_network.php" method="get">
 <h3>Network Settings:</h3>
 <table border=1 cellpadding='10' cellspacing='10'>

  <?php
	$NetworkSettings = "";
	$network_status = shell_exec('grep allowinterfaces /etc/dhcpcd.conf');

	if ($network_status == "allowinterfaces eth0\n") {
		$NetworkSettings = "dhcp";
	} else {
		$NetworkSettings = "static";
		$ipaddr = shell_exec('grep ip_address /etc/dhcpcd.conf');
		$routers = shell_exec('grep routers /etc/dhcpcd.conf');
		$dns = shell_exec('grep domain_name_servers /etc/dhcpcd.conf');
		list($static, $ipaddr) = split('[=]', $ipaddr);
		list($static, $routers) = split('[=]', $routers);
		list($static, $dns) = split('[=]', $dns);
	}

    	echo "<tr><td align='center'>Network Configuration:</td><td align='center'>";
    	echo "<select name='NetworkSettings'>";
        	echo "<option value='dhcp'"; if ($NetworkSettings == "dhcp") { echo " selected='selected'";} echo ">Dynamic Host Configuration Protocol (DHCP)</option>";
        	echo "<option value='static'"; if ($NetworkSettings == "static") { echo " selected='selected'";} echo ">Static IP Configuration Protocol (Static)</option>";
    	echo "</select>";
        echo "<tr><td align='center'>Static IP Address:</td><td align='center'><input type='text' value='".$ipaddr."' name='ip_address'></td></tr>";
        echo "<tr><td align='center'>Gateway / Router Address:</td><td align='center'><input type='text' value='".$routers."' name='routers'></td></tr>";
        echo "<tr><td align='center'>Domain Name Server (DNS):</td><td align='center'><input type='text' value='".$dns."' name='domain_name_servers'></td></tr>";
    	echo "</td></tr>";


  ?>


 </table>
<br>
 <table>
  <tr>
   <td></td>
   <td>
    <input type="submit" name='UpdateNetwork' value="Update Network Settings" />
   </td>
 </table>

</form>

<h4>If you update the network settings you will need to reboot the device to have them take effect.</h4>

</center>
</h3>


<?php require("footer.php"); ?>

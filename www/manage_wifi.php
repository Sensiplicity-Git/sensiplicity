<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }

require("header_admin.php");
require("header3.php");
?>

<h3><a href="list_wifi.php">Back To List Wifi Page</a></h3>

<form id="wifi_update" name="wifi_update" action="update_wifi.php" method="post">
<center>
<h3>
<table border=1 cellpadding='5' cellspacing='5'>
<?php
if (isset($_POST['ssid']))
	$ssid = htmlspecialchars($_POST['ssid']);
else
	$ssid = '';

if (isset($_POST['key_mgmt']))
	$key_mgmt = $_POST['key_mgmt'];
else
	$key_mgmt = '';

exec('/opt/sensiplicity/bin/wifi_control list_networks | tail -n +2', $cli_networks);
$cli_networks = array_reduce($cli_networks, function($result, $net) {
	$net = explode("\t", $net);

	$result[$net[1]] = $net[0];
	return $result;
}, array());

if (isset($_POST['edit']) && isset($_POST['ssid'])) {
	$network_id = escapeshellarg($cli_networks[$_POST['ssid']]);
	$key_mgmt = exec('/opt/sensiplicity/bin/wifi_control get_network '.$network_id.' key_mgmt');

	if ($key_mgmt == 'WEP' || $key_mgmt == 'WPA-PSK') {
		$psk = '********';
	}
	else {
		$psk = '';
	}

	if ($key_mgmt == 'WPA-EAP') {
		$identity = exec('/opt/sensiplicity/bin/wifi_control get_network '.$network_id.' identity');
		$identity = htmlspecialchars(substr($identity, 1, count($identity) - 2));
		$password = '********';
	}
	else {
		$identity = '';
		$password = '';
	}
}
else {
	$psk = '';
	$identity = '';
	$password = '';
}

if (array_key_exists($_POST['ssid'], $cli_networks) && isset($_POST['Disconnect'])) {
	$network_id = escapeshellarg($cli_networks[$_POST['ssid']]);
	exec('/opt/sensiplicity/bin/wifi_control disable_network '.$network_id);

	header('location: list_wifi.php');
}

if (array_key_exists($_POST['ssid'], $cli_networks) && !isset($_POST['edit'])) {
	foreach ($cli_networks as $net_id) {
		$net_id = escapeshellarg($net_id);
		exec('/opt/sensiplicity/bin/wifi_control disable_network '.$net_id);
	}
	
	$network_id = escapeshellarg($cli_networks[$_POST['ssid']]);
	exec('/opt/sensiplicity/bin/wifi_control enable_network '.$network_id);

	header('location: list_wifi.php');
}

echo '
<tr id="tssid">
	<td align="center">SSID :</td>
	<td align="center">
		<input type="text" id="ssid" name="ssid" value="'.$ssid.'" onkeyup="check_form()" '.(isset($_POST['edit']) ? 'readonly' : '').' />
	</td>
</tr>
<tr id="tencryption">
<td align="center">Encryption :</td>
<td align="center">
	<select id="key_mgmt" name="key_mgmt">
		<option value="NONE" '.(($key_mgmt == 'NONE') ? 'selected' : '').'>None</option>
		<option value="WEP" '.(($key_mgmt == 'WEP') ? 'selected' : '').'>WEP</option>
		<option value="WPA-PSK" '.(($key_mgmt == 'WPA-PSK') ? 'selected' : '').'>WPA/WPA2 Personal</option>
		<option value="WPA-EAP" '.(($key_mgmt == 'WPA-EAP') ? 'selected' : '').'>WPA/WPA2 Enterprise</option>
	</select>
</td>
</tr>
<tr id="tpsk" style="'.(($key_mgmt == 'WEP' || $key_mgmt == 'WPA-PSK') ? '' : 'display:none').'">
	<td align="center">PSK :</td>
	<td align="center">
		<input type="password" id="psk" name="psk" value="'.$psk.'" onkeydown="edit_password(this)" />
	</td>
</tr>
<tr id="tusername" style="'.(($key_mgmt == 'WPA-EAP') ? '' : 'display:none').'">
	<td align="center">Username :</td>
	<td align="center">
		<input type="text" id="identity" name="identity" value="'.$identity.'" onkeyup="check_form()" />
	</td>
</tr>
<tr id="tpassword" style="'.(($key_mgmt == 'WPA-EAP') ? '' : 'display:none').'">
	<td align="center">Password :</td>
	<td align="center">
		<input type="password" id="password" name="password" value="'.$password.'" onkeydown="edit_password(this)" />
	</td>
</tr>
';

?>
</table>
</h3>

<table>
<tr>
<td></td>
<td>
  <input type="submit" name='Update' value="Add/Update Network" />
  <input type="submit" name='Remove' value="Remove Network" <?php echo isset($_POST['edit']) ? '' : 'disabled'; ?> />
</td>
</table>
</center>
</form>

<script type="text/javascript">
function check_form() {
	var valid = true;

	if (document.getElementById('ssid').value.length == 0)
		valid = false;

	if ((document.getElementById('key_mgmt').value == 'WEP' || document.getElementById('key_mgmt').value == 'WPA-PSK')
	 && document.getElementById('psk').value.length == 0) {
		valid = false;
	}

	if (document.getElementById('key_mgmt').value == 'WPA-EAP'
	 && (document.getElementById('identity').value.length == 0 || document.getElementById('password').value.length == 0)) {
		valid = false;
	}

	if (!valid) {
		document.querySelector('input[name="Update"]').disabled = true;
	}
	else {
		document.querySelector('input[name="Update"]').disabled = false;
	}
}

document.addEventListener('DOMContentLoaded', function() {
	document.querySelector('select#key_mgmt').onchange = change_event_handler;
	check_form();
});

function change_event_handler(event) {
	if (event.target.value == 'NONE') {
		document.getElementById('tpsk').style = 'display:none';
		document.getElementById('tusername').style = 'display:none';
		document.getElementById('tpassword').style = 'display:none';
	}
	else if (event.target.value == 'WEP') {
		document.getElementById('tpsk').style = '';
		document.getElementById('tusername').style = 'display:none';
		document.getElementById('tpassword').style = 'display:none';
	}
	else if (event.target.value == 'WPA-PSK') {
		document.getElementById('tpsk').style = '';
		document.getElementById('tusername').style = 'display:none';
		document.getElementById('tpassword').style = 'display:none';
	}
	else if (event.target.value == 'WPA-EAP') {
		document.getElementById('tpsk').style = 'display:none';
		document.getElementById('tusername').style = '';
		document.getElementById('tpassword').style = '';
	}

	check_form();
}

function edit_password(field) {
	if (field.value == '********')
		field.value = '';

	field.removeAttribute('onkeydown');
	field.setAttribute('onkeyup', 'check_form()');

	check_form();
}
</script>

<?php require("footer.php"); ?>

<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }


require("header3.php");

$ssid = isset($_POST['ssid']) ? escapeshellarg($_POST['ssid']) : '';
$key_mgmt = isset($_POST['key_mgmt']) ? escapeshellarg($_POST['key_mgmt']) : '';
$psk = isset($_POST['psk']) ? escapeshellarg($_POST['psk']) : '';
$identity = isset($_POST['identity']) ? escapeshellarg($_POST['identity']) : '';
$password = isset($_POST['password']) ? escapeshellarg($_POST['password']) : '';
echo $key_mgmt;

exec('/opt/sensiplicity/bin/wifi_control list_networks | tail -n +2', $cli_networks);
/* array_map for associative arrays */
$cli_networks = array_reduce($cli_networks, function($result, $net) {
	$net = explode("\t", $net);

	$result[$net[1]] = $net[0];
	return $result;
}, array());

if (isset($_POST['Update'])) {
	/* Update existing network */
	if (array_key_exists($_POST['ssid'], $cli_networks)) {
		$network_id = escapeshellarg($cli_networks[$_POST['ssid']]);

		if ($key_mgmt)
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' key_mgmt '.$key_mgmt);

		if ($psk && $psk != '********')
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' psk \"'.$psk.'\"');

		if ($identity)
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' identity \"'.$identity.'\"');

		if ($password && $password != '********')
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' password \"'.$password.'\"');

		exec('/opt/sensiplicity/bin/wifi_control save_config');
	}
	else {  /* Add new network */
		$network_id = exec('/opt/sensiplicity/bin/wifi_control add_network');

		exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' ssid \"'.$ssid.'\"');
		exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' scan_ssid 1');
		exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' key_mgmt '.$key_mgmt);

		if ($_POST['key_mgmt'] === 'WEP' || $_POST['key_mgmt'] === 'WPA-PSK') {
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' psk \"'.$psk.'\"');
		}
		else if ($_POST['key_mgmt'] === 'WPA-EAP') {
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' eap PEAP');
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' identity \"'.$identity.'\"');
			exec('/opt/sensiplicity/bin/wifi_control set_network '.$network_id.' password \"'.$password.'\"');
		}

		exec('/opt/sensiplicity/bin/wifi_control enable_network '.$network_id);
		exec('/opt/sensiplicity/bin/wifi_control save_config');
	}
}
else if (isset($_POST['Remove'])) {
	if (array_key_exists($_POST['ssid'], $cli_networks)) {
		$network_id = escapeshellarg($cli_networks[$_POST['ssid']]);

		exec('/opt/sensiplicity/bin/wifi_control remove_network '.$network_id);
		exec('/opt/sensiplicity/bin/wifi_control save_config');
	}
}

header('location: list_wifi.php');
?>

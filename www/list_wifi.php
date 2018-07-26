<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
if($value == "") {
	header("location: login.php");
}

require("header_admin.php");
require("header3.php");
?>

<h3><a href="admin.php">Back To Admin Page</a> | <a href="list_wifi.php">Refresh This Page</a> </h3>

<?php
exec('/sbin/ifconfig wlan0',$return);
$strFlan0 = implode(" ",$return);
$strFlan0 = preg_replace('/\s\s+/', ' ', $strWlan0);
exec('/sbin/iwconfig wlan0',$return);
$strWlan0 = implode(" ",$return);
$strWlan0 = preg_replace('/\s\s+/', ' ', $strWlan0);
preg_match('/ether ([0-9a-f:]+)/i',$strWlan0,$result);
$strHWAddress = $result[1];
preg_match('/inet ([0-9.]+)/i',$strWlan0,$result);
$strIPAddress = $result[1];
preg_match('/netmask ([0-9.]+)/i',$strWlan0,$result);
$strNetMask = $result[1];
preg_match('/RX packets (\d+) bytes (\d+)/',$strWlan0,$result);
$strRxPackets = $result[1];
$strRxBytes = $result[2];
preg_match('/TX packets (\d+) bytes (\d+)/',$strWlan0,$result);
$strTxPackets = $result[1];
$strTxBytes = $result[2];
preg_match('/ESSID:\"([a-zA-Z0-9\_\-\s]+)\"/i',$strWlan0,$result);
$strSSID = str_replace('"','',$result[1]);
preg_match('/Access Point: ([0-9a-f:]+)/i',$strWlan0,$result);
$strBSSID = $result[1];
preg_match('/Bit Rate=([0-9.0-9]+ Mb\/s)/i',$strWlan0,$result);
$strBitrate = $result[1];
preg_match('/Tx-Power=([0-9]+ dBm)/i',$strWlan0,$result);
$strTxPower = $result[1];
preg_match('/Link Quality=([0-9]+\/[0-9]+)/i',$strWlan0,$result);
$linkQuality = explode('/', $result[1]);
$strLinkQuality = strval(round(100 * floatval($linkQuality[0]) / floatval($linkQuality[1]), 2)).'%';
preg_match('/Signal level=(-[0-9]+ dBm)/i',$strWlan0,$result);
$strSignalLevel = $result[1];
if(strpos($strWlan0, "UP") !== false && strpos($strWlan0, "RUNNING") !== false) {
	$strStatus = '<span style="color:green">Interface is up</span>';
} else {
	$strStatus = '<span style="color:red">Interface is down</span>';
}
if(isset($_GET['ifdown_wlan0'])) {
	exec('ifconfig wlan0 | grep -i running | wc -l',$test);
	if($test[0] == 1) {
		exec('/sbin/ifdown wlan0',$return);
	} else {
		echo 'Interface already down';
	}
} else if(isset($_GET['ifup_wlan0'])) {
	exec('ifconfig wlan0 | grep -i running | wc -l',$test);
	if($test[0] == 0) {
		exec('/sbin/ifup wlan0',$return);
	} else {
		echo 'Interface already up';
	}
}
echo '
<div class="infobox">
<div class="infoheader">Wireless Information and Statistics</div>
<div id="wifiinfo">
'.$strStatus.'<br />
Connected To : '.$strSSID.'<br />
Signal Strength : '.$strLinkQuality.'<br />
IP Address : '.$strIPAddress.'<br />
MAC Address : '.$strHWAddress.'<br />
<span id="wifishow" style="cursor:pointer" onClick="ToggleWifiHidden()">▶ Advanced Info</span>
<div id="wifihidden" style="display:none">
Network Mask : '.$strNetMask.'<br />
BSSID : '.$strBSSID.'<br />
Bitrate : '.$strBitrate.'<br />
Packets Sent : '.$strTxPackets.'<br />
Packets Received : '.$strRxPackets.'<br />
Bytes Sent : '.$strTxBytes.'<br />
Bytes Received : '.$strRxBytes.'<br />
</div>
</div>
</div>
';

?>

<div class="infoheader">Available Networks</div>

<table>
	<tr><td width="66%">
	</td>
	<td width="17%">
		<form method="POST" action="/manage_wifi.php">
		<input type="submit" value="Add Hidden Network" name="Connect" />
		</form>
	</td>
	<td width="17%">
		<input type="button" value="Scan for Networks" onClick="window.location.reload(true);" /><br />
	</td>
</tr>
</table>

<center>
<h4>
<table border=1 cellpadding='1' cellspacing='1'>
 <tr>
	<td align='center'>Connection Status</td>  <!-- Connected -->
	<td align='center'>SSID</td>
	<td align='center'>Encryption</td>
	<td align='center'>Connect</td>  <!-- Connect -->
	<td align='center'>Edit</td>  <!-- Edit -->
 </tr>

<?php
exec('/opt/sensiplicity/bin/wifi_control list_networks', $cli_networks);
$cli_networks = array_map(function($net) {
	return explode("\t", $net);
}, $cli_networks);

foreach ($cli_networks as $net) {
	$net_id = escapeshellarg($net[0]);
	$ssid = $net[1];
	$bssid = (count($net) > 3) ? $net[2] : '';
	$flags = $net[count($net) - 1];

	$key_mgmt = exec('/opt/sensiplicity/bin/wifi_control get_network '.$net_id.' key_mgmt');

	if ($key_mgmt == 'WPA-EAP') {
		$encryption_type = 'WPA/WPA2 Enterprise';
	}
	else if ($key_mgmt == 'WPA-PSK') {
		$encryption_type = 'WPA/WPA2 Personal';
	}
	else if ($key_mgmt == 'WEP') {
		$encryption_type = 'WEP';
	}
	else {
		$encryption_type = 'None';
	}

	echo '<tr>';

	if (strpos($flags, 'CURRENT') != false) {
		$connection_status = exec('/opt/sensiplicity/bin/wifi_control status | grep "wpa_state"');
		$connection_status = explode('=', $connection_status)[1];

		if ($connection_status == 'COMPLETED')
			echo '<td><center><img src="/images/good.gif"></center></td>';
		else
			echo '<td><center>Connecting...</center></td>';

		echo '
<td>'.$ssid.'</td>
<td>'.$encryption_type.'</td>
<td><center>
<form method="POST" action="/manage_wifi.php">
<input type="hidden" name="ssid" value="'.$ssid.'" />
<input type="hidden" name="key_mgmt" value="'.$key_mgmt.'" />
<input type="submit" name="Disconnect" value="Disconnect" />
</form>
</center></td>
		';
	}
	else {
		echo '
<td></td>
<td>'.$ssid.'</td>
<td>'.$encryption_type.'</td>
<td><center>
<form method="POST" action="/manage_wifi.php">
<input type="hidden" name="ssid" value="'.$ssid.'" />
<input type="hidden" name="key_mgmt" value="'.$key_mgmt.'" />
<input type="submit" name="Connect" value="Connect" />
</form>
</center></td>
		';
	}

	echo '
<td><center>
<form method="POST" action="/manage_wifi.php">
<input type="hidden" name="ssid" value="'.$ssid.'" />
<input type="hidden" name="edit" value="edit" />
<input type="submit" name="EditNetwork" value="Edit Network" />
</form>
</center></td>
		';

	echo '</tr>';
}

//exec('/opt/sensiplicity/bin/wifi_control scan | tail -n +2', $cli_scan);
//$cli_scan = array_map(function($net) {
//	return explode("\t", $net);
//}, $cli_scan);
//$cli_scan = array();
//
//$ssids = array();
//
//foreach ($cli_scan as $net) {
//	$bssid = $net[0];
//	$frequency = $net[1];
//	$strength = $net[2];  // DETERMINE SCALE
//	$flags = $net[3];
//	$ssid = $net[4];
//
//	$network_match = array_filter($cli_networks, function($net) use ($ssid) {
//		return $net[1] == $ssid;
//	});
//
//	$network_connected = array_filter($network_match, function($net) {
//		return count($net) > 3 && strpos($net[3], 'CURRENT') !== false;
//	});
//
//	if ($ssid == '' || in_array($ssid, $ssids) || count($network_match) > 0) 
//		continue;
//
//	$ssids[] = $ssid;
//
//	if (strpos($flags, 'WPA-EAP') !== false || strpos($flags, 'WPA2-EAP') !== false) {
//		$encryption_type = 'WPA/WPA2 Enterprise';
//		$key_mgmt = 'WPA-EAP';
//	}
//	else if (strpos($flags, 'WPA-PSK') !== false || strpos($flags, 'WPA2-PSK') !== false) {
//		$encryption_type = 'WPA/WPA2 Personal';
//		$key_mgmt = 'WPA-PSK';
//	}
//	else if (strpos($flags, 'WEP') !== false) {
//		$encryption_type = 'WEP';
//		$key_mgmt = 'WEP';
//	}
//	else {
//		$encryption_type = 'None';
//		$key_mgmt = 'NONE';
//	}
//
//	echo '<tr>';
//
//	if (count($network_connected) > 0) {
//		$connection_status = exec('/opt/sensiplicity/bin/wifi_control status | grep "wpa_state"');
//		$connection_status = explode('=', $connection_status)[1];
//
//		if ($connection_status == 'COMPLETED')
//			echo '<td><center><img src="/images/good.gif"></center></td>';
//		else
//			echo '<td><center>Connecting...</center></td>';
//
//		echo '
//<td>'.$ssid.'</td>
//<td>'.$encryption_type.'</td>
//<td><center>
//<form method="POST" action="/manage_wifi.php">
//<input type="hidden" name="ssid" value="'.$ssid.'" />
//<input type="hidden" name="key_mgmt" value="'.$key_mgmt.'" />
//<input type="submit" name="Disconnect" value="Disconnect" />
//</form>
//</center></td>
//		';
//	}
//	else {
//		echo '
//<td></td>
//<td>'.$ssid.'</td>
//<td>'.$encryption_type.'</td>
//<td><center>
//<form method="POST" action="/manage_wifi.php">
//<input type="hidden" name="ssid" value="'.$ssid.'" />
//<input type="hidden" name="key_mgmt" value="'.$key_mgmt.'" />
//<input type="submit" name="Connect" value="Connect" />
//</form>
//</center></td>
//		';
//	}
//
//
//	if (count($network_match) > 0) {
//		echo '
//<td><center>
//<form method="POST" action="/manage_wifi.php">
//<input type="hidden" name="ssid" value="'.$ssid.'" />
//<input type="hidden" name="edit" value="edit" />
//<input type="submit" name="EditNetwork" value="Edit Network" />
//</form>
//</center></td>
//		';
//	}
//	else {
//		echo '<td></td>';
//	}
//
//	echo '</tr>';
//}

?>
</table>
</center>

<script type="text/javascript">
function ToggleWifiHidden() {
	var wifiDiv = document.getElementById('wifihidden');
	if (wifiDiv.getAttribute('style') == 'display:none') {
		wifiDiv.setAttribute('style', 'display:block; position:relative; left:20px');
		document.getElementById('wifishow').innerHTML = "▼ Advanced Info";
	}
	else {
		wifiDiv.setAttribute('style', 'display:none');
		document.getElementById('wifishow').innerHTML = "▶ Advanced Info";
	}
}
</script>

<?php require("footer.php"); ?>

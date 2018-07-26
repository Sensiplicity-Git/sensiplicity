<?php
include('lock.php');
?>
<?php require("header1.php"); ?>      
<?php require("header2.php"); ?>      
<?php require("header3.php"); ?>      



    <center>

        <h3>

        <table cellpadding=6 cellspacing=0 border=1>
        <TR>
        <TD align="right" valign="middle">Hostname:</TD>
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
	        echo "        <TD>".$row['value']."</TD>\n";
	    }
	}
	?>
	<TD bgcolor="#454C43" width=2>&nbsp;</TD>
        <TD align="right" valign="middle">
        	IP Address:<br>
		LAN MAC:<br>
		<?php
			if (`/sbin/ifconfig | grep wlan0` != "") { 
				print "Wifi MAC:";
			}
		?>
	</TD>
        <TD>
        	<?php system("/sbin/ifconfig | grep 'inet' | grep -v 127.0.0.1 | awk '{print $2}'") ?><br>
        	<?php system("/sbin/ifconfig  eth0 | grep ether | awk '{print $2}'") ?><br>
        	<?php if (`/sbin/ifconfig | grep wlan0` != "") { system("/sbin/ifconfig  wlan0 | grep ether | awk '{print $2}'");} ?>
        </TD>
        </TR>

        <TR>
        <TD align="right" valign="middle">Model:</TD>
        <TD>SS-L1</TD>
	<TD bgcolor="#454C43" width=2>&nbsp;</TD>
        <TD align="right" valign="middle">Software Version:</TD>
        <TD>0.1</TD>
        </TR>

        <TR>
        <TD align="right" valign="middle">Manufacturer:</TD>
        <TD>Sensiplicity Systems<br>Oregon State University</TD>
	<TD bgcolor="#454C43" width=2>&nbsp;</TD>
        <TD align="right" valign="middle">Release Date:</TD>
        <TD>Feb 22, 2018</TD>
        </TR>

        <TR>
        <TD align="right" valign="middle">Website:</TD>
        <TD><a href="http://bpp.oregonstate.edu/">OSU Botany and Plant Pathology</a><br><a href="http://agsci.oregonstate.edu/">OSU College of Agricultural Sciences</a><br>
	<TD bgcolor="#454C43" width=2>&nbsp;</TD>
        <TD align="right" valign="middle">Serial Number:</TD>
        <TD><?php system('hostid') ?></TD>
        </TR>
        </table>
        </h3>


    <img height=300 src="/images/sensiplicity_640hight.png"></a>
  </center>

<?php require("footer.php"); ?>  

<?php
include('lock.php');
?>
<?php require("header1.php"); ?>      
<?php require("header2.php"); ?>      
<?php require("header3.php"); ?>      

<?php

// Create connection
$conn = new mysqli($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#$sql = "SELECT * FROM sensors_info ORDER BY sid DESC";
$sql = "SELECT * FROM sensors_info WHERE sensor_state = 'on' ORDER BY sid ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

$currentYear = date("Y");
$currentMonth = date("m");
$currentMonth = str_replace("^0","",$currentMonth);
$currentDay = date("d");
$currentDay = str_replace("^0","",$currentDay);
$currentHour = date("H");
$currentHour = str_replace("^0","",$currentHour);
$lowestYear = 2015;
$highestYear = $currentYear + 1;
$html_name = "";
$html_id = "";


?>

<center>
<form id="download_rawdata" name="download_rawdata" action="download_rawdata.php" method="GET" target="_blank" onsubmit="setTimeout(function () { window.location.reload(); }, 10)">
<table class="sensor_admin">
<tr><td valign="center">
<table><br><br><br>
<tr><td><button type="submit" name="TXT" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t"><b>Raw Sensor Data in Text Format</b></span></span></button></td></tr>
<tr><td><button type="submit" name="XML" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t"><b>Raw Sensor Data in XML Format</b></span></span></button></td></tr>
<tr><td><button type="submit" name="CSV" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t"><b>Raw Sensor Data in CSV Format</b></span></span></button></td></tr>
<!--<tr><td><button type="submit" name="SEN" class="Button"><span class="btn"><span class="l"></span><span class="r"></span><span class="t"><b>Raw Sensor Data in Sensatronics Text Format</b></span></span></button></td></tr> --> <!--Can't find the specification so this option isn't available for now-->
</table>
</td></tr>
</table>
</form>


<br>
<h2>Download Data From Start Date:</h2>

<table align="center" border=1 cellpadding='5' cellspacing='5'>
<form id="download_data" name="download_data" action="download_data.php" method="GET" target="_blank" onsubmit="setTimeout(function () { window.location.reload(); }, 10)">
<tr><td width="60%" align="right" valign="center">
<table>

<?php

    if ($result->num_rows > 0) {
    echo '<tr><td><b><font size=5pt>Sensor:</font></b></td><td><SELECT NAME="SensorID" >';

    while($row = $result->fetch_assoc()) {
                $sensor_id = $row["sensor_id"];
                $name = $row["sensor_id"];
                if ($row["sensor_name"] != "") {
                        $name  = $row["sensor_name"];
                }
                echo '        <OPTION VALUE="'.$sensor_id.'"';
                echo '>'.$name.'';

        }
    }

?>
</select>
</td></tr>


<tr><td>
<b><font size=5pt>Month:</font></b>
</td><td>
<select name="month">
<?php foreach(range(1,12) as $month): ?> 
<option value="<?php echo $month; ?>" <?php if($month == $currentMonth) echo ' SELECTED'; ?>><?php echo date("F",strtotime("2015-$month-01"));?></option>
<?php endforeach ?>
</select>
</td></tr>

<tr><td>
<b><font size=5pt>Day:</font></b>
</td><td>
<select name="day">
<?php foreach(range(1,31)as $day): ?>
<option value="<?php echo $day; ?>" <?php if($day == $currentDay) echo ' SELECTED'; ?>><?php echo $day;?></option>
<?php endforeach ?>
</select>
</td></tr>

<tr><td>
<b><font size=5pt>Year:</font></b>
</td><td>
<select name="year">
<?php foreach (range($lowestYear,$highestYear) as $year):?>
<option value="<?php echo $year; ?>" <?php if($year == $currentYear) echo ' SELECTED'; ?>><?php echo $year;?></option>
<?php endforeach?>
</select>
</td></tr>

<tr><td>
<b><font size=5pt>Hour:</font></b>
</td><td>
<select name="hour">
<?php foreach (range(0,23) as $hour):?>
<option value="<?php echo $hour; ?>" <?php if($hour == $currentHour) echo ' SELECTED'; ?>><?php echo $hour;?></option>
<?php endforeach?>
</select>
</td></tr>

<tr><td>
<b><font size=5pt>Minute:</font></b>
</td><td>
<select name="minute">
<?php foreach (range(0,59) as $minute):?>
<option value="<?php echo $minute; ?>"><?php echo $minute;?></option>
<?php endforeach?>
</select>
</td></tr>
</table>

</td>
<td>
<table>
<tr><td><input type="submit" name='DownloadDataCSV' value="Download CSV Data" /></td></tr>
<tr><td><input type="submit" name='DownloadDataXML' value="Download XML Data" /></td></tr>
</table>
</td>
</tr>
</table>
</form>

</center>
<?php }
else {
	echo '<h2>There are no sensors turned on under the admin section. If you want to view raw data you will need to enable some sensors.</h2>';
}?>


<?php require("footer.php"); ?>  

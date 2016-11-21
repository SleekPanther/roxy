<?php
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><h2>Add Showtime</h2></td>\n";
echo "\t\t\t\t</tr>\n";
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='lstHour'>Hour </label></td>\n";
echo "\t\t\t\t\t<td><select id='lstHour' name='lstHour' tabindex='".$tabIndex++."'";
if($showtimeHourError){echo " class='mistake' ";}
echo ">\n";
for($i=1; $i<=12; $i++){
	echo "\t\t\t\t\t\t<option value='".$i."'";
	if($showtimeHour==$i){ echo ' selected '; }
	echo ">".$i."</option>\n";
}
echo "\t\t\t\t\t</select></td>\n";
echo "\t\t\t\t</tr>\n";
echo "\t\t\t\t\n";

// if($i<10){ $i=sprintf("%02d",$i);}	//add leading zeros
?>
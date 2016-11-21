<?php
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><h2>Add Showtime</h2></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr class='showtimeRow noAlign'>\n";
echo "\t\t\t\t\t<td><label for='lstHour'>Hour </label>\n";
echo "\t\t\t\t\t<select id='lstHour' name='lstHour' tabindex='".$tabIndex++."'";
if($showtimeHourError){echo " class='mistake' ";}
echo ">\n";
for($i=1; $i<=12; $i++){
	echo "\t\t\t\t\t\t<option value='".$i."'";
	if($showtimeHour==$i){ echo ' selected '; }
	echo ">".$i."</option>\n";
}
echo "\t\t\t\t\t</select>\n";

echo "\t\t\t\t\t<label for='lstMinute'>Minute </label>\n";
echo "\t\t\t\t\t<select id='lstMinute' name='lstMinute' tabindex='".$tabIndex++."'";
if($showtimeMinuteError){echo " class='mistake' ";}
echo ">\n";
for($i=0; $i<=59; $i++){
	if($i<10){ $i=sprintf("%02d",$i);}	//add leading zeros
	echo "\t\t\t\t\t\t<option value='".$i."'";
	if($showtimeMinute==$i){ echo ' selected '; }
	echo ">".$i."</option>\n";
}
echo "\t\t\t\t\t</select>\n";

$validMeridians=array("AM","PM");
echo "\t\t\t\t\t<select id='lstMeridian' name='lstMeridian' tabindex='".$tabIndex++."'";
if($showtimeMeridianError){echo " class='mistake' ";}
echo ">\n";
foreach($validMeridians as $oneMeridian){
	echo "\t\t\t\t\t\t<option value='".$oneMeridian."'";
	if($showtimeMeridian==$oneMeridian){ echo ' selected '; }
	echo ">".$oneMeridian."</option>\n";
}
// for($i=0; $i<=59; $i++){
// 	if($i<10){ $i=sprintf("%02d",$i);}	//add leading zeros
// 	echo "\t\t\t\t\t\t<option value='".$i."'";
// 	if($showtimeHour==$i){ echo ' selected '; }
// 	echo ">".$i."</option>\n";
// }
echo "\t\t\t\t\t</select>\n";

echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t\n";

?>
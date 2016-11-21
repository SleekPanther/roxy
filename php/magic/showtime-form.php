<?php
echo "\t\t\t\t<tr class='showtimeRow noAlign'>\n";
echo "\t\t\t\t\t<td colspan='2'><label for='lstHour'>Hour </label>\n";
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
for($i=0; $i<=59; $i=$i+5){
	$i=leadingZeros($i,2);		//add leading for 2 digits
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
echo "\t\t\t\t\t</select>\n";
echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'><label for='datShowtimePosts'>Showtime Posts</label>\n";
echo "\t\t\t\t\t<input type='date' name='datShowtimePosts' id='datShowtimePosts' tabindex='".$tabIndex++."' value='".$showtimePosts."'";
if($showtimePostsError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t<label for='datShowtimeExpires'>Showtime Expires</label>\n";
echo "\t\t\t\t\t<input type='date' name='datShowtimeExpires' id='datShowtimeExpires' tabindex='".$tabIndex++."' value='".$showtimeExpires."'";
if($showtimeExpiresError){echo " class='mistake' ";}
echo ">\n";

$validDimensionss=array("2D","3D");
echo "\t\t\t\t\t<select id='lstDimension' name='lstDimension' tabindex='".$tabIndex++."'";
if($showtimeDimensionError){echo " class='mistake' ";}
echo ">\n";
foreach($validDimensionss as $onedimension){
	echo "\t\t\t\t\t\t<option value='".$onedimension."'";
	if($showtimeMeridian==$onedimension){ echo ' selected '; }
	echo ">".$onedimension."</option>\n";
}
echo "\t\t\t\t\t</select>\n";

echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t\n";

?>
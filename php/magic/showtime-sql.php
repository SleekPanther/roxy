<?php
// $query="INSERT INTO tblShowtimes (fnkMovieId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE fldHour=?, fldMinute=?, fldMeridian=?, fldShowtimePosts=?, fldShowtimeExpires=?, fldDimension=?";
// $data=array($currentMovieId,$showtimeHour,$showtimeMinute,$showtimeMeridian,$showtimePosts,$showtimeExpires,$showtimeDimension,   $showtimeHour,$showtimeMinute,$showtimeMeridian,$showtimePosts,$showtimeExpires,$showtimeDimension);
$query="INSERT INTO tblShowtimes (fnkMovieId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension) VALUES (?,?,?,?,?,?,?)";
$data=array($currentMovieId,$showtimeHour,$showtimeMinute,$showtimeMeridian,$showtimePosts,$showtimeExpires,$showtimeDimension);
$thisDatabaseWriter->testquery($query,$data,0);
$thisDatabaseWriter->insert($query,$data,0);
?>
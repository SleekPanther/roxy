<?php
$_SESSION['reviewAuthor']='';
$_SESSION['reviewDate']=date('Y-m-d', strtotime('today'));
$_SESSION['reviewSource']='';
$_SESSION['reviewLink']='';
$_SESSION['review']='';

$reviewAuthorError=false;
$reviewDateError=false;
$reviewSourceError=false;
$reviewLinkError=false;
$reviewError=false;
?>
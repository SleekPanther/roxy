<?php
if (!securityCheck(THIS_URL)) {
    $msg = "<p>Sorry you cannot access this page. ";
    $msg.= "Security breach detected and reported.</p>";
    die($msg);
}
?>
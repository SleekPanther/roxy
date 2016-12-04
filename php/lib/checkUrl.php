<?php
//can't use with advanced forms since they have GET variables & don't match securityCheck's URL
if (!securityCheck(THIS_URL)) {
    $msg = "<p>Sorry you cannot access this page. <br>Security breach detected and reported.</p>\n";
    die($msg);
}
?>
<?php
include "lib/constants.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Merril's Roxy Cinema</title>
    <meta charset="utf-8">
    <meta name="author" content="Noah Patullo">
    <meta name="description" content="Merril's Roxy Cinema">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
    <![endif]-->

    <?php
    $directories =explode("/",$PATH_PARTS['dirname']);
    $parentFolder=$directories[count($directories)-1];

    $cdPlaceholder='';      //holds ../ if 1 directory down, otherwise just empty
    //include special libraries for admin page
    if($parentFolder=='magic'){
        $cdPlaceholder='../';

        //get the current user (uvm id)
        //$username = strtolower(htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8"));     //also convert to lowercase to be safe
        include "lib/net-id-conversion.php";
        require "lib/validation-functions.php";
        require "lib/security.php";
    }
    echo "\t\t<link rel='stylesheet' href='".$cdPlaceholder."css/style.css' type='text/css' media='screen'>\n";

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // inlcude all libraries. 
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

    print "<!-- require Database.php -->\n";

    require_once(BIN_PATH . '/Database.php');

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // Set up database connection
    //
    // generally you dont need the admin on the web

    print "<!-- make Database connections -->\n";

    $dbUserName = get_current_user() . '_reader';
    $whichPass = "r"; //flag for which one to use.
    $dbName = DATABASE_NAME;

    $thisDatabaseReader = new Database($dbUserName, $whichPass, $dbName);

    $dbUserName = get_current_user() . '_writer';
    $whichPass = "w";
    $thisDatabaseWriter = new Database($dbUserName, $whichPass, $dbName);

    ?>	

</head>

<!-- **********************     Body section      ********************** -->
<?php
print '<body id="' . $PATH_PARTS['filename'] . '">';
include "header.php";
include "nav.php";
?>
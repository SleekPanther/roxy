<?php
include "lib/constants.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Merril's Roxy Cinema</title>
    <meta charset="utf-8">
    <meta name="author" content="Noah Patullo">
    <meta name="description" content="Merril's Roxy Cinema">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    require 'supplementary/helper-functions.php';

    $split_url = explode('/', $PATH_PARTS['dirname']);  //split dirname part of the array at each / character (creates array)

    $baseLevelIndex = 0;        //used to find the "base directory" in the url. If the site's home is in "topLevel/level1/level2/ROOT_SITE_FOLDER_HERE" then it's 3 folders down, so everything should relate the the url array from index 3. We iterate through the URL array to find the $ROOT_FOLDER, then adjust and make a new array
    for ($i = 0; $i < count($split_url); $i++){     //loop through the URL
        if ($split_url[$i] == ROOT_DIRECTORY){     //SUPER IMPORTANT ($ROOT_DIRECTORY must match the BASE folder that the site lives inside)
            $baseLevelIndex = $i;
             break;    //This stops when the 1st occurence of $ROOT_DIRECTORY is found. COMMENT OUT OR REMOVE THIS  break;  if your actual root directory has a parent folder with the exact same name
        }
    }

    $folderCount = count($split_url); //this gives an int of how many folders are in the URL
    $folderCountAdjusted = $folderCount - $baseLevelIndex - 1; //subtract $baseLevelIndex to get the base directory (no matter how deep the file structure, this resets it to a base folder. Then subtract 1 to make the "home" directory be 0 folders up from anything
    //0 means the homepage, 1 means top level pages (file is located in 1 folder below $ROOT_DIRECTORY), 2 means 2 levels down, etc.
    $split_url_adjusted=array();
    for($i=($folderCount - $folderCountAdjusted -1); $i<count($split_url); $i++){     //copy latter part of the URL array to a new array
        $split_url_adjusted[]=$split_url[$i];
    }

    $containing_folder = $split_url_adjusted[count($split_url_adjusted) -1] ; //IMPORTANT this gets the very last folder in the $split_url_adjusted array (the very last index of an array is 1 less than its size, hence: count($split_url_adjusted) -1 ). This folder "contains" the current page file. Used almost everywhere to tell what page I'm on since all my pages are called 'index.php' but have unique cotaining-folder names
    if($folderCountAdjusted == 0){      //special case for the homepage. Since its actual containing folder is the contents of $ROOT_DIRECTORY, it must be overridden to equal "index". This is to avoid confusion if $ROOT_DIRECTOY is NOT a a good name for the site. This disregards where the site is located & just make the homepage's containing folder = "index". ALSO USED TO PRINT ID'S IN THE BODY TAG FOR EACH PAGE
        $containing_folder = 'index';
    }

    $upFolderPlaceholder='';
    for($i=0; $i<$folderCountAdjusted; $i++){
        $upFolderPlaceholder.='../';      //append ../ for how many levels the currrent folder is below the root
    }

    $directories =explode("/",$PATH_PARTS['dirname']);
    $parentFolder=$directories[count($directories)-1];

    if($parentFolder=='magic'){     //get username since they must be logged in
        $username = strtolower(htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8"));
    }
    include $upFolderPlaceholder."php/lib/net-id-conversion.php";
    require $upFolderPlaceholder."php/lib/validation-functions.php";
    require $upFolderPlaceholder."php/lib/security.php";
    require $upFolderPlaceholder."php/lib/mail-message.php";

    echo "\t\t<link rel='stylesheet' href='".$upFolderPlaceholder."css/style.css' type='text/css' media='screen'>\n";

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

    //htaccess only allows certain people. But this gets the netid to display admin links only if they're an admin
    $validAdmins=array('npatullo', 'rerickso', 'ylin19');
    $netId='';      //initialize variable to empty since $_SERVER["REMOTE_USER"] is unset if they're not logged in
    if(isset($_SERVER["REMOTE_USER"])){
        $netId=strtolower(htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8"));  //get uvm netid
        if (in_array($netId, $validAdmins)){
            $_SESSION['loggedIn']=true;
        }
    }
    ?>
</head>

<!-- **********************  Body section  ********************** -->
<?php
//print correct php includes
if($parentFolder=='magic'){
    print "<body id='" . $parentFolder . "'>\n";
}
else{
    print "<body id='" . $PATH_PARTS['filename'] . "'>\n";
}
echo "\t<section class='container'>\n";
include "header.php";
// if($parentFolder=='magic'){
//     include "nav-magic.php";
// }else{
    include "nav.php";
// }


//$upFolderPlaceholder ===== convert later using php-magic-linking
?>
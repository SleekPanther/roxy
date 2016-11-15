<?php
print "<!--  BEGIN include security -->\n";
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// performs a simple security check to see if our page has submitted the form to itself
function securityCheck($formURL = "") {
    $debugThis = false;  // you have to spcefically want to test this
    
    $status = true; // start off thinking everything is good until a test fails
    
    // when it is a form page check to make sure it submitted to itself
    if ($formURL != "") {
        $fromPage = htmlentities($_SERVER['HTTP_REFERER'], ENT_QUOTES, "UTF-8");
        
        //remove http or https
        $fromPage = preg_replace('#^https?:#', '', $fromPage);
        
        if ($debugThis)
            print "<p>From: " . $fromPage . " should match your Url: " . $formURL;
        
        if ($fromPage != $formURL) {
            $status = false;
        }
    }
    
    return $status;
}
print "<!--  END include security -->\n";
?>
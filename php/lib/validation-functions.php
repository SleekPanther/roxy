<?php
print "<!--  BEGIN include validation-functions -->\n";
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// series of functions to help you validate your data. notice that each
// function returns true or false
function verifyAlphaNum ($testString) {
	// Check for letters, numbers and dash, period, space and single quote only. 
	return (preg_match ("/^([[:alnum:]]|-|\.| |')+$/", $testString));
}	
function verifyEmail ($testString) {
	// Check for a valid email address http://www.php.net/manual/en/filter.examples.validation.php
	return filter_var($testString, FILTER_VALIDATE_EMAIL);
}
function verifyNumeric ($testString) {
	// Check for numbers and period. 
	return (is_numeric ($testString));
}
function validateSqlDate($testString){
	if(strlen($testString) !=10){
		return false;
	}
	//if it's the correct length, now check a regular expression
	//regex to match 4 digits, 1 hyphen, 2 digits, 1 hyphen & then 2 digits (a mySql date). (The ^ negates the entire thing to find things that DON'T match)
	return (preg_match("/^\d{4}-\d{2}-\d{2}/", $testString));
}
function verifyPhone ($testString) {
	// Check for usa phone number http://www.php.net/manual/en/function.preg-match.php
        $regex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
	return (preg_match($regex, $testString));
}
print "<!--  END include validation-functions -->\n";
?>
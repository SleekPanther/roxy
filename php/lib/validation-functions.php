<?php
print "<!--  BEGIN include validation-functions -->\n";
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// series of functions to help you validate your data. notice that each
// function returns true or false
function verifyAlphaNum ($testString) {
	// Check for letters, numbers and dash, period, space and single quote only. 
	return (preg_match ("/^([[:alnum:]]|-|\.| |')+$/", $testString));
}

//verify long list of characters INCLUDING newlines
function verifyAlphaNumNewline($testString) {
	// /^[\r\n0-9a-zA-Z \/_:,.?@;-]+$/
	return (preg_match ("/^[\r\n0-9a-zA-Z ~`!@#$%^&*()\-_+=|;:',.?\/]+$/", $testString));
}

function verifyEmail ($testString) {
	// Check for a valid email address http://www.php.net/manual/en/filter.examples.validation.php
	return filter_var($testString, FILTER_VALIDATE_EMAIL);
}
function verifyNumeric ($testString) {
	// Check for numbers and period. 
	return (is_numeric ($testString));
}

//match a mysql date YYYY-MM-DD
function validateSqlDate($testString){
	//one attempt. works for most https://coderwall.com/p/jmarug/regex-to-check-for-valid-mysql-datetime-format
	// ^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])(?:( [0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$
	
	//http://www.webdeveloper.com/forum/showthread.php?178277-Decent-Mysql-datetime-regular-expression#postcount1063678 4th reply, 2nd option 
	$regex="/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|11)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468][048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(02)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))?$/";
	return (preg_match($regex, $testString));
}

//validate 1st date is less than 2nd
function validateDateRange($date1, $date2){
	return ( strtotime($date1) - strtotime($date2) ) <= 0;	//should be a negative number, or 0 if posts=expiration
}

function verifyPhone ($testString) {
	// Check for usa phone number http://www.php.net/manual/en/function.preg-match.php
        $regex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
	return (preg_match($regex, $testString));
}
print "<!--  END include validation-functions -->\n";
?>
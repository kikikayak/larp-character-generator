<?php

/*************************************************************
NAME: 	library.php
NOTES:	Library of useful PHP functions.
**************************************************************/ 


// This function courtesy of manchimen [at) yahoo [dot) com on 11/1/2010
// from the comments here: http://www.php.net/manual/en/function.array-search.php
function multi_array_search($needle, $haystack) {
	echo 'Needle: ' . $needle . ', haystack: ' . $haystack . '<br />';
	if (empty($needle) || empty($haystack)) {
		return false;
	}
	foreach ($haystack as $key => $value) {
		$exists = 0;
		foreach ($needle as $nkey => $nvalue) {
			if (!empty($value[$nkey]) && $value[$nkey] == $nvalue) {
				$exists = 1;
			} else {
				$exists = 0;
			}
		}
		if ($exists) return $key;
	}
   
	return false;
}

// Wrapper function for escaping data to be inserted into DB
// Requires open database handle ($dbh) and value to be escaped. 
function db_escape($val, $dbh) {
	return $dbh->real_escape_string($val);
}

// This function borrowed from this site: 
// http://phpsec.org/articles/2005/password-hashing.html
function generateHash($plainText, $salt = null) {
    if ($salt === null) {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    } else {
        $salt = substr($salt, 0, SALT_LENGTH);
    }

    return $salt . sha1($salt . $plainText);
}

function generateTmpPassword() {
	$pwdLength = rand(8, 12); // Temp password will be 8-12 characters long
	$tmpPwd = substr(md5(uniqid(rand(), true)), 0, $pwdLength);
	return $tmpPwd;	
}

// This function borrowed from Chris Shiflett at http://http://shiflett.org 
// http://shiflett.org/blog/2005/oct/convert-smart-quotes-with-php
function convert_smart_quotes($string) { 
    // echo 'Original string: ' . $string . '<br />';
    // echo 'Characters to search: ' . chr(145) . ' ' . chr(146) . ' ' . chr(147) . ' ' . chr(148) . ' ' . chr(151) . ' ' . '<br />';
    $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 
 
    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-'); 
 
    $convertedString = str_replace($search, $replace, $string);
    /* echo 'Converted string: ' . $convertedString . '<br /';
    if ($string == $convertedString) {
    	echo 'No changes. <br />';
    } else {
    	"Made changes. <br />";
    } */
    return $convertedString; 
}

/****************************************************************
UTILITY FUNCTIONS
*****************************************************************/

/* 
	$err_key_name: string key name (should be the same as the input field's id)
	$style: optional string list of style attributes to be written in a style tag
*/
function cg_createRow($err_key_name, $style = '') {
	// echo '<div class="row">';
	$row = '<a name="' . $err_key_name . 'Anchor"></a>';
	$row .= '<div id="' . $err_key_name . 'Row"';
	if (isset($_SESSION['UIMessage']) && !empty($_SESSION['UIMessage']) && array_key_exists($err_key_name, $_SESSION['UIMessage']->errorList)) {
		$row .= ' class= "row error"';
	} else {
		$row .= ' class= "row"';
	}
	if ($style != '') {
		$row .= ' style = "' . $style . '"';
	}
	$row .= '>'; // Closing bracket for div tag
	echo $row;
}

function cg_showError($err_key_name) {
	if (isset($_SESSION['UIMessage']) && !empty($_SESSION['UIMessage']) && array_key_exists($err_key_name, $_SESSION['UIMessage']->errorList)) {
		echo '<span class="errorMsg">' . $_SESSION['UIMessage']->errorList[$err_key_name]['error'] . '</span>';
	}
}

function cg_showUIMessage() {
	if (isset($_SESSION['UIMessage']) && $_SESSION['UIMessage'] != '') {
		$_SESSION['UIMessage']->displayMessage();
	}
}

function cg_clearUIMessage() {
	if (isset($_SESSION['UIMessage'])) {
		unset($_SESSION['UIMessage']);
	}
}

function cg_createHelp($id,$content) {
	echo '
		<div id="' . $id . '" class="help" style="display: none">
			<div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
			<div class="helpContent">' .
				$content
			. '</div>
			<div class="helpBottom"></div>
		</div>
		';
}

/****************************************************************
VALIDATION FUNCTIONS
*****************************************************************/

/* autop function provided by photomatt (http://photomatt.net/scripts.php/autop). Notes from his site: 
Call this function on the text you want to convert. Think of this code like nl2br on steroids. 
This is basically a cross-platform set of regular expressions that takes text formatted only by newlines and transforms it into text 
properly marked up with paragraph and line break tags. The line break part can also be turned off if you want.

To call this function simply use autop($text) in your code somewhere, or autop($text, 0) if you’d like to use it without the 
line break functionality enabled. It will not echo out the text by itself so you can either assign the parsed text to a variable or echo it out yourself.

The new “extended” version offers smarter paragraphs and breaks that are mindful of block-level HTML tags. 
If you have legacy content with these tags, or you would just like to be able to drop a tag in there every now and then, try that one out.	
*/ 
function autop($pee, $br = 1) {
	$pee = $pee . "\n"; // just to make things a little easier, pad the end
	$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
	$pee = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $pee); // Space things out a little
	$pee = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $pee); // Space things out a little
	$pee = preg_replace("/(\r\n|\r)/", "\n", $pee); // cross-platform newlines 
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "\t<p>$1</p>\n", $pee); // make paragraphs, including one at the end 
	$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace 
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee); 
	if ($br) $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
	$pee = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br />!', "$1", $pee);
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $pee);
	$pee = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $pee);
	
	return $pee; 
}

function is_empty($val) {
	if (trim($val) == '') {
		return true;
	} else {
		return false;
	}
}

function isValidEmail($email) {
	// The below is an email validation regex composited from examples in Ben Forta's book
	// and user notes on the preg_match() function on PHP.net. 
	// Amended to allow dashes and underscores 5/8/06. 
	if (preg_match('/^(([A-Za-z0-9\-\_]+\.)*[A-Za-z0-9\-\_]+@([A-Za-z0-9\-\_]+\.)+[A-Za-z]{2,6})?[ ]*$/', $email)) {
		return true;
	}
	else {
		return false;
	}
}

// The below is my own regex for free text fields. 
// It is intended to be more restrictive than isValidComment(). 
// Use for single-line entry fields and fields in public areas. 
function isValidText($text) {
	if (preg_match('/^[A-Za-z0-9 \.\,\#\"\'\(\)\-\_]*$/', $text)) {
		return true;
	}
	else {
		return false;
	}
}

// The below is my own regex for free text fields. 
// It is intended to be more restrictive than isValidComment(). 
// Use for single-line entry fields and fields in public areas. 
function isValidTextAdmin($text) {
	if (preg_match('/^[A-Za-z0-9 \.\,\#\"\'\(\)\-\_\!]*$/', $text)) {
		return true;
	}
	else {
		return false;
	}
}

// Sting $val
// This function is intended to be less restrictive than isValidText().
// Use for free entry fields in secure areas, or fields that will
// collect data with quotations, punctuation, etc. 
function isValidTextArea($val) {
	if (preg_match('/^[A-Za-z0-9 \(\)\.\,\"\'\?\!\:\;\-\_\<\>\*\/\n\r]*$/', $val)) {
		return true;
	}
	else {
		return false;
	}
}

// Sting $val
// Checks whether or not the specified password is valid 
function isValidPassword($val) {
	if (preg_match('/^[A-Za-z0-9\-\_\!\@\#\$]{6,20}$/', $val)) {
		return true;
	}
	else {
		return false;
	}
}

// String $val
// Checks whether or not the specified temporary password is valid 
function isValidTmpPassword($val) {
	if (preg_match('/^[A-Za-z0-9]{1,20}$/', $val)) {
		return true;
	} else {
		return false;
	}
}

// String $val
// Use this for validating URLs and Web addresses. 
function isValidURL($val) {
	if (preg_match('/^[A-Za-z0-9\.\%\/\-\_\:]*$/', $val)) {
		return true;
	}
	else {
		return false;
	}
}

// String $val
// Use this for validating textarea fields
// that need to accept HTML tags. 
function isValidHTMLTextArea($val) {
	if (preg_match('/^[A-Za-z0-9 \(\)\.\,\;\"\'\?\!\<\>\%\&\/\n\r]*$/', $val)) {
		return true;
	}
	else {
		return false;
	}
}

function isCorrectVitality($attribute2, $attribute5, $userVitality) {
	// Correct vitality = average of earth and void, rounded down
	$correctVitality = floor(($attribute2 + $attribute5) / 2); 
	if ($userVitality != $correctVitality) {
		return false;
	} else {
		return true;
	}
}

	
?>

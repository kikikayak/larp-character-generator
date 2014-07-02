<?php 
/************************************************************************
NAME: 	Debug.class.php
NOTES:	Class for creating/displaying debug info in the UI.  
*************************************************************************/


// require_once('includes/config.php'); // load configuration

class Debug {
	
	// $msgClass: String class to be used in output HTML. 
	// $msgContent: String (HTML) for message body. 
	// $msgBox: String (HTML) of final message to display in box at top of the page. 
	public $msgClass, $msgContent, $debugItems, $msgBox;
	
	// $msgType: string 'error' or 'success'
	// $msgHead: string text for header at top of error/confirm box
	// $msgContent: string body text. Can include HTML tags for formatting. 
	// $errorList: array of error fields and error messages, in the format $errorList[$fieldname]['error'] = errorMsg, $errorList[$fieldname]['fldLbl'] = display name of field
	function __construct($msgClass, $msgContent, $debugItems = array()) {
		
		$this->msgClass = $msgClass;
		$this->msgContent = $msgContent;
		$this->debugItems = $debugItems; 
		
	} // end of constructor
	
	public function outputDebug() {
		
		// Only output if debugging is turned on in config.php
		if (DEBUG == 'on') {
			$this->msgBox = '<div id="debugMessage" class="' . $this->msgClass . '"> ' .
				'<div class="content"><h2>Debug Information</h2> ' . $this->msgContent;
							
			// Build array of debug items (if they exist)
			if (count($this->debugItems) > 0) {
				$this->msgBox .= '<ul id="debugItems">';
				foreach ($this->debugItems as $val) {
					$this->msgBox .= '<li>'  . $val . '</li>';
				}
				$this->msgBox .= '</ul>';
			}
			
			$this->msgBox .= '</div> ' .
					'</div>';
			
			echo $this->msgBox;
		}
		
		return $this->debugItems;
	
	} // end of outputDebug function
	
} // end of class

?>
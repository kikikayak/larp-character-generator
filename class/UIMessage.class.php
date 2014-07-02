<?php 
/************************************************************************
NAME: 	UIMessage.class.php
NOTES:	Classes for creating/displaying messages in the UI.  
*************************************************************************/


// require_once('includes/config.php'); // load configuration

class UIMessage {
	
	// $msgType: String type of message: 'error' or 'success'
	// $msgHead: String to display as message header
	// $msgContent: String (HTML) for message body. 
	// $msgBox: String (HTML) of final message to display in box at top of the page. 
	public $msgType, $msgHead, $msgContent, $errorList, $msgBox;
	
	// $msgType: string 'error' or 'success'
	// $msgHead: string text for header at top of error/confirm box
	// $msgContent: string body text. Can include HTML tags for formatting. 
	// $errorList: array of error fields and error messages, in the format $errorList[$fieldname]['error'] = errorMsg, $errorList[$fieldname]['fldLbl'] = display name of field
	function __construct($msgType, $msgHead, $msgContent, $errorList = array()) {
		
		$this->msgType = $msgType;
		$this->msgHead = $msgHead;
		$this->msgContent = $msgContent;
		$this->errorList = $errorList; 
		
	} // end of constructor
	
	public function displayMessage() {
		
		/* Escape values for display
		$html = array(); // Initialize blank
		$html['msgType'] = htmlentities($this->msgType);
		$html['msgHead'] = htmlentities($this->msgHead);
		$html['msgContent'] = htmlentities($this->msgContent);
		*/

		$this->msgBox = '<div id="UIMessage" class="' . $this->msgType . '"> ' .
			'<div class="top"></div> ' .
			'<div class="content"> ' .
				'<h2>' . $this->msgHead . '</h2> ' .
				$this->msgContent;
						
		// Build array of field errors (if they exist)
		if (count($this->errorList) > 0) {
			$this->msgBox .= '<ul id="errorList">';
			foreach ($this->errorList as $fld => $val) {
				$this->msgBox .= '<li><strong><a href="#' . $fld . '">'  . $this->errorList[$fld]['fldLbl'] . '</a></strong>: ' . $this->errorList[$fld]['error'] . '.</li>';
			}
			$this->msgBox .= '</ul>';
		}
		
		$this->msgBox .= '</div> ' .
					'<div class="bottom"></div> ' .
				'</div>';
		
		echo $this->msgBox;
		
		return $this->errorList;
	
	} // end of displayMessage function
	
} // end of class

?>
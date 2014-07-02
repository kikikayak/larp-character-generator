<?php 

/********************************************************************
NAME: 	faq.html
NOTES:	Frequently Asked Questions for the Character Generator. 
*********************************************************************/

session_start();

require_once('includes/config.php');
require_once(LOCATION . 'includes/library.php');
require(LOCATION . 'class/classloader.php');
	
$user = new Login();
$user->initSession();

$title = "Frequently Asked Questions | " . $_SESSION['campaignName'] . "Character Generator";

require('includes/uaHeader.php');

?>

<body id="faqPage">

	<div id="faq">

		<h2>Frequently Asked Questions</h2>

		<h3>What do I need to do to use the Character Generator?</h3>
		<p>The Endgame staff needs to create a user name and password for you before you can log in. Please <a href="newPlayer.html">request a user name</a> from the staff; you will receive an email when your user name has been created. Please allow several days for your user name to be created. </p>
		<h3>What if I have problems?</h3>
		<p>Logging out of the Character Generator will resolve most problems. To 
		log out, click the "Log out" link on any page. Please try logging 
		out and logging back in before contacting the Webmaster. </p>
		<p>If you are still having problems, please email the <a href="#">Webmaster</a>. 
		Please do not email the mailing list. </p>

		<h3>What are the system requirements for the generator?</h3>
		<p>The Generator should work on any modern Web browser, including Internet Explorer 6+, Firefox, Safari, Opera, and Chrome. </p>
		<p>You must have cookies and JavaScript enabled in your browser. </p>
		<p>A fast Internet connection (e.g. cable modem, DSL) is helpful but not necessary. If you have any problems, 
		please email the <a href="#">Webmaster</a>.</p>

		<h3>Do I have to use the Character Generator?</h3>
		<p>The Character Generator is our standard way of maintaining and updating characters. But, if you do not have Web access or would prefer not to use the Character Generator for any reason, please contact the staff at <a href="#">pirateIsland@comcast.net</a>. </p>

		<h3>How many characters can I have in the Generator></h3>
		<p>The Character Generator only allows you to maintain one character 
		at a time. </p>
		<p>You can use the character creation wizard to experiment as much as you 
		want, but please do not actually save your character until you are sure 
		your headers, skills, and spells are correct. </p>
		<p>Once your character is saved, you can update roleplaying information 
		and add as many headers, skills, and spells as your CP allows, but you 
		will need to contact the game staff to delete a character or remove any skills you selected 
		by mistake. </p>

		<h3>Can I change my character once I have saved it?</h3>
		<p>You can update your roleplaying information (like your character's name, 
		family and goals) at any time, and we encourage you to do so whenever 
		it changes. You can also purchase additional headers, skills, and spells 
		at any time, provided you have enough free CP. </p>
		<p>Per game rules, you can not remove headers, skills, and spells after you have 
		purchased them. If you need to make a change to your character after this point, please contact the game staff. </p>
		<p class="note"><strong>Note</strong>: We print character cards 1-2 days before each 
		  event begins. Any changes you make after this deadline may not be reflected 
		  at check-in.</p>

		<a href="#">Back to top</a>
	</div><!--end of faq div-->

	<?php require('includes/uaFooter.php'); ?>

</body>
</html>
<?php

	/**************************************************************
	NAME: 	uaFooter.php
	NOTES: 	Footer for pages user sees when not logged in (reset password, login, etc)
	**************************************************************/

?>

<div id="footer">
	<p>Copyright &copy; <?php echo date('Y') . ' ' . $_SESSION['campaignName']; ?> <br />
	Information: <a href="mailto:<?php echo $_SESSION['contactEmail']; ?>"><?php echo $_SESSION['contactName']; ?></a><br />
	<a href="mailto:<?php echo $_SESSION['webmasterEmail']; ?>">Webmaster</a> | <a href="#" id="aboutLink">About</a> </p>
</div><!--end of footer div-->

<?php include('about.php'); ?>

<?php cg_clearUIMessage(); ?>

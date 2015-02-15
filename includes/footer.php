<?php

	/**************************************************************
	NAME: 	footer.php
	NOTES: 	Footer for pages in main authenticated area.
	**************************************************************/

?>    
	
	<div id="footer">
		<div class="contact">
			<p><a href="mailto:<?php echo $_SESSION['contactEmail']; ?>">Campaign &amp; Logistics Questions</a>
			&nbsp; &nbsp; | &nbsp; &nbsp;
			<a href="mailto:<?php echo $_SESSION['webmasterEmail']; ?>">Technical Issues</a>
			&nbsp; &nbsp; | &nbsp; &nbsp;
			<a href="#" id="aboutLink">About the Character Generator</a>
		</div>
		<div class="legal">
			<p>Game rules copyright &copy; <?php echo date('Y') . ' ' . $_SESSION['campaignName']; ?> (under license from Accelerant) <br />
	        The <a href="http://larpcharactergenerator.com" target="_blank">Character Generator</a> is free software created by Allison B. Corbett
	        and licensed under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU Public License v3.0.</a></p>
		</div>
		
    </div><!--/footer-->
	
	<?php include('about.php'); ?>

	<script type="text/javascript">
    //define function to be executed on document ready
    $(function(){
		init();
    });
    </script>
    
    <?php cg_clearUIMessage(); ?>
	
</body>
</html>

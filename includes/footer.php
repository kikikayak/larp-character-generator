<?php

	/**************************************************************
	NAME: 	footer.php
	NOTES: 	Footer for pages in main authenticated area.
	**************************************************************/

?>    
	
	<div id="footer">
    	<p>Copyright &copy; <?php echo date('Y') . ' ' . $_SESSION['campaignName']; ?> <br />
		Information: <a href="mailto:<?php echo $_SESSION['contactEmail']; ?>"><?php echo $_SESSION['contactName']; ?></a><br />
		<a href="mailto:<?php echo $_SESSION['webmasterEmail']; ?>">Webmaster</a> | <a href="#" id="aboutLink">About</a> </p>
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

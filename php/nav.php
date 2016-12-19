
    <!-- ######################     Main Navigation   ########################## -->
    <nav>
    <!--if username=in username array (npatullo, rerickso, yling19, ), then show extra link -->
        <a href="<?php echo $upFolderPlaceholder;?>index.php">Home</a>
        <a href="<?php echo $upFolderPlaceholder;?>synopses.php">Synopses</a>
        <a href="<?php echo $upFolderPlaceholder;?>reviews.php">Reviews</a>
        <a href="<?php echo $upFolderPlaceholder;?>soon.php">Coming Soon</a>
        <a href="<?php echo $upFolderPlaceholder;?>prices.php">Prices</a>
        <a href="<?php echo $upFolderPlaceholder;?>newsletter.php">Newsletter</a>
        <a href="<?php echo $upFolderPlaceholder;?>about.php">About</a>
    	<?php
    	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){		//only print admin links if they're an admin
    	   echo "<a href='".$upFolderPlaceholder."magic/index.php'>Admin</a>\n";
	    }        
	    ?>
    </nav>
    <!-- #################### Ends Main Navigation    ########################## -->

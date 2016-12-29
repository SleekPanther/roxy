
    <!-- ######################     Main Navigation   ########################## -->
    <nav>
    <!--if username=in username array (npatullo, rerickso, yling19, ), then show extra link -->
        <a href="<?php echo $upFolderPlaceholder;?>index.php">Home</a>
        <a href="<?php echo $upFolderPlaceholder;?>synopses/index.php">Synopses</a>
        <a href="<?php echo $upFolderPlaceholder;?>reviews/index.php">Reviews</a>
        <a href="<?php echo $upFolderPlaceholder;?>soon/index.php">Coming Soon</a>
        <a href="<?php echo $upFolderPlaceholder;?>prices/index.php">Prices</a>
        <a href="<?php echo $upFolderPlaceholder;?>newsletter/index.php">Newsletter</a>
        <a href="<?php echo $upFolderPlaceholder;?>about/index.php">About</a>
    	<?php
    	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){		//only print admin links if they're an admin
    	   echo "<a href='".$upFolderPlaceholder."magic/index.php'>Admin</a>\n";
	    }        
	    ?>
    </nav>
    <!-- #################### Ends Main Navigation    ########################## -->

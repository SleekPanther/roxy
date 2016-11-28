
    <!-- ######################     Main Navigation   ########################## -->
    <nav>
    <!-- public links for rest of site go here
    but have 1;eve; down nav here

    if username=in username array (npatullo, rerickso, yling19, ), then show extra link -->
        <a href="<?php echo $upFolderPlaceholder;?>index.php">Home</a>
        <a href="<?php echo $upFolderPlaceholder;?>synopses.php">Synopses</a>
        <a href="<?php echo $upFolderPlaceholder;?>reviews.php">Reviews</a>
        <a href="<?php echo $upFolderPlaceholder;?>soon.php">Coming Soon</a>
        <a href="<?php echo $upFolderPlaceholder;?>prices.php">Prices</a>
        <a href="<?php echo $upFolderPlaceholder;?>menu.php">Menu?</a>
        <a href="<?php echo $upFolderPlaceholder;?>about.php">About</a>
    	<?php
    	if (in_array($netId, $validAdmins)){		//only print admin links if they're an admin
		?>
        	<a href="<?php echo $upFolderPlaceholder;?>magic/index.php">Admin</a>
        <?php
	    }
	    ?>
    </nav>
    <!-- #################### Ends Main Navigation    ########################## -->

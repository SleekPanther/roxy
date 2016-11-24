    <!-- ######################     Main Navigation   ########################## -->
    <nav>
    <!-- public links for rest of site go here
    but have 1;eve; down nav here

    if username=in username array (npatullo, rerickso, yling19, ), then show extra link -->
        <a href="index.php">Home</a>
    	<?php
    	if (in_array($netId, $validAdmins)){		//only print admin links if they're an admin
		?>
        <a href="magic/index.php">Add/Edit Movies (admin)</a>
        <?php
	    }
	    ?>
    </nav>
    <!-- #################### Ends Main Navigation    ########################## -->


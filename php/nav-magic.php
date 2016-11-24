    <!-- ######################     Main Navigation   ########################## -->
    <nav>
    <!-- public links for rest of site go here
    but have 1;eve; down nav here
    & only print admin on this 1level down nav -->
        <a href="../index.php">Home</a>
    	<?php
    	if (in_array($netId, $validAdmins)){		//only print admin links if they're an admin
		?>
        <a href="index.php">Add/Edit Movies (admin)</a>
        <?php
	    }
	    ?>
    </nav>
    <!-- #################### Ends Main Navigation    ########################## -->


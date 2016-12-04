<?php
session_start();
include	"php/top.php";

$tabIndex=1;	//increment for every form element

$email='';
$emailError=false;

$errorMsg = array();

$dataRecord = array();

$mailed = false;

if (isset($_POST["btnSubmit"])) {
	include $upFolderPlaceholder.'php/lib/checkUrl.php';	//validate form came from same page

	$email=htmlentities($_POST['txtEmail'], ENT_QUOTES, "UTF-8");

	if($email==''){
		$errorMsg[]='Email cannot be empty';
		$emailError=true;
	}elseif (!verifyEmail($email)) {
		$errorMsg[]='Please enter a valid email';
		$emailError=true;
	}

	if(!$errorMsg){
		$query="INSERT INTO tblRecipients (pmkEmail) VALUES (?)";
		$data=array($email);
		$success=$thisDatabaseWriter->insert($query,$data,0);

		if(!$success){
			$errorMsg[]='That email is already on our list';
			$emailError=true;
		}

		// echo "success ".$success." 22";
		// if($success){
		// 	echo ' 1235 ';
		// }
		if(!$errorMsg){		//secondary check to make sure they entered a unique email
			$successMsg = "<h2>Thanks for signing up</h2>\n";

			$message=$successMsg."<p>You will receive next week's movie newsletter shortly<p>\n".
			"<p>-Merrill's Roxy staff</p>\n";
			$to = $email; // the person who filled out the form
	        $cc = "";
	        $bcc = "";
	        $from="noahpatullo+roxy@gmail.com";	//who they can respond to
	        // $from="npatullo+roxy@uvm.edu";	//who they can respond to

	        $todaysDate = strftime("%x");
	        $subject = "Newsletter Signup Confirmation - Merrill&#039;s Roxy Cinema - ".$todaysDate;

	        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
	    }
	}
}
?>
	<article class='movieContainer'>
		<h1>Mailing List</h1>
		<section class="articleBg">
			<?php
			if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of else marked with: "end body submit"
				echo $successMsg;
				$mailedMsgStatus="<p>A confirmation has been sent to: <a href='mailto:".$email."'>".$email."</a><p>\n";
				if(!$mailed){
					$mailedMsgStatus="<h2 class='mistake'>Error</h2>\n<p>A confirmation failed to send to has been sent to: <a href='mailto:".$email."'>".$email."</a><p>\n";
				}
				echo $mailedMsgStatus;
			}else{
				include $upFolderPlaceholder.'php/lib/display-form-errors.php';
			?>
				<form action="<?php echo PHP_SELF;?>" method='post'>
					<p>Sign up for our weekly mailing list to stay up date on showtimes, special events and upcoming movies</p>
					<label for="txtEmail"></label>
					<input type="text" name='txtEmail' id='txtEmail' autofocus value='<?php echo $email;?>' <?php if($emailError){echo "class='mistake'"; } echo "tabindex='".$tabIndex++."'"; ?>><br>
					<input type="submit" name='btnSubmit' value='Sign Up' <?php echo "tabindex='".$tabIndex++."'";?> >
				</form>
				sign up for our weekly mailing list to stay up date on showtimes, special events and upcoming movies
				validate valid email & displa error
				email person saying added
				form saves 2 datebase
				successfully added page
				if already in database, then say "This email is already in the list"
			<?php
			} // end body submit
			?>
		</section>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>
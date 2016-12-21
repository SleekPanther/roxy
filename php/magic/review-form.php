<?php
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'><label for='txtAuthor'>Author </label>\n";
echo "\t\t\t\t\t<input type='text' name='txtAuthor' id='txtAuthor' tabindex='".$tabIndex++."' value='".$_SESSION['reviewAuthor']."'";
if($reviewAuthorError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t<label for='datReviewDate'>Date </label>\n";
echo "\t\t\t\t\t<input type='date' name='datReviewDate' id='datReviewDate' tabindex='".$tabIndex++."' value='".$_SESSION['reviewDate']."'";
if($reviewDateError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t<label for='txtReviewSource'>Source (becomes link text) (optional)</label>\n";
echo "\t\t\t\t\t<input type='text' name='txtReviewSource' id='txtReviewSource' tabindex='".$tabIndex++."' value='".$_SESSION['reviewSource']."'";
if($reviewSourceError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t<label for='txtReviewLink'>Link (optional)</label>\n";
echo "\t\t\t\t\t<input type='text' name='txtReviewLink' id='txtReviewLink' tabindex='".$tabIndex++."' value='".$_SESSION['reviewLink']."'";
if($reviewLinkError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'><label for='txtReview'></label>\n";
echo "\t\t\t\t\t<textarea name='txtReview' id='txtReview' tabindex='".$tabIndex++."' placeholder='Review text goes here' ";
if($reviewError){echo " class='mistake' ";}
echo ">".$_SESSION['review']."</textarea>\n";
echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr class='displayNone'>\n";	//extra 2-column row to to pass validation on pages edit pages that don't have columns below
echo "\t\t\t\t\t<td></td><td></td>\n";
echo "\t\t\t\t</tr>\n";

?>
<?php
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'><label for='txtAuthor'>Review Author </label>\n";
echo "\t\t\t\t\t<input type='text' name='txtAuthor' id='txtAuthor' tabindex='".$tabIndex++."' value='".$reviewAuthor."'";
if($reviewAuthorError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t<label for='datReviewDate'>Date </label>\n";
echo "\t\t\t\t\t<input type='date' name='datReviewDate' id='datReviewDate' tabindex='".$tabIndex++."' value='".$reviewDate."'";
if($reviewDateError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t<label for='txtReviewSource'>Source (i.e. newspaper, magazine website) (optional)</label>\n";
echo "\t\t\t\t\t<input type='text' name='txtReviewSource' id='txtReviewSource' tabindex='".$tabIndex++."' value='".$reviewSource."'";
if($reviewSourceError){echo " class='mistake' ";}
echo ">\n";

echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'><label for='txtReview'></label>\n";
echo "\t\t\t\t\t<textarea name='txtReview' id='txtReview' tabindex='".$tabIndex++."' placeholder='Review is Optional'>".$review."</textarea>\n";
echo "\t\t\t\t\t</td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t\t<tr>\n";
echo "\t\t\t\t<td><input type='submit' name='btnAddReview' id='btnAddReview' tabindex='".$tabIndex++."' value='Add Review'></td>\n";
echo "\t\t\t\t</tr>\n";
?>
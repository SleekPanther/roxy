# [Merrill's Roxy Cinema (movie theater database website)](https://npatullo.w3.uvm.edu/cs148/roxy/index.php)

## Usage Highlights
- Movies have 2 visibility/display options **Current** or **Hidden**
- **`Coming Soon` is calculated automatically based on release date**
- Add new movies with visibility **Current**
- **Hidden** is for future movies with not enough info to display yet, or if you want to hide a past movie altogether  
But this is unnecessary as movies with no current showtimes aren't displayed

## Notes
- I use PHP to upload image files straight to a server. With a develop & live copy of the site, the images **must be added (committed) to git from the live site** (or already be on develop). Otherwise making changes to develop & pulling to live will remove any new images or changes from the live site.
- I didn't implement the `$databaseSuccess` validation for php mysql forms on the admin `edit.php` and `index.php` pages in the `magic/` folder. I felt it wasn't really needed.
- [W3 Schools Uploading images tutorial](http://www.w3schools.com/php/php_file_upload.asp)
- I had problems with [PHP not allowing Array constant](http://stackoverflow.com/questions/1290318/php-constants-containing-arrays)
- There's the potential to make `tblShowtimes` NOT have auto-increment primary keys, but it requires a lot of info to make a showtime unique. Currently you can add 2 "identical" showtimes for the same movie, with the same time, post date, expiration date & dimension. This is due to the auto-increment keys & no checks for duplicate times. However I didn't feel it was necessary, as they would soon see 2 identical times appear on the site.
- There may still be some lingering `LEFT JOIN`'s for `tblPictures` from when I allowed pictures to be optional. Now with the file uploads they're required, but the `LEFT JOIN`'s don't seem to break anything (for now)
- Some images still don't seem to upload even though they shouldn't be too big. I couldn't fix it with the `.htaccess` file as suggested on stack overflow [post 1](http://stackoverflow.com/a/1707115) or [post 2](http://stackoverflow.com/a/14290695)

## Potential Improvements
- CSS mobile responsive, especially `header.php` on small screens  
Some transition issues in IE when page grows/shrinks
- Jquery/ajax to validate date, update showtime expiration 6 days later when changing post date

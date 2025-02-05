<?php
$new_password = 'admin'; // Replace with your new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Create the hash
echo $hashed_password; // Output the hashed password so you can copy it
?>

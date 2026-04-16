<html>
<head>
	<title>Add Data</title>
</head>

<body>
<?php
// Include the database connection file
require_once("db.php");

if (isset($_POST['submit'])) {
	// Escape special characters in string for use in SQL statement	
	$name = mysqli_real_escape_string($mysqli, $_POST['name']);
	$password = mysqli_real_escape_string($mysqli, $_POST['password']);
	$email = mysqli_real_escape_string($mysqli, $_POST['email']);
	$address = mysqli_real_escape_string($mysqli, $_POST['address']);
	$phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
		
	// Check for empty fields
	if (empty($name) || empty($password) || empty($email) || empty($address) || empty($phone)) {
		if (empty($name)) {
			echo "<font color='red'>Name field is empty.</font><br/>";
		}
		
		if (empty($password)) {
			echo "<font color='red'>Password field is empty.</font><br/>";
		}
		
		if (empty($email)) {
			echo "<font color='red'>Email field is empty.</font><br/>";
		}

		if (empty($address)) {
			echo "<font color='red'>Address field is empty.</font><br/>";
		}

		if (empty($phone)) {
			echo "<font color='red'>Phone field is empty.</font><br/>";
		}
		
		// Show link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else { 
		// If all the fields are filled (not empty) 

		// Insert data into database
		$result = mysqli_query($mysqli, "INSERT INTO users (`name`, `password`, `email`, `address`, `phone`) VALUES ('$name', '$password', '$email', '$address', '$phone')");
		
		// Display success message
		echo "<p><font color='green'>Data added successfully!</p>";
		echo "<a href='index.php'>View Result</a>";
	}
}
?>
</body>
</html>
<!DOCTYPE html>
<html lang="en>
<title>Thank you!</title>
<head>

    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>






<?php
//initialize the session
session_start();

require_once "config.php";

if($link === false) {
	die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
	<div>
<?php
// set the username
// as a session variable
$username = htmlspecialchars($_SESSION["username"]);
$notadmin = "";

if ($username == "admin") 
{ 
// get all the results in the form or table for the administrator
$sql = "SELECT * FROM survey";
echo "<h2>Adminstrative Report<br/></h2>";
}
else
{ //only get the row in the form or table from the particular user
$sql = "SELECT * FROM survey WHERE username = ?";
echo "<h2>Thank you " . $username . " for your feedback!<br/></h2>";
$notadmin = "yes";
}

?>
	</div>
</head>

<body>

	<p>

<?php
        if($stmt = mysqli_prepare($link, $sql)){
	    if($notadmin == "yes") {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "s", $param_username);

              // Set parameters
              $param_username = $username;
	    }
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               $results = mysqli_stmt_get_result($stmt);
		// start with table header
                echo "<table class=\"center\"><tr><th>Username </th><th>Email </th><th>Gender </th><th>Genre </th><th>Favorite Book </th><th>Favorite Author </th><br/></tr> ";
		while ($row = mysqli_fetch_assoc($results) )
		 {
		  // print out table
		  // process row of table
			 echo "<tr><td>" . $row["Username"] . " </td>" ;
			 echo "<td>" . $row["Email"] . " </td>";
			 echo "<td>" .  $row["Gender"] . " </td>";
			 echo "<td>" . $row["Genre"] . " </td>";
			 echo "<td>" . $row["FavBook"] . " </td>";
			 echo "<td>" . $row["FavAuthor"] . " </td> <br/></tr>";
		 }
		  // end table
		  echo "</table>";
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                printf("Error1: %s.\n", $stmt->error);
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    // Close connection
    mysqli_close($link);
?>

	</p>

</body>
</html>

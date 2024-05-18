

<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

// Define all necessary variables as empty before use

$username = "";
$email = "";
$gender = "";
$genre = "";
$favAuthor = "";
$favBook = "";
$username_err = "";
$email_err = "";
$gender_err = "";
$genre_err = "";
$favAuthor_err = "";
$favBook_err = "";

// Find username and initialize to the username variable
$username = htmlspecialchars($_SESSION["username"]);

if($username == "admin") {
	header("location: thankyou.php");
}
// On submit button press
// Validate all field entries


if($_SERVER["REQUEST_METHOD"] == "POST") {

//echo trim($_POST["email"]);
//echo trim($_POST["gender"]);
//echo trim($_POST["genre"];
//echo trim($_POST["favauthor"]);
//echo trim($_POST["favbook"]);

	$username_err = "";
	$email_err = "";
	$gender_err = "";
	$genre_err = "";
	$favAuthor_err = "";
	$favBook_err = "";


// Validate username

//	$username = trim($_POST["username"]);

// Validate email
	if(empty(trim($_POST["email"]))) {
		$email_err = "Please enter an email.";
	}
	elseif(strlen(trim($_POST["email"])) > 30) {
		$email_err = "email must have fewer than 30 characters";
	}
	else {
		$email = trim($_POST["email"]);
	}

// Validate gender
	if(empty(trim($_POST["gender"]))) {
		$gender_err = "Please select a gender option.";
	}

	else {
		$gender = trim($_POST["gender"]);
	}

//Validate genre
	if(empty(trim($_POST["genre"]))) {
		$genre_err = "Please select a genre option.";
	}
	else {
		$genre = trim($_POST["genre"]);
	}

//Validate Favorite Book
	if(empty(trim($_POST["favbook"]))) {
		$favBook_err = "Please enter your favorite book.";
	}
	elseif(strlen(trim($_POST["favbook"])) > 60) {
		$favBook_err = "your favorite book cannot have more than 60 characters.";
	}
	else {
		$favBook = trim($_POST["favbook"]);
	}

// Validate Favorite Author
	if(empty(trim($_POST["favauthor"]))) {
		$favAuthor_err = "Please enter your favorite author.";
	}
	elseif(strlen(trim($_POST["favauthor"])) > 30) {
		$favAuthor_err = "Your favorite author's name cannot be longer than 30 characters.";
	}
	else {
		$favAuthor = trim($_POST["favauthor"]);
	}



//echo $username;
//echo $username_err;
//echo $email;
//echo $gender;
//echo $genre;
//echo $favAuthor;
//echo $favBook;

//Check for errors before attempting database insertion.
	if(empty($username_err) && empty($email_err) && empty($genre_err) && empty($gender_err) && empty($favBook_err) && empty($favAuthor_err)) {

		echo "successfully hitting sql section";
	//Prepare the insert statement
		$sql = "INSERT INTO survey (Username, Email, Gender, Genre, FavBook, FavAuthor) VALUES (?, ?, ?, ?, ?, ?)";

		if($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_email, $param_gender, $param_genre, $param_favbook, $param_favauthor);

			//set parameters
			$param_username = $username;
			$param_email = $email;
			$param_gender = $gender;
			$param_genre = $genre;
			$param_favbook = $favBook;
			$param_favauthor = $favAuthor;

			echo "parameters bound";

			//attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)) {
				echo "sql statement successful";

				//if successfull store result and go to the thank you page.
				mysqli_stmt_store_result($stmt);
				header("location: thankyou.php");
				//close the MYSQL statement
				mysqli_stmt_close($stmt);

			}


		}



	//close the connection to the sql database.
	mysqli_close($link);
	}



}









?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

    <form id="form" style="center:2.5em ;" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" METHOD="POST">

    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <h1 class="my-5">Please fill out our survey.</h1>


    <div class="form-group">
	<div>Email</div>
	<input type="text" id="email" name="email" class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : '' ?>" value="<?php echo $email; ?>">
	<span class="invalid-feedback"><?php echo $email_err; ?></span>
    </div>
    <br>


    <div class="form-group">

	<div>Gender</div>

	<select name="gender" id="gender" class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : '' ?>" value = "<?php echo $gender; ?>">
		<option value=""></option>
		<option value="male">Male</option>
		<option value="female">Female</option>
		<option value="non-binary">Non-Binary</option>
		<option value="other">Other</option>
	</select>
	<span class="invalid-feedback"><?php echo $gender_err; ?></span>
    </div>
    <br>


    <div class="form-group">

	<p>What is your favorite genre of book?</p>

	<input type="radio" id="Fantasy" name="genre" value="Fantasy">
	<label for="Fantasy">Fantasy</label><br>
	<input type="radio" id="Sci-fi" name="genre" value="Sci-fi">
	<label for="Sci-fi">Sci-fi</label><br>
	<input type="radio" id="Romance" name="genre" value="Romance">
	<label for="Romance">Romance</label><br>
	<input type="radio" id="Non-fiction" name="genre" value="Non-Fiction">
	<label for="Non-fiction">Non-fiction</label><br>
	<input type="radio" id="Other" name="genre" value="Other">
	<label for="Other">Other</label><br>
	<span class="invalid-feedback"><?php echo $genre_err; ?></span>
    </div>
    <br>


    <div class="form-group">
	<div>Who is your favorite author?</div>

	<input type="text" id="favauthor" name="favauthor" value="<?php echo $favAuthor; ?>">
	<span class="invalid-feedback"><?php echo $favAuthor_err; ?></span>
    </div>
    <br>


    <div class="form-group">
	<div>What is your favorite book?</div>

	<input type="text" id="favbook" name="favbook" value="<?php echo $favBook; ?>">
	<span class="invalid-feedback"><?php echo $favBook_err; ?></span>

    </div>
    <br>


	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Submit">
		<a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
		<a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>

	</div>
</form>
</body>
</html>

<?php
    if(empty($_POST['username'])) {
		echo 'You left the username blank!';
		exit_process();
    }
	 
	if(empty($_POST['email'])) {
		echo 'You left the email blank!';
		exit_process();
    }
    if(empty($_POST['password'])) {
		echo 'You left the password blank!';
		exit_process();
    }
	
    $username = trim($_POST['username']);
	$email = trim($_POST['email']);
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		echo 'invalid email';
		exit_process();
	}
    $password = $_POST['password'];
	
	$SQLi = new mysqli('localhost', 'root', 'password','mydatabase');

    if($SQLi -> connect_errno) {
      echo 'mySQL CONNECTION FAILURE: '.$SQLi -> connect_errno;
      exit_process();
    }
	
	$testUsername = sprintf("SELECT uid FROM Users WHERE LOWER(username) LIKE LOWER('%s');",
		$SQLi->real_escape_string($username));
	$result = $SQLi -> query($testUsername);
	
	if($result -> num_rows > 0){
		echo 'Sorry the username "'.$username.'" is already taken by another user<br>';
		$SQLi->close();
		exit_process();
	}
	
	$testEmail = sprintf("SELECT uid FROM Users WHERE LOWER(email) LIKE '%s';",
	$SQLi->real_escape_string($email));
	$result = $SQLi -> query($testEmail);
	if($result -> num_rows > 0){
		echo 'The email "'.$email.'" is already in use<br>';
		$SQLi->close();
		exit_process();
	}
	
	$query = sprintf(
	"INSERT INTO Users (username, email, password) VALUES ('%s', '%s', '%s');", 
	$SQLi->real_escape_string($username), $SQLi->real_escape_string($email), 
	$SQLi->real_escape_string($password));
		
	$SQLi-> query($query);
	
	echo 'Welcome. You have successfully created your account '.$username.'!<br>';
	echo '<a href="loginpage.html">login page</a>';
	
	$SQLi->close();
	
	function exit_process(){
		echo 'Click <a href="signup.html">here</a> to go to the signup page<br>';
		exit();
	}
?>

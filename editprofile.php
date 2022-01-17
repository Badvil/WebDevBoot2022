<html><b>Your profile</b><br><br></html>

<?php
	session_start();
	$uid = 'Guest'; 
	if(isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
	} 
	$_SESSION['uid'] = $uid;
	
	if(strcmp($uid, 'Guest') == 0){
		header('Location: loginpage.html');
	}
	
	$mysqli = new mysqli("localhost", "root", "password", "MyDatabase");
	if($mysqli -> connect_error){
		echo 'MySQL connect error: '.$mysqli -> connect_errno;
		exit();
	}
	error_reporting(0);
	
	if(!empty($_POST['bio'])){
		$bio = trim($_POST['bio']);
		
		$query = sprintf("UPDATE Users SET userbio = '%s' WHERE uid = %d;", $bio, $uid);
		if(!mysqli_query($mysqli, $query)){
			echo 'Error: failed to update info<br>';
			exit();
		}
	}
	
	$query = sprintf("SELECT username, userbio FROM Users WHERE uid = %d;", $uid);
	$result = mysqli_query($mysqli, $query);
	
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc(); 
		echo 'Username: '.$row['username'].'<br>';
		$form = "
		<form method = \"post\" action = \"editprofile.php\">
			<label for \"bio\"> Your current Bio: (2048 characters max.) </label><br>
			<textarea id = \"bio\" name = \"bio\" maxlength = \"2047\" style = \"width:600px; height:400px\">%s</textarea><br>
			<input type = \"submit\" value = \"update bio\">
		</form>";
		echo sprintf($form, $row['userbio']);
	} else {
		echo 'Sorry your profile seems missing<br>';
		echo '<a href="help.html">Contact us for help</a><br>';
	}
	$mysqli -> close();
?>

<html>
  
  
  <form method = "post" action = "logout_process.php">
    <input type = "submit" value = "Logout">
  </form><br>
</html>
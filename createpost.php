<html>Create a post! <br></html>
<?php
  
  session_start();
  $uid = 'Guest';
  if(isset($_SESSION['uid']))
    $uid = $_SESSION['uid'];
  $_SESSION['uid'] = $uid;
  
  if(strcmp($uid, 'Guest') == 0){
		header('Location: loginpage.html');
	}
  
  /*
  $populateDB .= 
		"INSERT INTO Posts 
		(ownerid, title, postTime, lastUpdate, postContent) VALUES
		(3, 'I stole 8 billion worth of bitcoin', '2022-1-10 22:45:00', '2022-1-10 22:45:00', 'This is how I stole 8 billion in bitcoin. Do not tell the IRS.etc...');";
	*/
	
	if(!empty($_POST['title']) && !empty($_POST['message'])){
		$title = trim($_POST['title']);
		$message = trim($_POST['message']);
		
		$SQLcon = new mysqli('localhost', 'root', 'password', 'mydatabase');
		if($SQLcon -> connect_errno) {
			echo 'mySQL CONNECTION FAILURE: '.$SQLcon -> connect_errno;
			exit();
		}
		
		$query = "INSERT INTO Posts 
		(ownerid, title, postTime, postContent) VALUES
		(".$uid.", '".$title."', CURRENT_TIME(), '".$message."');";
		
		echo $query;
		$SQLcon -> query($query);
		
		$_POST = array();
		$SQLcon -> close();
		header('Location: home.php');
	}
	echo '<br>';
?>
<html>
  <form method = "POST" action = "createpost.php">
	<label for "title"> *Title: (64 characters max.) </label><br>
    <textarea id = "title" name = "title" maxlength = "63" style = "width:600px; height:50px"> </textarea><br>
    <label for "message"> *Message: (4096 characters max.)</label><br>
    <textarea id = "message" name = "message" maxlength = "4095" style = "width:800px; height:500px"> </textarea><br>
    <input type = "submit" value = "Enter">
  </form>
</html>

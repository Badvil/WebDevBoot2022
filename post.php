<?php
	// This file is meant for displaying a post with minimal graphics
	
	// to display a specific post, the url should look like post.php?postid=x
	// with x being the postid number, the db is populated with ids 1, 2, 3
	session_start();
	$uid = 'Guest'; 
	if(isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
	} 
	$_SESSION['uid'] = $uid;
	
	if(strcmp($uid, 'Guest') == 0){
		header('Location: loginpage.html');
	}
	
	echo '<a href="home.php">home</a><br><br>';
	echo '<a href="logout_process.php">logout</a><br>';
	
	if(empty($_GET['postid']) or !is_numeric($_GET['postid'])){
		echo 'no post found';
		exit();
	}
	$postid = $_GET['postid'];
	
	$mysqli = new mysqli("localhost", "root", "password", "MyDatabase"); 
	if($mysqli -> connect_erno){
		echo 'failed to connect to MySQL '.$mysqli -> connect_error;
		exit();
	}
	
	
	
	//Query to get the post
	$postQuery = "
	SELECT 
		u.uid,
		u.username,
		p.title,
		DATE_FORMAT(p.postTime, \"%b %d, %Y at %r \") AS \"time\",
		DATE_FORMAT(p.lastUpdate, \"%b %d, %Y at %r \") AS \"lastUpdate\",
		p.postContent
	FROM POSTS p 
		INNER JOIN Users u
		ON u.uid = p.ownerid
	WHERE p.postid = ".$postid.";";
	$postResult = $mysqli -> query($postQuery);
	
	
	//display post
	if($postResult -> num_rows > 0) {
		$row = $postResult -> fetch_assoc();
		
		echo $row['title'].'<br>';
		echo 'by: <a href="viewprofile.php?user='.$row['uid'].'">'.$row['username'].'</a><br>';
		echo 'Posted at '.$row['time'].'<hr>';
		echo $row['postContent'].'<br>';
		echo '<i>Last Update at '.$row['lastUpdate'].'</i><hr>';
		
	} else {
		echo 'Sorry, post was not Found<br>';
		exit();
	}
	
	
	
	//Query to get the tags related to the post
	$tagQuery = "
	SELECT p.tagid, m.name FROM PostTags p
		INNER JOIN MetaTags m
		ON p.tagid = m.tagid
	WHERE p.postid = ".$postid.";";
	$tagResult = $mysqli -> query($tagQuery);
	
	
	//display the tags
	$tags = 'Tags: ';
	if($tagResult -> num_rows > 0) {
		while($row = $tagResult -> fetch_assoc()) {
				$tags .= $row['name'].' | ';
		}
		$tags = substr($tags, 0, -2);
	} else {
		$tags.= '<i>none</i>';
	}
	echo $tags.'<br>';
	
	//Query to get the comments
	$commentQuery = "
	SELECT 
		u.uid,
		u.username,
		p.postContent, 
		DATE_FORMAT(p.postTime, \"%b %d, %Y %r \") AS \"time\",
		NVL(DATE_FORMAT(p.lastUpdate, \"%b %d, %Y %r \"), \"none\") AS \"lastUpdate\"
	FROM PostComments p 
		INNER JOIN Users u
		ON u.uid = p.ownerid
	WHERE postid = ".$postid.";";
	$commentResult = $mysqli -> query($commentQuery);
	
	//display the comments
	echo 'Comments:<hr>';
	
	if($commentResult -> num_rows > 0) {
		while($row = $commentResult -> fetch_assoc()) {
			
			echo $row['time'].'<br>';
			echo $row['postContent'].'<br>';
			echo 'by: <a href="viewprofile.php?user='.$row['uid'].'">'.$row['username'].'</a><br>';
			
			//basically check if post was updated, display update time
			if(strcmp('none', $row['lastUpdate']) != 0 and 
			strcmp($row['time'], $row['lastUpdate']) != 0){
				echo '<i>last edited at: '.$row['lastUpdate'].'</i>';
			}
			echo '<hr>';
		}
	} else {
		echo '0 comments';
	}
	
	$mysqli->close();
?>
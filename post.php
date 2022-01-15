<?php
	// This file is meant for displaying a post with minimal graphics
	
	// to display a specific post, the url should look like post.php?postid=x
	// with x being the postid number, the db is populated with ids 1, 2, 3
	
	if(empty($_GET['postid']) or !is_numeric($_GET['postid'])){
		echo 'no post found';
		exit();
	}
	$postid = $_GET['postid'];
	
	session_start();
	$uid = 'Guest'; // for the uid we should set it to the number given from the user table
	
	if(isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
	} 
	$_SESSION['uid'] = $uid;
	
	$mysqli = new mysqli("localhost", "root", "password", "MyDatabase");
	if($msqli -> connect_erno){
		echo 'failed to connect to MySQL '.$mysqli -> connect_error;
		exit();
	}
	
	
	
	//Query to get the post
	$sql = "
	SELECT 
		p.title,
		DATE_FORMAT(p.postTime, \"%b %d, %Y at %r \") AS \"time\",
		DATE_FORMAT(p.lastUpdate, \"%b %d, %Y at %r \") AS \"lastUpdate\",
		p.postContent,
		u.username 
	FROM POSTS p 
		INNER JOIN Users u
		ON u.uid = p.ownerid
	WHERE p.postid = ".$postid.";";
	$result = $mysqli -> query($sql);
	
	
	//display post
	if($result -> num_rows > 0) {
		$row = $result -> fetch_assoc();
		
		echo $row['title'].'<br>';
		echo 'by: '.$row['username'].' (insert link to user profile page)<br>';
		echo 'Posted at '.$row['time'].'<hr>';
		echo $row['postContent'].'<br>';
		echo '<i>Last Update at '.$row['lastUpdate'].'</i><hr>';
		
	} else {
		echo 'Sorry, post was not Found<br>';
		exit();
	}
	
	
	
	//Query to get the tags related to the post
	$sql = "
	SELECT p.tagid, m.name FROM PostTags p
		INNER JOIN MetaTags m
		ON p.tagid = m.tagid
	WHERE p.postid = ".$postid.";";
	$result = $mysqli -> query($sql);
	
	
	//display the tags
	$tags = 'Tags: ';
	if($result -> num_rows > 0) {
		while($row = $result -> fetch_assoc()) {
				$tags .= $row['name'].' | ';
		}
		$tags = substr($tags, 0, -2);
	} else {
		$tags.= '<i>none</i>';
	}
	echo $tags.'<br>';
	
	//Query to get the comments
	$sql = "
	SELECT 
		u.username,
		p.postContent, 
		DATE_FORMAT(p.postTime, \"%b %d, %Y %r \") AS \"time\",
		NVL(DATE_FORMAT(p.lastUpdate, \"%b %d, %Y %r \"), \"none\") AS \"lastUpdate\"
	FROM PostComments p 
		INNER JOIN Users u
		ON u.uid = p.ownerid
	WHERE postid = ".$postid.";";
	$result = $mysqli -> query($sql);
	
	//display the comments
	echo 'Comments:<hr>';
	
	if($result -> num_rows > 0) {
		while($row = $result -> fetch_assoc()) {
			
			echo $row['time'].'<br>';
			echo $row['postContent'].'<br>';
			echo 'by: '.$row['username'].'<br>';
			
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
<?php
	require_once('./dbcon.php');

	$stmt = $dbh->prepare("SELECT COUNT(DISTINCT(person_name)) as nettotal, COUNT(*) as total FROM `people` LIMIT 1");
	$success_get_people = $stmt->execute();
	$row_people_voted = $stmt->fetch();
	
	$stmt = $dbh->prepare("SELECT COUNT(*) as totalvote FROM `votes` LIMIT 1");
	$success_get_votes = $stmt->execute();
	$row_votes = $stmt->fetch();

	$stmt = $dbh->prepare("SELECT COUNT(*) as totalvidadd FROM `videos` WHERE video_created>datetime('now','-24 hour') LIMIT 1");
	$success_get_videos = $stmt->execute();
	$row_vids = $stmt->fetch();
	
	?><!doctype html>
<html lang="en">
<head><title>Stats - Afterparty Song List</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta content='initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'/>
<meta http-equiv="X-UA-Compatible" content="IE=9"/>
<meta id="themecolor" name="theme-color" content="#607D8B"/>
</head>
<body><?php
	if($success_get_people){
		if(isset($row_people_voted["nettotal"])){
			
			echo "Total number of people who have voted: " . $row_people_voted["nettotal"];
			echo "<br/>";
			echo "Total number of people who created more than one account: " . ($row_people_voted["total"] - $row_people_voted["nettotal"]);
			echo "<br/>";
		}
	}
	if($success_get_votes){
		if(isset($row_votes["totalvote"])){
			
			echo "Total number of votes: " . $row_votes["totalvote"];
			echo "<br/>";
		}
	}
	
	if($success_get_videos){
		if(isset($row_vids["totalvidadd"])){
			
			echo "Videos added to the list(last 24 hours): " . $row_vids["totalvidadd"];
			echo "<br/>";
		}
	}
	?>
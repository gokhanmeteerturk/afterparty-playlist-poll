<?php

	require_once('./dbcon.php');
	
	require_once('./enter.php');
	require_once('./add.php');
	require_once('./givevote.php');
	

	if(isset($_SESSION["person_id"])){
		$video_objects = $dbh->prepare("SELECT MAX(v.video_name) as video_name, MAX(v.idvideos) as idvideos, v.video_id, COUNT(DISTINCT(o.idvotes)) as vote_count, COUNT(DISTINCT(u.idvotes)) as did_you_vote FROM videos as v LEFT JOIN votes as o ON o.idvideos=v.idvideos LEFT JOIN (SELECT * FROM votes WHERE person_id=" . $_SESSION["person_id"] . ") as u ON u.idvideos=v.idvideos GROUP BY v.video_id ORDER BY vote_count DESC, idvideos DESC LIMIT 350");
	}
	else{
	$video_objects = $dbh->prepare("SELECT MAX(v.video_name) as video_name, MAX(v.idvideos) as idvideos, v.video_id, COUNT(o.idvotes) as vote_count, 0 as did_you_vote FROM videos as v LEFT JOIN votes as o ON o.idvideos=v.idvideos WHERE 1=1 GROUP BY v.video_id ORDER BY vote_count DESC, idvideos DESC LIMIT 350");
	}
	$success_video_feed = $video_objects->execute();

	header('Content-Type: text/html; charset=utf-8');
	?><!doctype html>
<html lang="en">
<head><title>Afterparty Song List</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta content='initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'/>
<meta http-equiv="X-UA-Compatible" content="IE=9"/>
<meta id="themecolor" name="theme-color" content="#607D8B"/>
<link href="all.css?v=5" rel="stylesheet"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
	<div id="backvid">
		<div id='ytplayer' style='width:100vw;height:58vw;margin-top:-6vw;'></div>
		<div class="dots"></div>
<script>
  var tag = document.createElement("script");
tag.src = "https://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var player;
function onYouTubePlayerAPIReady() {
  document.getElementsByTagName("body");
  player = new YT.Player("ytplayer", {events:{onStateChange:function() {
    player.mute();
    player.setPlaybackRate(0.75);
    player.playVideo();
  }}, height:"100%", width:"100%", videoId:"edSUyOrXOr4", playerVars:{autoplay:1, rel:0, showinfo:0, showsearch:0, controls:0, loop:1, enablejsapi:1, playlist:"edSUyOrXOr4"}});
  setTimeout(function() {
    player.mute();
    player.setPlaybackRate(0.75);
    player.playVideo();
  }, 3000);
}
;

</script>


	</div>
<center>
<img width="120" height="86" border="0" src="logo.png"/>
<span class="title">Playlist Poll</span>
</center>
	
	<center><?php
	if(isset($_SESSION["nick"])){

		?><form method="POST">
		<input type="hidden" name="out" value="1"/>
		<input type="submit" value="logout"/>
		</form><br/><br/><?php
	}
	else{
		?><form method="POST" onsubmit="return validate();">
		<div class="mb4">
			<span class="phold" onmouseout="this.innerText='Pick a nickname'" onmouseover="this.innerText='Nick a pickname'">Pick a nickname:</span>
			<input id="nick" type="text" placeholder="crazybaguette23" name="nick"/>
		</div>
		<div class="mb4">
			<span class="phold">Your name:</span>
			<input id="name" type="text" placeholder="John Doe" name="name"/>
		</div>
		<input type="submit" value="enter"/>
		</form><?php
	}
?></center><?php


	if(isset($_SESSION["nick"])){
	
		?><center>
			<b>Vote for the videos below.<br/><br/></b>
			OR add new:
			<form method="POST">
				<input placeholder="youtube.com/watch?v=S5IdQIjFGJ4" type="text" name="videourl"/>
				<pre>paste a youtube link above, in order to add your music video</pre>
				<input type="submit" value="Add Video"/>
			</form>
		</center><?php
	}
	
	if($success_video_feed){
		?><ul><?php
		foreach ($video_objects as $video_object){
			?><li>
				<form method="POST"><input type="hidden" value="<?php echo $video_object["idvideos"]; ?>" name="votevidid"/>
					<a href="javascript:void(0)" class="ok<?php if($video_object["did_you_vote"]!=0){ echo " active"; } ?>" onclick="<?php
					
					if(isset($_SESSION["person_id"])){
						?>this.parentNode.submit()<?php
					}
					else{
						?>alert('You need to choose a nickname before you vote!')<?php
					}
					
					?>;"><svg fill="#cccccc" style="display:block;width:48px;height:48px" viewBox="0 0 24 24">
    						<path d="M15,20H9V12H4.16L12,4.16L19.84,12H15V20Z" />
						</svg>
					</a>
					<span class="count"><?php echo $video_object["vote_count"]; ?> votes</span>
				</form>
				<a class="video_title" target="_blank" href="https://youtu.be/<?php echo $video_object["video_id"]; ?>/"><?php echo htmlspecialchars($video_object["video_name"]); ?></a>
				<a class="video_image_a" target="_blank" href="https://youtu.be/<?php echo $video_object["video_id"]; ?>/"><img width="96" height="72" src="https://img.youtube.com/vi/<?php echo $video_object["video_id"]; ?>/default.jpg" border="0"/></a>
			</li>
			<?php
		}
		?></ul><?php
	}
	
?><center><span style="display:inline-block;transform: rotate(180deg);">&copy;</span>Copyleft All Wrongs Reserved.</center>
<script>
function validate() {
            var nick_input_value = document.getElementById("nick").value;
            var name_input_value = document.getElementById("name").value;
            var regexni = /^[a-z0-9]+$/;
            var regexna = /^[a-z0-9 ]+$/;

            if ((nick_input_value.length > 1) && (nick_input_value.length < 50) && regexni.test(nick_input_value) && (name_input_value.length > 1) && (name_input_value.length < 50) && regexna.test(name_input_value)){
               return true;
            }
            else {
               alert("2 to 50 alphanumerical(a-z, 0-9) characters only. \n (nicknames can't have spaces)");
               return false;
            }
         }
</script>

</body>
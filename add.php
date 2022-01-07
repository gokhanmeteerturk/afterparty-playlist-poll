<?php
function get_youtube($url){
    $youtube = "https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=". $url ."&format=json";
    $curl = curl_init($youtube);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($curl);
    curl_close($curl);
    $q= json_decode($return, true);
	return $q["title"];
}



	require_once('./dbcon.php');
	require_once('./enter.php');


	if(isset($_POST["videourl"]) && isset($_SESSION["nick"])){
		
		preg_match("#([\/|\?|&]vi?[\/|=]|youtu\.be\/|embed\/)([a-zA-Z0-9_-]+)#", $_POST["videourl"], $matches);
		if(count($matches)>=1){
			
			$video_id=end($matches);
			
			
			$video_name=get_youtube($video_id);
			
			if(strlen($video_id) >= 4 && strlen($video_name) >= 4){
				$stmt = $dbh->prepare("SELECT person_id, person_nick, person_name FROM people WHERE person_nick=:zuid LIMIT 1");
				$nicknick=$_SESSION["nick"];
				$stmt->bindParam(':zuid', $nicknick, PDO::PARAM_STR);
				$qconnect = $stmt->execute();
				$qtrow = $stmt->fetch();
				if($qconnect && isset($qtrow["person_nick"]) && strlen($qtrow["person_nick"]) >= 2){
					//add video
					$idperson=$qtrow["person_id"];
					
					$stmt = $dbh->prepare("SELECT video_id FROM videos WHERE video_id=:vididc LIMIT 1");
					$stmt->bindParam(':vididc', $video_id, PDO::PARAM_STR);
					$does_exist = $stmt->execute();
					$row_existing = $stmt->fetch();
					if($does_exist && isset($row_existing["video_id"]) && strlen($row_existing["video_id"]) >=2 ){
						//already there.
					}
					else{
					$stmt = $dbh->prepare("INSERT INTO videos(video_id, added_by, video_name) VALUES (:vidid, :kisid, :vname)");
					$stmt->bindParam(':vidid', $video_id, PDO::PARAM_STR);
					$stmt->bindParam(':kisid', $idperson, PDO::PARAM_STR);
					$stmt->bindParam(':vname', $video_name, PDO::PARAM_STR);
					$cnnect = $stmt->execute();
					header('Location: ' . $home_url);exit;
					}
				}
				
			}
			
		}
	}
	

?>
<?php
	require_once('./dbcon.php');
	if(isset($_SESSION["person_id"]) && isset($_POST["votevidid"]) && ctype_digit($_POST["votevidid"])){
		
		$vid=$_POST["votevidid"];
		$stmt = $dbh->prepare("SELECT idvideos, video_name FROM videos WHERE idvideos=:vid LIMIT 1");
		$stmt->bindParam(':vid', $vid, PDO::PARAM_STR);
		$nconnect = $stmt->execute();
		$nrow = $stmt->fetch();
		if($nconnect){
			if(isset($nrow["video_name"]) && strlen($nrow["video_name"]) >= 2){
				
				$stmt = $dbh->prepare("SELECT k.person_id, k.person_nick, k.person_name, COUNT(o.idvotes) as myvote FROM people as k LEFT JOIN votes as o ON o.person_id=k.person_id AND o.idvideos=:idvidd WHERE k.person_id=:zuidx GROUP BY k.person_id LIMIT 1");
				$idkisi=$_SESSION["person_id"];
				$stmt->bindParam(':zuidx', $idkisi, PDO::PARAM_STR);
				$stmt->bindParam(':idvidd', $vid, PDO::PARAM_STR);
				$qconnect = $stmt->execute();
				$qtrow = $stmt->fetch();
				if($qconnect && isset($qtrow["person_nick"]) && strlen($qtrow["person_nick"]) >= 2){
					
					if($qtrow["myvote"] == 0){
						//add vote
						$stmt = $dbh->prepare("INSERT INTO votes(idvideos, person_id) VALUES (:vidfid, :kisfid)");
						$stmt->bindParam(':vidfid', $vid, PDO::PARAM_STR);
						$stmt->bindParam(':kisfid', $idkisi, PDO::PARAM_STR);
						$cnnect = $stmt->execute();
						header('Location: ' . $home_url);exit;
					}
					else{
						//remove vote
						$stmt = $dbh->prepare("DELETE FROM votes WHERE idvideos=:vidfid AND person_id=:kisfid LIMIT 3");
						$stmt->bindParam(':vidfid', $vid, PDO::PARAM_STR);
						$stmt->bindParam(':kisfid', $idkisi, PDO::PARAM_STR);
						$cnnect = $stmt->execute();
						header('Location: ' . $home_url);exit;
					}
				
				}
				
				
			}
		}
		
	}

?>
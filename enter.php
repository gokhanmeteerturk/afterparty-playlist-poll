<?php

	require_once('./dbcon.php');
	if(isset($_POST["out"]) && $_POST["out"]=="1"){
		session_unset();
		session_destroy();
	}
	if(isset($_POST["nick"]) && isset($_POST["name"]) && strlen($_POST["nick"]) >= 2 && !preg_match('/[^a-z_\-0-9]/i', $_POST["nick"])){
		
		$stmt = $dbh->prepare("SELECT person_id, person_nick, person_name FROM people WHERE person_nick=:personnick LIMIT 1");
		$post_nick=$_POST["nick"];
		$stmt->bindParam(':personnick', $post_nick, PDO::PARAM_STR);
		$success_get_person = $stmt->execute();
		$row_person_found = $stmt->fetch();
		if($success_get_person){
			if(isset($row_person_found["person_nick"]) && strlen($row_person_found["person_nick"]) >= 2){
				// Person exists. Log in:
				$_SESSION["nick"] = $row_person_found["person_nick"];
				$_SESSION["name"] = $row_person_found["person_name"];
				$_SESSION["person_id"] = $row_person_found["person_id"];
				
			}
			else{
				// Person does not exist. Create:
				$post_nick = $_POST["nick"];
				$post_name = $_POST["name"];
				
				$stmt = $dbh->prepare("INSERT INTO people(person_nick, person_name, person_created) VALUES (:postxnick, :postxname, datetime('now'))");
				$stmt->bindParam(':postxnick', $post_nick, PDO::PARAM_STR);
				$stmt->bindParam(':postxname', $post_name, PDO::PARAM_STR);
				$success_insert_person = $stmt->execute();
				
				if($success_insert_person){
					$stmt = $dbh->prepare("SELECT person_id, person_nick, person_name FROM people WHERE person_nick=:postnick LIMIT 1");
					$post_nick=$_POST["nick"];
					$stmt->bindParam(':postnick', $post_nick, PDO::PARAM_STR);
					$get_person_again = $stmt->execute();
					$row_new_person = $stmt->fetch();
					
					if($get_person_again){
						if(isset($row_new_person["person_nick"]) && strlen($row_new_person["person_nick"]) >= 2){
							// Now it does. Let's log in:
							$_SESSION["nick"] = $row_new_person["person_nick"];
							$_SESSION["name"] = $row_new_person["person_name"];
							$_SESSION["person_id"] = $row_new_person["person_id"];
						}
						else{
							// We somehow failed. Maybe go chown 777 on db and its directory?
						}
					}
					header('Location: ' . $home_url);
					exit;
				}
				
			}
		}
		
	}
	
?>
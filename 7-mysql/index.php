<?php

	session_start();

	$error = "";
	
	if(array_key_exists("logout", $_GET)) {
		
		unset($_SESSION);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";
		
	} else if (array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) {
		
		header("Location: loggedinpage.php");
		
	}
	
	if (array_key_exists("submit", $_POST)) {
	
		$link = mysqli_connect("shareddb1a.hosting.stackcp.net", "secretdi-34f00e", "K/vjufhJDo4c", "secretdi-34f00e");
	
		if (mysqli_connect_error()){
			
			die ("Database Connection Error");
			
		}
	
		if (!$_POST['email']) {
			
			$error .= "An email address is required<br>";
			
		}
		
		if (!$_POST['password']) {
			
			$error .= "A password is required<br>";
			
		}
		
		if ($error != "") {
			
			$error = "<p>There were error(s) in your form:</p>".$error;
			
		} else {
			
			if ($_POST['signUp'] == '1') {
			
					$query = "SELECT id FROM `users` WHERE email = 
					'".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
					
					$result = mysqli_query($link, $query);
					
					if (mysqli_num_rows($result) > 0) {
						
						$error = "That email address is taken.";
						
					} else {
						
						
						if (!mysqli_query($link, $query)) {
							
							$error = "<p>Could not sign you up - please try again later.</p>";
							
						} else {
							
							$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = 
							".mysqli_insert_id($link)." LIMIT 1";
							
							mysqli_query($link, $query);
							
							$_SESSION['id'] = mysqli_insert_id($link);
							
							if ($_POST['stayLoggedIn'] == '1') {
								
								setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
								
							}
							
							header("Location: loggedinpage.php");
						}
						
					}
					
				}  else {
					
					print_r($_POST);
		
			}
		
		}
		
		
	}
	
	
?>

<div id="error"><?php echo $error; ?></div>

<form method="POST">

	<input type="email" name="email" placeholder="Your Email">
	
	<input type="password" name="password" placeholder="Password">
	
	<input type="checkbox" name="stayLoggedIn" value=1>
	
	<input type="hidden" name="signUp" value="1">
	
	<input type="submit" name="submit" value="Sign Up!">
	
</form>

<form method="POST">

	<input type="email" name="email" placeholder="Your Email">
	
	<input type="password" name="password" placeholder="Password">
	
	<input type="checkbox" name="stayLoggedIn" value=1>
	
	<input type="hidden" name="signUp" value="0">
	
	<input type="submit" name="submit" value="Log In!">
	
</form>
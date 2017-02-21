<?php session_start();
if(!isset($_SESSION['username'])) { // if no user logged in, direct to the home page
    header("Location: NewsWebsite.html");
}
?>
<!DOCTYPE HTML>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>Reset password Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="NewsWebsite.html"> Home </a>                    
                </nav>
            </header>
            <div class="login">
				<p>Please reset your password.</p>
				<form  method="POST">
					<label>
                    Old password:<br>
                    <input type="password" name="oldpassword"><br>
                    </label>
                    <label>
					New password: <br>
					<input type="password" name="password"> <br>
                    </label>
                    <label>
                    Input again: <br>
                    <input type="password" name="retype"> <br>
					</label>
                    <br>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" name = "submit" value="Submit">
				</form>
                <?php
                if (isset($_POST['submit'])){
			        // old password cannot be empty
			        if (empty($_POST['oldpassword'])){
			            echo "Old password required";
			            exit;
			        }
					// validate new password
                    if(!preg_match('/^[\w_\-]+$/', $_POST['password'])){
						echo "Invalid password";
						exit;
					}
					// old password must match
					include "helperfunctions3.php";
                    $checkpass = checkpassword($_SESSION['username'], $_POST['oldpassword']);
                    if (!$checkpass) {
                        echo "Old password does not match";
                        exit;
                    }
					// Password and retype Password must be the same
					if(strcmp($_POST['retype'], $_POST['password'])!=0){
					    echo "Retype password is different!";
					    exit;
					}
					// update the password
                    require "database3.php";
					$stmt = $mysqli->prepare("UPDATE users SET crypted_password= ? WHERE username = ?");
					if(!$stmt){
					    printf("Query Prep Failed: %s\n", $db->error);
					    exit;
					}
					$pwd_hash = crypt($_POST['password']);
					$stmt->bind_param('ss', $pwd_hash, $_SESSION['username']);  
					$stmt->execute();
					$stmt->close();
		
					// Change password successfully, destroy session and redirect to user login page
					echo "<p>Password changed! You will be logged out automatically and log in again in 5 seconds.</p>";
                    session_destroy();
					header("Refresh:5; url = login3.php");                       
				}   
                ?>
            </div>
        </body>
    </html>
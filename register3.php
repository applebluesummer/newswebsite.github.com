<?php session_start();?>
<!DOCTYPE HTML>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>Login Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="NewsWebsite.html"> home </a>
                    <a href="login3.php"> log in</a>
                </nav>
            </header>
            <div class="login">
				<p>Please enter a new username with length not longer than 30 characters, a password and an email address to register.</p>
				<form action="" method="POST">
					<label>
					Username: <br>
					<input type="text" name="username"> <br>
					</label>
					<label>
					Password: <br>
					<input type="password" name="password"> <br>
					</label>
					<label>
					Retype Password: <br>
					<input type="password" name="retype"> <br>
					</label>
					<label>
					Email Address: <br>
					<input type="text" name="email"> <br>
					</label>
					<br>
					<label>
					Type what you see in the CAPTCHA below, case-sensitive! <br>
					<input type="text" name="captcha_value"> <br>
					</label>
					<img src="captcha.png" /> <br>
					<input type="hidden" name="captcha_id" value="V4XBG" />
					<input type="submit" name = "register" value="Register">
				</form>
			    <?php
					include 'helperfunctions3.php';
					if(isset($_SESSION['username'])) { //check whether the user has logged in
					    header("Location:homedir3.php"); //if true, send to the home directory page
					    exit;
					}
					if(isset($_POST['register'])) { //check whether user has submitted the username 
						$username = $_POST['username'];
						$password = $_POST['password'];
						$retype = $_POST['retype'];
						$captcha_id = (string) $_POST["captcha_id"];
						$captcha_value = $_POST["captcha_value"];
						$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                            echo("$email is not a valid email address");
							exit;
                        }
						if(!preg_match('/^[\w_\-]+$/', $username)){
							echo "Invalid username";
							exit;
						}
						if(!preg_match('/^[\w_\-]+$/', $password)){
							echo "Invalid password";
							exit;
						}
						if ($retype != $password) {
							echo "passwords do not match";
							exit;
						}
                        $checkuser = checkuser($username); //check whether the username has been created
                        if ($checkuser != "null") {
                            echo "username already existed, please log in.";
                            exit;
                        }
				
						// check CAPTCHA
						require "database3.php";
						$captcha_stmt = $mysqli->prepare("select count(*) from CaptchaTable where id=? and value=?");
						if (!$captcha_stmt) {
							printf("Query Prep Failed: %s\n", $mysqli->error);
							exit;
						}
						$captcha_stmt->bind_param("ss", $captcha_id, $captcha_value);
						$captcha_stmt->execute();
						$captcha_stmt->bind_result($captcha_row);
						$captcha_stmt->fetch();
						$captcha_stmt->close();
 
						if ($captcha_row == 0){
							echo "Invalid CAPTCHA";
							exit;
						}
						
                        // create new user
                        $iscreate = adduser($username, $password, $email);
						if ($iscreate) {
						    echo "new user is created, please log in.";
						} else {
						   echo "Error: cannot create new user.";
						}
					}
				?>
            </div>
        </body>
    </html>
<?php session_start();?> //start the session for log in
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
                    <a href="logout3.php"> log out</a>
					<a href="register3.php"> Register </a>
                </nav>
            </header>
            <div class="login">
				<p>Please log in using a username and a password.</p>
				<form action="" method="POST">
					<label>
					Username: <br>
					<input type="text" name="username"> <br>
					</label>
					<label>
					Password: <br>
					<input type="password" name="password"> <br>
					</label>
					<input type="submit" name = "login" value="Log in">
				</form>
			    <?php
					include 'helperfunctions3.php';
					if(isset($_SESSION['username'])) { //check whether the user has logged in
					    header("Location:homedir3.php"); //if true, send to the home directory page
					    exit;
					}
					if(isset($_POST['login'])) { //check whether user has submitted the username 
						$username = $_POST['username'];
						$password = $_POST['password'];
						if(!preg_match('/^[\w_\-]+$/', $username)){
							echo "Invalid username";
							exit;
						}
						if(!preg_match('/^[\w_\-]+$/', $password)){
							echo "Invalid password";
							exit;
						}
					    $checkuser = checkuser($username); //check whether the username has been created
						if ($checkuser == "null") { // if false, create a new username and sent to home directory
						   echo "username does not exist. Please register as a new user.";
						} else if ($username == $checkuser) {
							$checkpass = checkpassword($username, $password);
							if ($checkpass == $password) {
								$_SESSION['username'] = $checkuser;
								$_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
								header("Location:homedir3.php"); //if true, send to the home directory page
							} else {
								echo "Incorrect password!";
							}
						} 
					}
				?>
            </div>
        </body>
    </html>
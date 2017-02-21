<?php session_start();?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>edit comment done Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="homedir3.php"> Return to User Profile </a>     
                </nav>
            </header>
            <div class="login">
            <?php
                if(!isset($_SESSION['username'])) { // if no user logged in, direct to the home page
                    header("Location: NewsWebsite.html");
                }
                if (isset($_POST['comment'])) {
                    // prevent CSRF
                    if($_SESSION['token'] !== $_POST['token']){
                        die("Request forgery detected");
                    }
                    $oldid = $_POST['id'];
                    $newcontent = $_POST['yourComment'];
                    // validate content
					if (!filter_var($newcontent, FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/[a-zA-Z0-9\s]+/")))){
						echo "Invalid comment content";
						exit;
					}
                    require "database3.php";
                    // update comments table
                    $stmt3 = $mysqli->prepare("UPDATE comments SET comment_content = ?, comment_time = ? WHERE id = ?");
                    if(!$stmt3){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
					date_default_timezone_set('America/Chicago');
					$date = date('Y-m-d H:i:s');
                    $stmt3->bind_param('sss', $newcontent, $date, $oldid);
                    $stmt3->execute();
                    $stmt3->close();
                    echo "Edit comment complete";
                }
            ?>
            </div>
        </body>
    </html>
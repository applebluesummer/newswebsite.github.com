<?php session_start();?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>Delete comment Page</title>
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
                if (isset($_GET['file']) && isset($_GET['token'])) {
                    $id = $_GET['file'];
				// prevent CSRF
				if($_SESSION['token'] !== $_GET['token']){
					die("Request forgery detected");
				}
                    require "database3.php";
                    // delete from comments table
                    $stmt3 = $mysqli->prepare("DELETE FROM comments WHERE id = ?");
                    if(!$stmt3){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt3->bind_param('s', $id);
                    $stmt3->execute();
                    $stmt3->close();
                    echo "Delete complete";
                }
            ?>
            </div>
        </body>
    </html>
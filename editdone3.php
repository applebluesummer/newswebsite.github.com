<?php session_start();?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>edit done Page</title>
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
                if (isset($_POST['submitstory'])) {
                    // prevent CSRF
				if($_SESSION['token'] !== $_POST['token']){
					die("Request forgery detected");
				}
                    // validate title, content, and url
                    $newtitle = $_POST['file'];
                    $newcontent = $_POST['yourStory'];
                    $newurl = $_POST['url'];
                    if ($newurl != null && filter_var($newurl, FILTER_VALIDATE_URL) === false) {
                         echo("$newurl is not a valid url link");
					exit;
                    }
				if(!filter_var($newtitle, FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/[a-zA-Z0-9\s]+/")))){
					echo "Invalid title";
					exit;
				}
				if(!filter_var($newcontent, FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/[a-zA-Z0-9\s]+/")))){
					echo "Invalid content";
					exit;
				}
                    
                    $oldtitle = $_POST['oldtitle'];
				
                    require "database3.php";
				// update stories table
				$stmt3 = $mysqli->prepare("UPDATE stories SET story_title = ?, story_content = ?, submit_time = ? WHERE story_title = ?");
                    if(!$stmt3){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
					date_default_timezone_set('America/Chicago');
					$date = date('Y-m-d H:i:s');
                    $stmt3->bind_param('ssss', $newtitle, $newcontent, $date, $oldtitle);
                    $stmt3->execute();
                    $stmt3->close();
                    // insert into storylink table
				$stmt4 = $mysqli->prepare("UPDATE storylink SET url = ? WHERE story_title = ?");
                    if(!$stmt4){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt4->bind_param('ss', $newurl, $newtitle);
                    $stmt4->execute();
                    $stmt4->close();
                    echo "Edit complete";
                }
            ?>
            </div>
        </body>
    </html>
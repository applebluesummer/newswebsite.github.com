<?php session_start();
    if (isset($_GET['file'])) {
        $story_title_input = $_GET['file'];
    }
?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>Logout Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href='viewstory3.php?file=<?php echo $story_title_input;?>'> Return to view your comment</a>     
                </nav>
            </header>
            <div class="login">
            <?php
                if(!isset($_SESSION['username'])) { // if no user logged in, direct to the home page
                    echo "Please log in to submit comment. The page will redirect to the view page in 7 seconds.";
                    header("Refresh: 7; url=newshome3.php");
					exit;
                }
            ?>
            <div class="submitstory">
				<p>Please submit your comment.</p>
				<form method="POST" class="submit">
					<label>
						Your Comment: <br>
						<textarea name="yourComment" rows="3" cols="80"></textarea> <br>
					</label>
					<br>
                    <input type="hidden" name="storyTitle" value="<?php echo $story_title_input;?>">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" name = "comment" value="Submit Comment">
				</form>
                <?php
                if (isset($_POST['comment'])) {
					// prevent CSRF
					if($_SESSION['token'] !== $_POST['token']){
						die("Request forgery detected");
					}
                    require "database3.php";
                    // submit to comments table
                    $comment_content = $_POST['yourComment'];
					// validate content
					if (!filter_var($comment_content, FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/[a-zA-Z0-9\s]+/")))){
						echo "Invalid comment content";
						exit;
					}
                    $story_title_input = $_POST['storyTitle'];
                    $stmt = $mysqli->prepare("INSERT INTO comments (username, story_title, comment_content, comment_time) VALUES (?, ?, ?, ?)");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
					date_default_timezone_set('America/Chicago');
					$date = date('Y-m-d H:i:s');
                    $stmt->bind_param('ssss', $_SESSION['username'], $story_title_input, $comment_content, $date);
                    $stmt->execute();
                    $stmt->close();
                    echo "submit comment complete";
                }
                ?>
            </div>
            </div>
        </body>
    </html>
<?php session_start();
	if(!isset($_SESSION['username'])) { // if no user logged in, direct to the home page
        header("Location: NewsWebsite.html");
    }
    if ($_GET["file"]) {
        require "database3.php";
        $story_title_input = $_GET["file"];
        // fetch story from stories table
        $stmt = $mysqli->prepare("select story_content from stories where story_title = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $story_title_input);
        $stmt->execute();
        $stmt->bind_result($story_content_data);
        $stmt->fetch();
        $stmt->close();
        // fetch url from storylink table
        $stmt2 = $mysqli->prepare("select url from storylink where story_title = ?");
        if (!$stmt2) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt2->bind_param('s', $story_title_input);
        $stmt2->execute();
        $stmt2->bind_result($url_data);
        $stmt2->fetch();
        $stmt2->close();
    }
?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>edit story Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="homedir3.php"> Return to User Profile </a>  
                </nav>
            </header>
            <div class="login">
            <div class="submitstory">
				<p>Please EDIT this story with a unique story title.</p>
				<form action="editdone3.php" method="POST" class="submit">
					<label>
						Story Title: <br>
						<textarea name="file" rows="2" cols="47"><?php echo $story_title_input;?></textarea> <br>
					</label>
					<br>
					<label>
						Your Story: <br>
						<textarea name="yourStory" rows="5" cols="47"><?php echo $story_content_data;?></textarea> <br>
					</label>
					<br>
					<label>
						URL:<br>
						<textarea name="url" rows="2" cols="47"><?php echo $url_data;?></textarea> <br>
					</label>
					<br>
                    <input type="hidden" name="oldtitle" value="<?php echo $story_title_input;?>">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" name = "submitstory" value="Submit Edited Story">
				</form>
            </div>
            
            </div>
        </body>
    </html>
<?php session_start();
    if(!isset($_SESSION['username'])) { // if no user logged in, direct to the home page
        header("Location: NewsWebsite.html");
    }
    if ($_GET["file"]) {
        require "database3.php";
        $id_input = $_GET["file"];
        // fetch story from stories table
        $stmt = $mysqli->prepare("select comment_content from comments where id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $id_input);
        $stmt->execute();
        $stmt->bind_result($comment_content_data);
        $stmt->fetch();
        $stmt->close();
    }
?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>edit comment Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="homedir3.php"> Return to User Profile </a>  
                </nav>
            </header>
            <div class="login">
            <div class="submitstory">
				<p>Please edit your comment.</p>
				<form action="editcomdone3.php" method="POST" class="submit">
					<label>
						Your Comment: <br>
						<textarea name="yourComment" rows="3" cols="80"><?php echo $comment_content_data;?></textarea> <br>
					</label>
					<br>
                    <input type="hidden" name="id" value="<?php echo $id_input;?>">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" name = "comment" value="Submit Comment">
				</form>
            </div>
            
            </div>
        </body>
    </html>
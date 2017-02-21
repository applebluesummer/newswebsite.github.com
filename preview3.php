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
					<a href="homedir3.php"> Return </a>
                </nav>
            </header>
			<div class="login">
            <?php
				// prevent CSRF
				if($_SESSION['token'] !== $_POST['token']){
					die("Request forgery detected");
				}
                // validate title, content, and url
                $title = $_POST['storyTitle'];
				$content = $_POST['yourStory'];
				$url = $_POST['url'];
                if ($url != null && filter_var($url, FILTER_VALIDATE_URL) === false) {
                    echo("$url is not a valid url link");
					exit;
                }
				if(!filter_var($title, FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/[a-zA-Z0-9\s]+/")))){
					echo "Invalid title";
					exit;
				}
				if(!filter_var($content, FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/[a-zA-Z0-9\s]+/")))){
					echo "Invalid content";
					exit;
				}
				include "helperfunctions3.php";
				// check unique story title
				$checkstorytitle = checkstorytitle($title);
				if ($checkstorytitle) {
					echo "Story title existed. Please choose a different title.";
					exit;
				}
            ?>
            <div class="preview">
                <div class="title">
                    <p>
                        <?php
                            echo "$title";
                        ?>
                    </p>
                </div>
                <div class="time">
                    <p>
                        <?php
                           echo "Posted by " .$_SESSION['username']. " at date and time when story is submitted";
                           //echo "Posted by 4444 at date and time when story is submitted";
                        ?>
                    </p>
                </div>
                <p class="content">
                    <?php
                        echo "$content";
                    ?>
                </p> <br>
                <a href="<?php echo $url; ?>" target="_blank">link to the original story</a>
            </div>
            <br>
            <form method="POST">
				<input type="hidden" name="storyTitle" value="<?php echo $title;?>">
				<input type="hidden" name="yourStory" value="<?php echo $content;?>">
				<input type="hidden" name="url" value="<?php echo $url;?>">
				<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                <input type="submit" name = "submitstory" value="Submit Story Now!">
            </form>
            <?php
                if (isset($_POST['submitstory'])) {
                    $username = $_SESSION['username'];
					// insert into stories table
                    require "database3.php";
					$stmt = $mysqli->prepare("INSERT INTO stories VALUES (?, ?, ?, ?)");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
					date_default_timezone_set('America/Chicago');
					$date = date('Y-m-d H:i:s');
                    $stmt->bind_param('ssss', $username, $title, $content, $date);
                    $stmt->execute();
                    $stmt->close();
					// insert into storylink table
					$stmt2 = $mysqli->prepare("INSERT INTO storylink VALUES (?, ?)");
                    if(!$stmt2){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt2->bind_param('ss', $title, $url);
                    $stmt2->execute();
                    $stmt2->close();
					echo "submit complete";
                }
            ?>
			</div>
        </body>
    </html>
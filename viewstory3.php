<?php session_start();
    if ($_GET["file"]) {
        require "database3.php";
        $story_title_input = $_GET["file"];
        // fetch story from stories table
        $stmt = $mysqli->prepare("select username, story_title, story_content, submit_time from stories where story_title = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $story_title_input);
        $stmt->execute();
        $stmt->bind_result($username_data, $story_title_data, $story_content_data, $submit_time_data);
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
        // fetch all comments
        $stmt3 = $mysqli->prepare("select username, comment_content, comment_time from comments where story_title = ? ORDER BY comment_time DESC");
        if (!$stmt3) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt3->bind_param('s', $story_title_input);
        $stmt3->execute();
        $stmt3->bind_result($comment_username_data, $comment_content_data, $comment_time_data);
        $comments = array();
        while($stmt3->fetch()) {
            $comments[] = array($comment_username_data, $comment_content_data, $comment_time_data);
        }
        $stmt3->close();
    } else {
        echo "Please select a story title to view";
    }
?>
 <!DOCTYPE HTML> 
   <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>View Story Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="newshome3.php"> Return to view story page </a>
                    <a href="homedir3.php"> Return to User Profile </a>     
                    <a href="submitcomment3.php?file=<?php echo $story_title_input;?>"> Submit a new comment </a>
                </nav>
            </header>
            <div class="login">
            <div class="preview">
                <div class="title">
                    <p>
                        <?php
                            echo "$story_title_data";
                        ?>
                    </p>
                </div>
                <div class="time">
                    <p>
                        <?php
                           echo "Posted by " .$username_data. " at " .$submit_time_data;
                        ?>
                    </p>
                </div>
                <p class="content">
                    <?php
                        echo "$story_content_data";
                    ?>
                </p> <br>
                <?php
                    if ($url_data != null) {
                        echo "<a href=$url_data target='_blank'>link to the original story</a>";
                    } else {
                        echo "<p class='nourl'> no url link provided by author </p>";
                    }
                ?>
            </div>
            <div>
                <p>Comments: </p>
                <?php
                    foreach($comments as $comment) {
                        list($comment_username_data, $comment_content_data, $comment_time_data) = $comment;
                        echo "<div class='preview'>";
                        echo "<div class='time'>";
                        echo 'Posted by ' .$comment_username_data. ' at ' .$comment_time_data;
                        echo "</div>";
                        echo "<div class='content'>";
                        echo $comment_content_data;
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
            </div>
        </body>
    </html>
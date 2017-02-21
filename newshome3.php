<!DOCTYPE HTML> 
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>View news Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="NewsWebsite.html"> home </a>
                    <a href="login3.php"> log in </a>     
                </nav>
            </header>
            <div class="login">
            <div class="filetable">
                <p>List of stories: </p>
                <?php
                    // read all story titles
                    require "database3.php";
                    $titles = array();
                    $stmt = $mysqli->prepare("select story_title from stories");
                    if (!$stmt) {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->execute();
                    $stmt->bind_result($titles_data);
                    while($stmt->fetch()) {
                        $titles[] = $titles_data;
                    }
                    $stmt->close();
                    
                    // make a table using the list of titles
                    echo "<table>"; //begin table
					echo "<tr>"; //begin header line
					echo "<th>Story Titles</th>"; // define headers
					echo "</tr>"; //close header line
					foreach($titles as $title) {
						echo "<tr>";
						echo "<td> <nav> <a href='viewstory3.php?file=$title'>$title</a></nav> </td>"; 
						echo "</tr>";
					}
					echo "</table>";
                ?>
            </div>
            </div>
        </body>
    </html>
<?php session_start(); ?>
<!DOCTYPE HTML>
    <html lang="en">
        <head>
            <link rel="stylesheet" type="text/css" href="homestyle3.css">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
            <title>Home News Page</title>
        </head>
        <body>
            <header class="top-header">
                <nav class="top-nav">
                    <a href="logout3.php"> log out </a>
					<a href="newshome3.php"> view news </a>
                </nav>
            </header>
            <div class="login">
            <?php
                if(!isset($_SESSION['username'])) { // if no user logged in, direct to the home page
                    header("Location: NewsWebsite.html");
                }
                echo "Hello, ".htmlentities($_SESSION['username'])."<br>";
                echo "Login Successfully";
            ?>
            </div>
            <div class="submitstory">
				<p>Please submit a story with a unique story title.</p>
				<form action="preview3.php" method="POST" class="submit">
					<label>
						Story Title: <br>
						<input type="text" name="storyTitle" class="width_only"> <br>
					</label>
					<br>
					<label>
						Your Story: <br>
						<textarea name="yourStory" rows="5" cols="47"></textarea> <br>
					</label>
					<br>
					<label>
						URL:<br>
						<input type="text" name="url" class="width_only"> <br>
					</label>
					<br>
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" name = "preview" value="Preview">
				</form>
            </div>
           
            <div class="filetable">
                <p>List of stories you have posted: </p>
                <?php
                    include 'helperfunctions3.php';
                    // read associated story titles that user posted
                    $titles = readtitle($_SESSION['username']);
                    echo storytable($titles, $_SESSION['token']); // make a table using the list of titles
                ?>
            </div>
            
            <div class="filetable">
                <p>List of comments you have posted: </p>
                <?php
					$token = $_SESSION['token'];
                    // read associated comments that user posted
                    $titles = readcomments($_SESSION['username']);
                    echo "<table>"; //begin table
					echo "<tr>"; //begin header line
					echo "<th>Auto-generated Commented ID</th>"; // define headers
					echo "<th>Actions</th>";
					echo "</tr>"; //close header line
					foreach($titles as $comment) {
                        list($id_data, $title_data) = $comment;
						//each file name occupies each line with three navigators: view, edit and delete
						echo "<tr>";
						echo "<td>".$id_data."</td>";
						echo "<td> <nav> <a href='viewstory3.php?file=$title_data'>View</a> <a href='editcomment3.php?file=$id_data'>Edit</a> <a href='deletecomment3.php?file=$id_data&token=$token'>Delete</a></nav> </td>"; 
						echo "</tr>";
					}
					echo "</table>";
                ?>
            </div>
			
			<div class="submitstory">
				<p>Do you want to reset your password?</p>
				<form action="resetpassword3.php" method="POST">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" name = "reset" value="Reset password">
				</form>
			</div>
        </body>
    </html>
<?php session_start();?>
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
                    <a href="NewsWebsite.html"> home </a>
                    <a href="login3.php"> log in </a>     
                </nav>
            </header>
            <div class="login">
            <?php
            echo "Logout Successfully";
            session_destroy();
            ?>
            
            </div>
        </body>
    </html>
<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

function outputPicture($row){
    echo '<tr>';
    echo '<td>';
    echo '<a href="Details.php?id='.$row['ImageID'].'">';
    echo  '<img src="travel-images/medium/' . $row['PATH'] . '" height="300" width="400" style="float: left">';
    echo '</a>';
    echo '<h3 style="float:left; padding-left: 40px;padding-top: 30px">' . $row['Title'] . '</h3>';
    echo '<br>';
    echo '<p style="float:left; padding-left: 40px;padding-top: 30px">' . $row['Description'] . '</p>';
    echo '<input type="button" name="delete" value="DELETE" >';
    echo '<inout type="button" name="modify" value="MODIFY">';
    echo '</td>';
    echo '</tr>';
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My_Photo</title>
    <link rel="stylesheet" type="text/css" href="CSS/My_Photo.css">
</head>
<body>

<div class="sticky">
    <ul>
        <li><img src="travel-images/small/222223.jpg" width="48" height="48"></li>
        <li><a href="Home.php" class="Home">Home</a></li>
        <li><a href="Browser.php" class="Browse">Browse</a></li>
        <li><a href="Search.php" class="Search">Search</a></li>
        <?php
        session_start();
        if(isset($_SESSION["UserName"])){
            echo '<div class="dropdown">
            <button class="Account">My Account</button>
            <div class="dropdown-content">
                <a href="Upload.php">Upload</a>
                <a href="Favor.php">Favor</a>
                <a href="MyPhoto.php">My_Photo</a>
                <a href="LogOut.php">LogOut</a>
            </div>
        </div>';
        }
        else{
            echo ' <a href="LogIn.html">LoginIn</a>';
        }
        ?>
    </ul>
</div>

<div class="Show">
    <table class="content" width="1400" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>My  Photo</th>
        </tr>
        </thead>

    </table>
</div>

<div style="float: left;margin-top: 40px;">
    <footer>
        Copyright @2019-2021 Web fundamental.All Rights Reserved.备案号：19302010076
    </footer>
    <br>
</div>
</body>
</html>

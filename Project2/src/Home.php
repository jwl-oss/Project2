<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

function OutputHotPicture()
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select * from travelimage order by UID DESC limit 6 ";
    $result = $pdo->query($sql);
    for ($i = 0; $i < 2; $i++) {
        echo '<tr>';
        for ($j = 0; $j < 3; $j++) {
            $row = $result->fetch();
            outputSinglePainting($row);
        }
        echo '</tr>';
    }
    $pdo = null;
}

function outputSinglePainting($row) {
    echo '<td>';
    echo '<a href="Details.php?id='.$row['ImageID'].'">';
    echo '<img src="travel-images/large/' .$row['PATH'].'" width="250" height="250" >';
    echo '</a>';
    echo '<div class="content">';
    echo '<h4 class="header">';
    echo $row['Title'];
    echo '</h4>';
    echo '<p class="description">';
    echo $row['Description'];
    echo '</p>';
    echo '</div>';
    echo '</td>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="CSS/Home.css">
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
<?php
    try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select * from travelimage where ImageId ='1'";
    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        echo '<img src="travel-images/large/' .$row['PATH'].'" width="1519" height="630" >';
    }
    $pdo = null;

    }catch (PDOException $e) {
       die( $e->getMessage() );
}
?>

<table class="show" border="0" cellspacing="50">
    <?php OutputHotPicture();?>
</table>

<a href="Refresh.php"><button class="refresh" >Alert</button></a>
<br>
<a href="#"><button class="back">back</button></a>

<div>
    <footer>
        <div style="margin-left: 350px;float: left">
            使用条款<br>
            <br>
            隐私保护<br>
            <br>
            Cookie<br>
        </div>
        <div style="margin-left: 600px;float: left">
            关于<br>
            <br>
            联系我们<br>
        </div>
        <img src="travel-images/微信二维码.jpg" width="100" height="100" style="float: right;margin-right: 100px">
        <br>
        <div style="margin-top: 90px;float: left">
            <p>
                Copyright @2019-2021 Web fundamental.All Rights Reserved.备案号：19302010076
            </p>
        </div>
    </footer>
</div>


</body>
</html>

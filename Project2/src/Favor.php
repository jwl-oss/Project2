<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'DELETE FROM travelimagefavor WHERE ImageID = :id and UID = :uid';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id',$_POST['id']);
    $statement->bindValue(':uid',$_SESSION['UID']);
    $statement->execute();
    $pdo = null;
}

catch (PDOException $e) {

    die( $e->getMessage() );

}

function outputFavorPic(){
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if(isset($_GET['page']) ){
            $page =  $_GET['page'];
        }
        else{
            $page = 1;
        }
        $PageSize = 6;
        $sql = 'SELECT COUNT(*) AS amount from travelimage JOIN travelimagefavor ON travelimagefavor.UID = '.$_SESSION['UID'].' WHERE travelimage.ImageID = travelimagefavor.ImageID';
        $result = $pdo->query($sql);
        $row = $result->fetch();
        $amount = $row['amount'];
        $totalPage=ceil($amount/$PageSize);
        $startNum = 6*($page-1);

        $id =  $_SESSION['UID'];
        $sql = 'select travelimage.ImageID,Title,Description,PATH from travelimage JOIN travelimagefavor ON travelimagefavor.UID = :id WHERE travelimage.ImageID = travelimagefavor.ImageID LIMIT '.$startNum.',6';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        while($row = $statement->fetch()){
            outputSinglePic($row);
        }
        $pdo = null;

    }catch (PDOException $e) {

        die( $e->getMessage() );

    }

}

function outputSinglePic($row) {

    echo '<tr>';
    echo '<td>';
    echo '<a href="Details.php?id='.$row['ImageID'].'">';
    echo '<img src="travel-images/large/' .$row['PATH'].'" width="400" height="400" >';
    echo '</a>';
    echo '<h3 style="float:left; padding-left: 40px;padding-top: 30px">' . $row['Title'] . '</h3>';
    echo '<br>';
    echo '<p style="float:left; padding-left: 40px;padding-top: 30px">' . $row['Description'] . '</p>';
    echo '</td>';
    echo '</tr>';
    echo '<input class="removeFavor" type="button" name="Remove" value="remove" alt="'.$row['ImageID'].'">';
}

?>

<script>
    $(".removeFavor").click(function () {
        let lastURL = window.location.href;
        let id = $(this).attr("alt");
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState===4 && xmlhttp.status===200)
            {
                location.replace(lastURL);
            }
        }
        xmlhttp.open("POST","../src/ArrangeFavorPics.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
    });
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favor</title>
    <link rel="stylesheet" type="text/css" href="CSS/Favor.css">
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

<div class="Favor">
    <table class="content" width="1400" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>My Favorite</th>
        </tr>
        </thead>
        <?php outputFavorPic(); ?>
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

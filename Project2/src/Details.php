<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

$id=$_GET['id'];

$sql = "SELECT * from travelimage NATURAL JOIN traveluser JOIN geocountries_regions ON geocountries_regions.ISO= travelimage.Country_RegionCodeISO JOIN geocities ON geocities.GeoNameID=travelimage.CityCode WHERE travelimage.ImageId ='$id'";

$sqlFavor = "SELECT COUNT(*) AS favornum FROM travelimagefavor WHERE ImageID = $id";

$result = mysqli_query($connection, $sql);

$row = mysqli_fetch_assoc($result);

$resultFavor=mysqli_query($connection, $sqlFavor);

$favornum=mysqli_fetch_assoc($resultFavor);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Details</title>
    <link rel="stylesheet" type="text/css" href="CSS/Details.css">
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
                <a href="LogOut.php">Uploaded</a>
            </div>
        </div>';
        }
        else{
            echo ' <a href="LogIn.html">LoginIn</a>';
        }
        ?>
    </ul>
</div>

<div class="Details">
    <table class="content" width="1300" cellspacing="0" cellpadding="0" >
        <thead class="table_head">
        <tr>
            <th>Details</th>
        </tr>
        </thead>
        <tr>
            <td style="padding-top: 20px;">
                <h2><?php echo $row['Title']?></h2>
                <br>
                <?php echo '<img src="travel-images/large/' . $row['PATH'] . '" width="550" height="450" style="float: left;margin-left: 100px" >';?>
                <table class="content" width="500" cellspacing="0" cellpadding="0" style="float: left;">
                    <thead class="table_head">
                    <tr>
                        <th>Like Number</th>
                    </tr>
                    </thead>
                    <tr>
                        <td style="height:50px">
                            <?php echo $favornum['favornum'];?>
                        </td>
                    </tr>
                </table>
                <table class="content" width="500" cellspacing="0" cellpadding="0" style="float: left;">
                    <thead class="table_head">
                    <tr>
                        <th>Image Details</th>
                    </tr>
                    </thead>
                    <tr>
                        <td>Content:
                            <?php echo $row['Content']?>
                        </td>
                    </tr>
                    <tr>
                        <td>Country:
                            <?php echo $row['Country_RegionName']?>
                        </td>
                    </tr>
                    <tr>
                        <td>City:
                            <?php echo $row['AsciiName']?>
                        </td>
                    </tr>
                </table>
                <?php LikeOrDislike(); ?>
                <br>
                <p style="float: left;margin-left: 70px;margin-top: 30px;margin-bottom: 40px">
                    <?php echo $row['Description'];?>
                    </p>
            </td>
        </tr>
    </table>
</div>

<div style="float: left;margin-top: 50px">
    <footer>
        Copyright @2019-2021 Web fundamental.All Rights Reserved.备案号：19302010076
    </footer>
    <br>
</div>

</body>
</html>

<?php

function LikeOrDislike(){
    $connection =mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
    $username=$_SESSION['UserName'];
    $id=$_GET['id'];
    if(mysqli_connect_errno()){
        die(mysqli_connect_errno());
    }
    $sql = "SELECT * FROM travelimagefavor NATURAL JOIN traveluser WHERE traveluser.UserName = '$username'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    //判定是否已经收藏
    if (!$row['ImageID']) {
        echo '<a href="Detail_Like.php"><button>收藏</button>';
        return;
    }
    else if (in_array($id, $row)) {//匹配搜索结果中可有此id；
        echo '<a href="Detail_Dislike.php"><button>取消收藏</button>';
    } else {
        echo '<a href="Detail_Like.php"><button>收藏</button>';
    }

}

?>

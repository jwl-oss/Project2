<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

function outputContent()
{
try {
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT Content, COUNT(Content) AS contentnum FROM travelimage GROUP BY Content ORDER BY contentnum DESC LIMIT 0,3";
$result = $pdo->query($sql);
while ($row = $result->fetch()) {
    echo '<tr>';
    echo '<td>';
    echo '<a href="Browser.php?category=content&content=' . $row['Content'] . '">' . $row['Content'] . '</a>';
    echo '</td>';
    echo '</tr>';
}
$pdo = null;
} catch (PDOException $e) {
die($e->getMessage());
}
}

function outputCountries()
{
try {
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT Country_RegionName,ISO,COUNT(travelimage.Country_RegionCodeISO) AS countrynum 
FROM geocountries_regions LEFT JOIN travelimage ON travelimage.Country_RegionCodeISO =geocountries_regions.ISO GROUP BY ISO ORDER BY countrynum DESC LIMIT 0,3';
$result = $pdo->query($sql);
while ($row = $result->fetch()) {
    echo '<tr>';
    echo '<td>';
    echo '<a href="Browser.php?category=country&iso=' . $row['ISO'] . '">' . $row['Country_RegionName'] . '</a>';
    echo '</td>';
    echo '</tr>';
}
$pdo = null;
} catch (PDOException $e) {
die($e->getMessage());
}
}

function outputCities()
{
try {
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT AsciiName,GeoNameID,COUNT(travelimage.CityCode) AS citynum 
FROM geocities  LEFT JOIN travelimage ON travelimage.CityCode =geocities.GeoNameID GROUP BY GeoNameID ORDER BY citynum DESC LIMIT 0,3";
$result = $pdo->query($sql);
while ($row = $result->fetch()) {
    echo '<tr>';
    echo '<td>';
    echo '<a href="Browser.php?category=city&geoNameID=' . $row['GeoNameID'] . '">' . $row['AsciiName'] . '</a>';
    echo '</td>';
    echo '</tr>';
}
$pdo = null;
} catch (PDOException $e) {
die($e->getMessage());
}
}

function outputFilterResult()
{
    $content=null;
    if($_GET['content']=='scenery'){
    $content='scenery';
}
    $category = $_GET['category'];
    $iso = $_GET['iso'];
    $citycode = $_GET['geoNameID'];
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);


if (!isset($_GET['page']))
    $currentPage = 1;
else
    $currentPage = $_GET['page'];

if ($category == 'content') {
    $amount = "select ImageID,PATH from travelimage WHERE Content = '$content'";
} elseif ($category == 'country') {
    $amount = "select ImageID,PATH from travelimage WHERE Country_RegionCodeISO =  '$iso'";
} elseif ($category == 'city') {
    $amount = "select ImageID,PATH from travelimage WHERE cityCode =  '$citycode'";
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title=$_POST['title'];
    $amount = "select ImageID,PATH from travelimage WHERE Title like '%" . $title . "%'";
}else{
    $amount = "select ImageID,PATH from travelimage LIMIT 16";
}

   $result = mysqli_query($connection, $amount);

if ($result)
    $totalCount = $result->num_rows;//共有多少条数据；
else
    $totalCount = 0;

if ($totalCount == 0)
    echo '<script type="text/javascript">alert("没有相关图片！");</script>';
else if ($totalCount >= 90) {
   $pageSize = 16;
   $totalPage = 5;
} else {
  $pageSize = 16;
   $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));
}

$mark = ($currentPage - 1) * $pageSize;


if (mysqli_connect_errno()) {
die(mysqli_connect_error());
}
if ($category == 'content') {
  $sql = "select ImageID,PATH from travelimage WHERE Content = '$content'LIMIT  $mark,16";

} elseif ($category == 'country') {

$sql = "select ImageID,PATH from travelimage WHERE Country_RegionCodeISO =  '$iso 'LIMIT $mark,16";
} elseif ($category == 'city') {

$sql = "select ImageID,PATH from travelimage WHERE cityCode =  '$citycode' LIMIT $mark,16";

}else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sql = "select ImageID,PATH from travelimage WHERE Title like '%" . $title . "%' LIMIT $mark,16";
}else{
    $sql="select ImageID,PATH from travelimage LIMIT $mark,16";
}



    $result = mysqli_query($connection, $sql);


    echo '<tr>';
    echo '<td>';
    for ($j = 0; $j < $pageSize; $j++) {
        $row = mysqli_fetch_assoc($result);//关联数组；
        if(!$row)break;
        echo '<a href="Details.php?id='.$row['ImageID'].'">';
        echo '<img src="travel-images/large/' . $row['PATH'] .'"width="230" height="230" >';
        echo '</a>';
    }
    echo '</td>';
    echo '</tr>';

    if($category=='content'){
        echo'<tr>
            <td>
        <a href="Browser.php?category=content&content='.$content.'&page=1"><<</a>';
        for($i=1;$i<$totalPage+1;$i++){
            echo '<a href="Browser.php?category=content&content='.$content.'&page='.$i.'">'.$i.'</a>';
        }
        echo '<a href="Browser.php?category=content&content='.$content.'&page='.$totalPage.'"> >> </a>';
        echo '</td></tr>';
    }
    if($category=='country'){
        echo'<tr><td>
        <a href="Browser.php?category=content='.$content.'&page=1"><<</a>';
        for($i=1;$i<$totalPage+1;$i++){
            echo '<a href="Browser.php?category=country&iso='.$iso.'&page='.$i.'">'.$i.'</a>';
        }
        echo '<a href="Browser.php?category=country&iso='.$iso.'&page='.$totalPage.'"> >> </a>';
        echo '</td></tr>';
    }
    if($category=='city'){
        echo'<tr><td>
        <a href="Browser.php?category=city&geoNamID='.$citycode.'&page=1"><<</a>';
        for($i=1;$i<$totalPage+1;$i++){
            echo '<a href="Browser.php?category=city&geoNamID='.$citycode.'&page='.$i.'">'.$i.'</a>';
        }
        echo '<a href="Browser.php?category=city&geoNamID='.$citycode.'&page='.$totalPage.'"> >> </a>';
        echo '</td></tr>';
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo'<tr><td>
        <a href="Browser.php?page=1"><<</a>';
        for($i=1;$i<$totalPage+1;$i++){
            echo '<a href="Browser.php?page='.$i.'">'.$i.'</a>';
        }
        echo '<a href="Browser.php?page='.$totalPage.'"> >> </a>';
        echo '</td></tr>';
    }

} ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browser</title>
    <link rel="stylesheet" type="text/css" href="CSS/Browser.css">
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

<div class="Hot" style="float: left">
    <div id="FilterSearch">
        <form action="Browser.php" method="post" role="form">
            <h3>Filter By Title</h3>
            <input type="text" name="title" id="title"><br>
            <input type="submit" name="submit" value="submit" ">
        </form>
    </div>

    <table class="content" width="300" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>Hot Content</th>
        </tr>
        </thead>
                <?php outputContent();?>
    </table>
    <table class="content" width="300" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>Hot City</th>
        </tr>
        </thead>
                <?php outputCities(); ?>
    </table>
    <table class="content" width="300" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>Hot Country</th>
        </tr>
        </thead>
        <?php outputCountries() ?>
    </table>
</div>

<div class="Filter" style="float: left">
    <table class="content" width="1010" cellpadding="0" cellspacing="0">
        <thead class="table_head">
        <tr>
            <th>Filter</th>
        </tr>
        </thead>
        <tr style="height: 80px">
            <td>
                <form action="" method="get" role ="form">
                    <select name="Content" required>
                        <option value="defalut" selected>Filter By Content</option>
                        <option value "scenery">Scenery</option>
                        <option value="city">City</option>
                        <option value="people">People</option>
                        <option value="wonder">Wonder</option>
                        <option value="other">Other</option>
                    </select>
                    <select name="CouRegs" id="Countries" onChange="" required>
                        <option value="default" selected>Filter By Country</option>
                    </select>

                    <select name="Cities" id="Cities">
                        <option value="default" selected>Filter By City</option>
                    </select>
                    <input type="submit" name="filter" value="FILTER">
                </form>
            </td>
        </tr>
        <?php
        outputFilterResult();
        ?>
                <footer>
                </footer>
    </table>
</div>
<br>
<div style="float: left">
    <footer>
Copyright @2019-2021 Web fundamental.All Rights Reserved.备案号：19302010076
</footer>
    <br>
</div>

</body>
</html>


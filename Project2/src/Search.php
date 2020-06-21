<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

function ShowSearchPicture(){
    $title=$_POST['Title'];
    $description=$_POST['Description'];
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        if ($title == null && $description == null) {
            echo '<script>alert("请输入信息！")</script>';
        } elseif ($title !== null) {
            $sql = "select ImageID,Title, Description,PATH from travelimage WHERE Title like '%" . $title . "%'";
        } elseif ($description !== null) {
            $sql = "select ImageID,Title, Description,PATH from travelimage WHERE Title like '%" . $description . "%' ";
        }
        $result=mysqli_query($connection,$sql);
        $amount=mysqli_num_rows($result);//数据总数

        if (isset($_GET['page']) && $_GET['page'] > 1){
            $page = $_GET['page'];
    }else{
            $page = 1;}

        $pageSize = 6;
        $mark=($page-1)*$pageSize;
        $pageNum=ceil($amount/$pageSize);

        if ($title == null && $description == null) {
            echo '<script>alert("请输入信息！")</script>';
        } elseif ($title !== null) {
            $sql = "select ImageID,Title, Description,PATH from travelimage WHERE Title like '%" . $title . "%' LIMIT $mark ,6 ";
        } elseif ($description !== null) {
            $sql = "select ImageID,Title, Description,PATH from travelimage WHERE Title like '%" . $description . "%'LIMIT $mark ,6 ";
        }
        $result = mysqli_query($connection, $sql);

        if ($result) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>';
                echo '<a href="Details.php?id='.$row['ImageID'].'">';
                echo  '<img src="travel-images/medium/' . $row['PATH'] . '" height="300" width="400" style="float: left">';
                echo '</a>';
                echo '<h3 style="float:left; padding-left: 40px;padding-top: 30px">' . $row['Title'] . '</h3>';
                echo '<br>';
                echo '<p style="float:left; padding-left: 40px;padding-top: 30px">' . $row['Description'] . '</p>';
                echo '</td>';
                echo '</tr>';
            }
        }
        echo '<tr>';
        echo '<td>';
        echo '<a href="Search.php?page=1"> << </a>';//第一页
        for($i=1;$i<=$pageNum;$i++){
            echo '<a href="Search.php?page='.$i.'">'.$i.'</a>';
        }
        echo '<a href="Search.php?page='.$pageNum.'"> >> </a>';//最后一页
        echo '</td>';
        echo '</tr>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="CSS/Search.css">
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

<div class="alert">
    <table class="content" width="1400" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>Search</th>
        </tr>
        </thead>
        <tr>
            <td>
                <form action="Search.php" method="post" role="form" >
                    <input type="radio" value="Title" name="Filter">Filter By Title<br>
                    <input type="text" name="Title" style="width: 1300px;height: 30px"><br>
                    <input type="radio" value="Description" name="Filter">Filter By Description<br>
                    <input type="text" name="Description" style="width: 1300px;height: 100px"><br>
                    <input style="margin-top: 20px"  type="submit" name="filter" value="FILTER"><br>
                </form>
            </td>
        </tr>
    </table>

</div>

<div class="result">
    <table class="content" width="1400" cellspacing="0" cellpadding="0">
        <thead class="table_head">
        <tr>
            <th>Result</th>
        </tr>
        </thead>
        <?php
         if($_SERVER["REQUEST_METHOD"]=="POST"){ShowSearchPicture();}?>
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

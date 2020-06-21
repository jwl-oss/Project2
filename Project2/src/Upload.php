<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);


if(isset($_GET['ImageID'])){
    try {
        //对已上传的图片进行信息的获取。
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT travelimage.ImageID, Title, Description,Content,PATH,geocountries_regions.Country_RegionName,travelimage.Country_RegionCodeISO,CityCode 
                from travelimage NATURAL JOIN traveluser JOIN geocountries_regions 
                ON geocountries_regions.ISO= travelimage.Country_RegionCodeISO WHERE travelimage.ImageId =:id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id',$_GET['ImageID']);
        $statement->execute();
        $row = $statement->fetch();
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }

}

function outputButton(){
    if(isset($_GET['ImageID'])){
        //图片已上传，进行修改。
        echo '<input type="submit" value="MODIFY" name="UploadSubmit">';
    }
    //图片未上传
    else echo '<input type="submit" value="SUBMIT" name="UploadSubmit">';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
    <link rel="stylesheet" type="text/css" href="CSS/Upload.css">
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
<main>
    <?php
    if(isset($_GET['ImageID'])){
         //已上传图片，post到modify页面进行处理
        echo '<form id="formMod" action="ModifyPicture.php" method="post" role="form" enctype="multipart/form-data">';
    }else{
         //未上传图片，post到upload页面处理
        echo '<form id="formUP" action="UploadPicture.php" method="post" role="form" enctype="multipart/form-data">';
    }
    ?>

    <fieldset>
        <?php
        if(isset($_GET['ImageID'])){
            echo '<input type="hidden" name="ImageID" value="'.$_GET['ImageID'].'">';
        }
        ?>

        <legend>Upload Picture</legend>

        <div class="uploadPic">
            <?php
            if(isset($_GET['ImageID'])){
                //页面显示已上传图片
                echo '<img src="travel-images/large/' .$row['PATH'].'" >';
            }else{
                //提醒上传图片
                echo '<img id="PicFromUser" src=""/><p id="placeholder">Upload  Picture </p>';
            }
            ?>

        </div>

        <div class="wrap">

            <span>UPLOAD</span>
            <?php
            if(isset($_GET['ImageID'])){
                echo '<input type="file" name="photoUpload" id="file" required>';

            } else{
                echo '<input type="file" name="photoUpload" id="file" required>';
            }

            ?>

        </div>

        <label id="uploadPicTitle">Picture Title:
            <?php
            if(isset($_GET['ImageID'])){
                //显示已上传图片的title；
                echo '<input type="text" name="UploadPhotoTitle" value="'.$row['Title'].'" required>';

            }else{
                //输入图片title
                echo '<input type="text" name="UploadPhotoTitle" required>';
            }
            ?>

        </label>

        </label>

        <label id="uploadDep">Description:

            <?php
            if(isset($_GET['ImageID'])){
                echo '<textarea name="UploadPhotoDescription">'.$row['Description'].'</textarea>';
            }else{
                echo '<textarea name="UploadPhotoDescription"></textarea>';
            }

            ?>

        </label>

        <label id ="uploadPicContent">Content:

            <select name="Content" required>

                <?php
                if(isset($_GET['ImageID'])){
                    //创建数组，获取已上传图片的content并显示
                    $contentArr = array('default','scenery','city','people','animal','building','wonder','other');
                    for($i = 1;$i < 8;$i++){
                        if($row['Content'] == $contentArr[$i]){
                            echo '<option value="'.$contentArr[$i].'" selected>'.$contentArr[$i].'</option>';
                        }else{
                            echo '<option value="'.$contentArr[$i].'">'.$contentArr[$i].'</option>';
                        }
                    }
                }
                else{
                    echo '<option value="scenery">Scenery</option>
                          <option value="city">City</option>
                          <option value="people">People</option>
                          <option value="animal">Animal</option>
                          <option value="building">Building</option>
                          <option value="wonder">Wonder</option>
                          <option value="other">Other</option>';
                }
                ?>

            </select>


        <label id="uploadCountry">Country:

            <?php

            function outputCouRegMod($picRow){

                try {

                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                    $sql = 'SELECT ISO,Country_RegionName from geocountries_regions';

                    $statement = $pdo->query($sql);

                    while($row = $statement->fetch()){

                        if($picRow['Country_RegionCodeISO'] == $row['ISO'])

                            echo '<option value="'.$row['ISO'].'" selected>'.$row['Country_RegionName'].'</option>';

                        else{

                            echo '<option value="'.$row['ISO'].'">'.$row['Country_RegionName'].'</option>';

                        }

                    }

                    $pdo = null;

                }catch (PDOException $e) {

                    die( $e->getMessage() );

                }

            }//获取已经上传图片的country-selected

            function outputCouRegUP(){

                try {

                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = 'SELECT ISO,Country_RegionName from geocountries_regions';

                    $statement = $pdo->query($sql);

                    while($row = $statement->fetch()){

                        echo '<option value="'.$row['ISO'].'">'.$row['Country_RegionName'].'</option>';

                    }

                    $pdo = null;

                }catch (PDOException $e) {

                    die( $e->getMessage() );

                }

            }//获取country-option

            if(isset($_GET['ImageID'])){

                $mode = $row['CityCode'];
                echo '<select name="Countries" onchange="setCity(this,this.form.Cities)" id="Countries" required class="a'.$row['CityCode'].'">';
                //这里的setCity牵扯到二级联动，具体在upload.js中。
                outputCouRegMod($row);
            }else{
                $mode = 'up';
                echo '<select name="Countries" onchange="setCity(this,this.form.Cities)" id="Countries" required class="up">';
                outputCouRegUP();
            }

            ?>
            </select>

        </label>
            <label id="uploadCity">City:
                <select name="Cities" id="Cities">
                    <option value="default" selected>Cities</option>
                      //一个失败的二级联动；
            </select>
            </label>

            <?php outputButton();?>

    </fieldset>

    </form>

</main>

<br>
<div style="float: left">
    <footer>
        Copyright @2019-2021 Web fundamental.All Rights Reserved.备案号：19302010076
    </footer>
    <br>
</div>
//除去下方两行，上传图片时无法显示图片，以及二级联动失败（连接了二级联动也失败了，嘤嘤嘤）
<script type="text/javascript" src="upload.js"></script>

<script type="text/javascript" src="TwoSelect.js"></script>
</body>
</html>
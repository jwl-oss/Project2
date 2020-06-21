<?php

session_start();

require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

function validRegister(){

    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT UserName FROM traveluser';
    $result = $pdo->query($sql);
    while($row = $result->fetch()){
        if($row['UserName'] == $_POST['username']){
            echo '<script type="text/javascript">alert("用户已注册");</script>';
            return false;
        }
    }if ($_POST['password']!==$_POST['password2']){
        echo '<script type="text/javascript">alert("密码不一致");</script>';
        return false;

    }

    $pdo = null;

    return true;

}

function addUser(){

    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //count number of users
    $sqlUID = 'SELECT COUNT(*) AS UserNum FROM traveluser';
    $result = $pdo->query($sqlUID);
    $UID= $result->fetch();
    $sqlAddUser = "INSERT INTO traveluser VALUES (:UID,:email,:username,:pass,'1',:dateJoined,:lastModified)";
    $stmAddUser = $pdo->prepare($sqlAddUser);
    $stmAddUser->bindValue(':UID',$UID['UserNum']+1);
    $stmAddUser->bindValue(':username',$_POST['username']);
    $stmAddUser->bindValue(':pass',$_POST['password']);
    $stmAddUser->bindValue(':email',$_POST['email']);
    $presentDate = date("Y-m-d H:i:s");
    $lastModifiedDate = date("Y-m-d H:i:s");
    $stmAddUser->bindValue(':dateJoined',$presentDate);
    $stmAddUser->bindValue(':lastModified',$lastModifiedDate);
    $stmAddUser->execute();
    $pdo = null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册界面</title>
</head>
<body>
<header>
</header>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(validRegister()){
        addUser();
        echo '<script type="text/javascript">alert("注册成功！"); window.location.href="LogIn.html";</script>';
}
}

?>

<main>

    <fieldset>
    <form method="post" action="" role="form">
        <div class="user">
            <label>
                Username:
                    <input type="text" name="username" pattern="^[_a-zA-Z0-9]{1,}$" required>
            </label>
        </div>
        <div class="email">
            <label>
                Email:
                <input type="email" name="email">
            </label>
        </div>
        <div class="password">
            <label>
                Password:
                <input type="password" name="password"  pattern="^[_a-zA-Z0-9]{3,}$" required>
            </label><br>
        </div>
        <div class="password">
            <label>
                Confirm Your Password::
                <input type="password" name="password2"  pattern="^[_a-zA-Z0-9]{3,}$" required>
            </label>
        </div>
        <input type="submit" name="registrySubmit" value="SUBMIT">
    </form>
    </fieldset>

</main>

<footer style="float: left; margin-top: 100px">
    Copyright @2019-2021 Web fundamental.All Rights Reserved.备案号：19302010076
</footer>


</body>

</html>


</body>

</html>

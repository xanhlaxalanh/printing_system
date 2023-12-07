<?php 
@include '../ConnectDB.php';

    session_start();
    
    if(isset($_GET['Id'])){
        $Id = $_GET['Id'];
        $query = "SELECT * FROM User WHERE Id = $Id";
        $re = mysqli_query($conn, $query);
    }

    if(isset($_POST['Save'])){
        $c_FullName = $_POST['FullName'];
        $c_Age = $_POST['Age'];
        $c_Sex = $_POST['Sex'];
        $c_Email = $_POST['Email'];
        $c_Sdt = $_POST['Sdt'];

        $sql = "UPDATE User SET FullName = '$c_FullName', Age = '$c_Age', Sex = '$c_Sex', Email = '$c_Email', Sdt = '$c_Sdt' WHERE Id=$Id";
            if(mysqli_query($conn, $sql)){
                echo ("Thành công");
            } else {
                echo ("Thất bại");
            }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Sửa thông tin sinh viên</title>
</head>
<body>
        <div class="container">
            <form method="POST" action="">

                <div class="form-group">
                    <label for="FullName">Fullname</label>
                    <input name="FullName" class="form-control"  placeholder="Enter email">

                <div class="form-group">
                    <label for="Age">Age</label>
                    <input name="Age" class="form-control"  placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label for="Sex">Sex</label>
                    <input name="Sex" class="form-control"  placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label for="Email">Email</label>
                    <input name="Email" class="form-control"  placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label for="Sdt">Sdt</label>
                    <input name="Sdt" class="form-control"  placeholder="Enter email">
                </div>

                <button type="submit" class="btn btn-primary" name="Save">Lưu</button>
            </form>
        </div>
</body>
</html>
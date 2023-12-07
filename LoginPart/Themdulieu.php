<?php
    @include 'database.php';

    session_start();

    if(isset($_POST['Them'])){
        $Id = $_POST['Id'];
        $FullName = $_POST['FullName'];
        $Age = $_POST['Age'];
        $Sex = $_POST['Sex'];
        $Email = $_POST['Email'];
        $Sdt = $_POST['Sdt'];
        $Token = $_POST['Token'];
        $State1 = $_POST['State1'];
        $Role_Type = $_POST['Role_Type'];
        
        if($conn -> query("INSERT INTO User (Id, FullName, Age, Sex, Email, Sdt, Token, State1, Role_Type) VALUES (N'$Id', N'$FullName', N'$Age', N'$Sex', N'$Email', N'$Sdt', N'$Token', N'$State1', N'$Role_Type')")){
            echo ("Ket noi thanh cong");
        } else{
            echo ("Khong ket noi duoc");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Thêm dữ liệu</title>
</head>
<body>
        <div class="container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="Id">Id</label>
                    <input name="Id" class="form-control" placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label for="FullName">Fullname</label>
                    <input name="FullName" class="form-control"  placeholder="Enter Fullname">
                </div>

                <div class="form-group">
                    <label for="Age">Age</label>
                    <input name="Age" class="form-control"  placeholder="Enter age">
                </div>

                <div class="form-group">
                    <label for="Sex">Sex</label>
                    <input name="Sex" class="form-control"  placeholder="Enter Sex">
                </div>

                <div class="form-group">
                    <label for="Email">Email</label>
                    <input name="Email" class="form-control"  placeholder="Enter Email">
                </div>

                <div class="form-group">
                    <label for="Sdt">Sdt</label>
                    <input name="Sdt" class="form-control"  placeholder="Enter Sdt">
                </div>

                <div class="form-group">
                    <label for="Token">Token</label>
                    <input name="Token" class="form-control"  placeholder="Enter Token">
                </div>

                <div class="form-group">
                    <label for="State1">State</label>
                    <input name="State1" class="form-control"  placeholder="Enter State">
                </div>

                <div class="form-group">
                    <label for="Role_Type">Role Rype</label>
                    <input name="Role_Type" class="form-control"  placeholder="Enter Role Type">
                </div>

                <button type="submit" class="btn btn-primary" name="Them">Thêm</button>
            </form>
        </div>
</body>
</html>

<?php 
@include '../ConnectDB.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Thông tin cá nhân</title>
</head>
<body>
    <form method="GET" action="Suathongtincanhan.php">
            <div class="form-group">
                <label for="Id">Id</label>
                <input name="Id" class="form-control" placeholder="Enter email">
            </div>
        <button type="submit" class="btn btn-primary" name="Change">Sửa</button>
    </form>
</body>
</html>
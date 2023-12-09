<?php
    @include_once("../ConnectDB.php");

    session_start();

    $ID = $_SESSION['id'];
    $Username = $_SESSION['username'];
    $Role = $_SESSION['role'];

    // Save the new order to DB
    if(isset($_POST['submit-order'])) {
        // Get Quantity
        $quantity = $_POST['quantity'];

        // Get the current date and time
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y/m/d H:i:s");

        // INSERT into DB
        $sql = "INSERT INTO BPP_Order (Order_ID, Order_Creation_Date, Quantity, Payment_Status, Owner_ID)
                VALUES (NULL, '$date', '$quantity', '0', '$ID')
                ";

        $conn->query($sql);
    }

    // Return
    header("Location: BuyPrintingPages.php");
?>
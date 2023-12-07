<?php
// Database connection details
$server = 'localhost';
$username = 'root';
$password = 'BQH14020031!';
$database = 'printservice';

// Create connection
$conn = new mysqli($server, $username, $password, $database, 3306) or die("Can not connect to database!");

$conn->select_db("printservice");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $printerID = $_POST['printerID'];
    $selection = $_POST['selection'];

    // Update query
    $sql = "UPDATE printer_list SET printer_avai = 'Y' WHERE printer_id = '$printerID'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
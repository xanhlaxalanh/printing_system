<?php
session_start();
// Retrieve the file details from the AJAX request
// $fileId = $_POST['fileId'] ?? '';
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $userId = $_SESSION['id'];
} else {
    $userId = 123;
}
$fileId = $_POST['fileId'] ?? substr(crc32(uniqid()), 0, 10);
$fileName = $_POST['uploadedFileName'] ?? '';
$uploadDate = date('Y-m-d H:i:s');
if (isset($_POST['fileType'])) {
    $fileType = $_POST['fileType']  ?? '';

} else {
    $fileType = '.pdf';
}

// WIP, dummy data
// $fileType = '.pdf';
$fileSize = '3MB';
$fileLink = 'dummyLink';
$numberOfPages = '5';


$host = 'localhost';
$dbname = 'printservice';
$username = 'root';
$password = 'BQH14020031!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Insert the file details into the file table
$sql = "INSERT IGNORE INTO file (ID, Name, File_Link, Type, Upload_Date, Number_Of_Pages, User_ID) VALUES (:fileId, :fileName, :fileLink, :fileType, :uploadDate, :numberOfPages, :userId)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':fileId', $fileId);
$stmt->bindParam(':fileName', $fileName);
$stmt->bindParam(':fileLink', $fileLink);
$stmt->bindParam(':fileType', $fileType);
$stmt->bindParam(':uploadDate', $uploadDate);
$stmt->bindParam(':numberOfPages', $numberOfPages);
$stmt->bindParam(':userId', $userId);


if ($stmt->execute()) {
    // File inserted successfully
    echo "File inserted successfully";
} else {
    // Error occurred while inserting the file
    $errorInfo = $stmt->errorInfo();
    echo "Error: " . $errorInfo[2];
}
?>
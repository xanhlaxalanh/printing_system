<?php
// Retrieve the user ID from the session


// session_start();
// $userId = $_SESSION['userId'];
$userId = 123;

if (!isset($userId)) {
        // Handle the case when the userId is not set
        echo "User ID is not set in the session";
        exit;
}

// Retrieve the print attributes from the AJAX request
$numOfCopies = $_POST['numOfCopiesOption'] ?? '';
// $orientation = $_POST['orientationOption'] ?? '';
$creationDate = date('Y-m-d H:i:s');
$duplex = $_POST['duplexOption'] ?? '';
$paperSize = $_POST['pageLayoutOption'] ?? '';
$paperSize= trim($paperSize);
$pagesPerSheet = '1';

// Set Total_Sheet as the value of pagesToPrintOption
$totalSheet = $_POST['pagesToPrintOption'] ?? '';

// Create a unique request ID
// $requestId = uniqid();
$fileId = 12;

// Establish a database connection
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

if (empty($numOfCopies)) {
        echo "Number of copies is missing";
        echo '<script>console.log("Number of copies is missing");</script>';
        exit;
}

if (empty($duplex)) {
        echo "Duplex option is missing";
        echo '<script>console.log("Duplex option is missing");</script>';
        exit;
}

if (empty($paperSize)) {
        echo "Paper size is missing";
        echo '<script>console.log("Paper size is missing");</script>';
        exit;
}
// Insert the print attributes into the REQUEST_PRINT table
$sql = "INSERT INTO print_request (ID, Creation_Date, Pages_Per_Sheet, Number_Of_Copies, Page_Size, `One/Doubled_Sided`, Total_Sheet, Status, File_ID)
        VALUES (:userId, :creationDate, :pagesPerSheet, :numOfCopies, :paperSize, :duplex, :totalSheet, '0', :fileId)";

// Prepare the SQL statement
$stmt = $pdo->prepare($sql);

// Bind the parameters
$stmt->bindParam(':userId', $userId);
$stmt->bindParam(':creationDate', $creationDate);
$stmt->bindParam(':pagesPerSheet', $pagesPerSheet);
$stmt->bindParam(':numOfCopies', $numOfCopies);
$stmt->bindParam(':paperSize', $paperSize);
$stmt->bindParam(':duplex', $duplex);
$stmt->bindParam(':totalSheet', $totalSheet);
$stmt->bindParam(':fileId', $fileId);

// $stmt->bindParam(':orientation', $orientation);

// Execute the SQL statement
// if($stmt->execute()){
//         $response = array('success' => true, 'message' => 'Print request sent successfully');
// echo json_encode($response);
// } else {
//         $response = array('success' => true, 'message' => 'Print request failed');
// echo json_encode($response);
// }
// Execute the SQL statement
if ($stmt->execute()) {
        $response = array('success' => true, 'message' => 'Print request sent successfully');
        echo json_encode($response);
        echo '<script>console.log("Print request sent successfully");</script>';
} else {
        $errorInfo = $stmt->errorInfo();
        echo "Error123456: " . $errorInfo[2]; // Print the error message
        $response = array('success' => true, 'message' => 'Print request failed');
        echo json_encode($response);
        echo '<script>console.log("Print request failed");</script>';
        
}

// Return a response to the AJAX request
// $response = array('success' => true, 'message' => 'Print request sent successfully');
// echo json_encode($response);
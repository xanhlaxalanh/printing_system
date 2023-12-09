<?php
// Retrieve the user ID from the session
session_start();

// session_start();
// $userId = $_SESSION['userId'];
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $userId = $_SESSION['id'];
} else {
        $userId = 1234567;
}

// if (!isset($userId)) {
//         // Handle the case when the userId is not set
//         echo "User ID is not set in the session";
//         exit;
// }

// Retrieve the print attributes from the AJAX request
$numOfCopies = $_POST['numOfCopiesOption'] ?? '';
// $orientation = $_POST['orientationOption'] ?? '';
$creationDate = date('Y-m-d H:i:s');
$duplex = $_POST['duplexOption'] ?? '';
$paperSize = $_POST['pageLayoutOption'] ?? '';
$paperSize = trim($paperSize);
$pagesPerSheet = '1';
$filenumpage = $_POST['numpage'] ?? '';
$filename = $_POST['name'] ?? '';
// Set Total_Sheet as the value of pagesToPrintOption
$pageRange = $_POST['pagesArray'] ?? ''; //1-5

// Create a unique request ID
// $requestId = uniqid();
// $fileId = $_POST['fileId'] ?? '';;
$fileId = $_POST['fileId'] ?? substr(crc32(uniqid()), 0, 10);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ssps";
try {

        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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

if (empty($pageRange)) {
        // Handle the case when the pageQuery is empty
        echo "Page Range is empty";
        exit;
}
@include '../ConnectDB.php';
// Insert the print attributes into the REQUEST_PRINT table
/*$sql = "INSERT INTO print_request ( Creation_Date, Pages_Per_Sheet, Number_Of_Copies, Page_Size, `One/Doubled_Sided`, Total_Sheet, Status, File_ID)
        VALUES ( :creationDate, :pagesPerSheet, :numOfCopies, :paperSize, :duplex, NULL , '0', :fileId)";

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
*/
@include '../ConnectDB.php';

$uploadfile = mysqli_query($conn, "INSERT INTO file(Name,File_Link,Type,Upload_Date,Number_Of_Pages, User_ID) VALUES('$filename', './$filename','Pdf',NOW(),'$filenumpage', '$userId')");
if ($uploadfile) {
        mysqli_multi_query($conn, "SELECT MAX(ID) INTO @fileid from file;");
        mysqli_multi_query($conn, "INSERT INTO print_request ( Creation_Date, Pages_Per_Sheet, Number_Of_Copies, Page_Size, `One/Doubled_Sided`, Total_Sheet, Status, File_ID)
        VALUES (NOW(), '$pagesPerSheet', '$numOfCopies', '$paperSize', '$duplex', NULL , '0', @fileid);");
        //if ($stmt->execute()) {

        $pageRangeArray = explode(",", $pageRange);
        for ($x = 0; $x < count($pageRangeArray); $x++) {
                $pageRangeChild = explode("-", $pageRangeArray[$x]);
                if ($pageRangeChild[1] == NULL) {
                        $$pageRangeChild[1] = $pageRangeChild[0];
                }
                if ($pageRangeChild[0] <= $pageRangeChild[1]) {

                        mysqli_multi_query($conn, "SELECT MAX(ID) INTO @id from print_request;");
                        mysqli_multi_query($conn, "INSERT INTO requested_page_numbers(Request_ID, Start_Page, End_Page) VALUES(@id,'$pageRangeChild[0]', '$pageRangeChild[1]') ;");
                } else {
                        echo '<script>alert("Page Range is invalid!");</script>';
                }
        }
        $response = array('success' => true, 'message' => 'Print request sent successfully');
        echo json_encode($response);
        echo '<script>console.log("Print request sent successfully");</script>';
} else {
        $response = array('success' => true, 'message' => 'Upload file failed');
        echo json_encode($response);
        echo '<script>console.log("Upload file failed");</script>';
}
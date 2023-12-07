<?php
@include '../ConnectDB.php';
if (isset($_GET['send_confirm_id'])) {
    $id = $_GET['send_confirm_id'];
    $check = mysqli_query($conn, "SELECT Status from print_request where id = '$id'");
    $getStatus = $check->fetch_all(MYSQLI_ASSOC);
    if ($getStatus[0]['Status'] == '0') {
        $upd1 = " UPDATE print_request set Status='1' WHERE id = '$id'";
        mysqli_query($conn, $upd1);
    } else if ($getStatus[0]['Status'] == '2') {
        mysqli_multi_query($conn, "SELECT Pages_Per_Sheet, Number_Of_Copies, Page_Size, `One/Doubled_Sided`,Total_Sheet, File_ID
        INTO @pagepersheet, @copies,@pagesize,@sides, @totalsheet, @fileid
        FROM print_request
        WHERE ID  = '$id';");
        mysqli_multi_query($conn,"INSERT INTO print_request(Creation_Date, Pages_Per_Sheet, Number_Of_Copies, Page_Size, `One/Doubled_Sided`,Total_Sheet,Status, File_ID) VALUES(NOW(),@pagepersheet, @copies,@pagesize,@sides,@totalsheet,'1', @fileid) ;");
    }
    header('location:activitylog.php');
}
?>
<?php
@include '../ConnectDB.php';
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
    if ($_POST['day'] / 10 == 0)
        $day = '0' . $_POST['day'];
    else
        $day = $_POST['day'];
    if ($_POST['month'] / 10 == 0)
        $month = '0' . $_POST['month'];
    else
        $month = $_POST['month'];
    $year = $_POST['year'];
    $sql = "delete perform from perform join requestprint as R 
        on perform.requestid = R.id 
        where YEAR(starttime)=$year and MONTH(starttime)=$month and DAY(starttime)=$day and state=1;";
    $res = mysqli_query($conn, $sql);
}
?>
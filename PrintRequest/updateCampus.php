<?php
@include 'database.php';

if (isset($_POST['campus'])) {
    $selectedCampus = $_POST['campus'];

    $query = "SELECT * FROM `printer_list` WHERE `printer_campusloc` = '$selectedCampus'";
    $result = $conn->query($query);

    if ($result) {
        $options = '';
        while ($row = $result->fetch_assoc()) {
            $columnValue = $row['printer_buildingloc'];
            $options .= '<option class="embed" value="' . $columnValue . '">' . $columnValue . '</option>';
        }
        echo $options;
    } else {
        echo "Error executing the query: " . $conn->error;
    }
}
?>
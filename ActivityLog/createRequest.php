<?php


@include 'database.php';
$maxfilesize = 50000000; //50MB
$allowUpload = true;
function convert_upload_file_array($upload_files)
{
    $converted = array();

    foreach ($upload_files as $attribute => $val_array) {
        foreach ($val_array as $index => $value) {
            $converted[$index][$attribute] = $value;
        }
    }
    return $converted;
}
$success = false;
if (isset($_POST['send'])) {

    $allowTypes = array('.docx', '.docm', '.dotx', '.dotm', '.xlsx', '.pptx', 'jpg', 'png', 'jpeg', 'pdf');
    if (isset($_FILES['fileupload'])) {
        $file_child = convert_upload_file_array($_FILES['fileupload']);

        foreach ($file_child as $key => $child) {
            $targetDir = 'upload/';
            $fileName = basename($child['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (!empty($child['name'])) {

                if (
                    (
                        ($child["type"] == "application/pdf")
                        || ($child["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
                        || ($child["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation")
                        || ($child["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                        || ($child["type"] == "image/gif")
                        || ($child["type"] == "image/jpeg")
                        || ($child["type"] == "image/jpg")
                        || ($child["type"] == "application/msword")
                        || ($child["type"] == "image/pjpeg")
                        || ($child["type"] == "image/x-png")
                        || ($child["type"] == "image/png")
                        && ($child["size"] < 20000000)
                        && in_array($fileType, $allowTypes)
                    )
                ) {
                    if ($child["error"] > 0) {
                        echo "Return Code: " . $child["error"] . "<br>";
                    } else {
                        if (file_exists("upload/" . $child["name"])) {
                            echo $child["name"] . " already exists. ";
                        } else {
                            $totalpage = 0;
                            // Upload file to server
                            if (move_uploaded_file($child['tmp_name'], $targetFilePath)) {
                                if (($child["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")) {
                                    $wdStatisticPages = 2; // Value that corresponds to the Page count in the Statistics
                                    $namefile = "C:\\xampp\htdocs\printing_service\upload\\$fileName";
                                    $word = new COM("word.application") or die("Could not initialise MS Word object.");
                                    print "Loaded Word, version {$word->Version}\n";
                                    $word->Documents->Open($namefile);
                                    $totalpage = $word->ActiveDocument->ComputeStatistics($wdStatisticPages);
                                    /*#$word->ActiveDocument->PrintOut();*/
                                    $word->ActiveDocument->Close();
                                    $word->Quit();
                                } else if (($child["type"] == "application/pdf")) {
                                    $totalpage = count_pdf_pages($targetFilePath);
                                } else if (($child["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation")) {
                                    $totalpage = PageCount_PPTX($targetFilePath);
                                } else {
                                    $totalpage = 0;
                                }
                                $insert = $conn->query("INSERT into file (userid,name,createddate,state,totalpage,filepath) VALUES ('1','$fileName',NOW(),'Mới tải lên','" . $totalpage . "','" . $targetFilePath . "')");
                                if ($insert) {
                                    $statusMsg = "The file has been uploaded successfully.";

                                    $success = true;
                                    echo '<div>Bạn vừa tải lên
                                    <label>Tên file: ' . $fileName . '</label>
                                    Số pages/slides: ' . $totalpage . '</div>';
                                    // Get the printer that is suitable with user
                                    echo '<div><button>Xem các lựa chọn máy in</button></div>
                                    ';
                                    // Get information of properties from user to create a request
                                    echo '<form method="post" action="">
            Bạn muốn in bao nhiêu bản (copy): 
            <input type="text" placeholder="Nhập số bản in" name"numbercopies">
            Bạn muốn in bao nhiêu mặt? 
            <input type="text" placeholder="Nhập số mặt in" name"numbersides">
            <input type="submit" value="Submit" name="info_request">
            </form>
            ';

                                    if (isset($_POST['info_request'])) {
                                        $numbersides = $POST['numbersides'];
                                        $numbercopies = $POST['numbercopies'];

                                        if (!empty($numbercopies) && !empty($numbersides)) {
                                            $fileid = $conn->query("SELECT MAX(id)FROM [file];");
                                            $insertProperties = $conn->query("INSERT into requestprint (userid,fileid,printerid,state,numbersides) VALUES ('1', $fileid, )");
                                        }
                                    }
                                } else
                                    $statusMsg = "File upload failed, please try again.";
                            } else {
                                $statusMsg = "Sorry, there was an error uploading your file.";
                            }
                            # $statusMsg = 'Sorry, only valid type files are allowed to upload.';
                        }
                    }
                } else {
                    echo 'Invalid file';
                }
            }
        }
    }

}

function PageCount_PPTX($file)
{
    $pageCount = 0;

    $zip = new ZipArchive();

    if ($zip->open($file) === true) {
        if (($index = $zip->locateName('docProps/app.xml')) !== false) {
            $data = $zip->getFromIndex($index);
            $zip->close();
            $xml = new SimpleXMLElement($data);
            $pageCount = $xml->Slides;
        }
        #$zip->close();
    }

    return $pageCount;
}
function count_pdf_pages($pdfname)
{
    $pdftext = file_get_contents($pdfname);
    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

    return $num;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a request</title>
</head>

<body>
    <div>
        <label>Tạo yêu cầu in</label>
    </div>
    <div>
        <label>Upload File mới:</label>
        <?php
        if (isset($statusMsg)) {

            echo '<span style="padding-bottom: 2%; font-size: 25px" class="error-msg">' . $statusMsg . '</span>';

            ;
        }
        ;
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            Chọn file để upload:
            <input type="file" name="fileupload[]" multiple>
            <input type="submit" value="Submit" name="send">
        </form>
    </div>

</body>

</html>
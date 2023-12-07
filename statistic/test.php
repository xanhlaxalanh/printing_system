<?php
@include '../ConnectDB.php';
$sql = mysqli_query($conn, "SELECT MONTHNAME(Creation_Date) as Date_, SUM(Total_Sheet) as sum
FROM print_request
where File_ID in (select id from file where file.User_ID = '1')
GROUP BY MONTHNAME(Creation_Date)");
$dataPoints = array();

while ($row = mysqli_fetch_assoc($sql)) {
    array_push($dataPoints, array("y" => $row['sum'], "label" => $row['Date_']));
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ sinh viên</title>

    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Thống kê số page Sinh viên Dương đã in"
                },
                axisY: {
                    title: "Tổng số page"
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>

<body>
    <section class="header">
        <div class="left-side">
            <div class="logo">
                <a href="#">
                    <img src="../images/logo.png" alt="logo" />
                    <p>ĐẠI HỌC QUỐC GIA TP.HCM<br>TRƯỜNG ĐẠI HỌC BÁCH KHOA</p>
                </a>
            </div>

            <div class="menu-bar">
                <div class="first-option"><a href="">trang chủ</a></div>
                <div class="second-option"><a href="">dịch vụ của tôi</a></div>
            </div>
        </div>

        <div class="right-side">
            <div class="username">Username</div>
            <div class="seperator">|</div>
            <div>
                <a href="#" class="login">Đăng xuất</a>
            </div>
        </div>
    </section>
    <div class="body">

        <div id="chartContainer" style="height: 370px; width: 100%;"></div>

    </div>

    <div class="footer-container">
        <section class="footer">
            <div class="box-container">
                <div class="box">
                    <h3>student smart printing service</h3>
                    <img src="images/logo.png" alt="logo" />
                </div>

                <div class="box">
                    <h3>website</h3>
                    <a href="https://hcmut.edu.vn/" class="hcmut">HCMUT</a>
                    <a href="https://mybk.hcmut.edu.vn/my/index.action" class="mybk">MyBK</a>
                    <a href="https://mybk.hcmut.edu.vn/bksi/public/vi/" class="bksi">BKSI</a>
                </div>

                <div class="box">
                    <h3>liên hệ</h3>
                    <a href="#">
                        <div class="location-icon"></div>268 Ly Thuong Kiet Street Ward 14, District 10, Ho Chi Minh
                        City, Vietnam
                    </a>
                    <a href="#">
                        <div class="phone-icon"></div>(028) 38 651 670 - (028) 38 647 256 (Ext: 5258, 5234)
                    </a>
                    <a href="mailto:elearning@hcmut.edu.vn" class="email">
                        <div class="email-icon"></div>elearning@hcmut.edu.vn
                    </a>
                </div>
            </div>
        </section>
        <div class="copyright">
            <p>Copyright 2007-2022 BKEL - Phát triển dựa trên Moodle</p>
        </div>
    </div>
    <!-- footer section ends -->






    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>

</html>
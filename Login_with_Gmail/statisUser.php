<?php
    session_start();

    @include 'database.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê trang in</title>

    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" type="text/css" href="../ActivityLog/actstyle.css">
    
</head>

<body>


    <!-- heaer section start -->
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
            <div class="username"><a href="infoManage.php">
                <?php
                    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['name'])) {
                        echo '<span class="user-name">' . htmlspecialchars($_SESSION['user_info']['name']) . '</span>';
                    }
                ?>
                </a>
            </div>
            <div class="seperator">|</div>
            <div>
                <a href="../Login_with_Gmail/home.php" class="logout">Đăng xuất</a>
            </div>
        </div>
    </section>

    <!--header section ends -->

    <div class="body">
        <h1 class="title">THỐNG KÊ TRANG IN</h1>
        <div id="wrap-selectDate">
            <form class="wrapper-selectDate" action="statisUser.php" method="post">
                <p>Chọn ngày bắt đầu:</p>
                <input type="date" class="selectedDate" name="startday" /><br>
                <p>Chọn ngày kết thúc:</p>
                <input type="date" class="selectedDate" name="endday" />
                <script>
                    var list = document.querySelectorAll('.selectedDate')
                    for (var i = 0; i < list.length; i++) {
                        list[i].max = new Date().toISOString().slice(0, -14);

                    }
                </script>
                <p><button class="button" type="submit">Submit</button></p>
            </form>

            <form class="wrapper-selectDate" action="statisUser.php" method="post">
                <input class="button" type="submit" name="all" value="Xem tất cả" />
            </form>
        </div>

        <section>
            <table border="1" id="spso_log_table">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>

                <thead>
                    <tr>
                        <th>Họ</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Ngày sinh</th>
                        <th>Số giấy đã sử dụng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $student_id = "";
                    function changeNumForm($date)
                    {
                        if ($date / 10 == 0)
                            $res = '0' . $date;
                        else
                            $res = $date;
                        return $res;
                    }
                    function handle_date($date)
                    {
                        $date = explode("-", $date);
                        $getDay = changeNumForm($date[2]);
                        $getMonth = changeNumForm($date[1]);
                        $getYear = $date[0];
                        return array($getYear, $getMonth, $getDay);

                    }


                    if (isset($_POST['startday']) && isset($_POST['endday'])) {
                        list($start_Year, $start_Month, $start_Day) = handle_date($_POST['startday']);
                        list($end_Year, $end_Month, $end_Day) = handle_date($_POST['endday']);
                        $result = mysqli_query($conn, "SELECT SUM(Total_Sheet) as sum, users.Fname as fn, users.Lname as ln, users.ID as id, users.Email as e, users.Date_Of_Birth as db
                        FROM users join file on users.ID = file.User_ID
                        join print_request on file.ID = print_request.File_ID
                        where print_request.Creation_Date between '$start_Year-$start_Month-$start_Day 00:00:00' and '$end_Year-$end_Month-$end_Day 23:59:00'
                        GROUP BY id
                        ORDER BY sum DESC;");

                        $sql = mysqli_query($conn, "SELECT MONTHNAME(Creation_Date) as Date_, SUM(Total_Sheet) as sum
                        FROM print_request
                        where File_ID in (select id from file)
                        and print_request.Creation_Date between '$start_Year-$start_Month-$start_Day 00:00:00' and '$end_Year-$end_Month-$end_Day 23:59:00'
                        GROUP BY MONTHNAME(Creation_Date)
                        ORDER BY MONTHNAME(Creation_Date) DESC");

                        $dataPoints = array();

                        while ($row = mysqli_fetch_assoc($sql)) {
                        array_push($dataPoints, array("y" => $row['sum'], "label" => $row['Date_']));
                        }


                    } else {
                        $result = mysqli_query($conn, "SELECT SUM(Total_Sheet) as sum, users.Fname as fn, users.Lname as ln, users.ID as id, users.Email as e, users.Date_Of_Birth as db
                        FROM users join file on users.ID = file.User_ID
                        join print_request on file.ID = print_request.File_ID
                        GROUP BY id
                        ORDER BY sum DESC;");

                        $sql = mysqli_query($conn, "SELECT MONTHNAME(Creation_Date) as Date_, SUM(Total_Sheet) as sum
                        FROM print_request
                        where File_ID in (select id from file)
                        GROUP BY MONTHNAME(Creation_Date)
                        ORDER BY MONTHNAME(Creation_Date) DESC");

                        $dataPoints = array();

                        while ($row = mysqli_fetch_assoc($sql)) {
                        array_push($dataPoints, array("y" => $row['sum'], "label" => $row['Date_']));
                        }
                    }

                    $data = $result->fetch_all(MYSQLI_ASSOC);

                    if (empty($data) && !isset($_POST['startday']) && !isset($_POST['endday'])) {
                        echo "<p style='border:None; color:var(--text-color); font-weight:500; font-size:17px;'>Nhật ký của sinh viên này hiện đang trống!</p>";
                    } else
                        foreach ($data as $row) {
                            echo '
                                <tr>
                                    <td>
                                        ' . $row['ln'] . '
                                    </td>

                                    <td>
                                        ' . $row['fn'] . '
                                    </td>

                                    <td>
                                        ' . $row['e'] . '
                                    </td>

                                    <td>
                                        ' . $row['db'] . '
                                    </td>
                                    
                                    <td>
                                        ' . $row['sum'] . '
                                    </td>
                                </tr> 
                            ';
                        }
                    ?>
                </tbody>
            </table>
        </section>
        <!-- <div id="chartContainer" style="height: 370px; width: 100%;"></div> -->
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Thống kê số page Sinh viên đã in",
                    fontFamily: "Arial",
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
    </div>
    <style>
        /* Design Calendar */
        #calendar-start {
            background-color: #ffffff;
            text-align: center;
            border-radius: 0%;
            z-index: 100000;
            display: block;
        }

        #calendar-start .days li.today,
        #calendar-end .days li.today {
            color: rgba(255, 0, 0, 0.321);
            font-weight: 1000;
        }
    </style>

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

<script>
    localStorage.setItem("ID", <?php echo $_SESSION['student'] ?>);
    localStorage.setItem("Role", <?php echo $_SESSION['role'] ?>);
    localStorage.setItem("Username",<?php echo "\"". $_SESSION["name"] ."\"" ?>);

</script>
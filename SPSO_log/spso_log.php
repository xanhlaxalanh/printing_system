<?php
@include '../ConnectDB.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPSO_Log</title>

    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!-- remix icon link -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="../ActivityLog/actstyle.css">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <!-- header section starts -->

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
                <a href="login.php" class="login">Đăng xuất</a>
            </div>
        </div>
    </section>
    <!-- header section ends -->

    <div class="body-side">
        <h1>NHẬT KÝ SỬ DỤNG DỊCH VỤ IN CỦA SINH VIÊN</h1>
        <div id="wrap-selectDate">
            <form class="wrapper-selectDate" action="../SPSO_log/spso_log.php" method="post">
                <p>Chọn ngày bắt đầu:</p>
                <input type="date" class="selectedDate" name="startday" />
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
            <form class="wrapper-selectDate" action="../SPSO_log/spso_log.php" method="post">
                <input class="button" type="submit" name="all" value="Xem tất cả" />
            </form>
        </div>

        <section>
            <?php
            if (isset($_GET['nameStudent'])) {
                $_SESSION['getName'] = $_GET['nameStudent'];
            }
            echo '<h2 class="displayName">' . $_SESSION['getName'] . '</h2>';
            ?>
            <table border="1" id="spso_log_table">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>

                <thead>
                    <tr>
                        <th>Nội dung đăng ký in</th>
                        <th>Số giấy đã trả</th>
                        <th>Thời gian in</th>
                        <th>Trạng thái</th>
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
                    if (isset($_GET['id'])) {
                        $_SESSION['student_id'] = $_GET['id'];
                    }
                    if (isset($_POST['startday']) && isset($_POST['endday'])) {
                        list($start_Year, $start_Month, $start_Day) = handle_date($_POST['startday']);
                        list($end_Year, $end_Month, $end_Day) = handle_date($_POST['endday']);
                        $result = mysqli_query($conn, "select perform.End_Time as endtime, print_request.Status as state_requestprint, 
                        print_request.Total_Sheet, file.Name as filename, users.Fname as student_name, printer_list.printer_name as printer_model, print_request.Total_Sheet as total_sheet
                        from perform join print_request on perform.Request_ID = print_request.ID
                        join printer_list on perform.Printer_ID = printer_list.printer_id 
                        join file on print_request.File_ID = file.ID 
                        join users on file.User_ID = users.ID
                                where perform.End_Time between '$start_Year-$start_Month-$start_Day 00:00:00' and '$end_Year-$end_Month-$end_Day 23:59:00' and print_request.Status = '2' and users.ID = '" . $_SESSION['student_id'] . "' order by perform.End_Time desc;");
                    } else {
                        $result = mysqli_query($conn, "select perform.End_Time as endtime, print_request.Status as state_requestprint, 
                        print_request.Total_Sheet, file.Name as filename, users.Fname as student_name, printer_list.printer_name as printer_model, print_request.Total_Sheet as total_sheet
                        from perform join print_request on perform.Request_ID = print_request.ID
                        join printer_list on perform.Printer_ID = printer_list.printer_id 
                        join file on print_request.File_ID = file.ID 
                        join users on file.User_ID = users.ID
                        where print_request.Status = '2' and users.ID = '" . $_SESSION['student_id'] . "' order by perform.End_Time desc;");
                    }

                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    if (empty($data)) {
                        echo "<p style='border:None; color:var(--text-color); font-weight:500; font-size:17px;'>Nhật ký của sinh viên này hiện đang trống!</p>";
                    } else
                        foreach ($data as $row) {
                            echo '
                        <tr>
                            <td>
                                ' . $row['filename'] . '
                            </td>
                            <td>
                                ' . $row['total_sheet'] . '
                            </td>
                            <td>
                                ' . $row['endtime'] . '
                            </td>
                            <td> ';
                            if ($row['state_requestprint'] == '0')
                                $state = '<a  class="payment_link_text">Đã lưu</a>';
                            else if ($row['state_requestprint'] == '1')
                                $state = 'Đã hoàn thành';
                            else
                                $state = 'Đã gửi in';
                            echo $state;
                            echo '
                            </td>
                        </tr> 
                 ';
                        }
                    ?>
                </tbody>
            </table>
        </section>
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
    <!-- footer section starts -->
    <div class="footer-container">
        <section class="footer" style="background: url(../images/footer-bg.jpg);">
            <div class="box-container">
                <div class="box">
                    <h3>STUDENT SMART PRINTING SERVICE</h3>
                    <img src="../images/logo.png" alt="logo" />
                </div>

                <div class="box">
                    <h3>WEBSITE</h3>
                    <a href="https://hcmut.edu.vn/" class="hcmut">HCMUT</a>
                    <a href="https://mybk.hcmut.edu.vn/my/index.action" class="mybk">MyBK</a>
                    <a href="https://mybk.hcmut.edu.vn/bksi/public/vi/" class="bksi">BKSI</a>
                </div>

                <div class="box">
                    <h3>CONTACT</h3>
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


    <!-- swiper js link -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- custom js file link -->
    <script src="../ActivityLog/actscript.js"></script>
    <!--jquery cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

</body>

</html>
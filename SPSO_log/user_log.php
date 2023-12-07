<?php
@include '../ConnectDB.php';

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
        <section>
            <input type="text" id="searchInput" onkeyup="search(1,'spso_log_table')"
                placeholder="Search for student name..">
            <table border="1" id="spso_log_table" style="overflow-y:scroll;height:300px;display:block;">
                <colgroup>
                    <col span="5">
                </colgroup>

                <thead>
                    <tr>
                        <th>Họ sinh viên</th>
                        <th>Tên sinh viên</th>
                        <th>Email</th>
                        <th>Ngày sinh</th>
                        <th>Số dư page</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "select * from users ORDER BY Fname;");
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    foreach($data as $row) {
                        echo '
                        <tr>
                            <td>
                            
                                '.$row["Lname"].' 
                            </td>
                            <td>
                            <a class="nameClick" href="../SPSO_log/spso_log.php?id='.$row["ID"].'&nameStudent='.$row["Lname"] . " " . $row['Fname'].' ">
                                '.$row['Fname'].'</a>
                            </td>
                            <td>
                                '.$row['Email'].'
                            </td>
                            <td>
                                '.$row['Date_Of_Birth'].'
                            </td>
                            <td>
                                '.$row['Balance'].'
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
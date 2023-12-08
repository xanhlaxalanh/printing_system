<?php
@include '../ConnectDB.php';
session_start();

$ID = $_SESSION['id'];
$Username = $_SESSION['username'];
$Role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>

    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" type="text/css" href="UserHome.css">

</head>

<body>
    <!-- header section starts -->

    <section class="header">
        <div class="left-side">
            <div class="logo">
                <a href="../UserHome/BeforeLoad.php">
                    <img src="../images/logo.png" alt="logo" />
                    <p>ĐẠI HỌC QUỐC GIA TP.HCM<br>TRƯỜNG ĐẠI HỌC BÁCH KHOA</p>
                </a>
            </div>

            <div class="menu-bar">
                <div class="first-option"><a href="../UserHome/BeforeLoad.php">trang chủ</a></div>
                <div class="second-option"><a id="tagA" href="">dịch vụ của tôi</a></div>
                    <script>
                        var getEleA = document.getElementById('tagA');
                        var role = "<?php echo $Role; ?>";
                        if (role == "Student") {
                            getEleA.href = "../Login_with_Gmail/homeAfterLogin_User.php";
                        }
                        else{
                            getEleA.href = "../Login_with_Gmail/homeAfterLogin_Manage.php";
                        }

                    </script>
                
            </div>

            <div class="right-side">
                <div class="username">
                    <a><?php echo $Username; ?></a>
                </div>
                <div class="seperator">|</div>
                <div>
                    <a href="../Login_with_Gmail/home.php" class="login">Đăng xuất</a>
                </div>
            </div>
    </section>
    <!-- header section ends -->

    <div class="main">
        <img src="../images/slbktv.jpg" alt="backkhoa" />
        <div class="home-title">
            <p class="school-name">TRƯỜNG ĐẠI HỌC BÁCH KHOA - ĐHQG TP.HCM</p>
            <p class="service-name">STUDENT SMART PRINTING SERVICE</p>
        </div>
    </div>









    <!-- footer section starts -->
    <div class="footer-container">
        <section class="footer">
            <div class="box-container">
                <div class="box">
                    <h3>student smart printing service</h3>
                    <img src="../images/logo.png" alt="logo" />
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










    <!-- swiper js link -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- custom js file link -->
</body>

</html>
<?php
session_start();

@include '../ConnectDB.php';
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

</head>

<body>
    <!-- header section starts -->

    <section class="header">
        <div class="left-side">
            <div class="logo">
                <a href="#">
                    <img src="images/logo.png" alt="logo" />
                    <p>ĐẠI HỌC QUỐC GIA TP.HCM<br>TRƯỜNG ĐẠI HỌC BÁCH KHOA</p>
                </a>
            </div>

            <div class="menu-bar">
                <div class="first-option"><a href="../UserHome/BeforeLoad.php">trang chủ</a></div>
                <div class="second-option"><a href="./homeAfterLogin_Manage.php">dịch vụ của tôi</a></div>
            </div>
        </div>

        <div class="right-side">
            <div class="username">
                <?php
                if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['name'])) {
                    echo '<a style="pointer-events:none;">' . htmlspecialchars($_SESSION['user_info']['name']) . '</a>';
                }
                ?>
            </div>

            <div class="seperator">|</div>

            <div>
                <a href="home.php" class="loglogout">Đăng xuất</a>
            </div>
        </div>
    </section>

    <!-- header section ends -->




    <!-- body section starts -->

    <div class="body">
        <h1 class="title">
            <?php
            if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['name'])) {
                echo '<span class="user-name">' . htmlspecialchars($_SESSION['user_info']['name']) . '</span>';
            }
            ?>
        </h1>

        <div class="service-list">
            <div>
                <h2>
                    Họ và tên:
                    <?php
                    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['name'])) {
                        echo '<span class="user-name">' . htmlspecialchars($_SESSION['user_info']['name']) . '</span>';
                    }
                    ?>
                </h2>
            </div>

            <div>
                <h2>
                    Email:
                    <?php
                    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['email'])) {
                        echo '<span class="user-name">' . htmlspecialchars($_SESSION['user_info']['email']) . '</span>';
                    }
                    ?>
                </h2>
            </div>

            <div>
                <h2>
                    Năm sinh:
                    <?php
                    $email = $_SESSION['user_info']['email'];
                    $get = mysqli_query($conn, "select Date_Of_Birth from users where email = '$email' ");
                    $getData = $get->fetch_all(MYSQLI_ASSOC);
                    $Date_Of_Birth = $getData[0]['Date_Of_Birth'];
                    echo '<span class="user-name">' . htmlspecialchars($Date_Of_Birth) . '</span>';
                    ?>
                </h2>
            </div>

            <div>
                <h2>
                    Giới tính:
                    <?php
                    $email = $_SESSION['user_info']['email'];
                    $get = mysqli_query($conn, "select Sex from users where email = '$email' ");
                    $getData = $get->fetch_all(MYSQLI_ASSOC);
                    $Sex = $getData[0]['Sex'];
                    if ($Sex == '0') {
                        $x = "Nữ";
                        echo '<span class="user-name">' . htmlspecialchars($x) . '</span>';
                    } else if ($Sex == '1') {
                        $x = "Nam";
                        echo '<span class="user-name">' . htmlspecialchars($x) . '</span';
                    }
                    ?>
                </h2>
            </div>

        </div>

    </div>

    <!-- body section ends -->




    <!-- footer section starts -->
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









    <!-- swiper js link -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- custom js file link -->
</body>

</html>

<script>
    localStorage.setItem("ID", <?php echo $_SESSION['student'] ?>);
    localStorage.setItem("Role", <?php echo $_SESSION['role'] ?>);
    localStorage.setItem("Username", <?php echo "\"" . $_SESSION["name"] . "\"" ?>);

</script>
<?php
@include '../ConnectDB.php';

session_start();

$ID = $_SESSION['student'];
if (isset($_POST['Change'])) {
    $c_Date_Of_Birth = $_POST['Date_Of_Birth'];
    $c_Sex = $_POST['Sex'];

    $date = DateTime::createFromFormat('Y-m-d', $c_Date_Of_Birth);
    if (!$date || $date->format('Y-m-d') !== $c_Date_Of_Birth) {
        echo ("Ngày sinh không hợp lệ");
        return;
    }

    $c_gender = $c_Sex == 'Nam' ? 1 : 0;

    $sql = "UPDATE users SET Date_Of_Birth = '$c_Date_Of_Birth', Sex = '$c_gender' WHERE ID = $ID";
    if (mysqli_query($conn, $sql)) {
        echo ("Cập nhật thành công");
    } else {
        echo ("Cập nhật không thành công");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin</title>

    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" type="text/css" href="../BuyPrintingPages/BuyPrintingPages.css">

</head>

<body>
    <!-- header section starts -->

    <section class="header">
        <div class="left-side">
            <div class="logo">
                <a href="../UserHome/BeforeLoad.php">
                    <img src="images/logo.png" alt="logo" />
                    <p>ĐẠI HỌC QUỐC GIA TP.HCM<br>TRƯỜNG ĐẠI HỌC BÁCH KHOA</p>
                </a>
            </div>

            <div class="menu-bar">
                <div class="first-option"><a href="../UserHome/BeforeLoad.php">trang chủ</a></div>
                <div class="second-option"><a href="homeAfterLogin_User.php">dịch vụ của tôi</a></div>
            </div>
        </div>

        <div class="right-side">
            <div class="username"><a href="infoUser.php">
                    <?php
                    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['name'])) {
                        echo htmlspecialchars($_SESSION['user_info']['name']);
                    }
                    ?>
                </a>
            </div>

            <div class="seperator">|</div>

            <div>
                <a href="home.php" class="logout">Đăng xuất</a>
            </div>
        </div>
    </section>

    <!-- header section ends -->




    <!-- body section starts -->

    <div class="body">
        <h1 class="title">Chỉnh sửa thông tin cá nhân</h1>

        <form method="POST" action="">

            <div class="form-group">
                <label for="Date_Of_Birth">Năm sinh: </label>
                <input type="date" name="Date_Of_Birth" class="form-control" placeholder="YYYY-MM-DD">
            </div>

            <br>

            <div class="form-group">
                <label for="Sex">Giới tính: </label>
                <select name="Sex" class="form-control">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <br>

            <button type="submit" class="submit-order" name="Change">Thay đổi thông tin</button>
        </form>

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
    localStorage.s etItem("Username", <?php echo "\"" . $_SESSION["name"] . "\"" ?>);

</script>
<?php
    session_start();

    require 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ sinh viên</title>

    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="style.css" >
    <link rel="stylesheet" type="text/css" href="modal.css" >

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
                <div class="first-option"><a href="">trang chủ</a></div>
                <div class="second-option"><a href="" >dịch vụ của tôi</a></div>
            </div>
        </div>
        
        <div class="right-side">
            <div class="first-option"><a href="infoManage.php">
                <?php
                    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['name'])) {
                        echo '<span class="user-name">' . htmlspecialchars($_SESSION['user_info']['name']) . '</span>';
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
        <h1 class="title">BÁO CÁO SỬ DỤNG HỆ THỐNG IN</h1>

        <div class="service-list">
            <h2>Thống kê số tờ giấy sinh viên dùng cho mỗi lần in trong khoảng thời gian được chia thành 3 hoặc 4 nhóm. Trung bình số tờ giấy sử dụng cho mỗi lần in. Số lượng giấy đã dùng của mỗi loại.</h2>
            
            <h2>
            <form method="POST" action="check.php">
                <div class="form-group">
                    <label for="sonhom">Số nhóm: </label>
                    <input name="sonhom" class="form-control"  placeholder="Chỉ 3 hoặc 4">
                </div>

                <div class="form-group">
                    <label for="batdau">Ngày bắt đầu: </label>
                    <input name="batdau" class="form-control"  placeholder="YYYY-MM-DD: 2023-12-01">
                </div>

                <div class="form-group">
                    <label for="ketthuc">Ngày kết thúc: </label>
                    <input name="ketthuc" class="form-control"  placeholder="YYYY-MM-DD: 2023-12-30">
                </div>

                <button type="submit" class="btn btn-primary" name="Save">Phân tích</button>
            </form>
            </h2>

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
                    <a href="#"> <div class="location-icon"></div>268 Ly Thuong Kiet Street Ward 14, District 10, Ho Chi Minh City, Vietnam </a>
                    <a href="#"> <div class="phone-icon"></div>(028) 38 651 670 - (028) 38 647 256 (Ext: 5258, 5234) </a>
                    <a href="mailto:elearning@hcmut.edu.vn" class="email"> <div class="email-icon"></div>elearning@hcmut.edu.vn </a>
                </div>
            </div>
        </section>
        <div class="copyright">
            <p>Copyright 2007-2022 BKEL - Phát triển dựa trên Moodle</p>
        </div>
    </div>
    <!-- footer section ends -->

    <!-- Modal -->
    <div id="analysisModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
        <h2>Kết quả phân tích</h2>
        </div>
        <div class="modal-body">
        <h3>Nhóm 1: </h3>
        <h3>Nhóm 2: </h3>
        <h3>Nhóm 3: </h3>

        <h3>Nhóm A0: </h3>
        <h3>Nhóm A1: </h3>
        <h3>Nhóm A2: </h3>
        <h3>Nhóm A3: </h3>
        <h3>Nhóm A4: </h3>

        <h3>Trung bình: </h3>

        <!-- The results can be dynamically injected into this div -->
        <div id="resultContent"></div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
    </div>







    <!-- swiper js link -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- custom js file link -->
    <script src="script.js"></script>
    <script src="modal.js"></script>
</body>
</html>
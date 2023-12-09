<?php
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
    <title>Mua Thêm Trang In</title>

    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" type="text/css" href="BuyPrintingPages.css">

    <!-- js file link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="BuyPrintingPages.js"></script>

</head>

<body>
    <!-- header section starts -->

    <section class="header">
        <div class="left-side">
            <div class="logo">
                <a href="../UserHome/BeforeLoad.php">
                    <img src="../images/logo.png" alt="logo" style="cursor:pointer;" />
                    <p>ĐẠI HỌC QUỐC GIA TP.HCM<br>TRƯỜNG ĐẠI HỌC BÁCH KHOA</p>
                </a>
            </div>

            <div class="menu-bar">
                <div class="first-option"><a href="../UserHome/BeforeLoad.php">trang chủ</a></div>
                <div class="second-option"><a href="../Login_with_Gmail/homeAfterLogin_User.php">dịch vụ của tôi</a>
                </div>
            </div>
        </div>

        <div class="right-side">
            <div class="username"><a href="../Login_with_Gmail/infoUser.php">
                    <?php echo $Username; ?>
                </a></div>
            <div class="seperator">|</div>
            <div>
                <a href="../Login_with_Gmail/home.php" class="login">Đăng xuất</a>
            </div>
        </div>
    </section>

    <!-- header section ends -->

    <!-- body section starts -->

    <div class="body">
        <h1 class="title">MUA THÊM TRANG IN</h1>

        <div class="balance-container">
            <p>Số trang in hiện tại:</p>
            <?php
            @include_once("../ConnectDB.php");

            // Get Balance
            $sql = "SELECT Balance 
                    FROM Users
                    WHERE ID =" . $ID;
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $balance = $row['Balance'];
            }

            echo "<p class='balance'>$balance trang (Khổ A4)</p>";
            ?>
        </div>

        <form method="POST" action="RegisterAnOrder.php" class="registration" onsubmit="return validateInputs()">
            <label for="quantity">Số lượng trang in mua thêm:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Số lượng trang (Khổ A4)" min="1">
            <button type="submit" id="submit-order" name='submit-order' class="submit-order">Đăng ký</button>
        </form>

        <?php
        // Get Paper_Price
        $sql = "SELECT Paper_Price 
            FROM Configuration
            ";
        $result = $conn->query($sql);

        $price = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $price = $row['Paper_Price'];
        }

        echo "<p class='info-about-price'>(Lệ phí: $price VNĐ/trang in khổ  A4)</p>";
        ?>

        <div class="registration-history">
            <p>Lịch sử đăng ký:</p>
            <table>
                <thead>
                    <tr>
                        <th>Mã phiếu</th>
                        <th>Ngày đăng ký</th>
                        <th>Số lượng</th>
                        <th>Lệ phí</th>
                        <th class="payment-status-th">Thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    @include_once("../ConnectDB.php");

                    $sql = "SELECT Order_ID, Order_Creation_Date, Quantity, Payment_Status, Owner_ID
                            FROM BPP_Order
                            WHERE Owner_ID = " . $ID . "
                            ORDER BY Order_Creation_Date DESC
                            ";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $payment_status = 'Đã thanh toán';
                            $status = 'paied';
                            if ($row["Payment_Status"] == 0) {
                                $payment_status = 'Thanh toán ngay';
                                $status = 'unpaid';
                            }

                            // Calculate Total_Price = Quantity * Paper_Price
                            $total_price = number_format($price * $row['Quantity']);

                            echo "<tr>
                                <td>" . $row["Order_ID"] . "</td>
                                <td>" . $row["Order_Creation_Date"] . "</td>
                                <td>" . $row["Quantity"] . "</td>
                                <td class='total-price'>" . $total_price . "</td>
                                <td class='payment-status $status'>
                                    <a href='UpdateBalance.php?Owner_ID=" . $row['Order_ID'] . "' class='pay-btn  payment-btn $status' onclick='return confirmPay()'>" . $payment_status . "</a>
                                    <span>/ </span>
                                    <a href='DeleteAnOrder.php?Order_ID=" . $row['Order_ID'] . "' class='delete-btn payment-btn' onclick='return confirmDelete()'>Xóa</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                            <td class='total-price'>...</td>
                            <td class='payment-status'>...</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- body section ends -->

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









</body>

</html>
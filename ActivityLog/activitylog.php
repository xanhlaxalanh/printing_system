<?php
@include '../ConnectDB.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log</title>

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

<body><!--onload="timer = setTimeout('auto_reload()',10000);">
    <!- header section starts -->

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



    <!--POP UP -->
    <!-- Send print request POP UP  -->
    <?php

    if (isset($_GET['send_id'])) {
        $send_id = $_GET['send_id'];
        $getInfoToSend = mysqli_query($conn, "SELECT * FROM `requested_page_numbers` join print_request on 
        requested_page_numbers.Request_ID = print_request.id join file on print_request.File_ID = file.id join users on file.User_ID = users.ID where Request_ID='$send_id';");
        $getdata = $getInfoToSend->fetch_all(MYSQLI_ASSOC);
        $Now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        //  <!--End get data task -->
        echo '<div class="popup" id="sendprint_popup">
            <img src="../images/message.jpg" width="50px" height="50px">
            <div class="popup_text">
                <h3 style="margin-top:5%; color:var(--main-color)">Gửi yêu cầu in</h3>
                <table>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-timer-fill"></i>Thời gian hiện tại:</th>
                        </td>
                        <td>
                          ' . $Now->format('Y-m-d H:i:s') . '
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-user-fill"></i>Tên người dùng:</th>
                        </td>
                        <td>
                           ' . $getdata[0]['Lname'] . $getdata[0]['Fname'] . '
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-file-fill"></i>Tập tin đã chọn:</th>
                        </td>
                        <td>
                           ' . $getdata[0]["Name"] . '
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-file-paper-2-fill"></i>Số mặt in:</th>
                        </td>
                        <td>
                            ' . $getdata[0]["One/Doubled_Sided"] . ' 
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-file-copy-fill"></i>Số bản copy:</th>
                        </td>
                        <td>
                            ' . $getdata[0]["Number_Of_Copies"] . '
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-file-list-3-fill"></i>Số trang trên một tờ giấy in:</th>
                        </td>
                        <td>
                            ' . $getdata[0]["Pages_Per_Sheet"] . '
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-file-paper-fill"></i>Khổ giấy:</th>
                        </td>
                        <td>
                            ' . $getdata[0]["Page_Size"] . '
                        </td>
                    </tr>
                        <td>
                        <th class="title_"><i class="ri-money-dollar-circle-fill"></i>Số page bị trừ vào ví:</th>
                        </td>
                        <td>
                            ' . $getdata[0]["Total_Sheet"] . '
                        </td>
                    <tr>
                        <td>
                        <th class="title_"><i class="ri-list-check"></i>Số trang muốn in:</th>
                        </td>
                        <td>';
        $display = "";
        for ($i = 0; $i < count($getdata); $i++) {
            if ($getdata[$i]['Start_Page'] != $getdata[$i]['End_Page']) {
                $display .= $getdata[$i]['Start_Page'] . "-" . $getdata[$i]['End_Page'];
            } else {
                $display .= $getdata[$i]['Start_Page'];
            }
            $display .= ",";
        }
        $display = substr($display, 0, -1);
        echo $display;
        echo '
                        </td>
                    </tr>
                </table>
        <div class="button-group">
            <button onclick="ClosePopup(\'sendprint_popup\', \'activitylog.php\')" class="button" type="button">Thoát</button>
            <a href="#" type="button" class="button">Chỉnh sửa</a>
            <a class="button" href="send_activitylog.php?send_confirm_id=' . $send_id . '" type="button">Xác nhận</a>
        </div>
        </div>
    </div>';
    } ?>
    <!-- END Send print request POP UP  -->
    <!-- ---------------------------------------------------------------------------------------------------------- -->
    <!-- Confirm delete request POP UP -->
    <?php
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        echo ' <div class="popup" id="DELETE_popup">
            <img src="../images/message.jpg" width="50px" height="50px">
            <div class="popup_text">
                <h2 style="margin-top:5%; color:var(--main-color)">Message:</h2>
                <h4 style="color:var(--text-color)">Bạn có chắc chắn muốn xóa không?</h4>
            </div>
            <div class="button-group">
                <button onclick="ClosePopup(\'DELETE_popup\',\'activitylog.php\')" class="button" type="button">Thoát</button>
                <a class="button" href="delete_activitylog.php?id=' . $delete_id . '">Xóa</a>
            </div>
        </div>';
    } ?>
    <!-- END Confirm delete request POP UP  -->
    <!-- ---------------------------------------------------------------------------------------------------------- -->
    <!-- Delete multiple request POP UP -->
    <?php
    if (isset($_GET['DELETE_range'])) {
        echo ' <div class="popup" id="DELETE_range">
        <img src="/printing_service/image/message.jpg" width="50px" height="50px">
        <div class="popup_text">
            <div class="delete_range">
                <div class="delete_range1">
                    <p>Xóa theo khoảng thời gian:</p>
                </div>
                <div class="delete_range1">
                    <select id="delete_range_select">
                        <option value="delete_start=true">Chọn khoảng thời gian</option>
                        <option value="delete_hour=true">1 giờ trước</option>
                        <option value="delete_day=true">1 ngày trước</option>
                        <option value="delete_week=true">1 tuần trước</option>
                        <option value="delete_month=true">1 tháng trước</option>
                        <option value="delete_year=true">1 năm trước</option>
                    </select>
                </div>

            </div>
            <div class="button-group">
                <a onclick="setHref()" id="confirm_delete_button" href="" class="button" type="button">Xác
                    nhận
                    xóa</a>
                <button onclick="ClosePopup(\'DELETE_range\')" class="button" type="button">Thoát</button>
            </div>
            <script>
            function setHref() {
                _("confirm_delete_button").href =`delete_activitylog.php?confirm_delete_range=true&${_("delete_range_select").value}`;
            }
            </script>
        </div>
    </div>';
    }
    ;
    if (isset($_GET['DELETE_particularDay'])) {
        echo '<div class="popup" id="DELETE_particularDay">
        <img src="../images/message.jpg" width="50px" height="50px">
        <div class="popup_text">
            <div class="delete_range">
                <div class="delete_range1">
                    <p>Chọn một ngày cụ thể:</p>
                </div>
                <div class="delete_range1"><i class="ri-calendar-2-fill"
                        onclick="_(\'calendar\').classList.toggle(\'display_calendar\')"></i></div>

            </div>
            <div class="button-group">
                <button onclick="deleteActiveClass()" class="button" type="button">Xác nhận xóa</button>
                <button onclick="ClosePopup(\'DELETE_particularDay\',\'activitylog.php\')" class="button" type="button">Thoát</button>
            </div>
            <p style="font-size:10px">Note: Chỉ có thể xóa các yêu cầu in ở trạng thái "Đã hoàn thành" </p>
            <div class="wrapper" id="calendar">
                <div class="header-calendar">
                    <p class="current-date"></p>
                    <div class="icons">
                        <i id="prev" class="ri-arrow-left-line"></i>
                        <i id="next" class="ri-arrow-right-line"></i>
                    </div>
                </div>
                <div class="calendar">
                    <ul class="weeks">
                        <li>Sun</li>
                        <li>Mon</li>
                        <li>Tue</li>
                        <li>Wed</li>
                        <li>Thu</li>
                        <li>Fri</li>
                        <li>Sat</li>
                    </ul>
                    <ul class="days"></ul>
                </div>
            </div>
        </div>
    </div>';
    } ?>
    <script>
        function deleteActiveClass() {
            var listActiveDays = document.querySelectorAll('.active');
            for (var i = 0; i < listActiveDays.length; ++i) {
                let date = listActiveDays[i].textContent;
                const splitDate = date.split(" ");
                $.post(" delSelectDay.php", { day: splitDate[0], month: splitDate[1], year: splitDate[2] });
            }
            ClosePopup('DELETE_particularDay');
            auto_reload('../ActivityLog/activitylog.php');
        }
    </script>
    <!-- END Delete multiple request POP UP  -->
    <!-- END POP UP -->

    <?php
    $result = mysqli_query($conn, "CALL displayLog('1');");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="body-side">
        <h1>NHẬT KÝ SỬ DỤNG DỊCH VỤ IN</h1>
        <section>
            <input type="text" id="searchInput" onkeyup="search(2,'user_table')" placeholder="Search for file name..">
            <table border="1" id="user_table">
                <colgroup>
                    <col span="3" style="width: 280px">
                    <col span="6" style="width: 120px;">
                    <col span="3" style="width: 150px">
                </colgroup>
                <thead>
                    <tr>
                        <th>Thời gian bắt đầu in</th>
                        <th>Thời gian kết thúc in</th>
                        <th>Nội dung đăng ký in</th>
                        <th>Tổng số page</th>
                        <th>Số mặt</th>
                        <th>Số bản copy</th>
                        <th>Số trang trên giấy in</th>
                        <th>Khổ giấy</th>
                        <th>Số page bị trừ trong ví</th>
                        <th>Mã máy in</th>
                        <th>Trạng thái</th>
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php /*if(empty($data)) {
echo "<p style='border:None; color:var(--text-color); font-weight:500; font-size:17px;'>Nhật ký của bạn hiện đang trống!</p>";
} else*/
                    foreach ($data as $row): ?>
                        <tr>
                            <td>
                                <?= $row['starttime'] ?>
                            </td>
                            <td>
                                <?= $row['endtime'] ?>
                            </td>
                            <td>
                                <?= $row['filename'] ?>
                            </td>
                            <td>
                                <?= $row['totalpage'] ?>
                            </td>
                            <td>
                                <?= $row["numbersides"] ?>
                            </td>
                            <td>
                                <?= $row["numbercopies"] ?>
                            </td>
                            <td>
                                <?= $row["paper_per_sheet"] ?>
                            </td>
                            <td>
                                <?= $row["papersize"] ?>
                            </td>
                            <td>
                                <?= $row["total_sheet"] ?>
                            </td>

                            <td>
                                <?= $row['printer_model'] ?>
                            </td>
                            <td>
                                <?php
                                if ($row['state_requestprint'] == '0')
                                    $state = '<a  class="payment_link_text">Đã lưu</a>';
                                else if ($row['state_requestprint'] == '2')
                                    $state = 'Đã hoàn thành';
                                else if ($row['state_requestprint'] == '1')
                                    $state = 'Đã gửi in';
                                else
                                    $state = 'Lỗi';
                                ?>
                                <?= $state ?>
                            </td>
                            <td>
                                <?php
                                if ($row['state_requestprint'] == '0' || $row['state_requestprint'] == '2') {
                                    echo '
                                    <div class="dropdown" style="float:right;">
                                    <i style="font-size:25px" class="ri-arrow-down-s-fill dropbtn"></i>
                                    <div class="dropdown-content">
                                        <a href="activitylog.php?send_id=' . $row['requestid'] . '">Send</a>
                                        <a href="activitylog.php?delete_id=' . $row['requestid'] . '">Delete</a>
                                    </div>
                                </div>';
                                } else {
                                    echo '<div style="pointer-events: none; " class="dropdown" style="float:right;">
                                    <i style="font-size:25px;margin-left: 12rem;" class="ri-arrow-down-s-fill dropbtn"></i>
                                    <div class="dropdown-content">
                                        <a href="activitylog.php?send_id=' . $row['requestid'] . '">Send</a>
                                        <a href="activitylog.php?delete_id=' . $row['requestid'] . '">Delete</a>
                                    </div>
                                </div>';
                                }
                                ?>

                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="dropdown" style="float:right; margin: 1%; padding:0.3%; display:none;">
                <button type="button" class="button" id="delete_multi"><i class="ri-arrow-down-s-fill dropbtn"></i>Xóa
                    nhiều file</button>
                <div class="dropdown-content">
                    <a type="button" href="activitylog.php?DELETE_range=true">Xóa theo khoảng thời gian</a>
                    <a type="button" href="activitylog.php?DELETE_particularDay=true">Xóa theo ngày cụ thể</a>
                </div>
            </div>
        </section>
    </div>


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
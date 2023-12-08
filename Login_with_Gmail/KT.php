<?php
ob_start();
session_start();

require_once 'vendor/autoload.php';
@include 'database.php';
require 'function.php';

function insertUser($name_first, $Lname, $Email, $Role, $Sex, $Date_of_Birth, $Balance)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "ssps";

    $conn = new mysqli($servername, $username, $password, $database);
    if (!$conn) {
        echo ("Can't connect");
    } else {
        mysqli_query($conn, "INSERT INTO users(Fname,Lname,Email,Role,Sex,Date_Of_Birth,Balance) VALUES('$name_first','$Lname', '$Email', '$Role', '$Sex', '$Date_of_Birth', '$Balance')");
    }
}

$client = clientGoogle();
$service = new Google_Service_Oauth2($client);

if (isset($_GET['code'])) {
    $miss = $client->authenticate($_GET['code']); 
    if (isset($miss['error'])) { //Nếu nó lỗi 
        header('Location: home.php');
        exit();
    } else { //Nếu không xảy ra lỗi
        $temp1 = $client->getAccessToken();
        $user = $service->userinfo->get();

        $_SESSION['user_info'] = [
            'email' => $user->email,
            'name' => htmlspecialchars($user->name),
            'id' => $user->id,
            'picture' => $user->picture,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'phone' => $user->phone,
        ];

        $email = mysqli_real_escape_string($conn, $user->email);
        $check = "SELECT id FROM users WHERE Email = '$email'";
        $result = mysqli_query($conn, $check);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        //var_dump($data);
        //echo $email;
        if (!empty($data)) { //Nếu có email xuất hiện
            $email_gg = $user->email;
            $domain = "@hcmut.edu.vn";

            $position = strpos($email_gg, $domain);

            if ($position !== false) { // && $position == strlen($email_gg) - strlen($domain)){
                $stmt = $conn->prepare("select role from users where email = ?");
                $stmt->bind_param('s', $user->email);
                $stmt->execute();
                $result = $stmt->get_result();
                $value = $result->fetch_object();
                $Role = $value->role;
                
                
                $get = mysqli_query($conn, "select id,role from users where email = '$user->email' ");
                $getData = $get->fetch_all(MYSQLI_ASSOC);
                $ID = $getData[0]['id'];

                $_SESSION['student'] = $ID;
                $_SESSION['name'] = $user->name;
                $_SESSION['role'] = $Role;
                if ($Role == "Student") {
                    header('Location: ../Login_with_Gmail/homeAfterLogin_User.php');
                } else if ($Role == "SPSO") {
                    header('Location: ../Login_with_Gmail/homeAfterLogin_Manage.php');
                }
            } else {
                $_SESSION['Fail_Login'] = True;
                header('Location: http://localhost/printing_system/Login_with_Gmail/home.php');
                exit();
            }
        } else {

            $email_gg = $user->email;
            $domain = "@hcmut.edu.vn";

            $position = strpos($email_gg, $domain);

            if ($position !== false) { // && $position == strlen($email_gg) - strlen($domain)){
                $separate = explode(' ', $_SESSION['user_info']['name']);
                $Fname = $separate[0];
                $Lname = substr($_SESSION['user_info']['name'], strlen($Fname) + 1);
                insertUser($Fname, $Lname, $_SESSION['user_info']['email'], 'Student', $_SESSION['user_info']['gender'], $_SESSION['user_info']['birthday'], '0');
                $get = mysqli_query($conn, "select id,role from users where email = '$user->email' ");
                $getData = $get->fetch_all(MYSQLI_ASSOC);
                $ID = $getData[0]['id'];

                $_SESSION['student'] = $ID;
                $_SESSION['name'] = $user->name;

                header('Location: homeAfterLogin_User.php');
            } else {

                $_SESSION['Fail_Login'] = True;
                header('Location: home.php');
                exit();
            }
        }
    }
}
?>

<script>
    localStorage.setItem("ID", <?php echo $_SESSION['student'] ?>);
    localStorage.setItem("Role", <?php echo $_SESSION['role'] ?>);
    localStorage.setItem("Username",<?php echo "\"". $_SESSION["name"] ."\"" ?>);
</script>
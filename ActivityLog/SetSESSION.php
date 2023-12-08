<?php
    session_start();

    $_SESSION['id'] = $_POST['id'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['role'] = $_POST['role'];
?>
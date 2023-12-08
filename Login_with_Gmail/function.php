<?php 

    require_once 'vendor/autoload.php';
    @include 'database.php';

    function clientGoogle(){
        $client_id = "1053091317356-34mbtug0u96cvk5bmi2ch6v7dqbnqlf2.apps.googleusercontent.com"; 
        $client_secret = "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"; 
        $redirect_uri = "http://localhost/printing_system/Login_with_Gmail/KT.php"; 
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");
        return $client;
    }
?>
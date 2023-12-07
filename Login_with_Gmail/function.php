<?php 

    require_once 'vendor/autoload.php';
    @include 'database.php';

    function clientGoogle(){
        $client_id = "265101506403-ecunt1amgham7ssvao450153020uq5af.apps.googleusercontent.com"; 
        $client_secret = "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"; 
        $redirect_uri = "http://localhost/CODE_BTL/printing_service/Login_with_Gmail/KT.php"; 
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");
        return $client;
    }
?>
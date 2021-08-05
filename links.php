<?php
//Session starten, damit man darauf zugreifen kann
session_start();

//überprüfen, ob ein Login erfolgt ist
//Quelle: Schulprojekt "Dupload" => cloud.derbeton.ch
if (!isset($_SESSION["access_token"])) {
    //Umleiten auf Login-Seite, da es ein unbefugter Zugriff ist
    header("Location: login.php");
}

//Prüfen, ob Seriennummer in der URL steht
if (isset($_GET['serialNr'])) {

    //Seriennummer in die Session-Variable schreiben
    $_SESSION['serialNr'] = $_GET['serialNr'];
    //Umleiten auf die Seite der Seriennummern
    header("Location: serialNr.php");
}

//Prüfen, ob Anlagenummer in der URL steht
if (isset($_GET['assetNr'])) {

    //Anlagenummer in die Session-Variable schreiben
    $_SESSION['assetNr'] = $_GET['assetNr'];
    //Umleiten auf die Seite der Anlagenummern
    header("Location: assetNr.php");
}

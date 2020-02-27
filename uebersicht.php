<?php
//Session starten, damit man darauf zugreifen kann
session_start();

//überprüfen, ob ein Login erfolgt ist
if(!isset($_SESSION["access_token"])){
    //Umleiten auf Login-Seite, da es ein unbefugter Zugriff ist
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Titel</title>
    <!-- Einbinden von Schriftarten und Icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS einbinden-->
    <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <!-- Eigenes CSS einbinden -->
    <link rel="stylesheet" href="assets/css/stylesheet.css" />
    <!--    jQuery-Bibliothek einbinden
            Quelle: https://www.w3schools.com/jquery/jquery_get_started.asp -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  </head>
  <body>
      
      <!--  Logout-Button   -->
      <button type="button" class="btn btn-primary logout">Logout</button>
      
      <!--   jQuery-Code   -->
      <script>
            $(document).ready(function(){
                //Wird ausgeführt, sobald das Dokument vollständig geladen wurde
                
                //Logout-Button aktivieren
                $(".logout").on("click", function(){
                    //Beim Klick auf den Button auf logout.php weiterleiten
                    window.location.href = "logout.php";
                })
            })
      </script>
  </body>
</html>
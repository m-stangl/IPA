<?php
//Session starten, damit man darauf zugreifen kann
session_start();

//überprüfen, ob ein Login erfolgt ist
//Quelle: Schulprojekt "Dupload" => cloud.derbeton.ch
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
      
    <!-- Einbinden des originalen Bootstrap CSS für Stretched-Links auf der Übersicht  Quelle: https://getbootstrap.com/-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
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
  <body class="backgroundWatch">
      <div class="transparentLayer">
          <!--   Halb-transparente Schicht über Hintergrund
                 Quelle: https://stackoverflow.com/questions/9182978/semi-transparent-color-layer-over-background-image   -->
      </div>
      
      <!--   Beginn der Übersicht   -->
      <div class="container centerMid" style="">
        <div class="row align-items-center">
            <div class="col-md-4">
              <div class="card">
                  <div class="card-header card-header-text card-header-primary">
                    <div class="card-text">
                      <h4 class="card-title">Here is the Text</h4>
                      <a href="serialNr.php" class="stretched-link"></a>
                    </div>
                  </div>
                  <div class="card-body">
                      The place is close to Barceloneta Beach and bus stop just 2 min by walk and near to "Naviglio" where you can enjoy the main night life in Barcelona...
                      <a href="serialNr.php" class="stretched-link"></a>
                  </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                  <div class="card-header card-header-text card-header-primary">
                    <div class="card-text">
                      <h4 class="card-title">Here is the Text</h4>
                      <a href="assetNr.php" class="stretched-link"></a>
                    </div>
                  </div>
                  <div class="card-body">
                      The place is close to Barceloneta Beach and bus stop just 2 min by walk and near to "Naviglio" where you can enjoy the main night life in Barcelona...
                      <a href="assetNr.php" class="stretched-link"></a>
                  </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                  <div class="card-header card-header-text card-header-primary">
                    <div class="card-text">
                      <h4 class="card-title">Here is the Text</h4>
                      <a href="report.php" class="stretched-link"></a>
                    </div>
                  </div>
                  <div class="card-body">
                      The place is close to Barceloneta Beach and bus stop just 2 min by walk and near to "Naviglio" where you can enjoy the main night life in Barcelona...
                      <a href="report.php" class="stretched-link"></a>
                  </div>
              </div>
            </div>
        </div>
      </div>
      <!--   Ende der Übersicht   -->
      
      <!--  Logout-Button   -->
      <button type="button" class="btn btn-primary logout">Logout</button>
      
      <!--   jQuery-Code   -->
      <script>
            $(document).ready(function(){
                //Wird ausgeführt, sobald das Dokument vollständig geladen wurde
                
                //Logout-Button aktivieren
                //Quelle: Schulprojekt "Tesla"
                $(".logout").on("click", function(){
                    //Beim Klick auf den Button auf logout.php weiterleiten
                    window.location.href = "logout.php";
                })
                
            })
      </script>
  </body>
</html>
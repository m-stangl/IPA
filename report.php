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

<!doctype html>
<html lang="en">

<head>
  <title>Hello, world!</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  
  
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
      <div class="wrapper ">
        <div class="sidebar backgroundWatch" data-color="purple" data-background-color="white">
          <div class="transparentLayer">
              <!--   Halb-transparente Schicht über Hintergrund
                     Quelle: https://stackoverflow.com/questions/9182978/semi-transparent-color-layer-over-background-image   -->
          </div>
          <div class="logo">
              <div class="logo-wrapper">
                <img src="assets/img/iwc_logo.png" alt="logo" id="logo">
              </div>
              <br>
            <a href="uebersicht.php" class="simple-text logo-mini" id="weiss">
              Übersicht
            </a>
          </div>
          <div class="sidebar-wrapper">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" href="serialNr.php">
                  <i class="material-icons">devices</i>
                  <p class="whiteFont">Seriennummern</p>
                </a>
              </li>
              <!-- your sidebar here -->
              <li class="nav-item">
                <a class="nav-link" href="assetNr.php">
                  <i class="material-icons whiteFont">description</i>
                  <p class="whiteFont">Anlagenummern</p>
                </a>
              </li>
                <li class="nav-item active">
                <a class="nav-link" href="report.php">
                  <i class="material-icons">report</i>
                  <p class="whiteFont">Report</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="main-panel">
          <!-- Navbar -->
          <!-- Muss in den Hintergrund für Logout-Button, Quelle: https://stackoverflow.com/questions/15782078/bring-element-to-front-using-css -->
          <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top" style="z-index:-1">
            <div class="container-fluid">
              <div class="navbar-wrapper">
                <a class="navbar-brand" href="javascript:;">Report Anlagenummern</a>
              </div>
            </div>
          </nav>
          <!-- End Navbar -->
          <div class="content">
            <div class="container-fluid">
              <!-- your content here -->
                <div class="row" id="report">
                  
                </div>
              
            </div>
          </div>

        </div>
      </div>
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
                
                //Suchen-Button aktivieren
                $("#aktualisieren").on("click", function(){
                    //Funktion zur AJAX-Abfrage aufrufen
                    getReport();
                })
                
                getReport();
                
                //Funktion holt Report
                function getReport(){
                                        
                    //AJAX-Request an apiHandler.php
                    //Quelle: Schulprojekt "Waluegemer" => https://waluegemer.derbeton.ch/
                    $.ajax({
                        type: 'post',
                        url: 'apiHandler.php',
                        data: {
                            //Aufgabe für apiHandler schicken
                            task: 'getReport',
					},
					success: function (response) {
					 //War die Übertragung erfolgreich, wird folgender Code ausgeführt
                        
                     //Antwort anzeigen
					 $('#report').html(response);
                     
					        
					}
				});
                }
            })
      </script>
    </body>
</html>
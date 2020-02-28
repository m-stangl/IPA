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
              <li class="nav-item active">
                <a class="nav-link" href="#0">
                  <i class="material-icons">dashboard</i>
                  <p class="whiteFont">Seriennummern</p>
                </a>
              </li>
              <!-- your sidebar here -->
              <li class="nav-item">
                <a class="nav-link" href="#0">
                  <i class="material-icons whiteFont">dashboard</i>
                  <p class="whiteFont">Anlagenummern</p>
                </a>
              </li>
                <li class="nav-item">
                <a class="nav-link" href="#0">
                  <i class="material-icons">dashboard</i>
                  <p class="whiteFont">Report</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="main-panel">
          <!-- Navbar -->
          <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
              <div class="navbar-wrapper">
                <a class="navbar-brand" href="javascript:;">Seriennummern</a>
              </div>
            </div>
          </nav>
          <!-- End Navbar -->
          <div class="content">
            <div class="container-fluid">
              <!-- your content here -->
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                      <div class="card">
                          <div class="card-header card-header-text card-header-primary">
                            <div class="card-text">
                              <h4 class="card-title">Nach einer Seriennummer suchen</h4>
                            </div>
                          </div>
                          <div class="card-body">
                              <br>
                                  <div class="row">
                                      <div class="col-md-1"></div>
                                      <div class="col-md-5">
                                    <label for="inputSerial">Seriennummer</label>
                                    <input type="text" class="form-control" id="inputSerial" placeholder="" value="ZURFAZE">
                                          </div>
                                      <div class="col-md-3"></div>
                                      <div class="col-md-3">
                                        <button type="button" class="btn btn-primary" id="suchen">Suchen</button>
                                      </div>
                                  </div>
                              <br>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-2"></div>
                </div>
                <div class="row" id="details"></div>
              
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
                $("#suchen").on("click", function(){
                    //Funktion zur AJAX-Abfrage aufrufen
                    getDetails();
                })
                
                //Funktion holt Details zur Seriennummer
                function getDetails(){
                    //Wert aus Inputfeld lesen
                    //Quelle: https://api.jquery.com/val/
                    var serialNr = $("#inputSerial").val();
                    
                    //AJAX-Request an apiHandler.php
                    //Quelle: Schulprojekt "Waluegemer" => https://waluegemer.derbeton.ch/
                    $.ajax({
                        type: 'post',
                        url: 'apiHandler.php',
                        data: {
                            //Seriennummer und Aufgabe für apiHandler mitschicken
                            serialNr: serialNr,
                            task: 'getDetails',
					},
					success: function (response) {
					 //War die Übertragung erfolgreich, wird folgender Code ausgeführt
                        
                     //Antwort anzeigen
					 $('#details').html(response);
                     
                     //Speichern-Button aktivieren
                     $('#speichern').on('click', function(){
                         //Speichern einleiten
                         postDetails();
                     })
                     
                     //Überprüfe, wann sich der Status ändert
                     $('#inputStatus').on("change", function(){
                         //Status hat sich geändert, Lagerorte müssen neu ausgelesen werden
                         
                         //Lese Status-Name, nicht ID!, aus dem Formular
                         //Quelle: https://stackoverflow.com/questions/6454016/get-text-of-the-selected-option-with-jquery/6454073
                         var status = $('#inputStatus option:selected').html();
                         
                        //AJAX-Request an apiHandler.php
                        //Quelle: Schulprojekt "Waluegemer" => https://waluegemer.derbeton.ch/
                        $.ajax({
                            type: 'post',
                            url: 'apiHandler.php',
                            data: {
                                //Details und Aufgabe für apiHandler mitschicken
                                status: status,
                                task: 'updateStock',
                        },
                        success: function (response) {
                         //War die Übertragung erfolgreich, wird folgender Code ausgeführt

                        //Select-Option Elemente ersetzen
                        $("#inputLagerort").html(response);

                        }
                        });
                         
                     })
					        
					}
				});
                }

                //Funktion speichert Details der Seriennummer
                function postDetails(){
                    
                    //Details auslesen
                    //Quelle: https://johannesdienst.net/jquery-data-attribute-auslesen/
                    var serialId = $('#serialId').data('serial');
                    var statusId = $('#inputStatus').val();
                    var lagerortId = $('#inputLagerort').val();
                    
                    //RFID-Tag auslesen, ID und Name
                    var tagId = $('#inputTag').attr('data-tag');
                    var tagName = $('#inputTag').val();
                    
                    //AJAX-Request an apiHandler.php
                    //Quelle: Schulprojekt "Waluegemer" => https://waluegemer.derbeton.ch/
                    $.ajax({
                        type: 'post',
                        url: 'apiHandler.php',
                        data: {
                            //Details und Aufgabe für apiHandler mitschicken
                            serialId: serialId,
                            statusId: statusId,
                            lagerortId: lagerortId,
                            tagId: tagId,
                            tagName: tagName,
                            task: 'postDetails',
					},
					success: function (response) {
					 //War die Übertragung erfolgreich, wird folgender Code ausgeführt
                        
                    //Kurze Meldung geben, dass es geklappt hat
                    alert(response);
                        
					}
				    });
                }

            })
      </script>
    </body>
</html>
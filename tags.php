<?php
//Session starten, damit man darauf zugreifen kann
session_start();

//überprüfen, ob ein Login erfolgt ist
//Quelle: Schulprojekt "Dupload" => cloud.derbeton.ch
if (!isset($_SESSION["access_token"])) {
  //Umleiten auf Login-Seite, da es ein unbefugter Zugriff ist
  header("Location: login.php");
}
?>
<!-- Material Template -->
<!doctype html>
<html lang="en">

<head>
  <title>IWC Inventory Admin Panel</title>
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
          <?php require_once 'sidebar.html'; ?>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <!-- Muss in den Hintergrund für Logout-Button, Quelle: https://stackoverflow.com/questions/15782078/bring-element-to-front-using-css -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top" style="z-index:-1">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">RFID-Tags</a>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- my content here -->
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-text card-header-primary">
                  <div class="card-text">
                    <h4 class="card-title">Nach einem Tag suchen</h4>
                  </div>
                </div>
                <div class="card-body">
                  <br>
                  <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                      <label for="inputTag">Tag-Nummer</label>
                      <input type="text" class="form-control" id="inputTag" placeholder="" <?php
                                                                                            //Wert ins Suchfeld geben, falls man über Link auf diese Seite kommt
                                                                                            if (isset($_SESSION['tagNr'])) {
                                                                                              echo "value='" . $_SESSION['tagNr'] . "'";
                                                                                            }
                                                                                            ?>>
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
    $(document).ready(function() {
      //Wird ausgeführt, sobald das Dokument vollständig geladen wurde

      //Aktive Seite in Sidebar markieren
      var url = window.location.pathname;
      var filename = url.substring(url.lastIndexOf('/') + 1, url.lastIndexOf('.'));
      var sidebarID = "#" + filename;
      $(sidebarID).addClass("active");

      //Logout-Button aktivieren
      //Quelle: Schulprojekt "Tesla"
      $(".logout").on("click", function() {
        //Beim Klick auf den Button auf logout.php weiterleiten
        window.location.href = "logout.php";
      })

      //Suchen-Button aktivieren
      $("#suchen").on("click", function() {
        //Funktion zur AJAX-Abfrage aufrufen mit false als Argument
        getTags(false);
      })

      //Suche mit Enter starten
      $("#inputTag").on("keyup", function(e) {
        //Prüfen, ob Enter gedrückt wurde
        if (e.which == 13) {
          getTags(false);
        }
      })

      <?php
      //Wenn man auf einen Link auf diese Seite kommt, steht Tagnummer in der Session-Variable
      //Details müssen beim Aufrufen sofort abgefragt und angezeigt werden

      if (isset($_SESSION['tagNr'])) {
        //Funktion ausführen mit true als Argument
        echo "getTags(true)";
      }
      ?>

      //Funktion holt Details zum Tag/allen Tags
      function getTags(getOrNot) {

        //Prüfen, ob der Wert aus der Session-Variable oder dem Suchfeld genommen wird
        if (getOrNot) {
          //Wert aus der Session-Variable
          <?php
          if (isset($_SESSION['tagNr'])) {
            //Variable in JS-Code übergeben
            echo "var tagNr = '" . $_SESSION['tagNr'] . "';";
          }

          //Session-Variable löschen, da sie sonst manuelle Suchen behindert
          //Quelle: https://www.geeksforgeeks.org/php-unset-session-variable/
          unset($_SESSION['tagNr']);
          ?>
        } else {
          //Wert aus Inputfeld lesen
          //Quelle: https://api.jquery.com/val/
          var tagNr = $("#inputTag").val().trim();
        }

        //AJAX-Request an apiHandler.php
        //Quelle: Schulprojekt "Waluegemer" => https://waluegemer.derbeton.ch/
        $.ajax({
          type: 'post',
          url: 'apiHandler.php',
          data: {
            //Tagnummer und Aufgabe für apiHandler mitschicken
            tagNr: tagNr,
            task: 'getTags',
          },
          success: function(response) {
            //War die Übertragung erfolgreich, wird folgender Code ausgeführt

            console.log(response);
            //Prüfen, ob Suche erfolgreich war
            if (response.charAt(0) !== "<") {
              alert(response);
            } else {
              //Antwort anzeigen
              $('#details').html(response);
              //console.log(response);

              //Speichern-Button aktivieren
              $('#speichern').on('click', function() {
                //Speichern einleiten
                postDetails();
              })

              //Löschen-Button aktivieren
              $('#loeschen').on('click', function() {
                if (confirm('Das Gerät wird gelöscht und kann nicht wiederhergestellt werden. Fortfahren?')) {
                  //Löschen einleiten
                  deleteDevice();
                }

              })

              //Überprüfe, wann sich der Status ändert
              $('#inputStatus').on("change", function() {
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
                  success: function(response) {
                    //War die Übertragung erfolgreich, wird folgender Code ausgeführt

                    //Select-Option Elemente ersetzen
                    $("#inputLagerort").html(response);

                  }
                });
              })
            }


          }
        });
      }

      //Funktion speichert Details der Seriennummer
      function postDetails() {

        //Details auslesen
        //Quelle: https://johannesdienst.net/jquery-data-attribute-auslesen/
        var serialId = $('#serialId').data('serial');
        var statusId = $('#inputStatus').val();
        var lagerortId = $('#inputLagerort').val();

        //RFID-Tag auslesen, ID und Name
        var tagId = $('#derTag').data('tag');
        var tagName = $('#derTag').data('tagname');

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
          success: function(response) {
            //War die Übertragung erfolgreich, wird folgender Code ausgeführt

            //Kurze Meldung geben, dass es geklappt hat
            alert(response);

          }
        });
      }

      //Funktion löscht das Gerät komplett
      function deleteDevice() {

        //SerialID auslesen
        var serialId = $('#serialId').data('serial');

        //AJAX-Request an apiHandler.php
        //Quelle: Schulprojekt "Waluegemer" => https://waluegemer.derbeton.ch/
        $.ajax({
          type: 'post',
          url: 'apiHandler.php',
          data: {
            //Details und Aufgabe für apiHandler mitschicken
            serialId: serialId,
            task: 'deleteDevice',
          },
          success: function(response) {
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
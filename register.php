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
            <a class="navbar-brand" href="javascript:;">Geräte erfassen</a>
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
                    <h4 class="card-title">Geräte erfassen</h4>
                  </div>
                </div>
                <div class="card-body">
                  <br>
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                      <table style="width:100%">
                        <tbody>
                          <tr>
                            <th></th>
                            <th style="width:70px"></th>
                            <th></th>
                          </tr>
                          <tr>
                            <td style="text-align:right;height:50px">Seriennummer: </td>
                            <td></td>
                            <td><input type="text" class="form-control" id="inputSerial" placeholder=""></td>
                          </tr>
                          <tr>
                            <td style="text-align:right;height:50px">Modell: </td>
                            <td></td>
                            <td>
                              <select class="form-control" id="dd_modelle">
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td style="text-align:right;height:50px">Anlagenummer: </td>
                            <td></td>
                            <td><input type="text" class="form-control" id="inputAsset" placeholder=""></td>
                          </tr>
                          <tr>
                            <td style="text-align:right;height:50px">Status: </td>
                            <td></td>
                            <td>
                              <select class="form-control" id="dd_status">
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td style="text-align:right;height:50px">Lagerort: </td>
                            <td></td>
                            <td>
                              <select class="form-control" id="dd_lager">
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td style="text-align:right;height:50px">RFID-Tag: </td>
                            <td></td>
                            <td>
                              <input type="text" class="form-control" id="inputTag" placeholder="">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="col-md-4"></div>
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

      //Infos in DDs laden
      getInfos();

      //Logout-Button aktivieren
      //Quelle: Schulprojekt "Tesla"
      $(".logout").on("click", function() {
        //Beim Klick auf den Button auf logout.php weiterleiten
        window.location.href = "logout.php";
      })

      //Suchen-Button aktivieren
      $("#suchen").on("click", function() {
        //Funktion zur AJAX-Abfrage aufrufen mit false als Argument
        getDetails(false);
      })

      //Suche mit Enter starten
      $("#inputSerial").on("keyup", function(e) {
        //Prüfen, ob Enter gedrückt wurde
        if (e.which == 13) {
          getDetails(false);
        }
      })

      <?php
      //Wenn man auf einen Link auf diese Seite kommt, steht Seriennummer in der Session-Variable
      //Details müssen beim Aufrufen sofort abgefragt und angezeigt werden

      if (isset($_SESSION['serialNr'])) {
        //Funktion ausführen mit true als Argument
        echo "getDetails(true)";
      }
      ?>

      //Funktion speichert Details der Seriennummer
      function postDetails() {

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
          success: function(response) {
            //War die Übertragung erfolgreich, wird folgender Code ausgeführt

            //Kurze Meldung geben, dass es geklappt hat
            alert(response);

          }
        });
      }

      //Funktion holt sich alle Modelle, Stati & Lagerorte
      function getInfos() {

        //Modelle
        $.ajax({
          type: 'post',
          url: 'apiHandler.php',
          dataType: 'json',
          data: {
            task: 'getModels',
          },
          success: function(response) {
            //War die Übertragung erfolgreich, wird folgender Code ausgeführt
            if (response) {
              //DD leeren
              $("#dd_modelle").html("");
              $.each(response, function(id, model) {
                //Alle Modelle im Dropdown anzeigen
                $("#dd_modelle").append('<option value="' + id + '" >' + model + '</option>');
              })
            } else {
              console.log(response);
            }
          }
        });

        //Stati
        $.ajax({
          type: 'post',
          url: 'apiHandler.php',
          dataType: 'json',
          data: {
            task: 'getStati',
          },
          success: function(response) {
            //War die Übertragung erfolgreich, wird folgender Code ausgeführt
            //console.log(response);
            if (response) {
              //DD leeren
              $("#dd_status").html("");
              $.each(response, function(id, status) {
                //Alle Modelle im Dropdown anzeigen
                $("#dd_status").append('<option value="' + id + '" >' + status + '</option>');
              })

              //IT vorauswählen
              $("#dd_status option").each(function() {
                if ($(this).html() == "IT") {
                  $(this).attr("selected", true);
                }
              });

              //Lagerorte holen
              updateStock();



              //Neue Lagerorte holen, wenn sich Status ändert
              $("#dd_status").on("change", function() {
                updateStock();
              });

            } else {
              console.log(response);
            }
          }
        });
      }
    })

    //Funktion holt Lagerorte zu Stati
    function updateStock() {
      //Lese Status-Name, nicht ID!, aus dem Formular
      //Quelle: https://stackoverflow.com/questions/6454016/get-text-of-the-selected-option-with-jquery/6454073
      var status = $('#dd_status option:selected').html();

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
          $("#dd_lager").html(response);

          //Wenn Status IT ausgewählt ist, Schrank 1 vorauswählen
          if ($("#dd_status :selected").html() == "IT") {
            $("#dd_lager option").each(function() {
              if ($(this).html() == "Schrank 1") {
                $(this).attr("selected", true);
              }
            });
          }

        }
      });
    }
  </script>
</body>

</html>
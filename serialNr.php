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
          <!--
          Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

          Tip 2: you can also add an image using data-image tag
      -->
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
                
                <!--  Logout-Button   -->
          <button type="button" class="btn btn-primary logout">Logout</button>


            </div>
          </div>

        </div>
      </div>
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
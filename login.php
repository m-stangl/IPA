<?php
session_start();
//Session beginnen

//überprüfen, ob Benutzername und Kennwort eingegeben worden sind
if(isset($_POST["username"]) && isset($_POST["password"])){
    
    //Lese Zugangsdaten aus POST-Variablen, damit sie im Code-Snippet problemlos eingefügt werden können
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    //Code-Snippet aus dem Postman generiert
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://iwc.ios-business-apps.com/api/iwc/login",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>"{\n\t\"username\": \"$username\",\n\t\"password\": \"$password\"\n}",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //Ende vom Code-Snippet
    
    //JSON-Objekt in ein PHP-Array umwandeln
    $responseArray = json_decode($response, true);
    
    //Prüfen, ob der Loginversuch erfolgreich war
    //Quelle https://stackoverflow.com/questions/10176293/how-to-know-whether-key-exists-in-json-string/10176383
    if(isset($responseArray['access_token'])){
        
        //Login erfolgreich, schreibe wichtige Infos in Session-Variable
        $_SESSION["username"] = $username;
        $_SESSION["access_token"] = $responseArray['access_token'];
        
        //Rolle wird gespeichert, damit Berechtigungsstufe greifen kann
        $_SESSION["role"] = $responseArray['role'];
        
        //Umleiten auf die Übersicht
        header("Location: uebersicht.php");
    }
    
}
?>
<!-- HTML-Grundgerüst -->
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
  </head>
  <body class="backgroundWatch">
      
      <div class="transparentLayer">
      </div>
      
      
      <!--   Card   -->
      <div class="col-md-6">
          <div class="card" id="loginCard">
              <div class="card-header card-header-text card-header-primary">
                <div class="card-text">
                  <h4 class="card-title" id="cardTitleLogin">Here is the Text - Login</h4>
                </div>
              </div>
              <div class="card-body">
                    <!--    Login-Formular  -->
                    <form name="login" action="login.php" method="POST" data-ajax="false">
                      <div class="form-group test">
                        <label for="inputUsername">Benutzername</label>
                          <!--  Feld ausfüllen, falls der Benutzername schon eingegeben wurde-->
                        <input type="text" class="form-control" name="username" <?php if(isset($_POST["username"])){echo 'value="' . $_POST['username'] . '"';}?>>
                      </div>
                      <div class="form-group test">
                        <label for="inputPassword">Kennwort</label>
                        <input type="password" class="form-control" name="password">
                      </div>
                      <!--  Login-Button, muss in der Mitte sein
                            Quelle: https://stackoverflow.com/questions/4221263/center-form-submit-buttons-html-css-->
                      <div class="buttonHolder" align="center">
                        <input type="submit" class="btn btn-primary" value="Login">
                      </div>
                    </form>
                    <!--    Login-Formular Ende-->
              </div>
          </div>
      </div>
      <!--   Card Ende   -->
  </body>
</html>

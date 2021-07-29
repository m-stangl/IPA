<?php
//Session starten, damit man darauf zugreifen kann
session_start();

//überprüfen, ob ein Login erfolgt ist
//Quelle: Schulprojekt "Dupload" => cloud.derbeton.ch
if(!isset($_SESSION["access_token"])){
    //Umleiten auf Login-Seite, da es ein unbefugter Zugriff ist
    header("Location: login.php");
}

//Access-Token auslesen, weil er für jede API-Abfrage benötigt wird
$access_token = $_SESSION["access_token"];

//Task auslesen und entsprechende Funktion starten
if(isset($_POST["task"]) && $_POST["task"] == "getDetails"){
    
    //Seriennummer auslesen, da sie der Funktion übergeben werden muss
    $serialNr = $_POST["serialNr"];
    
    //getDetails ausführen mit den Argumenten "access_token" und "serialNr"
    getDetails($access_token, $serialNr);
    
}elseif(isset($_POST["task"]) && $_POST["task"] == "postDetails"){
    
    //Details auslesen, da sie der Funktion übergeben werden müssen
    $serialId = $_POST["serialId"];
    $statusId = $_POST["statusId"];
    $lagerortId = $_POST["lagerortId"];
    
    //Anpassungen aus Test T-007
    if(isset($_POST["tagId"])){
        $tagId = $_POST["tagId"];
    }else{
        //tagId definieren, falls sie noch nicht existiert
        $tagId = "";
    }
    
    $tagName = $_POST["tagName"];
    
    //postDetails ausführen mit "access_token" und allen Details als Argumente
    postDetails($access_token, $serialId, $statusId, $lagerortId, $tagId, $tagName);
    
}elseif(isset($_POST["task"]) && $_POST["task"] == "updateStock"){
    
    //Status auslesen, damit er der Funktion übergeben werden kann
    $status = $_POST["status"];
    
    //updateStock ausführen mit den Argumenten "access_token" und "status"
    updateStock($access_token, $status);
    
}elseif(isset($_POST["task"]) && $_POST["task"] == "getAsset"){
    
    //Anlagenummer auslesen, damit sie der Funktion übergeben werden kann
    $assetNr = $_POST["assetNr"];
    
    //getAsset ausführen mit den Argumenten "access_token" und "assetNr"
    getAsset($access_token, $assetNr);
    
}elseif(isset($_POST["task"]) && $_POST["task"] == "getReport"){
    
    //getAsset ausführen mit dem Argument "access_token"
    getReport($access_token);
    
}

/////////////////////////////////////////////////////////////////////////////////////////////
//    FUNKTION GETDETAILS                                                                  //
/////////////////////////////////////////////////////////////////////////////////////////////

function getDetails($access_token, $serialNr){
    
    //Code-Snippet aus dem Postman generiert
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/serial/$serialNr",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token"
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //Ende vom Code-Snippet
    
    //JSON-Objekt in ein PHP-Array umwandeln
    //Quelle: https://www.w3schools.com/js/js_json_php.asp
    $responseArray = json_decode($response);
        
    //Prüfen, ob Gerät gefunden wurde
    if(isset($responseArray[0]->id)){
        //Gerät wurde gefunden
        
        //Antwort aufbauen
        //Quelle: https://www.w3schools.com/php/php_operators.asp
        $antwort  = '<div class="col-md-2"></div>';
        $antwort .= '<div class="col-md-8">';
        $antwort .=     '<div class="card">';
        $antwort .=         '<div class="card-header card-header-text card-header-primary">';
        $antwort .=             '<div class="card-text">';
        $antwort .=                 '<h4 class="card-title" data-serial="' . $responseArray[0]->id . '" id="serialId">' . $serialNr . '</h4>';
        $antwort .=             '</div>';
        $antwort .=         '</div>';
        $antwort .=         '<div class="card-body"><br>';
        //Erste Zeile
        $antwort .=             '<div class="row">';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<p>Bezeichnung: </p>';
        $antwort .=                 '</div>';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                 $responseArray[0]->master->description;
        $antwort .=                 '</div>';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                 '</div>';
        $antwort .=             '</div>';
        //Zweite Zeile
        $antwort .=             '<div class="row">';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<p>Anlagenummer: </p>';
        $antwort .=                 '</div>';
        $antwort .=                 '<div class="col-md-4"><a href="links.php?assetNr=' . $responseArray[0]->assetNumber . '">';
        $antwort .=                 $responseArray[0]->assetNumber;
        $antwort .=                 '</a></div>';
        $antwort .=             '</div>';
        //Dritte Zeile
        $antwort .=             '<div class="row">';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<p>Status: </p>';
        $antwort .=                 '</div>';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<select id="inputStatus" class="form-control">';
        
        //Alle Stati auslesen
        
        //Code-Snippet aus dem Postman
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/area",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $access_token"
          ),
        ));

        $stati = curl_exec($curl);

        curl_close($curl);
        //Ende vom Code-Snippet
    
        //JSON-Objekt in ein PHP-Array umwandeln
        //Quelle: https://www.w3schools.com/js/js_json_php.asp
        $statiArray = json_decode($stati);
                
        //Alle Stati durchgehen, Status des Geräts auswählen und IDs als Value hinterlegen
        foreach($statiArray as $status){
            if($responseArray[0]->warehouse->id == $status->id){
                $antwort .= "<option value='" . $status->id . "' selected>" . $status->description . "</option>";
            }else{
                $antwort .= "<option value='" . $status->id . "'>" . $status->description . "</option>";
            }
        }
        
        $antwort .=                     '</select>';  
        $antwort .=                 '</div>';
        $antwort .=             '</div><br>';
        //Vierte Zeile
        $antwort .=             '<div class="row">';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<p>Lagerort: </p>';
        $antwort .=                 '</div>';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<select id="inputLagerort" class="form-control">';
        
        //Lagerorte zum Status auslesen
        
        //Lagerortsname in passende Form bringen
        $stockName = $responseArray[0]->warehouse->description;
        
        //Code-Snippet aus dem Postman
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/area/$stockName",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $access_token"
          ),
        ));

        $stock = curl_exec($curl);

        curl_close($curl);
        //Ende vom Code-Snippet
        
        //JSON-Objekt in ein PHP-Array umwandeln
        //Quelle: https://www.w3schools.com/js/js_json_php.asp
        $stockArray = json_decode($stock);
        
        //Objekte alphabetisch sortieren
        //Quelle: https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields
        usort($stockArray, function($a, $b){
            return strcmp($a->description, $b->description);
        });
        
        //Alle Lagerorte durchgehen, Lagerort des Geräts auswählen und IDs als Value hinterlegen
        foreach($stockArray as $lagerorte){
            if($responseArray[0]->closet->id == $lagerorte->id){
                $antwort .= "<option value='" . $lagerorte->id . "' selected>" . $lagerorte->description . "</option>";
            }else{
                $antwort .= "<option value='" . $lagerorte->id . "'>" . $lagerorte->description . "</option>";
            }
        }
                
        $antwort .=                     '</select>';
        $antwort .=                 '</div>';
        $antwort .=             '</div><br>';
        
        //Fünfte Zeile
        $antwort .=             '<div class="row">';
        $antwort .=                 '<div class="col-md-4">';
        $antwort .=                     '<p>RFID-Tag: </p>';
        $antwort .=                 '</div>';
        $antwort .=                 '<div class="col-md-4">';
        
        //Prüfen, ob bereits ein RFID-Tag vorhanden ist
        //Falls ja, Identity als Value anzeigen und ID als data-Attribut hinzufügen
        //Quelle: https://www.w3schools.com/tags/att_data-.asp
        if(isset($responseArray[0]->tags[0]->id)){
            $antwort .=                 '<input type="text" class="form-control" id="inputTag" value="' . $responseArray[0]->tags[0]->identity . '" data-tag="' . $responseArray[0]->tags[0]->id .'">';
        }else{
            $antwort .=                 '<input type="text" class="form-control" id="inputTag">';
        }
        
        $antwort .=                 '</div>';
        $antwort .=             '</div><br>';
        //Zeile für den Button
        //Button nur einfügen, wenn die Rolle des Benutzers admin oder manager ist
        if($_SESSION['role'] == "admin" or $_SESSION['role'] == "manager"){
            $antwort .=             '<div class="row">';
            $antwort .=                 '<div class="col" style="text-align:center">';
            $antwort .=                     '<button type="button" class="btn btn-primary" id="speichern">Speichern</button>';
            $antwort .=                 '</div>';
            $antwort .=             '</div>';
        }
        //Card schliessen
        $antwort .=         '</div>';
        $antwort .=     '</div>';
        $antwort .= '</div>';
        $antwort .= '<div class="col-md-2"></div>';
        
        //Antwort zurückschicken
        echo $antwort;
    }else{
        //Gerät wurde nicht gefunden
        echo "sry man gits nid";
    }
    
}

/////////////////////////////////////////////////////////////////////////////////////////////
//    FUNKTION POSTDETAILS                                                                 //
/////////////////////////////////////////////////////////////////////////////////////////////

function postDetails($access_token, $serialId, $statusId, $lagerortId, $tagId, $tagName){
    
    //Überprüfen, ob Tag schon existiert
    
    //Code-Snippet aus dem Postman
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/tag/$tagName",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token"
      ),
    ));
    
    $tagInfos = curl_exec($curl);

    curl_close($curl);
    //Ende vom Code-Snippet
    
    //JSON-Objekt in ein PHP-Array umwandeln
    //Quelle: https://www.w3schools.com/js/js_json_php.asp
    $tagInfoArray = json_decode($tagInfos);
    
    //Prüft, ob die Abfrage einen Tag gefunden hat mit dem Namen
    //Wenn ja, wird die ID des gefundenen Tags genommen, damit der Tag wechselt
    //Wenn nicht, wird der Tag-Name mitgegeben, sodass ein neuer Tag erstellt wird
    if(isset($tagInfoArray->id)){
        //Tag existiert, ID muss benutzt werden
        //ID wechselt den Tag des Geräts
        $tagId = $tagInfoArray->id;
        
        //Code-Snippet aus dem Postman
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/item",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{\r\n        \"id\": \"$serialId\",\r\n        \"warehouse\": {\r\n            \"id\": \"$statusId\"\r\n        },\r\n        \"closet\": {\r\n            \"id\": \"$lagerortId\"\r\n        },\r\n        \"tags\": [{\r\n        \t\"id\": \"$tagId\"\r\n        }]\r\n    }",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $access_token"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //Ende Code-Snippet
        
    }elseif($tagName!=""){
        //Tag existiert nicht, muss angelegt werden
        //Name erstellt einen neuen Tag 
        
        //Code-Snippet aus dem Postman
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/item",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{\r\n        \"id\": \"$serialId\",\r\n        \"warehouse\": {\r\n            \"id\": \"$statusId\"\r\n        },\r\n        \"closet\": {\r\n            \"id\": \"$lagerortId\"\r\n        },\r\n        \"tags\": [{\r\n        \t\"identity\": \"$tagName\"\r\n        }]\r\n    }",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $access_token"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //Ende Code-Snippet
    }else{
        //Tag-Feld wurde leergelassen, muss für allfällige Änderungen am Status/Lagerort dem leeren Tag zugeordnet werden
        
        //ID des leeren Tags
        $tagId = "d5ca2683-534e-411e-9a07-2654623cdca0";
        
        //Code-Snippet aus dem Postman
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/item",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{\r\n        \"id\": \"$serialId\",\r\n        \"warehouse\": {\r\n            \"id\": \"$statusId\"\r\n        },\r\n        \"closet\": {\r\n            \"id\": \"$lagerortId\"\r\n        },\r\n        \"tags\": [{\r\n        \t\"id\": \"$tagId\"\r\n        }]\r\n    }",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $access_token"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //Ende Code-Snippet
    }
    
    echo "Änderungen abgespeichert";
    
}

/////////////////////////////////////////////////////////////////////////////////////////////
//    FUNKTION UPDATESTOCK                                                                 //
/////////////////////////////////////////////////////////////////////////////////////////////

function updateStock($access_token, $status){
    
    //Alle Lagerorte zum Status auslesen
    
    //Code-Snippet aus dem Postman
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/area/$status",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token"
      ),
    ));

    $stock = curl_exec($curl);

    curl_close($curl);
    //Ende Code-Snippet
    
    //JSON-Objekt in ein PHP-Array umwandeln
    //Quelle: https://www.w3schools.com/js/js_json_php.asp
    $stockArray = json_decode($stock);

    //Objekte alphabetisch sortieren
    //Quelle: https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields
    usort($stockArray, function($a, $b){
        return strcmp($a->description, $b->description);
    });
    
    //Alle Lagerorte durchgehen, Lagerort des Geräts auswählen und IDs als Value hinterlegen
    foreach($stockArray as $lagerorte){
        echo "<option value='" . $lagerorte->id . "'>" . $lagerorte->description . "</option>";  
    }
    
    
}

/////////////////////////////////////////////////////////////////////////////////////////////
//    FUNKTION GETASSET                                                                    //
/////////////////////////////////////////////////////////////////////////////////////////////

function getAsset($access_token, $assetNr){
    //Alle Infos zur Anlagenummer auslesen
    
    //Code-Snippet aus dem Postman
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/report/asset/$assetNr",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token"
      ),
    ));

    $asset = curl_exec($curl);

    curl_close($curl);
    //Ende vom Code-Snippet
    
    //JSON-Objekt in ein PHP-Array umwandeln
    //Quelle: https://www.w3schools.com/js/js_json_php.asp
    $assetArray = json_decode($asset);
    
    //Prüfen, ob Anlagenummer gefunden wurde
    if($assetArray->count !== 0){
        //Anlagenummer existiert und ein oder mehr Geräte darin
        
        //Antwort aufbauen
        $antwort  = '<div class="col-md-2"></div>';
        $antwort .= '<div class="col-md-8">';
        $antwort .=     '<div class="card">';
        $antwort .=         '<div class="card-header card-header-text card-header-primary">';
        $antwort .=             '<div class="card-text">';
        //Anlagenummer und Anzahl Geräte auf gleicher Zeile anzeigen
        //Quelle: https://stackoverflow.com/questions/12438339/how-may-i-align-text-to-the-left-and-text-to-the-right-in-the-same-line
        $antwort .=                 '<h4 class="card-title" style="display: inline-block">' . $assetNr . '</h4>';
        //Tab zwischen Text und Nummer
        //Quelle: https://www.quora.com/How-do-you-add-a-tab-space-in-HTML
        $antwort .=                 '<h4 class="card-title" style="display: inline-block;float:right;">Anzahl Geräte:&nbsp&nbsp&nbsp&nbsp' . $assetArray->count . '</h4>';
        $antwort .=             '</div>';
        $antwort .=         '</div>';
        $antwort .=         '<div class="card-body"><br>';
        //Card-body
        
        //Geräte pro Status ausgeben
        
        //Prüfen, ob Geräte Status "IT" haben
        if($assetArray->countIt !== 0){
            //Es existieren Geräte mit Status "IT" in dieser Anlagenummer
            $antwort .= '<h4 style="display:inline-block">Status "IT"</h4>';
            $antwort .= '<h4 style="display:inline-block;float:right;">Anzahl Geräte:&nbsp&nbsp&nbsp&nbsp' . $assetArray->countIt . '</h4> <br>';
            
            //Tabelle erstellen
            $antwort .= '<table class="table">';
            $antwort .=     '<thead>';
            $antwort .=         '<th>Seriennummer</th>';
            $antwort .=         '<th class="text-right">Bezeichnung</th>';
            $antwort .=     '</thead>';
            $antwort .=     '<tbody>';
            
            //Jedes Gerät vom Status "IT" auslesen
            //Quelle: https://www.w3schools.com/php/php_looping_foreach.asp
            foreach($assetArray->articlesIt as $value){
                $antwort .=     '<tr><td><a href="links.php?serialNr=' . $value->serialNumber . '">' . $value->serialNumber . '</a></td>';
                $antwort .=     '<td class="text-right">' . $value->master->description . '</td></tr>';
            }
            $antwort .=     '</tbody>';
            $antwort .= '</table><br>';
        }
        
        //Prüfen, ob Geräte Status "Extern" haben
        if($assetArray->countExtern !== 0){
            //Es existieren Geräte mit Status "Extern" in dieser Anlagenummer
            $antwort .= '<h4 style="display:inline-block">Status "Extern"</h4>';
            $antwort .= '<h4 style="display:inline-block;float:right;">Anzahl Geräte:&nbsp&nbsp&nbsp&nbsp' . $assetArray->countExtern . '</h4> <br>';
            
            //Tabelle erstellen
            $antwort .= '<table class="table">';
            $antwort .=     '<thead>';
            $antwort .=         '<th>Seriennummer</th>';
            $antwort .=         '<th class="text-right">Bezeichnung</th>';
            $antwort .=     '</thead>';
            $antwort .=     '<tbody>';
            
            //Jedes Gerät vom Status "Extern" auslesen
            //Quelle: https://www.w3schools.com/php/php_looping_foreach.asp
            foreach($assetArray->articlesExtern as $value){
                $antwort .=     '<tr><td><a href="links.php?serialNr=' . $value->serialNumber . '">' . $value->serialNumber . '</a></td>';
                $antwort .=     '<td class="text-right">' . $value->master->description . '</td></tr>';
            }
            $antwort .=     '</tbody>';
            $antwort .= '</table><br>';
        }
        
        //Prüfen, ob Geräte Status "IWC" haben
        if($assetArray->countIwc !== 0){
            //Es existieren Geräte mit Status "IWC" in dieser Anlagenummer
            $antwort .= '<h4 style="display:inline-block">Status "IWC"</h4>';
            $antwort .= '<h4 style="display:inline-block;float:right;">Anzahl Geräte:&nbsp&nbsp&nbsp&nbsp' . $assetArray->countIwc . '</h4> <br>';
            
            //Tabelle erstellen
            $antwort .= '<table class="table">';
            $antwort .=     '<thead>';
            $antwort .=         '<th>Seriennummer</th>';
            $antwort .=         '<th class="text-right">Bezeichnung</th>';
            $antwort .=     '</thead>';
            $antwort .=     '<tbody>';
            
            //Jedes Gerät vom Status "IWC" auslesen
            //Quelle: https://www.w3schools.com/php/php_looping_foreach.asp
            foreach($assetArray->articlesIwc as $value){
                $antwort .=     '<tr><td><a href="links.php?serialNr=' . $value->serialNumber . '">' . $value->serialNumber . '</a></td>';
                $antwort .=     '<td class="text-right">' . $value->master->description . '</td></tr>';
            }
            $antwort .=     '</tbody>';
            $antwort .= '</table><br>';
        }
        
        //Card-body Ende
        $antwort .=         '</div>';
        //Card schliessen
        $antwort .=     '</div>';
        //Column schliessen
        $antwort .= '</div>';
        $antwort .= '<div class="col-md-2"></div>';
        
        //Antwort zurückschicken
        echo $antwort;
        
    }else{
        //Anlagenummer existiert nicht oder beinhaltet keine Geräte
        echo "sry man gits nid";
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////
////    FUNKTION GETREPORT                                                                 //
/////////////////////////////////////////////////////////////////////////////////////////////

function getReport($access_token){
    
    //Alle Anlagenummern auflisten, deren Geräte den Status "Extern" haben
    
    //Code-Snippet aus dem Postman
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://inventory-dashboard.iwc.com:8080/api/iwc/report/external",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token"
      ),
    ));

    $report = curl_exec($curl);

    curl_close($curl);
    //Ende vom Code-Snippet
    
    //JSON-Objekt in ein PHP-Array umwandeln
    //Quelle: https://www.w3schools.com/js/js_json_php.asp
    $reportArray = json_decode($report);
   
    //Alle Anlagenummern und Geräteanzahl ausgeben
    
    //Antwort aufbauen
    
    $antwort  = '<div class="col-md-2"></div>';
    $antwort .= '<div class="col-md-8">';
    $antwort .=     '<div class="card">';
    $antwort .=         '<div class="card-header card-header-text card-header-primary">';
    $antwort .=             '<div class="card-text">';

    $antwort .=                 '<h4 class="card-title">Report Anlagenummern "Extern"</h4>';
    $antwort .=             '</div>';
    $antwort .=         '</div>';
    $antwort .=         '<div class="card-body"><br>';
    //Card-body
    
    //Tabelle erstellen
    $antwort .= '<table class="table">';
    $antwort .=     '<thead>';
    $antwort .=         '<th>Anlagenummer</th>';
    $antwort .=         '<th class="text-right">Anzahl Geräte</th>';
    $antwort .=     '</thead>';
    $antwort .=     '<tbody>';
    
    //Anlagenummern auflisten
    foreach($reportArray as $val){
        $antwort .= "<tr>";
        $antwort .=     "<td><a href='links.php?assetNr=" . $val->assetNumber . "'>". $val->assetNumber . "</a></td>";
        $antwort .=     "<td class='text-right'>" . $val->countItems . "</td>";
        $antwort .= "</tr>";
    }
    $antwort .=     '</tbody>';
    $antwort .= '</table>';
    
    //Aktualisieren-Button einfügen
    $antwort .= '<div class="row">';
    $antwort .=     '<div class="col" style="text-align:center">';
    $antwort .=         '<button type="button" class="btn btn-primary" id="aktualisieren">Aktualisieren</button>';
    $antwort .=     '</div>';
    $antwort .= '</div>';
    
    //Card-body Ende
    $antwort .=         '</div>';
    //Card schliessen
    $antwort .=     '</div>';
    //Column schliessen
    $antwort .= '</div>';
    $antwort .= '<div class="col-md-2"></div>';

    //Antwort zurückschicken
    echo $antwort;
    
}
?>
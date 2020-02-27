<?php

//Session starten, dass man darauf zugreifen kann
session_start();

//Session-Variablen löschen
session_unset();

//Session zerstören und auf die Login-Seite leiten
session_destroy();
header("Location: login.php");

?>
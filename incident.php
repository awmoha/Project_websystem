<?php
require_once('db.php');
require_once('track_visit.php');

session_start();

// Säkerställ att användaren är inloggad
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Sätt titel
$title = "Incidents";

// Bestäm vilken content som ska inkluderas
$content = "pages/incidents_content.php";

// Hämta roll från sessionen (vi förutsätter att roll lagras vid login)
$user_role = $_SESSION['role_name']; // Ex: 'Administrator', 'Reporter', 'Responder'

// Lägg till roll i layouten
include('layout/layout.php');
?>

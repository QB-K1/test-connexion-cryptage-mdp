<?php

// pour initialiser $_SESSION, qui est initialisé vide (variable superglobale)
session_start();

// pour vider $_SESSION
session_destroy();

// renvoie sur page d'accueil
header('location: index.php');

// indique que fin de modif de la page
exit();

?>

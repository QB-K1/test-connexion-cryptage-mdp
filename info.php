<?php

// pour initialiser $_SESSION, qui est initialisé vide (variable superglobale)
session_start();

?>

<!-- si clique sur bouton accueil renvoie à page d'accueil -->
<a href="index.php">Accueil</a>
<!-- si clique sur bouton envoie process de deco (voir deco.php) -->
<a href="deco.php">Deconnexion</a>
<h1>Info</h1>
<!-- affiche info de user stockées dans variable superglobale $_SESSION (voir index.php) -->
<p> Utilisateur : <?= $_SESSION['user'] ?></p>
<p> Age : <?= $_SESSION['age'] ?></p>
<p> Job : <?= $_SESSION['job'] ?></p>
<?php

// fonction pour crypter le MDP avant de le rentrer dans la BDD
function hashPassword($password)
	{
	    /*
	    * Génération du sel, nécessite l'extension PHP OpenSSL pour fonctionner.
	    *
	    * openssl_random_pseudo_bytes() va renvoyer n'importe quel type de caractères.
	    * Or le chiffrement en blowfish nécessite un sel avec uniquement les caractères
	    * a-z, A-Z ou 0-9.
	    *
	    * On utilise donc bin2hex() pour convertir en une chaîne hexadécimale le résultat,
	    * qu'on tronque ensuite à 22 caractères pour être sûr d'obtenir la taille
	    * nécessaire pour construire le sel du chiffrement en blowfish.
	    */
	    $salt = '$2y$11$'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);

	    // Voir la documentation de crypt() : http://devdocs.io/php/function.crypt
	    return crypt($password, $salt);
	}


// condition si champs remplis, donc contenu dans $_POST
if (!empty($_POST)) { // équivalent de if (empty($_POST) == false)

	// Création d'un PDO 
	$pdo = new PDO
		(
			'mysql:host=localhost;dbname=exo connexion;charset=UTF8',
			'root',
			'mdp'
	);

	// mettre ensuite pour le type de caractères comme en HTML
	$pdo->exec('SET NAMES UTF8');

	// Requête SQL pour rentrer les infos du formulaire
	$query = $pdo->prepare(
	    '
		INSERT INTO 
			Utilisateurs(
						Name, Password, Age, Job
						)
		VALUES
			(?, ?, ?, ?)
		');

	// stocke le password rentré par user dans variable password
	$password = $_POST['pswCreate'];

	// hash les MDP en remplacant les MDP par leur hash en passant par fonction hashPassword (à qui on passe le MDP contenu dans $password) du coup maintenant dans les values on appelle plus $password mais $passwordHash
	$passwordHash = hashPassword($password);


	// Exécute la requête SQL en évitant injection SQL (récupère valeur correspondant aux names des champs du form)
	$query -> execute(array($_POST['nameCreate'], $passwordHash, $_POST['ageCreate'], $_POST['jobCreate']));


	// Va enregistrer dans variable ce que j’ai récupéré par ma requête SQL
	// Créé un tableau associatif où les keys sont les noms des colonnes et les values le contenu des cellules du tableau de la base de données
	$newUser = $query -> fetchAll(PDO::FETCH_ASSOC);


	// redirige vers page de base
	header('Location: index.php');

}

?>

<h1>Exo connexion cryptage</h1>

<!-- condition si user est bien celui attendu -->
<?php if(!empty($_SESSION['user']))  {?>
	<!-- envoi message d'accueil en récup valeur de user pour l'afficher -->
	<p>Bonjour <?=$_SESSION['user'] ?></p>
	<!-- si clique sur bouton envoie process de info (voir info.php) -->
	<a href="info.php">Infos</a>
	<!-- si clique sur bouton envoie process de deco (voir deco.php) -->
	<a href="deco.php">Deconnexion</a>

<!-- sinon (donc si user pas celui attendu ou si pas rempli, donc aussi page de base (formulaire) -->
<?php } else { ?>
	<form action="create.php" method="POST">
		<input type="text" name="nameCreate" placeholder="Nom"><br/><br/>
		<input type="text" name="pswCreate" placeholder="Mot de passe"><br/><br/>
		<input type="text" name="ageCreate" placeholder="Age (nombre)"><br/><br/>
		<input type="text" name="jobCreate" placeholder="Métier"><br/><br/>
		<input type="submit" name="">
	</form>
<?php }?>
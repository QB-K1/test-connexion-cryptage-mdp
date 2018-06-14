<?php

// pour initialiser $_SESSION, qui est initialisé vide (variable superglobale)
session_start();


// fonction pour déhasher password de la BDD et vérifier si vaut celui qu'on lui passe 
function verifyPassword($password, $hashedPassword)
	{
        // Si le mot de passe entré dans le champs par user est le même que la version hachée de la BDD alors renvoie true.
		return crypt($password, $hashedPassword) == $hashedPassword;
	}


// condition si champs remplis, donc contenu dans $_POST
if (!empty($_POST)) {

	// Création d'un PDO 
	$pdo = new PDO
		(
			'mysql:host=localhost;dbname=exo connexion;charset=UTF8',
			'root',
			'mdp'
	);

	// mettre ensuite pour le type de caractères comme en HTML
	$pdo->exec('SET NAMES UTF8');

	// Requête SQL pour récup les infos du user
	$query = $pdo->prepare(
	    '
	        SELECT
	            *
	        FROM
	            Utilisateurs
	        WHERE 
	        	Name = ?
	    ');


	// Exécute la requête SQL en évitant injection SQL (récupère valeur correspondant aux names des champs du form)
	$query -> execute(array($_POST['name']));


	// Va enregistrer dans variable ce que j’ai récupéré par ma requête SQL
	// Créé un tableau associatif où les keys sont les noms des colonnes et les values le contenu des cellules du tableau de la base de données
	$utilisateurs = $query -> fetchAll(PDO::FETCH_ASSOC);

	foreach ($utilisateurs as $utilisateur) {
		$name = $utilisateur['Name'];
		$psw = $utilisateur['Password'];
		$age = $utilisateur['Age'];
		$job = $utilisateur['Job'];
	}


	// si le contenu du formulaire correspond aux variables que l'on a défini
		// condition 1 : si valeur tapé dans champs ac name "name" est égale à valeur de variable name définie plus haut
		// condition 2 : envoie password rentré par user et password bouclé dans la BDD pour vérifier s'il y a correspondance en le déhashant par fonction verifyPassword, si correspondent doit renvoyer true
	if ($_POST['name'] == $name && verifyPassword($_POST['psw'], $psw) == true) {
		// on stocke dans index de variable superglobale $_SESSION les infos
    	$_SESSION['user'] = $name;
		$_SESSION['age'] = $age;
		$_SESSION['job'] = $job;
    }
}

?>


<h1>Test Connexion avec cryptage de mot de passe</h1><br/><br/>

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
	<form action="index.php" method="POST">
		<input required type="text" name="name" placeholder="Nom"><br/><br/>
		<input required type="password" name="psw" placeholder="Mot de passe"><br/><br/>
		<input type="submit" name="">
	</form>
<?php }?>

<br/><br/>
<hr/>
<br/>

<a href="create.php">Création d'un utilisateur</a>
<?php
	//--------- BDD
	$mysqli = new mysqli("localhost", "root", "", "site");
		if ($mysqli->connect_error) die('Un problème est survenu lors de la tentative de connexion à la BDD : ' . $mysqli->connect_error);
	// $mysqli->set_charset("utf8");
 
	//--------- SESSION
	session_start();

	//--------- CHEMIN
	define("RACINE_SITE",'http://'.$_SERVER['SERVER_NAME'].'/');
 
	//--------- VARIABLES
	$contenu = '';

 

?>
<!Doctype html>
<html>
    <head>
        <title>Mon Site</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="style.css"/>
		
    </head>
    <body> 
		<?php include("footer.php");?>
		<script>
			<?php
				function executeRequete($req)
				{
					global $mysqli; 
					$resultat = $mysqli->query($req); 
					if (!$resultat)
					{
						die("Erreur sur la requete sql.<br />Message : " . $mysqli->error . "<br />Code: " . $req);
					}
					return $resultat;
				}
				//------------------------------------
				function debug($var, $mode = 1) 
				{
						echo '<div style="background: orange; padding: 5px; float: right; clear: both; ">';
						$trace = debug_backtrace(); 
						$trace = array_shift($trace);
						echo "Debug demandé dans le fichier : $trace[file] à la ligne $trace[line].<hr />";
						if($mode === 1)
						{
							echo "<pre>"; print_r($var); echo "</pre>";
						}
						else
						{
							echo "<pre>"; var_dump($var); echo "</pre>";
						}
					echo '</div>';
				}
				//------------------------------------
				function internauteEstConnecte()
				{  
					if(!isset($_SESSION['membre'])) 
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				//------------------------------------
				function internauteEstConnecteEtEstAdmin()
				{ 
					if(internauteEstConnecte() && $_SESSION['membre']['statut'] == 1) 
					{
							return true;
					}
					return false;
				}

				function creationDuPanier()
				{
				   if (!isset($_SESSION['panier']))
				   {
					  $_SESSION['panier']=array();
					  $_SESSION['panier']['titre'] = array();
					  $_SESSION['panier']['id_produit'] = array();
					  $_SESSION['panier']['quantite'] = array();
					  $_SESSION['panier']['prix'] = array();
				   }
				}

				function ajouterProduitDansPanier($titre,$id_produit,$quantite,$prix)
				{
					creationDuPanier(); 
					$position_produit = array_search($id_produit,  $_SESSION['panier']['id_produit']); 
					if ($position_produit !== false)
					{
						 $_SESSION['panier']['quantite'][$position_produit] += $quantite ;
					}
					else 
					{
						$_SESSION['panier']['titre'][] = $titre;
						$_SESSION['panier']['id_produit'][] = $id_produit;
						$_SESSION['panier']['quantite'][] = $quantite;
						$_SESSION['panier']['prix'][] = $prix;
					}
				} 
				//------------------------------------
				function montantTotal()
				{
				   $total=0;
				   for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) 
				   {
					  $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
				   }
				   return round($total,2);
				}
				//------------------------------------
				function retirerproduitDuPanier($id_produit_a_supprimer)
				{
					$position_produit = array_search($id_produit_a_supprimer,  $_SESSION['panier']['id_produit']);
					if ($position_produit !== false)
					{
						array_splice($_SESSION['panier']['titre'], $position_produit, 1);
						array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
						array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
						array_splice($_SESSION['panier']['prix'], $position_produit, 1);
					}
				}
				?>
		</script>
        <header>
			<div class="conteneur">  
				<div class="loguetoi">
				<span>
					<a href="http://servphp1.php/"><img src="logo.png"></a>
					
                </span>
				<nav>
					<?php
					if(internauteEstConnecteEtEstAdmin()) // admin
					{ // BackOffice
						echo '<a href="' . RACINE_SITE . 'admin/gestion_membre.php">Gestion des membres</a>';
						echo '<a href="' . RACINE_SITE . 'admin/gestion_commande.php">Gestion des commandes</a>';
						echo '<a href="' . RACINE_SITE . 'admin/gestion_boutique.php">Gestion de la boutique</a>';
					}
					if(internauteEstConnecte()) // membre et admin
					{
						echo '<a href="' . RACINE_SITE . 'profil.php">Voir votre profil</a>';
						echo '<a href="' . RACINE_SITE . 'boutique.php">Accés à la boutique</a>';
						echo '<a href="' . RACINE_SITE . 'panier.php">Voir votre panier</a>';
						echo '<a href="' . RACINE_SITE . 'connexion.php?action=deconnexion">Se déconnecter</a>';
					}
					else // visiteur
					{
						echo '<a href="' . RACINE_SITE . 'inscription.php">Inscription</a>';
						echo '<a href="' . RACINE_SITE . 'connexion.php">Connexion</a>';
						echo '<a href="' . RACINE_SITE . 'boutique.php">Accés à la boutique</a>';
						echo '<a href="' . RACINE_SITE . 'panier.php">Voir votre panier</a>';
					}
					// visiteur=4 liens - membre=4 liens - admin=7 liens
					?>
				</nav>
			</div>
        </header>
        <section>
			<div class="conteneur">
			
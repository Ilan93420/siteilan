<?php
require_once("index.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
if(!internauteEstConnecte()) 
{
	header("location:connexion.php");
}
$contenu .= '<p class="centre">Compte : <strong>' . $_SESSION['membre']['pseudo'] . '</strong></p>'; // exercice: tenter d'afficher le pseudo de l'internaute pour lui dire Bonjour.
$contenu .= '<div class="cadre"><h2> Votre profil : </h2>';
$contenu .= '<p> votre email est: ' . $_SESSION['membre']['email'] . '<br>';
$contenu .= 'votre ville est: ' . $_SESSION['membre']['ville'] . '<br>';
$contenu .= 'votre cp est: ' . $_SESSION['membre']['code_postal'] . '<br>';
$contenu .= 'votre adresse est: ' . $_SESSION['membre']['adresse'] . '</p></div><br /><br />';
	

echo $contenu;

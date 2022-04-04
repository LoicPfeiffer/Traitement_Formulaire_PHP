<?php
// tester pseudo 😃😃😃
// tester mot de passe <script>alert('xss' + " test")</script>&euro;&&apos;
// modifier appareils dans la barre d'adresse

const HTTP_UNPROCESSABLE_ENTITY = 422;

// Formulaire non valide, envoi du code HTTP 422
function formulaireNonValide($message)
{    
    http_response_code(HTTP_UNPROCESSABLE_ENTITY);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $message . "\n" . 'Code : '. http_response_code();
    die();
}

function afficherFormulaire() 
{    
    header('Location: formulaire.html');
    die();
}


if(empty($_GET)) 
{
    // Page traitement.php sans aucune donnée envoyée
    // Affichage du formulaire
    afficherFormulaire();
}

// Filtrage du pseudo
$erreurs = [];
$pseudo = trim(filter_input(INPUT_GET,'pseudo',FILTER_SANITIZE_STRING));

// Récupération des mots de passe bruts
$mdp1 = $_GET['p1'] ?? '';
$mdp2 = $_GET['p2'] ?? '';

// Filtrage de l'OS d'après une liste de valuers possibles
$os = $_GET['OS'] ?? '';
if(!in_array($os,['W11','W10','WOld','OS','Linux','Other',''])) 
{
    formulaireNonValide('Valeur Système d\'exploitation incorrecte');
}

// Filtrage de la liste des appareils
$appareils = $_GET['appareils'] ?? [];
if(!is_array($appareils))
{
    // Le paramètre 'appareils' n'est pas une liste => formulaire non valide
    formulaireNonValide('Valeur appareils incorrecte');
}

// Vérification de chaque code appareil d'après une liste de codes valides
foreach($appareils as $unAppareil) {
    if(!in_array($unAppareil,['bureau','port','netb','tabl'])) 
    {
        formulaireNonValide('Un appareil incorrect');
    }
}

// Vérification de la profession d'après une liste de codes profession valides
$profession = $_GET['profession'] ?? '';
if(!in_array($profession,['cho','etu','fon','priv','retr','aut',''])) 
{
    formulaireNonValide('Valeur Profession incorrecte'); 
}

// Vérification du bouton submit
if($_GET['submit'] != 'S\'inscrire') 
{
    formulaireNonValide('Erreur paramètre submit'); 
}

 
?>

<!doctype html>
<html lang="fr">
    <title>Données reçues</title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <meta charset="utf-8" />
<?php
echo 'Pseudo : ' . $pseudo . '<br>' . "\n";
echo 'Longueur des mots de passe : ' . mb_strlen($mdp1) . ' ' . mb_strlen($mdp2) . '<br>' . "\n";
// Affichage des mots de passe avec encodage des caractères spéciaux
echo 'Mdp1 = ' . filter_var($mdp1,FILTER_SANITIZE_SPECIAL_CHARS) . '<br>' . "\n";
echo 'Mdp2 = ' . filter_var($mdp2,FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '<br>' . "\n";
echo 'Code OS Préféré : ' . $os . '<br>' . "\n";
// Implode : transforme un tableau en chaîne de caractères
echo 'Code Appareils Préférés : ' . implode(' ',$appareils) . '<br>' . "\n";
echo 'Code Profession : ' . $profession . '<br>' . "\n";
?>
</html>
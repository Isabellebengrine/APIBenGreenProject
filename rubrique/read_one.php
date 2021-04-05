<?php



// les headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// les inclusions
include_once '../config/database.php';
include_once '../objects/rubrique.php';

// on se connecte
$database = new Database();
$db = $database->getConnection();

$rubrique = new Rubrique($db);

// Récupérons l'id de l'objet à lire
$rubrique->id = isset($_GET['id']) ? $_GET['id'] : die();

// on lit les détails du produit
$rubrique->readOne();
if ($rubrique->name != null) {
    // création d'un tableau
    $rubrique_arr[] = ["id" => $rubrique->id,
        "parent_id" => $rubrique->parent_id,
        "name" => $rubrique->name,
        "picture" => $rubrique->picture];
    // requête ok 200
    http_response_code(200);

    //On traduit ça en Json
    echo json_encode($rubrique_arr);
} else{
    http_response_code(404);
    echo json_encode(["message"=>"Cette catégorie de produits n'existe pas!"]);
}
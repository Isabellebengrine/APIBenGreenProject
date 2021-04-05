<?php



// les headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// les inclusions
include_once '../config/database.php';
include_once '../objects/product.php';

// on se connecte
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// Récupérons l'id de l'objet à lire
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// on lit les détails du produit
$product->readOne();
if ($product->name != null) {
    // création d'un tableau
    $product_arr[] = ["id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "rubrique" => $product->rubrique_id,
        "rubrique_name" => $product->rubrique_name];
    // requête ok 200
    http_response_code(200);

    //On traduit ça en Json
    echo json_encode($product_arr);
} else{
    http_response_code(404);
    echo json_encode(["message"=>"Ce produit n'existe pas!"]);

}
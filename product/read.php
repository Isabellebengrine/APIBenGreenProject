<?php
// Les headers dont nous avons besoin
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// inclusion de database et product
include_once '../config/database.php';
include_once '../objects/product.php';

// instanciation de database
$database = new Database();
$db = $database->getConnection();

// On initialise product
$product = new Product($db);

// le query
$stmt = $product->read();
$num = $stmt->rowCount();

// on regarde si on a plus d'un résultat
if($num>0){

    // tableau de produits
    $products_arr = array();//le résultat de la requête est un tableau de produits
    $products_arr["records"]=array();//chaque produit est lui-même un tableau clé-valeur pour chaque champ

    // on récupère le contenu de la table
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $product_item = array(
            "id" => $id,//ici reprendre noms champs dans table de bdd (attention à casse)
            "rubrique_id" => $rubrique_id,
            "products_name" => $products_name,
            "products_description" => $products_description,
            "products_price" => $products_price,
            "rubrique_name" => $rubrique_name,
            "products_picture" => $products_picture
        );

        array_push($products_arr["records"], $product_item);//on remplit le produit avec ses infos prises de la table
    }

    // on envoie la réponse http à 200 OK
    http_response_code(200);

    // on renvoie la réponse en Json
    echo json_encode($products_arr["records"]);

} else {
    // on renvoie le code 404 Not found
        http_response_code(404);
    
    // On averti l'utilisateur
        echo json_encode(array("message"=> "Aucun produit trouvé."));
}



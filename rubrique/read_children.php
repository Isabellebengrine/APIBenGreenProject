<?php
// Les headers dont nous avons besoin
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// inclusion de database et rubrique
include_once '../config/database.php';
include_once '../objects/rubrique.php';

// instanciation de database
$database = new Database();
$db = $database->getConnection();

// On initialise rubrique
$rubrique = new Rubrique($db);

// le query
$stmt = $rubrique->readChildren();
$num = $stmt->rowCount();

// on regarde si on a plus d'un résultat
if($num>0){

    // tableau de rubriques:
    $rubriques_arr = array();//le résultat de la requête est un tableau de rubriques
    $rubriques_arr["records"]=array();//chaque rubrique est elle-même un tableau clé-valeur pour chaque champ

    // on récupère le contenu de la table
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $rubrique_item = array(
            "id" => $id,//ici reprendre noms champs dans table de bdd (attention à casse)
            "parent_id" => $parent_id,
            "rubrique_name" => $rubrique_name,
            "rubrique_picture" => $rubrique_picture
        );

        array_push($rubriques_arr["records"], $rubrique_item);
    }

    // on envoie la réponse http à 200 OK
    http_response_code(200);

    // on renvoie la réponse en Json
    echo json_encode($rubriques_arr);

} else {
    // on renvoie le code 404 Not found
        http_response_code(404);
    
    // On averti l'utilisateur
        echo json_encode(array("message"=> "Aucun résultat trouvé."));
}



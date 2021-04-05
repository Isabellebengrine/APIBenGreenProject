<?php
class Product {

    // connexion à la base
    private $conn;
    private $table_name = "products";

    // membres de l'objet
    public $id;
    public $rubrique_id;
    public $rubrique_name;
    public $name;
    public $description;
    public $stock;
    public $picture;
    public $status;
    public $price;

    // un constructeur avec $db comme connexion à la base
    public function __construct($db) {
        $this->conn = $db;
    }

    // lecture de tous les produits
    //(testée avec read.php via Postman le 06/01/21 - OK)
    public function read() {
        // requête select avec jointure
        $query = "SELECT
                    rubrique.rubrique_name, products.id, products.products_name, products.products_description, products.products_price, products.rubrique_id, products.products_picture
                    FROM "
                    . $this->table_name . "
                    LEFT JOIN
                    rubrique ON products.rubrique_id = rubrique.id
                    ORDER BY
                    products.id";

        // on prépare la requête
        $stmt = $this->conn->prepare($query);

        // on execute la requête
        $stmt->execute();

        return $stmt;
    }

    //lecture d'un produit choisi par son id:
    //testée avec Postman le 06/01/21 - OK :
    public function readOne() {
        // requête pour lire 1 enregistrement
        $query = "SELECT rubrique.rubrique_name, products.id, products.rubrique_id, products.products_name, products.products_description, products.products_price, products.products_picture
        FROM "
        . $this->table_name . "
        LEFT JOIN
        rubrique ON products.rubrique_id = rubrique.id
        WHERE
            products.id = ?
        LIMIT 0,1";
        // on prépare la requête
        $stmt = $this->conn->prepare($query);
        // on met l'id à sa place
        $stmt->bindParam(1, $this->id);
        // on execute
        $stmt->execute();
        // on récupère le résultat
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
        // on renvoie ça dans l'objet:
        $this->name = $row['products_name'];//nom entre '' doit être identique à nom du champ en bdd (attention à casse!)
        $this->price = $row['products_price'];
        $this->description = $row['products_description'];
        $this->rubrique_id = $row['rubrique_id'];
        $this->rubrique_name = $row['rubrique_name'];
        $this->picture = $row['products_picture'];
    }

    //recherche des produits par sous-catégorie (rubrique_id)
    //testée via postman (find_by_rubrique.php) le 06/01/21 - OK
    public function findByRubrique(){
        $query = "SELECT rubrique.rubrique_name, products.id, products.rubrique_id, products.products_name, products.products_description, products.products_price, products.products_picture
        FROM "
        . $this->table_name . "
        LEFT JOIN
        rubrique ON products.rubrique_id = rubrique.id
        WHERE
            products.rubrique_id = ?";
        // on prépare la requête
        $stmt = $this->conn->prepare($query);
        // on lie le paramètre à sa valeur:
        $stmt->bindParam(1, $this->rubrique_id);
        // on execute
        $stmt->execute();

        return $stmt;
    }

}
?>
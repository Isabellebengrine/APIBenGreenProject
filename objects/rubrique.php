<?php

class Rubrique {

    // connexion à la base
    private $conn;
    private $table_name = "rubrique";

    // membres de l'objet
    public $id;
    public $parent_id;
    public $name;
    public $picture;

    // un constructeur avec $db comme connexion à la base
    public function __construct($db) {
        $this->conn = $db;
    }

    // lecture des catégories principales (rubriques pour lesquelles parent_id == null) de produits:
    //(testée avec read.php via Postman le 06/01/21 - OK)
    public function readParents() {
        // requête pour trouver les rubriques principales:
        $query = "SELECT * FROM "
                    . $this->table_name . "
                    WHERE parent_id IS NULL";

        // on prépare la requête
        $stmt = $this->conn->prepare($query);

        // on execute la requête
        $stmt->execute();

        return $stmt;
    }

    // lecture des sous-catégories (rubriques pour lesquelles parent_id != null) de produits:
    //(testée avec read.php via Postman le 06/01/21 - OK)
    public function readChildren() {
        // requête pour trouver les sous-rubriques :
        $query = "SELECT * FROM "
                    . $this->table_name . "
                    WHERE parent_id IS NOT NULL
                    ORDER BY
                    rubrique_name";

        // on prépare la requête
        $stmt = $this->conn->prepare($query);

        // on execute la requête
        $stmt->execute();

        return $stmt;
    }

    // lecture des sous-catégories d'un Parent (rubriques pour lesquelles parent_id == get('parent_id')) :
    //testée via postman le 06/01/21 - ok!
    public function readKidsOf() {
        // requête pour trouver les sous-rubriques d'une rubrique principale :
        $query = "SELECT * FROM "
                    . $this->table_name . "
                    WHERE parent_id = ?";

        // on prépare la requête
        $stmt = $this->conn->prepare($query);

        //on lie le paramètre à sa valeur:
        $stmt->bindParam(1, $this->parent_id);

        // on execute la requête
        $stmt->execute();

        return $stmt;
    }

    //(testée avec read.php via Postman le 06/01/21 - OK)
    
    public function readOne() {
        // requête pour lire 1 enregistrement
        $query = "SELECT * FROM "
        . $this->table_name . "
        WHERE id = ?
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
        $this->parent_id = $row['parent_id'];//nom entre '' doit être identique à nom du champ en bdd (attention à casse!)
        $this->name = $row['rubrique_name'];
        $this->picture = $row['rubrique_picture'];
    }


}
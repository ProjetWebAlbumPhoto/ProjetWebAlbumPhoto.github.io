<?php
    class Photo{
        //DB 
        private $conn;
        private $table = 'Photo';

        //attribues Photo

        public $Photo_id;
        public $Photo_nom;
        public $Photo_url;
        public $Album_nom;
        
        //constructeur base de doneee

        public function __construct($db) {
            $this->conn = $db;
            
        }
        // fonction lecture affiche les photos par album
        public function lecture() {
            $query = 'SELECT
                    p.Photo_id,
                    p.Photo_nom,
                    p.Photo_url,
                    a.Album_nom
                    FROM 
                    '. $this->table.' p
                    LEFT JOIN Album a
                    ON (a.Album_id = p.Album_id)' ; // ()-->Mariadb!!!           
            

            //execution
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return($stmt);


        }

        //fonction lectureId affiche photo par id

        public function lectureId(){
            $query = 'SELECT
                    p.Photo_id,
                    p.Photo_nom,
                    p.Photo_url,
                    a.Album_nom
                    FROM 
                    '. $this->table.' p
                    LEFT JOIN Album a
                    ON (a.Album_id = p.Album_id)
                    WHERE 
                    p.Photo_id = ?' ; 
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1 , $this->id);//http://localhost/projetWeb/api/post/lectureId.php?id=2
            $stmt->execute();
            $row= $stmt->fetch(PDO::FETCH_ASSOC);

            $this->Photo_nom= $row['Photo_nom'];
            $this->Photo_url= $row['Photo_url'];
            $this->Album_nom= $row['Album_nom'];
        }



        //fonction insert inserer une photo

        public function insert(){
            $query='INSERT INTO ' .
                $this->table . '
                SET 
                Photo_nom = :Photo_nom, 
                Photo_url = :Photo_url,
                Album_id = :Album_id';// pas espace apres : !!!!!!!!
            
            $stmt = $this->conn->prepare($query);

            //verification des donne!!!
            $this->Photo_nom = htmlspecialchars(strip_tags($this->Photo_nom));
            $this->Photo_url = htmlspecialchars(strip_tags($this->Photo_url));
            $this->Album_id = htmlspecialchars(strip_tags($this->Album_id));

            //binding data
            $stmt->bindParam(':Photo_nom' , $this->Photo_nom);
            $stmt->bindParam(':Photo_url' , $this->Photo_url);
            $stmt->bindParam(':Album_id' , $this->Album_id);

            //execution
            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error %s. \n",$stmt->erro); 
                return false;

            }
            
        }


         //fonction update mettre a jour une photo

         public function update(){
            $query='UPDATE' .
                $this->table . '
                SET 
                Photo_nom = :Photo_nom, 
                Photo_url = :Photo_url,
                Album_id = :Album_id
                WHERE Photo_id =:id';// pas espace apres : !!!!!!!!
            
            $stmt = $this->conn->prepare($query);

            //verification des donne!!!
            $this->Photo_nom = htmlspecialchars(strip_tags($this->Photo_nom));
            $this->Photo_url = htmlspecialchars(strip_tags($this->Photo_url));
            $this->Album_id = htmlspecialchars(strip_tags($this->Album_id));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            //binding data
            $stmt->bindParam(':Photo_nom' , $this->Photo_nom);
            $stmt->bindParam(':Photo_url' , $this->Photo_url);
            $stmt->bindParam(':Album_id' , $this->Album_id);
            $stmt->bindParam(':id' , $this->id);

            //execution
            if ($stmt->execute()) {
                return true;
            } else {
                printf(" update Error  %s. \n",$stmt->erro); 
                return false;

            }
            
        }

    }
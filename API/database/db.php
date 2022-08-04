<?php
    class Db{
        // Connection to the database. ///////////////////////////////
        private function dbConnection(){
    
            $serverName = "localhost";
            $userName = "root";
            $password = "Hesoyam1???";
            $dbName = "dbastronauti";

            return new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
        }
        //////////////////////////////////////////////////////////////

       public function createDatabase($serverName,$userName,$password,$dbName){
            try{
                $connection = new PDO("mysql:host=$serverName", $userName, $password);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $connection->query("CREATE DATABASE IF NOT EXISTS $dbName");
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }

       public function createTable($serverName,$userName,$password,$dbName,$tableName){
            try{
                $connection = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
                $connection->query("CREATE TABLE IF NOT EXISTS $tableName(
                    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    firstName varchar(40) COLLATE utf8_czech_ci NOT NULL,
                    lastName varchar(40) COLLATE utf8_czech_ci NOT NULL,
                    birthdate varchar(40) COLLATE utf8_czech_ci NOT NULL,
                    superpower varchar(40) COLLATE utf8_czech_ci NOT NULL)"
                );
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }   
    
         // Insert new astronaut to the database.
        public function insertUser($tableName,$firstName, $lastName, $birthdate,$superpower){
    
            $this->dbConnection()->query("INSERT INTO $tableName (firstName, lastName, birthdate,superpower)
            VALUES ('$firstName', '$lastName', '$birthdate','$superpower')");
         }
    
         // Returns all users as an array.
       public function fetchAllUsers($tableName){
    
            $allUsers =  $this->dbConnection()->query("SELECT * FROM $tableName ORDER BY id DESC");
            while($row = $allUsers->fetch(PDO::FETCH_ASSOC)){
                $data[]=$row;
            }
            return $data;
         }

           // Finds the user by user id and returns it as an array.
        public function fetchSingleUser($userId,$tableName){
            
            $searchedUser =  $this->dbConnection()->query("SELECT * FROM $tableName WHERE id = '$userId'");
            $result = $searchedUser->fetchAll();

            foreach($result as $row){
             $data['id'] = $row['id'];
             $data['firstName'] = $row['firstName'];
             $data['lastName'] = $row['lastName'];
             $data['birthdate'] = $row['birthdate'];
             $data['superpower'] = $row['superpower'];

            }
            return $data;   
         }
         
          // Inserts the updated user data back in to the database.
        public function updateUser($tableName,$firstName, $lastName, $birthdate,$superpower,$id){
            $this->dbConnection()->query("UPDATE $tableName SET firstName ='$firstName',
            lastName ='$lastName', birthdate ='$birthdate',superpower='$superpower' WHERE id =$id");
         }

         // Delete user by user id.
        public function deleteUser($id,$tableName){
            $this->dbConnection()->query("DELETE FROM $tableName WHERE id =$id");
         }
    }
?>
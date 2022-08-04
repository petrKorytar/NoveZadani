<?php
    header('Access-Control-ALlow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: *");

    require_once("./database/db.php");

    // Connection to the database. /////////////////
    $serverName = "localhost";
    $userName = "root";
    $password = "Hesoyam1???";
    $dbName = "dbastronauti";
    $tableName = "astronauti";
    ///////////////////////////////////////////////////

    $response = [];
    $db = new Db();

    $db->createDatabase($serverName,$userName,$password,$dbName);
    $db->createTable($serverName,$userName,$password,$dbName,$tableName);

    //Based on inputs from App.vue, it calls methods in the Bb class.
    $received_data = json_decode(file_get_contents("php://input"));
         
    switch($received_data->action){
       case 'fetchall': // Returns all users.
          echo json_encode($db->fetchAllUsers($tableName));
          break;

       case 'insert': // Insert new user to the database.
          $firstName = $received_data->firstName;
          $lastName = $received_data->lastName;
          $birthdate = $received_data->birthdate;
          $superpower = $received_data->superpower;
      
          $db->insertUser($tableName,$firstName, $lastName,$birthdate,$superpower);
          break;

       case 'fetchSingle': // Finds the user by user id and returns it.
          $result = $db->fetchSingleUser($received_data->id,$tableName);
          echo json_encode($result);
          break;

       case 'update': // Inserts the updated user data back in to the database.
            $firstName = $received_data->firstName;
            $lastName = $received_data->lastName;
            $birthdate = $received_data->birthdate;
            $superpower = $received_data->superpower;
            $id = $received_data->hiddenId;
   
          $db->updateUser($tableName,$firstName,$lastName,$birthdate,$superpower,$id);
          break;

       case 'delete': // Delete user by user id.
          $id = $received_data->id;
          $db->deleteUser($id,$tableName);
          break;
    }
?>
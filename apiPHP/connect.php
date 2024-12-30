<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

header("Content-Type: application/json");

    $servername = "localhost";
    $user = "root";
    $password = "";
    $database = "office";

    $id = $_SERVER['QUERY_STRING'];
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);

    $conn = new mysqli($servername, $user, $password, $database);

    if ($conn -> connect_error){
        die("failed: ". $conn -> connect_error);
    }

    else{
        switch ($method) {
            case 'GET':
                if ($id){
                    $query = "SELECT * FROM `employee` WHERE id  ". ($id);
                    $data = ($conn -> query($query))->fetch_assoc();
                }
                
                else{
                    $query = "SELECT * FROM `employee`;";
                    $temp = ($conn -> query($query));
                    $data = [];
                    while ($row = $temp->fetch_assoc()){
                        $data[] = $row;
                    }
                }
                $inJson =  json_encode($data);
                echo($inJson);
                break;

            case 'POST':
                
                /*

                $name =  $_POST['name'];
                $phone = $_POST['phone'];
                
                */

                $name =  $input['name'];
                $phone = $input['phone'];
                
                $query = "INSERT INTO `employee`(`id`, `name`, `phone`) VALUES (NULL,'" . $name ."'," . $phone . ")";
                $conn -> query($query);
                echo($query . " executed");
                break;

            case 'DELETE':
                $query = "DELETE FROM `employee` WHERE id " . $id;
                $conn -> query($query);
                echo($query . " executed");
            
                break;
            case 'PUT':
                $name =  $input['name'];
                $phone = $input['phone'];
                $query = "UPDATE `employee` SET `name`='" . $name . "',`phone`='" . $phone . "' WHERE id ". $id;
                $conn -> query($query);
                echo($query . " executed");
                break;
    }
}

/*

Use url/?=somenumber to execute query

Ex: 

http://localhost/apiPHP/connect.php/?=64  in browser give item with id = 64

http://localhost/apiPHP/connect.php/?=64 in postman with delete method delete that id and same way for updation

Use same URL with different methods based on use

*/

?>


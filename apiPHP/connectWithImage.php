<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

header("Content-Type: application/json");

    $servername = "localhost";
    $user = "root";
    $password = "";
    $database = "phpapi";

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
                    $query = "SELECT * FROM `products` WHERE id  ". ($id);
                    $data = ($conn -> query($query))->fetch_assoc();
                }
                
                else{
                    $query = "SELECT * FROM `products`;";
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

                $banner=$_FILES['banner']['name']; 
                $expbanner=explode('.',$banner);
                $bannerexptype=$expbanner[1];
                date_default_timezone_set('Asia/Calcutta');
                $date = date('m_d_Yh_i_sa', time());
                $encname=$date;
                $bannername=$date."_".$banner;
                $bannerpath="./uploads/".$bannername;
                move_uploaded_file($_FILES["banner"]["tmp_name"],$bannerpath);
                //echo($bannername);
                
                $prod_name =  $_POST['prod_name'];
                $price = $_POST['price'];
                $query = "INSERT INTO `products`(`id`, `prod_name`, `prod_image`, `price`) VALUES (NULL,'" . $prod_name . "','" . $bannername . "','" . $price . "')";
                $conn -> query($query);
				$query = "SELECT MAX(id) FROM `products`";
                $idp = (($conn -> query($query))->fetch_assoc())['MAX(id)'];
				
				//$q = array("res"=>id);
				$query = "SELECT * FROM `products` WHERE id  =". ($idp);
                $data = ($conn -> query($query))->fetch_assoc();
                echo json_encode($data);
                break;

            case 'DELETE':
                $query = "DELETE FROM `employee` WHERE id " . $id;
				$conn -> query($query);
				$query = "SELECT * FROM `products` WHERE id  ". ($id);
                $datat = ($conn -> query($query))->fetch_assoc();
				unlink('./uploads/'.$datat['prod_image']);
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

<?php
//This is a REST enpoint where clients request video servers and video servers register themselves.
//A server's capacity may vary, so servers will send a request to de-register themselves.
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
//echo $method;
$servername = "mysql:host=db;port=3306;dbname=movie";
$username = "watcher";
$password = "alaspooryorick!iknewhimhoratio";
switch ($method) {
    case 'GET': //Get ip of a video server
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            try {
                $conn = new PDO($servername, $username, $password);
                $sql = "SELECT * FROM USERS WHERE AuthID='". $id . "'";
                //echo $sql;
                $result = $conn->query($sql);
                $data = $result->fetch(PDO::FETCH_ASSOC);
                echo json_encode($data);
            } catch (PDOException $e) {
                echo json_encode("Error!: " . $e->getMessage() . "<br/>");
                die();
            }
        } else {
            try {
                $conn = new PDO($servername, $username, $password);
                $result = $conn->query("SELECT * FROM USERS");
                $videos = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $videos[] = $row;
                }
                echo json_encode($videos);
            } catch (PDOException $e) {
                echo json_encode("Error!: " . $e->getMessage() . "<br/>");
                die();
            }
        }
    break;
        
    case 'POST': //Register new video
        //echo $method;
        $vidusername = $input['UserName'];
        $authID = $input['AuthID'];
        $creator = $input['creator'];
        try {
            $conn = new PDO($servername, $username, $password);
            $sql = "SELECT UserName, AuthID FROM USERS WHERE UserName='" . $vidusername . "' AND AuthID='" . $authID . "'"; //Check if our user is in the Database
            //echo $sql;
            $result = $conn->query($sql);

            if ($result->rowCount() == 0) { 
                
                $sql = "INSERT INTO USERS (UserName, AuthID, creator) VALUES ('" . $vidusername . "', '" . $authID . "', " . $creator . ")";
                //echo $sql;
                $conn->query($sql);
                echo json_encode(["message" => "User added successfully"]);
            } else {
                echo json_encode(["message" => "User already exists"]);
            }
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
    break;
    
    case 'PUT':
        //echo $method;
        $id = $_GET['id'];
        $vidusername = $input['UserName'];
        //$authID = $input['AuthID'];
        $creator = $input['creator'];
        // $enabled = false;
        // if ($creator == "on"){
        //         $enabled = true;
        // }
        try {
            $conn = new PDO($servername, $username, $password);
            $sql = "UPDATE USERS SET UserName='".$vidusername."', creator=".$creator." WHERE AuthID=$id";
            echo $sql;
            $conn->query($sql);
            echo json_encode(["message" => "User updated successfully"]);
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
    break;    
        
    case 'DELETE': //Remove server from avaiable list
        $id = $_GET['id'];
        try {
            $conn = new PDO($servername, $username, $password);
            $conn->query("DELETE FROM USERS WHERE id=$id");
            echo json_encode(["message" => "User deleted successfully"]);
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
    break;
        
    default:
        echo json_encode(["message" => "Invalid request method!"]);
    break;
}


?>

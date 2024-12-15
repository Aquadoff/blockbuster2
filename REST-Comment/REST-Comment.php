<?php
//This is a REST enpoint which manages comments
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$servername = "mysql:host=db;port=3306;dbname=movie";
$username = "uploader";
$password = "akingdomforastage";
switch ($method) {
    case 'GET': //Get ip of a video server
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            try {
                $conn = new PDO($servername, $username, $password);
                $result = $conn->query("SELECT * FROM COMMENT WHERE VidID='$id'");
                //$data = $result->fetch(PDO::FETCH_ASSOC);
                if ($result->rowCount() != 0) {
                    $comments = [];
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $comments[] = $row;
                    }
                    //echo print_r($data);
                    echo json_encode($comments);
                } else {
                    echo json_encode(array(["UserName" => "No Comments!", "Body" => "Be the first to comment!"]));
                }
            } catch (PDOException $e) {
                echo json_encode("Error!: " . $e->getMessage() . "<br/>");
                die();
            }
        } else {
            try {
                $conn = new PDO($servername, $username, $password);
                $result = $conn->query("SELECT * FROM COMMENT");
                $comments = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $comments[] = $row;
                }
                echo json_encode($comments);
            } catch (PDOException $e) {
                echo json_encode("Error!: " . $e->getMessage() . "<br/>");
                die();
            }
        }
    break;
        
    case 'POST': //Register new comment
        $video = $input['VidID'];
        $user = $input['UserID'];
        $name = $input['UserName'];
        $body = $input['Body'];
        try {
            $conn = new PDO($servername, $username, $password);
            $sql = "INSERT INTO COMMENT (VidID, UserID, UserName, Body) VALUES (" . $video . ", '" . $user . "', '" . $name . "','" . $body . "')";
            $conn->query($sql);
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
    break;
    
    case 'PUT':
        $id = $_GET['id'];
        $name = $input['UserName'];
        $authID = $input['AuthID'];
        $creator = $input['creator'];
        try {
            $conn = new PDO($servername, $username, $password);
            $conn->query("UPDATE COMMENT SET UserName='$name',
                     AuthID='$authID', creator=$creator WHERE ComID=$id");
            echo json_encode(["message" => "Comment updated successfully"]);
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
    break;    
        
    case 'DELETE': //Remove server from avaiable list
        $id = $_GET['id'];
        try {
            $conn = new PDO($servername, $username, $password);
            $conn->query("DELETE FROM COMMENT WHERE id=$id");
            echo json_encode(["message" => "Comment deleted successfully"]);
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

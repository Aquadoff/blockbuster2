<?php
//This is a REST enpoint where clients request video servers and video servers register themselves.
//A server's capacity may vary, so servers will send a request to de-register themselves.
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$servername = "mysql:host=db;port=3306;dbname=movie";
$username = "blockclinet";
$password = "cryhavokandletslipthedogsofwar";
switch ($method) {
    case 'GET': //Get ip of a video server
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            try {
                $conn = new PDO($servername, $username, $password);
                $result = $conn->query("SELECT * FROM VIDEO WHERE VidID=$id");
                $data = $result->fetch(PDO::FETCH_ASSOC);
                echo json_encode($data);
            } catch (PDOException $e) {
                echo json_encode("Error!: " . $e->getMessage() . "<br/>");
                die();
            }
        } else {
            try {
                $conn = new PDO($servername, $username, $password);
                $result = $conn->query("SELECT * FROM VIDEO");
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
        $title = $input['Title'];
        $publisher = $input['Publisher'];
        $producer = $input['Producer'];
        $director = $input['Director'];
        $genre = $input['Genre'];
        $agerating = $input['AgeRating'];
        try {
            $conn = new PDO($servername, $username, $password);
            $sql = "INSERT INTO VIDEO (Title, Publisher, Producer, Director, Genre, Agerating) VALUES ('" . $title . "', '" . $publisher . "', '" . $producer ."', '" . $director . "', '" . $genre . "', '" . $agerating . "')";
            $conn->query($sql);
            $sql2 = "SELECT VidID FROM VIDEO WHERE Title='" . $title . "' AND Director='" . $director . "'";
            $result = $conn->query($sql2);
            $data = $result->fetch(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
        break;
    
    case 'PUT':
        $id = $_GET['id'];
        $title = $input['Title'];
        $publisher = $input['Publisher'];
        $producer = $input['Producer'];
        $director = $input['Director'];
        $genre = $input['Genre'];
        $agerating = $input['AgeRating'];
        $rating = $input['Rating'];
        try {
            $conn = new PDO($servername, $username, $password);
            $result1 = $conn->query("SELECT UserRating, NumRatings FROM VIDEO WHERE VidID=$id");
            $numRatings;
            while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                $Ratings = $row['UserRating'];
                $numRatings = $row['NumRatings'];
                if (!$numRatings) {
                    $numRatings = 0;
                }
                $tempRatings = $Ratings * $numRatings;
                $tempRatings = $tempRatings + $rating;
                $numRatings = $numRatings + 1;
                $tempRatings = $tempRatings / $numRatings;
                $rating = $tempRatings;
            }
            $conn->query("UPDATE VIDEO SET Title='$title',
                     Publisher='$publisher', Producer='$producer', Director='$director', Genre='$genre', AgeRating='$agerating', UserRating=$rating, NumRatings=$numRatings WHERE VidID=$id");
            echo json_encode(["message" => "Video updated successfully"]);
        } catch (PDOException $e) {
            echo json_encode("Error!: " . $e->getMessage() . "<br/>");
            die();
        }
        break;    
        
    case 'DELETE': //Remove server from avaiable list
        $id = $_GET['id'];
        try {
            $conn = new PDO($servername, $username, $password);
            $conn->query("DELETE FROM VIDEO WHERE VidID=$id");
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

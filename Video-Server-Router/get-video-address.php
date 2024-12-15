<?php
//This is a REST enpoint where clients request video servers and video servers register themselves.
//A server's capacity may vary, so servers will send a request to de-register themselves.
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$servers = array();
$servers = file("servers.txt");
//$servers = json_decode($file, true);
//$pointer = 0; //Cycle through servers so that one server does not get overloaded
//$len = sizeof($servers);
//$server = "test";
switch ($method) {
    case 'GET': //Get ip of a video server
        if (sizeof($servers) > 0) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $server = $servers[$id - 1];
                $server = trim($server);
            //$server = $servers[$pointer];
            // if ($pointer >= ($len - 1)) {
            //         $pointer = 0;
            // } else {
            //     $pointer++;
            // }
                echo json_encode($server);
            } else {
                $index = rand(0,sizeof($servers) - 1);
                $server = $servers[$index];
                $server = trim($server);
                echo json_encode($server);
            }
        } else {
            echo json_encode(["message" => "No servers available!\n"]);
        }
        break;
        
    case 'POST': //Register videoserver as available
        $address = $input['address'];
        if (!in_array($address . "\n", $servers)) {
            $index = sizeof($servers);
            $servers[] = "[".$index."] => ".$address;
            //$len = sizeof($servers);
            
            // if ($pointer >= ($len - 1)) {
            //         $pointer = 0;
            // }
            // echo $servers;
            // $file = print_r($servers, true);
            //unlink("servers.txt");
            //foreach ($servers as $server) {
            file_put_contents("servers.txt", $address . "\n", FILE_APPEND );
            echo sizeof($servers) . "\n";
            echo json_encode(["message" => "Server added"]);
         } else {
             echo json_encode(["message" => "Server already exists!"]);   
         }
        break;
        
    case 'DELETE': //Remove server from avaiable list
        $address = $input['address'] . "\n";
        echo $address;
        echo implode(',',$servers );
        $key = array_search($address, $servers);
       // echo $key . "\n";
        if ($key != false) {
            unset($servers[$key]);
            //$len = sizeof($servers);
            // if ($pointer >= ($len - 1)) {
            //         $pointer = 0;
            // }
            //$file = json_encode($servers);
            unlink("servers.txt");
            foreach ($servers as $server) {
                file_put_contents("servers.txt", $server);
            }
            echo json_encode(["message" => "Server removed"]);
        } else {
            echo json_encode(["message" => "Server not found!"]);
        }
        break;
        
    default:
        echo json_encode(["message" => "Invalid request method!"]);
        break;
}


?>

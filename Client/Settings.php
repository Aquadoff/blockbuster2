<?php
define('OAUTH2_CLIENT_ID', 'Ov23lij2YlC6qCeH0FZn');
define('OAUTH2_CLIENT_SECRET', 'ad28f184674dbe66645df75a9722b20ad22bfa59');

$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';

session_start();

function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Linux useragent'); //change agent string

  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  # add access token to header 
  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response); //decode response
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

$userID = $_GET["user"];
$user = httpPost("http://REST-User:8886/REST-User.php?id=$userID");
$self = htmlspecialchars($_SERVER["PHP_SELF"]);
// list($ip,)=explode(':',$_SERVER['HTTP_HOST']);
// $userAPI = $ip . "8886";
print_r($user);

if (isset($_POST['creator'])) {
    $creator = htmlspecialchars($_POST['creator']);
    $out = false;
    if ($creator == "on") {
        $out = true;
        //echo "creator";
    }
    // echo httpPut("http://REST-User:8886/REST-User.php?id=$userID", array(
    //     'creator' => $out
    // ));
    echo shell_exec("curl --header \"Content-Type: application/json\" --request PUT --data '{\"UserName\":\"$user->UserName\",\"creator\":\"$out\" }' http://REST-User:8886/REST-User.php?id=".$userID);
}

$check = "";
if ($user->creator == 1){
    $check = "checked";
}
echo <<<EOD
    <h2>User Settings</h2>
    <form action="$self?user=$userID" method="post" enctype="multipart/form-data">
    Creator:<input type="checkbox" name="creator" $check><br>
    <input type="submit" value="Update Settings" name="submit">
EOD;

function httpPost($url, $data=FALSE)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    if($data) {
      json_encode($data);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $headers[] = 'Content-Type: application/json';
    $response = curl_exec($ch);
    
    if ($response === false) {
    echo 'Error occurred while fetching the data: ' . curl_error($ch);
    exit();
    }
    
    curl_close($ch);
    return json_decode($response);
}

function httpPut($url, $data)
{
    // ob_start();  
    // $out = fopen('php://output', 'w');

//     $ch = curl_init();
// 
//     curl_setopt($ch, CURLOPT_URL, $url);
//     //if($data) {
//     json_encode($data);
//     curl_setopt($ch, CURLOPT_POST, false);
//     curl_setopt($ch, CURLOPT_PUT, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//     // curl_setopt($ch, CURLOPT_VERBOSE, true);  
//     // curl_setopt($ch, CURLOPT_STDERR, $out);
//     //}
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     
//     //$headers[] = 'Content-Type: application/json';
//     $response = curl_exec($ch);
//     // fclose($out);  
//     // $debug = ob_get_clean();
//     // echo $debug;
//     
//     if ($response === false) {
//     echo 'Error occurred while fetching the data: ' . curl_error($ch);
//     exit();
//     }
//     
//     curl_close($ch);
//     return json_decode($response);
    $call = callAPI('PUT', $url, json_encode($data));
    $response = json_decode($call, true);
    $errors = $response['response']['errors'];
    return $response['response']['data'][0];
}

function callAPI($method, $url, $data){   
    $curl = curl_init();   
    switch (strtoupper($method)){      
        case "POST":         
            curl_setopt($curl, CURLOPT_POST, 1);         
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;      
        case "PUT":         
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)            
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;      
        default:         
            if ($data)            
            $url = sprintf("%s?%s", $url, http_build_query($data));   
        
    }
     // OPTIONS:   
     curl_setopt($curl, CURLOPT_URL, $url);   
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(      'Content-Type: application/json',   ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
     // EXECUTE:   
     $result = curl_exec($curl);
     if(!$result){
         die("Connection Failure");
    }   
    curl_close($curl);
    return $result;
}
?>
<br>
<a href="index.php">Return Home</a>
<!--<h2>User Settings</h2>
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
<input type="checkbox" name="creator">
<input type="submit" value="Update Settings" name="submit">-->

<?php
define('OAUTH2_CLIENT_ID', 'Ov23lij2YlC6qCeH0FZn');
define('OAUTH2_CLIENT_SECRET', 'ad28f184674dbe66645df75a9722b20ad22bfa59');

$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';

$domain = 'http//192.168.1.117';
session_start();

// Start the login process by sending the user to Github's authorization page
if(get('action') == 'login') {
  // Generate a random hash and store in the session for security
  $_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
  unset($_SESSION['access_token']);

  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    'scope' => 'user',
    'state' => $_SESSION['state']
  );

  // Redirect the user to Github's authorization page
  header('Location: ' . $authorizeURL . '?' . http_build_query($params));
  die();
}

// When Github redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {
  // Verify the state matches our stored state
  if(!get('state') || $_SESSION['state'] != get('state')) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    die();
  }

  // Exchange the auth code for a token
  $token = apiRequest($tokenURL, array(
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    'state' => $_SESSION['state'],
    'code' => get('code')
  ));
  $_SESSION['access_token'] = $token->access_token;

  header('Location: ' . $_SERVER['PHP_SELF']);
  
//   $servername = "mysql:host=db;port=3306;dbname=movie";
//   $username = "blockclinet";
//   $password = "cryhavokandletslipthedogsofwar";
//   
//   try{
//         $conn = new PDO($servername, $username, $password);
//         $user = apiRequest($apiURLBase . 'user');
//         // Check connection
//         // if ($conn->connect_error) {
//         // die("Connection failed: " . $conn->connect_error);
//         // }
//         
//         //$conn->query("USE " . $dbname);
//         $sql = "SELECT UserName, AuthID FROM USER WHERE UserName='" . $user->login. " AND AuthID='" . $user->id . "'"; //Check if our user is in the Database
//         $result = $conn->query($sql);
// 
//         if ($result->rowCount() != 0) { //If not, then add a new user
//         //echo "<table><tr><th>ID</th><th>Name</th></tr>";
//         // output data of each row
//           $sql = "INSERT INTO USER (UserName, AuthID, creator) VALUES ('" . $user->login . "', '" . $user->id . "', false)";
//           $conn->query($sql);
//         }
//         $conn = null; //Close Connection
//   } catch (PDOException $e) {
//         print "Error!: " . $e->getMessage() . "<br/>";
//         die();
//   }
  
}

if(session('access_token')) {
    $user = apiRequest($apiURLBase . 'user');
    // echo httpPost("http://REST-User:8886/REST-User.php", array(
    //     'UserName' => $user->login,
    //     'AuthID' => $user->id,
    //     'creator' => "false"
    // ));
    shell_exec("curl --header \"Content-Type: application/json\" --request POST --data '{\"UserName\":\"$user->login\",\"AuthID\":\"$user->id\",\"creator\":\"true\" }' http://REST-User:8886/REST-User.php");
    
    echo '<h3>Logged In</h3>';
    echo '<h4>' . $user->login . '</h4>';
    echo '<pre>';
    print_r($user);
    echo '</pre>';

    // $servername = "mysql:host=db;port=3306;dbname=movie";
    // $username = "blockclinet";
    // $password = "cryhavokandletslipthedogsofwar";
    //$dbname = "movie";

    echo "<h>Welcome to Blockebuster 2 !<h>";
    echo "<br>";
    // Create connection
//     try{
//         $conn = new PDO($servername, $username, $password);
//         // Check connection
//         // if ($conn->connect_error) {
//         // die("Connection failed: " . $conn->connect_error);
//         // }
//         
//         //$conn->query("USE " . $dbname);
//         $sql = "SELECT VidID, Title, AgeRating, ThumbURL FROM VIDEO";
//         $result = $conn->query($sql);
// 
//         if ($result->rowCount() > 0) {
//         //echo "<table><tr><th>ID</th><th>Name</th></tr>";
//         // output data of each row
//         while($row = $result->fetch(PDO::FETCH_ASSOC)) {
//             $title = $row["Title"];
//             $URL = $row["VidID"];
//             $page = "watch.php?video=";
//             echo "
//                         <div style='display: block'>
//                             <a href=\"$page" . "$URL\" >
//                             $title
//                             </a>
//                         </div>
//                     ";
//         }
//         //echo "</table>";
//         } else {
//         echo "0 results \n";
//         }
//         
//         $sql = "SELECT creator FROM USERS WHERE AuthID='" . $user->id . "'";
//         $result = $conn->query($sql);
//         
//         // while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
//         //   echo "<a href=". $domain . "/upload.php>Add Movie</a>";
//         // }
//         $conn = null;
//     } catch (PDOException $e) {
//         print "Error!: " . $e->getMessage() . "<br/>";
//         die();
//     }
    $videos = json_decode(shell_exec("curl http://REST-Video:8887/REST-Video.php"), true);
    foreach ($videos as $video) {
      $title = $video["Title"];
      $URL = $video["VidID"];
      $page = "watch.php?video=";
      echo "
                  <div style='display: block'>
                      <a href=\"$page" . "$URL\" >
                      $title
                      </a>
                  </div>
      ";
    }
    $userID = $user->id;
    echo "<a href=Settings.php?user=" . $userID . " >Settings</a> <br>";
    $blockuser = httpPost("http://REST-User:8886/REST-User.php?id=".$userID);
    print_r($blockuser);
    if ($blockuser->creator == true) {
      echo '<p><a href=":8088/upload.php">Upload Movie</a></p>';
      echo <<<EOD
      <script language="JavaScript">
        document.addEventListener('click', function(event) {
        var target = event.target;
        if (target.tagName.toLowerCase() == 'a')
        {
            var port = target.getAttribute('href').match(/^:(\d+)(.*)/);
            if (port)
            {
              target.href = window.location.origin + port[2];
              target.port = port[1];
            }
        }
      }, false);
      </script>
      EOD;
    }
} else {
  echo '<h3>Not logged in</h3>';
  echo '<p><a href="?action=login">Log In</a></p>';
  echo $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "<br>";
  echo 'http://192.168.1.117:8080' . $_SERVER['PHP_SELF'];
}


function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Linux useragent'); //change agent string

  if($post) {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
  }

  $headers[] = 'Accept: application/json';

  # add access token to header 
  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response); //decode response
}

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

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}


?>

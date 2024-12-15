<?php
define('OAUTH2_CLIENT_ID', 'Ov23lij2YlC6qCeH0FZn');
define('OAUTH2_CLIENT_SECRET', 'ad28f184674dbe66645df75a9722b20ad22bfa59');

$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';

session_start();

// Start the login process by sending the user to Github's authorization page
// if(get('action') == 'login') {
//   // Generate a random hash and store in the session for security
//   $_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
//   unset($_SESSION['access_token']);
// 
//   $params = array(
//     'client_id' => OAUTH2_CLIENT_ID,
//     'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME']  . $_SERVER['PHP_SELF'],
//     'scope' => 'user',
//     'state' => $_SESSION['state']
//   );
// 
//   // Redirect the user to Github's authorization page
//   header('Location: ' . $authorizeURL . '?' . http_build_query($params));
//   die();
// }
// 
// // to kill all Sessions and reset code base 
// if(get('action') == 'exit') {
//     unset($_SESSION['state']);
//     unset($_SESSION['access_token']);
//     session_destroy();
//     exit();
// }
// 
// // When Github redirects the user back here, there will be a "code" and "state" parameter in the query string
// if(get('code')) {
//   // Verify the state matches our stored state
//   if(!get('state') || $_SESSION['state'] != get('state')) {
//     header('Location: ' . $_SERVER['PHP_SELF']);
//     die();
//   }
// 
//   // Exchange the auth code for a token
//   $token = apiRequest($tokenURL, array(
//     'client_id' => OAUTH2_CLIENT_ID,
//     'client_secret' => OAUTH2_CLIENT_SECRET,
//     'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME']  . $_SERVER['PHP_SELF'],
//     'state' => $_SESSION['state'],
//     'code' => get('code')
//   ));
//   $_SESSION['access_token'] = $token->access_token;
// 
//   header('Location: ' . $_SERVER['PHP_SELF']);
// }

if(session('access_token')) {
$APIuser = apiRequest($apiURLBase . 'user');
$vidID = $_GET["video"];
$vidServer = apiRequest("http://video-server-router:8888/get-video-address.php"); //"http://localhost/download.php";
//$vidServer = "http://192.168.1.117:8088/download.php";
$comment = "";
//Video edge server will be in the same pod, however it will be copied from a master server in a different pod.
echo " <video width='320' height='240' controls>";
echo " <source src=http://".$vidServer. "?video=" .$vidID." type='video/mp4'>";

echo "</video>";
//echo $vidServer;

} else {
  echo '<h3>Not logged in</h3>';
  echo '<p><a href="index.php?action=login">Log In</a></p>';
}

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
?>
<br>
<?php
// $servername = "mysql:host=db;port=3306;dbname=movie";
// $username = "watcher";
// $password = "alaspooryorick!iknewhimhoratio";
// $dbname = "movie";

$video = json_decode(shell_exec("curl http://REST-Video:8887/REST-Video.php?id=$vidID"), true);
echo "<br>";
echo "<table><tr><th>Title</th><th>AgeRating</th><th>Director</th><th>Publisher</th><th>Producer</th><th>Genre</th><th>Rating</th></tr>";
//foreach ($videos as $video) {
            $title = $video["Title"];
            $Age = $video["AgeRating"];
            $Dir = $video["Director"];
            $Pub = $video["Publisher"];
            $Pro = $video["Producer"];
            $Gen = $video["Genre"];
            $Use = $video["UserRating"];
            echo "<tr><td>".$title."</td><td>".$Age."</td><td>".$Dir."</td><td>".$Pub."</td><td>".$Pro."</td><td>".$Gen."</td><td>".$Use."</td></tr>";
//}
        echo "</table>";
        
// Create connection
// try{
//     $conn = new PDO($servername, $username, $password);
//     $sql = "SELECT Title, AgeRating, Publisher, Producer, Director, Genre, AgeRating, UserRating FROM VIDEO WHERE VidID = " . $vidID;
//     $result = $conn->query($sql);
// 
//     if ($result->rowCount() > 0) {
//         echo "<table><tr><th>Title</th><th>AgeRating</th><th>Director</th><th>Publisher</th><th>Producer</th><th>Genre</th><th>UserRating</th></tr>";
//         // output data of each row
//         while($row = $result->fetch(PDO::FETCH_ASSOC)) {
//             $title = $row["Title"];
//             $Age = $row["AgeRating"];
//             $Dir = $row["Director"];
//             $Pub = $row["Publisher"];
//             $Pro = $row["Producer"];
//             $Gen = $row["Genre"];
//             $Use = $row["UserRating"];
//             echo "<tr><td>".$title."</td><td>".$Age."</td><td>".$Dir."</td></tr>".$Pub."</td></tr>".$Pro."</td></tr>".$Gen."</td></tr>".$Use."</td></tr>";
//         }
//         echo "</table>";
//     } else {
//     echo "0 results";
//     }
// 
//     $sql2 = "SELECT Body, UserName FROM COMMENT WHERE VidID =" . $vidID;
//     $result2 = $conn->query($sql2);
//     
//     if ($result2->rowCount() > 0) {
// 
//         while($row = $result->fetch(PDO::FETCH_ASSOC)) {
//             $user = $row["UserName"];
//             $body = $row["Body"];
// 
//             echo "<p>" . $user . ": " . $body . "</p>";
//         }
//     } else {
//     echo "<p>No Comments! Be the first to comment!</p>";
//     echo "<br>";
//     }
//     $conn = null;
// } catch (PDOException $e) {
//     print "Error!: " . $e->getMessage() . "<br/>";
//     die();
// }
?>

<br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?video=$vidID";?>">
<br>Comment: <br>
<textarea name="comment" rows="5" cols="40">Write a new comment</textarea>
<br>Rate This Movie!<br>
<input type="range" min="1" max="5" value="5" class="slider" name="rating">
<br><br>
<input type="submit" name="submit" value="Submit">
</form>

<?php
    // Create connection
if (isset($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $rating = htmlspecialchars($_POST['rating']);
    // try{
    //     $conn = new PDO($servername, $username, $password);
    //     $sql = "INSERT INTO COMMENT (VidID, Body) VALUES (" . $vidID . ", " . $comment . ")";
    //     $conn->query($sql);
    //     $conn = null;
    // } catch (PDOException $e) {
    //     print "Error!: " . $e->getMessage() . "<br/>";
    //     die();
    // }
    shell_exec("curl --header \"Content-Type: application/json\" --request POST --data '{\"VidID\":\"$vidID\",\"UserID\":\"$APIuser->id\",\"UserName\":\"$APIuser->login\",\"Body\":\"$comment\" }' http://REST-Comment:8885/REST-Comment.php");
    shell_exec("curl --header \"Content-Type: application/json\" --request PUT --data '{\"Title\":\"$title\",\"Publisher\":\"$Pub\",\"Producer\":\"$Pro\",\"Director\":\"$Dir\",\"Genre\":\"$Gen\",\"AgeRating\":\"$Age\",\"Rating\":\"$rating\" }' http://REST-Video:8887/REST-Video.php?id=$vidID");
}

$comments = json_decode(shell_exec("curl http://REST-Comment:8885/REST-Comment.php?id=$vidID"), true);

foreach ($comments as $comment) {
            $user = $comment["UserName"];
            $body = $comment["Body"];

            echo "<p>" . $user . ": " . $body . "</p>";
}
?>

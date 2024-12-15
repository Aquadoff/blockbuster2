<!DOCTYPE html>
<html>
<body>

<?php
define('OAUTH2_CLIENT_ID', 'Ov23lij2YlC6qCeH0FZn');
define('OAUTH2_CLIENT_SECRET', 'ad28f184674dbe66645df75a9722b20ad22bfa59');

$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';

session_start();

// if(session('access_token')) {

$dbname = "movie";
$vidID = "0";
// define variables and set to empty values
$title = $agerating = $director = $publisher = "";
$producer = $genre = "";
$titleErr = $ageratingErr = $directorErr = $publisherErr = $producerErr = $genreErr = "";
if (isset($_POST['title'], $_POST['agerating'],$_POST['director'], $_POST['publisher'],$_POST['producer'], $_POST['genre'])) {
  $title = htmlspecialchars($_POST['title']);
  $agerating = htmlspecialchars($_POST['agerating']);
  $director = htmlspecialchars($_POST['director']);
  $publisher = htmlspecialchars($_POST['publisher']);
  $producer = htmlspecialchars($_POST['producer']);
  $genre = htmlspecialchars($_POST['genre']);

//   $servername = "mysql:host=db;port=3306;dbname=movie";
//   $username = "uploader";
//   $password = "akingdomforastage";
//   // Create connection
//   try{
//         $conn = new PDO($servername, $username, $password);
//         $sql = "INSERT INTO VIDEO (Title, Publisher, Producer, Director, Genre, Agerating) VALUES ('" . $title . "', '" . $publisher . "', '" . $producer ."', '" . $director . "', '" . $genre . "', '" . $agerating . "')";
//         echo $sql;
//         $conn->query($sql);
//         
//         $sql2 = "SELECT VidID FROM VIDEO WHERE Title='" . $title . "' AND Director='" . $director . "'";
//         echo $sql2;
//         $result2 = $conn->query($sql2);
//         
//         if ($result2->rowCount() > 0) {
//             while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
//                 $vidID = $row["VidID"];
//             }
//         }
//         echo $vidID;
//   } catch (PDOException $e) {
//         print "Error!: " . $e->getMessage() . "<br/>";
//         die();
//   }
  
  $resp = json_decode(shell_exec("curl --header \"Content-Type: application/json\" --request POST --data '{\"Title\":\"$title\",\"Publisher\":\"$publisher\",\"Producer\":\"$producer\",\"Director\":\"$director\",\"Genre\":\"$genre\",\"AgeRating\":\"$agerating\" }' http://REST-Video:8887/REST-Video.php"),true);
  echo $vidID = $resp['VidID'];
  //$target_dir = "/movies"; $_FILES['userFile']['tmp_name']
  //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);userFile
    //$tmp_name; 
    $info = pathinfo($_FILES['fileToUpload']['name']);
    $ext = $info['extension']; // get the extension of the file
    $newname = $vidID.".".$ext;
    $target = "/tmp/".$newname;
    $uploadOk = 1;
    move_uploaded_file( $_FILES['fileToUpload']['tmp_name'], $target);
}
$self = htmlspecialchars($_SERVER["PHP_SELF"]);
echo <<<EOD
<form action='$self' method='post' enctype='multipart/form-data'>
  Title: <input type='text' name='title' value='$title'>
  <span class='error'>* $titleErr</span>
  <br><br>
  Age Rating: <input type='text' name='agerating' value='$agerating'>
  <span class='error'>* $ageratingErr</span>
  <br><br>
  Director: <input type='text' name='director' value='$director'>
  <span class='error'>* $directorErr</span>
  <br><br>
  Publisher: <input type='text' name='publisher' value='$publisher'>
  <span class='error'>* $publisherErr</span>
  <br><br>
  Producer: <input type='text' name='producer' value='$producer'>
  <span class='error'>* $producerErr</span>
  <br><br>
  Genre: <input type='text' name='genre' value='$genre'>
  <span class='error'>* $genreErr</span>
  <br><br>
  <p>Select image to upload:</p>
  <input type='file' name='fileToUpload' id='fileToUpload'>
  <input type='submit' value='Upload movie' name='submit'>
</form>
EOD;
// } else {
//   echo '<h3>Not logged in</h3>';
//   echo '<p><a href=":8080/index.php?action=login">Log In</a></p>';
//   echo <<<EOD
//   <script language="JavaScript">
//     document.addEventListener('click', function(event) {
//     var target = event.target;
//     if (target.tagName.toLowerCase() == 'a')
//     {
//         var port = target.getAttribute('href').match(/^:(\d+)(.*)/);
//         if (port)
//         {
//           target.href = window.location.origin + port[2];
//           target.port = port[1];
//         }
//     }
//   }, false);
//   </script>
//   EOD;
// }

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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


<!--<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
  Title: <input type="text" name="title" value="<?php echo $title;?>">
  <span class="error">* <?php echo $titleErr;?></span>
  <br><br>
  Age Rating: <input type="text" name="agerating" value="<?php echo $agerating;?>">
  <span class="error">* <?php echo $ageratingErr;?></span>
  <br><br>
  Director: <input type="text" name="director" value="<?php echo $director;?>">
  <span class="error">* <?php echo $directorErr;?></span>
  <br><br>
  Publisher: <input type="text" name="publisher" value="<?php echo $publisher;?>">
  <span class="error">* <?php echo $publisherErr;?></span>
  <br><br>
  Producer: <input type="text" name="producer" value="<?php echo $producer;?>">
  <span class="error">* <?php echo $producerErr;?></span>
  <br><br>
  Genre: <input type="text" name="genre" value="<?php echo $genre;?>">
  <span class="error">* <?php echo $genreErr;?></span>
  <br><br>
  <p>Select image to upload:</p>
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload movie" name="submit">
</form>-->

</body>
</html>

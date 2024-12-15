<!DOCTYPE html>
<html>
<body>

<?php
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

  $servername = "mysql:host=db;port=3306;dbname=movie";
  $username = "uploader";
  $password = "akingdomforastage";
  // Create connection
  try{
        $conn = new PDO($servername, $username, $password);
        $sql = "INSERT INTO VIDEO (Title, Publisher, Producer, Director, Genre, Agerating) VALUES ('" . $title . "', '" . $publisher . "', '" . $producer ."', '" . $director . "', '" . $genre . "', '" . $agerating . "')";
        echo $sql;
        $conn->query($sql);
        
        $sql2 = "SELECT VidID FROM VIDEO WHERE Title='" . $title . "' AND Director='" . $director . "'";
        echo $sql2;
        $result2 = $conn->query($sql2);
        
        if ($result2->rowCount() > 0) {
            while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
                $vidID = $row["VidID"];
            }
        }
        echo $vidID;
  } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
  }
  
  //$target_dir = "/movies"; $_FILES['userFile']['tmp_name']
  //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);userFile
    //$tmp_name; 
    $info = pathinfo($_FILES['fileToUpload']['name']);
    $ext = $info['extension']; // get the extension of the file
    $newname = $vidID.".".$ext;
    $target = "/usr/share/app/movies/".$newname;
    $uploadOk = 1;
    move_uploaded_file( $_FILES['fileToUpload']['tmp_name'], $target);
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>


<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
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
</form>

<?php
 


?>
</body>
</html>

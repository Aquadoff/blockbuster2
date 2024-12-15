<?php
$servername = "db container uri goes here";
$username = "root";
$password = "cryhavokandletslipthedogsofwar";
$dbname = "blockDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, firstname, lastname FROM MyGuests";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr><th>ID</th><th>Name</th></tr>";
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["id"]."</td><td>".$row["firstname"]." ".$row["lastname"]."</td></tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}

?>

<?php echo "Welcome to Blockbuster 2!"; ?>
// https://phppot.com/php/search-videos-by-keyword-using-php-youtube-data-api/
<?php
    if (isset($_POST['submit']) )
     {
        $keyword = $_POST['keyword'];

        if (empty($keyword))
        {
            $response = array(
                  "type" => "error",
                  "message" => "Please enter the keyword."
                );
        }
    }
?>
<h2>Search Videos by keyword using YouTube Data API V3</h2>
<div class="search-form-container">
    <form id="keywordForm" method="post" action="">
        <div class="input-row">
            Search Keyword : <input class="input-field" type="search"
                id="keyword" name="keyword"
                placeholder="Enter Search Keyword">
        </div>

        <input class="btn-submit" type="submit" name="submit"
            value="Search">
    </form>
</div>

<?php
if(!empty($response)) {
?>
<div class="response <?php echo $response["type"]; ?>
    ">
    <?php echo $response["message"]; ?>
</div>
<?php
}
?>

<?php
if (isset($_POST['submit']) )
{



  if (!empty($keyword))
  {
    //$apikey = 'YOUR API KEY';
    $blockbURL = $row["VidURL"] ; //. $keyword . '&maxResults=' . MAX_RESULTS . '&key=' . $apikey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $blockbURI);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $value = json_decode(json_encode($data), true);

    $conn->close();
?>

<div class="result-heading">About <?php echo MAX_RESULTS; ?> Results</div>
<div class="videos-data-container" id="SearchResultsDiv">

<?php
    for ($i = 0; $i < MAX_RESULTS; $i++) {
        $videoId = $value['items'][$i]['id']['videoId'];
        $title = $value['items'][$i]['snippet']['title'];
        $description = $value['items'][$i]['snippet']['description'];
        ?>

<div class="video-tile">
<div  class="videoDiv">
    <iframe id="iframe" style="width:100%;height:100%" src="//www.youtube.com/embed/<?php echo $videoId; ?>"
data-autoplay-src="//www.youtube.com/embed/<?php echo $videoId; ?>?autoplay=1"></iframe>
</div>
<div class="videoInfo">
<div class="videoTitle"><b><?php echo $title; ?></b></div>
<div class="videoDesc"><?php echo $description; ?></div>
</div>
</div>
           <?php
        }
    }

}
?>

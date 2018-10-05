<?php
require_once "pdo.php";
require_once "util.php";
session_start();

?>

<!DOCTYPE html>
<html>
<head>
<title>Banny's Resume Registry</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
</head>
<body>
<div class="container">
  <h1>Profile Information</h1>
<?php
  $profile_id = $_GET['profile_id'];
  $stmt = $pdo->query("SELECT first_name,
  last_name, email, headline, summary FROM Profile
  WHERE profile_id=".$profile_id);
  while ($profile = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo ('<p>First Name:'.htmlentities($profile['first_name']).'</p>');
    echo ('<p>Last Name:'.htmlentities($profile['last_name']).'</p>');
    echo ('<p>Email:'.htmlentities($profile['email']).'</p>');
    echo ('<p>Headline:'.htmlentities($profile['headline']).'</p>');
    echo ('<p>Summary:'.htmlentities($profile['summary']).'</p>');
  }
  echo "<p>Positions<p>";
  echo "<ul>";

  $stmt = $pdo->query("SELECT rank, year, description FROM Position WHERE
  profile_id=".$profile_id);
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li>";
    echo $row['rank']," ",$row['year'],"/",$row['description'];
    echo("</li>");}
    ?>
  </ul>
  <p>
    <a href="index.php">Done</a>
  </p>
</div>
</body>
</html>

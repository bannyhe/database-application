<?php
require_once "pdo.php";
require_once "util.php";

session_start();

//Retrieve the Profiles from the database
$stmt = $pdo->query('SELECT * FROM Profile');
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
//Output for the page
?>
<!DOCTYPE html>
<html>
<head>
  <title>Banny a5462039 - Resume Registry</title>
  <?php require_once "head.php"; ?>
</head>
<body>
  <div class="container">
  <h1>Banny's Resume Registry</h1>
<?php
flashMesssages();

if ( isset($_SESSION['user_id']) ) {
  echo('<p><a href="logout.php">Logout</a></p>'."\n");
} else {
  echo('<p><a href="login.php">Please log in</a></p>'."\n");
}

if ( count($profiles) > 0 ) {
  echo('<table border="1">'."\n");
  echo('<tr><th>Name</th><th>Headline</th>');
  if ( isset($_SESSION['user_id']) ) {
    echo('<th>Action</th>');
  }
  echo("<tr>\n");

  foreach ($profiles as $profile ) {
    echo("<tr><td>\n");
    echo('<a href="view.php?profile_id='.$profile['profile_id'].'">');
    echo(htmlentities($profile['first_name']));
    echo(' ');
    echo(htmlentities($profile['last_name']));
    echo('</a>');
    echo("</td><td>\n");
    echo(htmlentities($profile['headline']));
    echo("</td>");
    if ( isset($_SESSION['user_id']) ) {
      echo("<td>\n");
      if ( $_SESSION['user_id'] == $profile['user_id']) {
        echo('<a href="edit.php?profile_id='.$profile['profile_id'].'">Edit</a>');
        echo(' ');
        echo('<a href="delete.php?profile_id='.$profile['profile_id'].'">Delete</a>');
      }
      echo("</td>");
    }
    echo("</tr>\n");
  }
  echo("</table>\n");
} else {
  echo("<p>No Rows Found</p>\n");
}
if ( isset($_SESSION['user_id']) ) {
  echo('<p><a href="add.php">Add New Entry</a></p>'."\n");
}
?>
  </div>
</body>
</html>

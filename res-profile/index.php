<?php
require_once "pdo.php";
session_start();
?>
<html>
<head>
  <title>40cccad2 - Resume Registry</title>
</head>
<body>
  <h1>Banny's Resume Registry</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

if ( isset($_SESSION['user_id'])) {
  echo('<table border="1">'."\n");
  $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM
  Profile WHERE user_id = " . $_SESSION['user_id']);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo('<thead><tr><th>Name</th><th>Headline</th><th>Action</th></tr></thead>');
    echo("<tr><td>");
    echo(htmlentities($row['first_name'].' '.$row['last_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
  }
  echo('</table>');
  echo("<a href='add.php'>Add New Entry </a>");
  echo("<a href='logout.php'> Logout</a>\n");
}else{
  echo('<p><a href="login.php">Please log in</a></p>');
}
?>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<html>
<head>
  <title>Mu He - Index Page</title>
</head>
<body>
  <h1>Welcome to the Dog Clients Database</h1>
  <p><a href="login.php">Please log in</a></p>
  <p>Attempt to <a href="add.php">add data</a> without logging in</p>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
echo('<table border="1">'."\n");
$stmt = $pdo->query("SELECT name, breed, owner, email, age, dogs_id FROM dogs");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo('<thead><tr><th>Name</th><th>Breed</th><th>Owner Name</th><th>Owner Email</th><th>Age</th><th>Action</th></tr></thead>');
    echo("<tr><td>");
    echo(htmlentities($row['name']));
    echo("</td><td>");
    echo(htmlentities($row['breed']));
    echo("</td><td>");
    echo(htmlentities($row['owner']));
    echo("</td><td>");
    echo(htmlentities($row['email']));
    echo("</td><td>");
    echo(htmlentities($row['age']));
    echo("</td><td>");
    echo('<a href="edit.php?dogs_id='.$row['dogs_id'].'">Edit</a> / ');
    echo('<a href="delete.php?dogs_id='.$row['dogs_id'].'">Delete</a>');
    echo("</td></tr>\n");
}
?>
</body>
</html>

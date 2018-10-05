<html>
  <head>
    <title> Mu He - Request/Response Cycle </title>
  </head>
  <body>
    <h1>Mu He Request / Response</h1>
    <p>The SHA256 hash of Mu He is</p>
    <?php
      $hash = hash('sha256', 'Mu He');
      echo "$hash";
    ?>
    <pre>
ASCII ART:

    ***     ***
    ** *   * **
    **  * *  **
    **   *   **
    **       **

    </pre>
    <a href="check.php">Click here to check the error setting</a>
    <br>
    <a href="fail.php">Click here to cause a traceback</a>
  </body>
</html>

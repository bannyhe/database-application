<!DOCTYPE html>
<html>
<head>
<title>Banny - Profile Registry</title>

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script src="js/handlebars.js"></script>
</head>
<body>
<div class="container">
<h1>Banny - Profile Registry</h1>
<div id="list-area"><img src="spinner.gif"></div>
<?php

if ( isset($_SESSION['user_id']) ) {
  echo('<p><a href="logout.php">Logout</a></p>'."\n");
} else {
  echo('<p><a href="login.php">Please log in</a></p>'."\n");
}

if ( isset($_SESSION['user_id']) ) {
  echo('<p><a href="form.php">Add</a></p>'."\n");
}
?>

<script id="list-template" type="text/x-handlebars-template">
  {{#if profiles.length}}
    <p><table border="1">
      <tr><th>Name</th><th>Headline</th>
      {{#if ../loggedin}}<th>Action</th>{{/if}}</tr>
      {{#each profiles}}
        <tr><td><a href="view.php?profile_id={{profile_id}}">
        {{first_name}} {{last_name}}</a>
        </td><td>{{headline}}</td>
        {{#if ../loggedin}}
          <td>
          <a href="form.php?profile_id={{profile_id}}">Edit</a>
          <a href="delete.php?profile_id={{profile_id}}">Delete</a>
          </td>
        {{/if}}
        </tr>
      {{/each}}
    </table></p>
  {{/if}}
</script>

<script>
$(document).ready(function(){
    $.getJSON('profiles.php', function(profiles) {
        window.console && console.log(profiles);
        var source  = $("#list-template").html();
        var template = Handlebars.compile(source);
        var context = {};
        context.loggedin = true;
        context.profiles = profiles;
        $('#list-area').replaceWith(template(context));
    }).fail( function() { alert('getJSON fail'); } );
});
</script>
</div>
</body>
</html>

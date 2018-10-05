
<!DOCTYPE html>
<html>
<head>
  <title>Mu He - Profile</title>
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
<h1>Editing Profile for UMSI</h1>
<form method="post" action="form.php"><div id="profile"><img src="spinner.gif"></div><input type="hidden" name="profile_id" value="458" /><p>Education: <input type="submit" id="addEdu" value="+" style="display:none;">
<div id="edu_fields">
<img src="spinner.gif"></div></p>
<p>Position: <input type="submit" id="addPos" value="+" style="display:none;">
<div id="position_fields">
<img src="spinner.gif"></div></p>
<p>
<input type="submit" value="Save" id="save_button" style="display:none;">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<!-- Handlebars templates before our functions -->
<script id="profile-template" type="text/x-handlebars-template">
<p>First Name:
<input type="text" name="first_name" size="60" value="{{first_name}}" /></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="{{last_name}}" /></p>
<p>Email:
<input type="text" name="email" size="30" value="{{email}}" /></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="{{headline}}" /></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80">{{summary}}</textarea>
</script>
<script id="edu-template" type="text/x-handlebars-template">
  <div id="edu{{count}}">
    <p>Year: <input type="text" name="edu_year{{count}}" value="{{school.year}}" />
    <input type="button" value="-" onclick="$('#edu{{count}}').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school{{count}}"
        class="school" value="{{school.name}}" />
    </p>
  </div>
</script>
<script id="pos-template" type="text/x-handlebars-template">
  <div id="position{{count}}">
    <p>Year: <input type="text" name="year{{count}}" value="{{position.year}}" />
    <input type="button" value="-" onclick="$('#position{{count}}').remove();return false;"></p>
    <textarea name="desc{{count}}" rows="8" cols="80">{{position.description}}</textarea>
  </div>
</script>

<script>
countPos = 0;
countEdu = 0;
source  = $("#profile-template").html();
templateProfile = Handlebars.compile(source);
source  = $("#edu-template").html();
templateEdu = Handlebars.compile(source);
source  = $("#pos-template").html();
templatePos = Handlebars.compile(source);

function addEdu(context) {
    context = context || {}; // optional parameter
    if ( countEdu >= 9 ) {
        alert("Maximum of nine entries exceeded");
        return;
    }
    countEdu++;
    window.console && console.log("Adding education "+countEdu);
    context.count = countEdu;
    $('#edu_fields').append(templateEdu(context));

    // Make sure to hook in all of the autocompletes
    $('.school').autocomplete({
        source: "school.php"
    });

}

function addPos(context) {
    context = context || {}; // optional parameter
    if ( countPos >= 9 ) {
        alert("Maximum of nine entries exceeded");
        return;
    }
    countPos++;
    window.console && console.log("Adding position "+countPos);
    context.count = countPos;
    $('#position_fields').append(templatePos(context));
}

function setup_events() {
    $('#addEdu').click(function(event){
        event.preventDefault();
        addEdu();
    });
    $('#addEdu').show();
    $('#addPos').click(function(event){
        event.preventDefault();
        addPos();
    });
    $('#addPos').show();
    $('#save_button').show();
}

$(document).ready(function(){
    $.getJSON('profile.php?profile_id=458', function(data) {
        window.console && console.log(data);

        $('#profile').replaceWith(templateProfile(data.profile));

        $('#position_fields').empty();
        for(var i=0; i<data.positions.length; i++) {
            var context = {};
            context.count = i;
            context.position = data.positions[i];
            console.log(context);
            addPos(context);
        }

        $('#edu_fields').empty();
        for(var i=0; i<data.schools.length; i++) {
            var context = {};
            context.count = i;
            context.school = data.schools[i];
            addEdu(context);
        }
        setup_events();
    }).fail( function() { alert('getJSON fail'); } );
});
</script>
</div>
</body>
</html>

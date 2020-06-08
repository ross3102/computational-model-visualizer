<?php generateHeader("<script src=\"https://apis.google.com/js/platform.js\" async defer></script>
"); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<meta name="google-signin-client_id" content="360547850019-c9i47eme2popofmrdfeq6315n4diug17.apps.googleusercontent.com">
<div class="center-align">
    <h1>Computational Model Visualizer</h1>
    <br>
    <div style="margin: 0 auto" class="btn btn-large waves-effect white g-signin2" id="google-signin"></div>
</div>


<script>
    $(window).on('load', init);

    function init() {
        var auth2 = gapi.auth2.getAuthInstance();
        element = document.getElementById('google-signin');
        auth2.attachClickHandler(element, {}, onSuccess, onFailure);
    }

    function onSuccess(googleUser) {
        var profile = googleUser.getBasicProfile();
        var id_token = googleUser.getAuthResponse().id_token;
        var first_name = profile.getGivenName();
        var last_name = profile.getFamilyName();
        var email = profile.getEmail();

        document.cookie = "session=" + id_token;

        location.href = "./index.php?action=authenticate&id_token=" + id_token + "&first_name=" + first_name + "&last_name=" + last_name + "&email="+email;
    }
    function onFailure() {
        alert("Couldn't authenticate.")
    }
</script>

<?php generateFooter() ?>

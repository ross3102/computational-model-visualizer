<?php generateHeader("<script src=\"https://apis.google.com/js/platform.js?onload=renderButton\" async defer></script>
"); ?>

<meta name="google-signin-client_id" content="360547850019-c9i47eme2popofmrdfeq6315n4diug17.apps.googleusercontent.com">

<div class="g-signin2" id="glogin"></div>


<script>
    function renderButton() {
        gapi.signin2.render('glogin', {
            'scope': 'profile email',
            'width': 240,
            'height': 50,
            'longtitle': true,
            'theme': 'dark',
            'onsuccess': onSuccess,
            'onfailure': onFailure
        });
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

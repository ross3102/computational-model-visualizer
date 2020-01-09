<?php

generateHeader(""); ?>
<div class="row" style="height: 100%">
    <div class="col s12" style="height: 100%;">
        <div class="container" style="height: 100%;">
            <div class="center-align valign-wrapper" style="height: 100%;">
            <h3 style="margin: auto">You're in!<br>Waiting for teacher<br><span id="dots">&nbsp;</span></h3>
            </div>
        </div>
    </div>
</div>

<?php generateFooter() ?>


<script>
    var num = 0;

    function dot() {
        num = (num + 1) % 4;
        $("#dots").html("&nbsp;" + ("•".repeat(num)));
    }

    setInterval(dot, 200);
</script>
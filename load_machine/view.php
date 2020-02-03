<?php generateHeader(); ?>

<h4 style="margin: 2px"><?php echo $question["text"] ?></h4>

<div class="row" style="height: 100%;">
    <div class="col s9" id="canvas-container" style="height: 100%;">
        <canvas id="canvas" height="500" width="600" style="border: 1px solid black"></canvas>
    </div>
    <div class="col s3">
        <form class="row" action="." method="post">
            <input type="hidden" name="action" value="create_turing">
            <input type="hidden" name="question_id" value="<?php echo $question_id ?>">
            <div class="input-field col s12">
                <input type="text" name="start_state" id="start-state">
                <label for="start-state">Start State</label>
            </div>
            <div class="input-field col s12">
                <textarea class="materialize-textarea" name="transitions" id="transitions"></textarea>
                <label for="transitions">Transitions</label>
            </div>
            <div class="input-field col s12">
                <input type="text" name="end_state" id="end-state">
                <label for="end-state">End State</label>
            </div>
            <div class="center-align">
                <button class="btn btn-large waves-effect waves-light blue" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

<?php generateFooter(); ?>


<script>
    class State {
        x = 0;
        y = 0;
        constructor(x, y) {
            this.x = x;
            this.y = y;
        }
    }

    class Transition {
        start = null;
        read = "";
        write = "";
        direction = 1;
        end = null;
        constructor(start, end, read="", write="", direction=1) {
            this.start = start;
            this.end = end;
            this.read = read;
            this.write = write;
            this.direction = direction;
        }
    }


    let canvas = document.getElementById("canvas");
    let ctx = canvas.getContext("2d");
    let mouseX = 0, mouseY = 0;

    let states = [];

    function resizeCanvas() {
        ctx.canvas.width  = $("#canvas-container").width();
        ctx.canvas.height = $("#canvas-container").height() - $("h4").height() - 2;
    }

    function draw() {
        resizeCanvas();
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw circle at mouse (temp)
        ctx.fillStyle = 'rgba(0, 0, 200, 0.5)';
        ctx.beginPath();
        ctx.ellipse(mouseX, mouseY, 20, 20, 0, 0, 2*Math.PI);
        ctx.stroke();
    }

    $(document).ready(function () {
        draw();
        setInterval(draw, 1);
    });

    canvas.addEventListener("mousemove", function(evt) {
        var rect = canvas.getBoundingClientRect();
        mouseX = evt.clientX - rect.left;
        mouseY = evt.clientY - rect.top;
        draw();
    });
</script>
<?php

$head = "<style>

#main-box {
    width: 90% !important;
}

</style>";

generateHeader($head); ?>

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
        constructor(x, y, name) {
            this.x = x;
            this.y = y;
            this.name = name;
            this.transitions = [];
        }
    }

    class Transition {
        read = "";
        write = "";
        direction = 1;
        end = null;
        constructor(end, read="", write="", direction=1) {
            this.end = end;
            this.read = read;
            this.write = write;
            this.direction = direction;
        }
    }

    let radius = .04;

    let canvas = document.getElementById("canvas");
    let ctx = canvas.getContext("2d");

    let mouseX = 0,
        mouseY = 0,
        mouseOn = true;

    let dragging = false;
    let transitioning = false;

    let states = [];
    let start = null;
    let end = [];

    function bx(x) {
        return x*canvas.width;
    }
    function sx(x) {
        return x/canvas.width;
    }
    function by(y) {
        return y*canvas.height;
    }
    function sy(y) {
        return y/canvas.height;
    }

    function dist(x1, y1, x2, y2) {
        return Math.sqrt((x1-x2)**2 + (y1-y2)**2)
    }

    function inCircle(x, y, cx, cy, radius) {
        return dist(x, y, cx, cy) <= radius;
    }

    function resizeCanvas() {
        canvas.width  = $("#canvas-container").width();
        canvas.height = $("#canvas-container").height() - $("h4").height() - 2;
    }

    function drawCircle(state) {
        ctx.strokeStyle = 'rgb(0, 0, 0)';
        if (state.name === start) {
            ctx.strokeStyle = 'rgb(0, 255, 0)';
        }
        ctx.beginPath();
        let scaledX = bx(state.x),
            scaledY = by(state.y);
        ctx.ellipse(scaledX, scaledY, bx(radius), bx(radius), 0, 0, 2*Math.PI);
        ctx.stroke();

        if (end.includes(state.name)) {
            ctx.beginPath();
            ctx.ellipse(scaledX, scaledY, bx(radius)*.8, bx(radius)*.8, 0, 0, 2*Math.PI);
            ctx.stroke();
        }

        ctx.font = "12px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText(state.name, scaledX, scaledY+3);
    }

    function drawLine(x1, y1, x2, y2, text="") {
        x1 = bx(x1);
        y1 = by(y1);
        x2 = bx(x2);
        y2 = by(y2);
        ctx.strokeStyle = 'rgb(0, 0, 0)';

        ctx.beginPath();
        drawArrow(x1, y1, x2, y2);
        ctx.stroke();

        ctx.save();
        ctx.translate((x1+x2)/2, (y1+y2)/2);
        ctx.rotate(Math.atan((y2-y1)/(x2-x1)));
        ctx.textAlign = "center";
        ctx.fillText(text, 0, 0);
        ctx.restore();
    }

    function drawArrow(fromx, fromy, tox, toy) {
        var headlen = 10; // length of head in pixels
        var dx = tox - fromx;
        var dy = toy - fromy;
        var angle = Math.atan2(dy, dx);
        ctx.moveTo(fromx, fromy);
        ctx.lineTo(tox, toy);
        ctx.lineTo(tox - headlen * Math.cos(angle - Math.PI / 6), toy - headlen * Math.sin(angle - Math.PI / 6));
        ctx.moveTo(tox, toy);
        ctx.lineTo(tox - headlen * Math.cos(angle + Math.PI / 6), toy - headlen * Math.sin(angle + Math.PI / 6));
    }
    
    function drawLineBuffer(x1, y1, x2, y2, b, text="") {
        x1 = bx(x1);
        y1 = by(y1);
        x2 = bx(x2);
        y2 = by(y2);
        b = bx(b);

        let d = dist(x1, y1, x2, y2);

        let nx1 = sx(x1+(x2-x1)*b/d),
            ny1 = sy(y1+(y2-y1)*b/d),
            nx2 = sx(x1+(x2-x1)*(1-b/d)),
            ny2 = sy(y1+(y2-y1)*(1-b/d));

        drawLine(nx1, ny1, nx2, ny2, text);
    }

    function draw() {
        resizeCanvas();
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (dragging) {
            drawCircle(dragging);
            dragging.transitions.forEach(function (tr) {
                drawLineBuffer(dragging.x, dragging.y, tr.end.x, tr.end.y, radius, tr.read + ", " + tr.write + ", " + (tr.direction ? ">" : "<"));
            });
        }

        states.forEach(function (state) {
            drawCircle(state);
            state.transitions.forEach(function (tr) {
                drawLineBuffer(state.x, state.y, tr.end.x, tr.end.y, radius, tr.read + ", " + tr.write + ", " + (tr.direction ? ">" : "<"))
            });
        });

        if (transitioning) {
            drawLine(transitioning.x, transitioning.y, sx(mouseX), sy(mouseY));
        }
    }

    $(document).ready(function () {
        resizeCanvas();
        mouseX = bx(1.0/2);
        mouseY = by(1.0/2);
        draw();
        setInterval(draw, 1);
    });

    canvas.addEventListener("mousemove", function(evt) {
        var rect = canvas.getBoundingClientRect();
        mouseX = evt.clientX - rect.left;
        mouseY = evt.clientY - rect.top;
        if (dragging) {
            dragging.x = sx(mouseX);
            dragging.y = sy(mouseY);
        }
    });

    canvas.addEventListener("mousedown", function(evt) {
        for (s=0; s<states.length; s++) {
            state = states[s];
            if (inCircle(mouseX, mouseY, bx(state.x), by(state.y), bx(radius))) {
                if (evt.button === 0) {
                    dragging = state;
                    states.splice(s, 1); // Delete state
                    return;
                } else {
                    for (e=0; e<end.length; e++) {
                        if (state.name === end[e]) {
                            end.splice(e, 1);
                            return;
                        }
                    }
                    end.push(state.name);
                    return;
                }
            }
        }
        dragging = new State(mouseX, mouseY, "");
    });

    canvas.addEventListener("mouseup", function (evt) {
        while (dragging.name == "") {
            dragging.name = prompt("Enter state name");
        }
        if (dragging.name != null)
            states.push(dragging);
        dragging = false;
    });

    canvas.addEventListener("dblclick", function (evt) {
        for (s=0; s<states.length; s++) {
            state = states[s];
            if (inCircle(mouseX, mouseY, bx(state.x), by(state.y), bx(radius))) {
                if (state.name === start)
                    start = null;
                else
                    start = state.name;
            }
        }
    });

    canvas.addEventListener("contextmenu", function (evt) {
        evt.preventDefault();
    });

    canvas.addEventListener("mouseover", function (evt) {
        mouseOn = true;
    });

    canvas.addEventListener("mouseout", function (evt) {
        mouseOn = false;
        while (dragging.name == "") {
            dragging.name = prompt("Enter state name");
        }
        if (dragging.name != null)
            states.push(dragging);
        dragging = false;
    });

    window.addEventListener("keydown", function (evt) {
        if (mouseOn && evt.keyCode === 84 && !transitioning) {
            for (s=0; s<states.length; s++) {
                state = states[s];
                if (inCircle(mouseX, mouseY, bx(state.x), by(state.y), bx(radius))) {
                    transitioning = state;
                    return;
                }
            }
        }
    });

    window.addEventListener("keyup", function (evt) {
        if (evt.keyCode === 84 && transitioning) {
            for (s=0; s<states.length; s++) {
                state = states[s];
                if (inCircle(mouseX, mouseY, bx(state.x), by(state.y), bx(radius))) {
                    let end = state,
                        read = "",
                        write = "",
                        direction = "";
                    while (read.length < 1) {
                        read = prompt("Character to read");
                        if (read == null) {
                            transitioning = false;
                            return;
                        }
                    }
                    while (write.length < 1) {
                        write = prompt("Character to write");
                        if (write == null) {
                            transitioning = false;
                            return;
                        }
                    }
                    while (direction !== ">" && direction !== "<") {
                        direction = prompt("Direction to move");
                        if (direction == null) {
                            transitioning = false;
                            return;
                        }
                    }
                    transitioning.transitions.push(new Transition(end, read, write, direction))
                }
            }
            transitioning = false;
        }
    })
</script>
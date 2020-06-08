<?php

$head = "<style>

#main-box {
    width: 90% !important;
    padding: 20px;
}

textarea {
    height: 50vh;
    overflow-y: scroll;
}

</style>";

generateHeader($head); ?>

<h4 style="margin: 2px"><?php echo $question["text"] ?></h4>

<!--<div class="row" style="height: 100%;">-->
    <div id="tape-container" style="height: 100px;">
        <canvas id="tape"></canvas>
    </div>
    <div id="canvas-container" style="height: 400px;">
        <canvas id="canvas" height="500" width="600" style="border: 1px solid black"></canvas>
    </div>
    <br>
    <div class="center-align">
        <a onclick="submitMachine()" class="btn btn-large waves-effect waves-light blue lighten-1">Submit</a>
        <a onclick="load()" class="btn btn-large waves-effect waves-light blue lighten-1">Load Input</a>
        <a onclick="run()" class="btn btn-large waves-effect waves-light blue lighten-1" id="run-button">Run</a>
        <a onclick="pause(); nextStep(true)" class="btn btn-large waves-effect waves-light blue lighten-1" id="step-button">Step</a>
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
        readStack = "";
        write = "";
        direction = "";
        end = null;
        constructor(end, read="", readStack = "", write="", direction=">") {
            this.end = end;
            this.read = read;
            this.readStack = readStack;
            this.write = write;
            this.direction = direction;
        }
    }

    class Tape {
        left = "";
        right = "";
        constructor() {
            this.left = "";
            this.right = "";
        }

        load(input) {
            this.left = "";
            this.right = input;
        }

        popright() {
            if (this.right.length == 0)
                return " ";
            let c = this.right.charAt(0);
            this.right = this.right.slice(1);
            return c;
        }

        popleft() {
            if (this.left.length == 0)
                return " ";
            let c = this.left.charAt(0);
            this.left = this.left.slice(1);
            return c;
        }

        read() {
            if (this.right.length == 0)
                return " ";
            return this.right.charAt(0);
        }

        write(c) {
            if (this.right.length == 0)
                this.right = c;
            else {
                this.popright();
                this.right = c + this.right;
            }
        }
    }

    class PDAStack {
        stack = "";
        constructor() {
            this.stack = "";
        }

        clear() {
            this.stack = "";
        }

        peek(c) {
            if (c == "") return true;
            if (this.stack.length == 0)
                return false;
            return this.stack.charAt(0) == c;
        }

        pop(c) {
            if (c == "") return true;
            if (this.stack.length == 0)
                return false;
            let r = this.stack.charAt(0);
            if (c != r) return false;
            this.stack = this.stack.slice(1);
            return true;
        }

        push(c) {
            if (this.stack.length == 0)
                this.stack = c;
            else {
                this.stack = c + this.stack;
            }
        }
    }


    let tape = new Tape();
    let pdaStack = new PDAStack();

    let radius = .035;

    let canvas = document.getElementById("canvas");
    let ctx = canvas.getContext("2d");

    let tapecanvas = document.getElementById("tape");
    let tapectx = tapecanvas.getContext("2d");

    let mouseX = 0,
        mouseY = 0,
        mouseOn = true;

    let dragging = false;
    let transitioning = false;

    let states = [];
    let start = null;
    let end = [];

    let offset = 0;
    let input = "";
    let curstate = "";
    let running = false;

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
        tapecanvas.width = $("#canvas-container").width();
        tapecanvas.height = $("#tape-container").height();
        canvas.width  = $("#canvas-container").width();
        canvas.height = $("#canvas-container").height();
    }

    function drawCircle(state) {
        ctx.strokeStyle = 'rgb(0, 0, 0)';

        if (state.name == curstate && curstate != "")
            ctx.strokeStyle = 'rgb(255, 0, 0)';

        ctx.beginPath();
        let scaledX = bx(state.x),
            scaledY = by(state.y);
        ctx.ellipse(scaledX, scaledY, bx(radius), bx(radius), 0, 0, 2*Math.PI);
        ctx.stroke();

        if (state.name === start) {
            ctx.beginPath();
            drawArrow(scaledX-bx(radius)-bx(0.05),scaledY,scaledX-bx(radius),scaledY);
            ctx.stroke();
        }

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
        ctx.translate((x1 + x2) / 2, (y1 + y2) / 2);
        ctx.rotate(Math.atan((y2 - y1) / (x2 - x1)));
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

         {
            let d = dist(x1, y1, x2, y2);

            let nx1 = sx(x1 + (x2 - x1) * b / d),
                ny1 = sy(y1 + (y2 - y1) * b / d),
                nx2 = sx(x1 + (x2 - x1) * (1 - b / d)),
                ny2 = sy(y1 + (y2 - y1) * (1 - b / d));

            drawLine(nx1, ny1, nx2, ny2, text);
        }
    }

    function draw() {
        resizeCanvas();
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        updateTape();

        drawCircle(new State(0.085, 0.125, ""));

        ctx.font = "12px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText("New", bx(0.085), by(0.125)-3);
        ctx.fillText("State", bx(0.085), by(0.125)+9);

        ctx.beginPath();
        ctx.rect(bx(0.025), by(0.25), bx(0.12), by(0.7));
        if (dragging && 0.025 < sx(mouseX) && sx(mouseX) < 0.145 && 0.25 < sy(mouseY) && sy(mouseY) < 0.95)
            ctx.fillStyle = "pink";
        else
            ctx.fillStyle = "red";
        ctx.fill();
        ctx.stroke();

        ctx.font = "18px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText("Trash", bx(0.085), by(0.6));

        states.concat(dragging ? [dragging]: []).forEach(function (state) {
            let modifier_x = 0,
                modifier_y = 0;

            let x = 0,
                y = 0;

            let end_list = {};

            drawCircle(state);
            state.transitions.forEach(function (tr) {
                let transText = "";
                <?php if ($machine_type == FSM) { ?>
                transText = tr.read;
                <?php } else if ($machine_type == PDA) { ?>
                transText = tr.read + ", " + tr.readStack + " -> " + tr.write;
                <?php } else { ?>
                transText = tr.read + ", " + tr.write + ", " + tr.direction;
                <?php } ?>

                let x1 = bx(state.x),
                    x2 = bx(tr.end.x),
                    y1 = by(state.y),
                    y2 = by(tr.end.y);

                let arrow_start_x = x1,
                    arrow_start_y = y1,
                    arrow_x = x2,
                    arrow_y = y2;

                if (y1 == y2 && x1 == x2) {
                    modifier_x = 1;
                    modifier_y = 1;
                    x = x1;
                    y = y1 - bx(radius)*2 + 12 - 15 * ((end_list[tr.end.name] || 0) + 1);
                } else if (y1 == y2) {
                    if (x2 > x1) {
                        modifier_x = 0;
                        modifier_y = -1;
                        arrow_start_x = x1 + bx(radius);
                        arrow_x = x2 - bx(radius);
                    } else {
                        modifier_x = 0;
                        modifier_y = 1;
                        arrow_start_x = x1 - bx(radius);
                        arrow_x = x2 + bx(radius);
                    }

                    x = (x1 + x2) / 2;
                    y = y1 + modifier_y * 15 * ((end_list[tr.end.name] || 0) + 1);
                } else if (x2 == x1) {
                    if (y2 > y1) {
                        modifier_x = 1;
                        modifier_y = 0;
                        arrow_start_y = y1 + bx(radius);
                        arrow_y = y2 - bx(radius);
                    } else {
                        modifier_x = -1;
                        modifier_y = 0;
                        arrow_start_y = y1 - bx(radius);
                        arrow_y = y2 + bx(radius);
                    }

                    x = x1 + modifier_x * 15 * ((end_list[tr.end.name] || 0) + 1);
                    y = (y1 + y2) / 2;
                } else {
                    let slope = -(x2 - x1) / (y2 - y1),
                        arrow_slope = (y1 - y2) / (x1 - x2);
                    if (x2 - x1 < 0) {
                        if (y2 - y1 < 0) {
                            modifier_y = 1;
                            modifier_x = -1;
                        } else {
                            modifier_y = 1;
                            modifier_x = 1;
                        }
                        arrow_start_y = bx(radius) * -arrow_slope / Math.sqrt(arrow_slope ** 2 + 1) + y1;
                        arrow_y = bx(radius) * arrow_slope / Math.sqrt(arrow_slope ** 2 + 1) + y2;
                    } else {
                        if (y2 - y1 < 0) {
                            modifier_y = -1;
                            modifier_x = -1;
                        } else {
                            modifier_y = -1;
                            modifier_x = 1;
                        }
                        arrow_start_y = bx(radius) * arrow_slope / Math.sqrt(arrow_slope ** 2 + 1) + y1;
                        arrow_y = -bx(radius) * arrow_slope / Math.sqrt(arrow_slope ** 2 + 1) + y2;
                    }
                    y = modifier_y * 15 * ((end_list[tr.end.name] || 0) + 1) * Math.abs(slope) / Math.sqrt(slope ** 2 + 1) + (y2 + y1) / 2;
                    x = (y - (y2 + y1) / 2) / slope + (x2 + x1) / 2;
                    arrow_start_x = (arrow_start_y - y1) / arrow_slope + x1;
                    arrow_x = (arrow_y - y2) / arrow_slope + x2;
                }

                end_list[tr.end.name] = (end_list[tr.end.name] || 0) + 1;

                if (x1 == x2 && y1 == y2) {
                    ctx.beginPath();
                    ctx.arc(x1,y1-bx(radius),bx(radius),Math.PI/6,5*Math.PI/6,true);
                    var headlen = 10;
                    var angle = 2*Math.PI/3;
                    var tox = x1 + bx(radius)*Math.cos(Math.PI/6),
                        toy = y1 - bx(radius)*Math.sin(Math.PI/6);
                    ctx.moveTo(tox, toy);
                    ctx.lineTo(tox - headlen * Math.cos(angle - Math.PI / 6), toy - headlen * Math.sin(angle - Math.PI / 6));
                    ctx.moveTo(tox, toy);
                    ctx.lineTo(tox - headlen * Math.cos(angle + Math.PI / 6), toy - headlen * Math.sin(angle + Math.PI / 6));
                    ctx.stroke();

                    ctx.font = "12px Arial";
                    ctx.fillStyle = "black";
                    ctx.textAlign = "center";
                    ctx.fillText(transText, x, y);
                } else {
                    arrow_start_x += modifier_x * 3;
                    arrow_x += modifier_x * 3;
                    arrow_start_y += modifier_y * 3;
                    arrow_y += modifier_y * 3;
                    drawLine(sx(arrow_start_x), sy(arrow_start_y), sx(arrow_x), sy(arrow_y), "");
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(Math.atan((y2 - y1) / (x2 - x1)));
                    ctx.font = "12px Arial";
                    ctx.fillStyle = "black";
                    ctx.textAlign = "center";
                    ctx.fillText(transText, 0, 0);
                    ctx.restore();
                }

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
        updateTape();
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
        if (inCircle(mouseX, mouseY, bx(0.085), by(0.125), bx(radius)))
            dragging = new State(mouseX, mouseY, "");
    });

    canvas.addEventListener("mouseup", function (evt) {
        if (0.025 < sx(mouseX) && sx(mouseX) < 0.145 && 0.25 < sy(mouseY) && sy(mouseY) < 0.95) {
            states.forEach(function(state) {
                for (t=0; t<state.transitions.length; t++)
                    if (state.transitions[t].end.name === dragging.name)
                        state.transitions.splice(t, 1);
            });
            if (dragging.name == start)
                start = null;
            if (end.includes(dragging.name))
                end.splice(end.indexOf(dragging.name),1)
        } else {
            while (dragging.name == "") {
                dragging.name = prompt("Enter state name");
            }
            if (dragging.name != null)
                states.push(dragging);
        }
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
                        readStack = "",
                        write = "",
                        direction = ">";
                    while (read.length < 1) {
                        read = prompt("Character to read from input");
                        if (read == null) {
                            transitioning = false;
                            return;
                        }
                    }
                    <?php
                    if ($machine_type == PDA) { ?>
                        readStack = prompt("Character to read from stack");
                        if (readStack == null) {
                            transitioning = false;
                            return;
                        }
                        write = prompt("Character to write to tape/stack");
                        if (write == null) {
                            transitioning = false;
                            return;
                        }
                    <?php } else if ($machine_type == FSM) { ?>
                        write = read;
                    <?php } else if ($machine_type == TM) { ?>
                        while (write.length < 1) {
                            write = prompt("Character to write to tape/stack");
                            if (write == null) {
                                transitioning = false;
                                return;
                            }
                        }
                        do {
                            direction = prompt("Direction to move");
                            if (direction == null) {
                                transitioning = false;
                                return;
                            }
                        } while (direction !== ">" && direction !== "<");
                    <?php } ?>
                    transitioning.transitions.push(new Transition(end, read, readStack, write, direction))
                }
            }
            transitioning = false;
        }
    });

    // function convertToText() {
    //     $("#start-state").val(start);
    //     let transitionString = "";
    //     let endString = "";
    //     states.forEach(function(state) {
    //         state.transitions.forEach(function(tr) {
    //             transitionString += state.name + " " + tr.read + " " + tr.write + " " + tr.direction + " " + tr.end.name + "\n";
    //         });
    //     });
    //
    //     end.forEach(function(endName) {
    //         endString += endName + " ";
    //     });
    //     $("#transitions").val(transitionString);
    //     $("#end-state").val(endString);
    //     M.updateTextFields();
    // }

    function save(correct) {
        let transitionString = "";
        let endString = "";
        states.forEach(function(state) {
            state.transitions.forEach(function(tr) {
                transitionString += state.name + " " + tr.read + " ";
                <?php if ($machine_type == PDA) { ?>
                    transitionString += tr.readStack + " " + tr.write + " ";
                <?php } else if ($machine_type == TM) { ?>
                    transitionString += tr.write + " " + tr.direction + " ";
                <?php } ?>
                transitionString += tr.end.name + "\n";
            });
        });

        end.forEach(function(endName) {
            endString += endName + " ";
        });

        $.ajax({
            url: "./index.php",
            method: "POST",
            data: {
                action: "save_machine",
                question_id: <?php echo $question_id ?>,
                machine_type: <?php echo $machine_type ?>,
                start_state: start,
                transitions: transitionString,
                end_state: endString,
                correct: correct
            },
            success: function () {
                M.toast({html: "Saved"})
            }
        });
    }

    function updateTape() {
        tapectx.clearRect(0, 0, tapecanvas.width, tapecanvas.height);

        tapectx.font = "24px Arial";
        tapectx.fillStyle = "black";
        tapectx.textAlign = "center";
        for (let i=0; i<Math.min(11,tape.right.length); i++) {
            tapectx.fillText(tape.right.charAt(i), bx((i+10)/20.0)+offset, 5.5*tapecanvas.height/10+6);
        }

        for (let i=0; i<Math.min(10, tape.left.length); i++) {
            tapectx.fillText(tape.left.charAt(i), bx((9-i)/20.0)+offset, 5.5*tapecanvas.height/10+6)
        }

        for (let i=-1; i<22; i++) {
            tapectx.beginPath();
            tapectx.rect(bx(i/20.0)-bx(0.025)+offset, 2*tapecanvas.height/10, bx(0.05), 7*tapecanvas.height/10);
            tapectx.stroke();
            tapectx.beginPath();
            tapectx.moveTo(tapecanvas.width/2-5,tapecanvas.height/10);
            tapectx.lineTo(tapecanvas.width/2+5,tapecanvas.height/10);
            tapectx.lineTo(tapecanvas.width/2,2*tapecanvas.height/10);
            tapectx.closePath();
            tapectx.fill();
        }
    }

    function moveLeft() {
        tape.right = tape.popleft() + tape.right;
        if (end.includes(curstate) && <?php echo $machine_type == TM ? 1: 0 ?>) {
            return true;
        } else if (running) {
            return nextStep(false);
        }
    }

    function animateLeft() {
        offset = 0;
        int = setInterval(function(){
            offset++;
            updateTape();
            if (offset > bx(0.05)) {
                clearInterval(int);
                offset = 0;
                tape.right = tape.popleft() + tape.right;
                if (end.includes(curstate) && <?php echo $machine_type == TM ? 1: 0 ?>) {
                    alert("Match");
                } else if (running) {
                    setTimeout(nextStep, 200, true);
                } else {
                    $("#step-button").removeAttr("disabled");
                    $("#run-button").removeAttr("disabled");
                }
            }
        },5);
    }

    function moveRight() {
        tape.left = tape.popright() + tape.left;
        if (end.includes(curstate) && <?php echo $machine_type == TM ? 1: 0 ?>) {
            return true;
        } else if (running) {
            return nextStep(false);
        }
    }

    function animateRight() {
        offset = 0;
        int = setInterval(function(){
            offset--;
            updateTape();
            if (offset < -bx(0.05)) {
                clearInterval(int);
                offset = 0;
                tape.left = tape.popright() + tape.left;
                if (end.includes(curstate) && <?php echo $machine_type == TM ? 1: 0 ?>) {
                    alert("Match");
                } else if (running) {
                    setTimeout(nextStep, 200, true);
                } else {
                    $("#step-button").removeAttr("disabled");
                    $("#run-button").removeAttr("disabled");
                }
            }
        },5);
    }

    function load() {
        input = prompt("Enter an input to run");
        tape.load(input);
        updateTape();
        reset();
    }

    function nextStep(animate=false) {
        $("#step-button").attr("disabled", "true");
        if (!running)
            $("#run-button").attr("disabled", "true");
        for (s=0; s<states.length; s++) {
            state = states[s];
            if (state.name === curstate) {
                for (t=0; t<state.transitions.length; t++) {
                    transition = state.transitions[t];
                    if (transition.read == tape.read() && pdaStack.peek(transition.readStack)) {
                        <?php if ($machine_type == PDA) { ?>
                            pdaStack.pop(transition.readStack);
                            pdaStack.push(transition.write);
                        <?php } else { ?>
                            tape.write(transition.write);
                        <?php } ?>
                        curstate = transition.end.name;
                        if (transition.direction == "<") {
                            if (animate) animateLeft();
                            else return moveLeft();
                        } else {
                            if (animate) animateRight();
                            else return moveRight();
                        }
                        return;
                    }
                }
                break;
            }
        }

        $("#step-button").removeAttr("disabled");
        $("#run-button").removeAttr("disabled");
        if (end.includes(curstate)) {
            if (animate) alert("Match");
            done();
            return true
        } else {
            if (animate) alert("No match");
            done();
            return false;
        }
    }

    function reset() {
        running = false;
        $("#run-button").text("Run");
        $("#run-button").attr("onclick","run()");
        $("#step-button").removeAttr("disabled");
        curstate = start;
        tape.load(input);
        pdaStack.clear();
    }

    function pause() {
        running = false;
        $("#run-button").text("Run");
        $("#run-button").attr("onclick","play()");
        $("#step-button").removeAttr("disabled");
    }

    function play(animate=true) {
        running = true;
        if (animate) {
            $("#run-button").text("Pause");
            $("#run-button").attr("onclick", "pause()");
            $("#step-button").attr("disabled", "true");
        }
        return nextStep(animate);
    }

    function run(animate=true) {
        reset();
        return play(animate);
    }

    function done() {
        running = false;
        $("#run-button").text("Reset");
        $("#run-button").attr("onclick","reset()");
        $("#step-button").attr("disabled", "true");
    }

    function submitMachine() {
        let correct = 0;
        let total = 0;

        if(confirm("Are you sure you want to submit?")) {
            <?php foreach(get_test_cases($question_id) as $case) { ?>
                input = '<?php echo $case["input"]?>';
                if(run(false) == <?php echo $case["pass"] ? 1: 0 ?>) {
                    correct += 1;
                }
                total += 1;
            <?php } ?>

            save(correct);
            alert("You got " + correct + " correct out of " + total + ".");
            location.href = "./index.php?action=show_form&room_code=<?php echo $room_code ?>&question_num=<?php echo ((int) $question_num) + 1?>"
        }
    }
</script>
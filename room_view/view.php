<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
    <div class="container">
        <h3><?php echo $room["name"] ?></h3>
        <h5 style="text-decoration: underline;">Questions</h5>
        <ul class="collection">
            <?php foreach ($questions as $question) { ?>
                <li class="collection-item"><span style="display: inline-block; width: calc(100% - 65px);"><?php echo $question["text"] ?></span>
                    <span class="badge" style="margin: 0;">
                        <a onclick="show_test(<?php echo $question["question_id"] ?>)" style="cursor: pointer"><i class="material-icons blue-text">assignment</i></a>
                        <a href="./index.php?action=delete_question&question_id=<?php echo $question["question_id"] ?>&room_id=<?php echo $room_id ?>"><i class="material-icons red-text">delete</i></a></span></li>
            <?php } ?>
        </ul>
        <div class="center-align">
            <a href="#add-questionm" class="btn btn-large btn-floating waves-effect waves-light blue lighten-1 modal-trigger"><i class="material-icons">add</i></a>
            <br>
            <br>
            <a href="./index.php?action=create&room_id=<?php echo $room_id ?>" class="btn btn-large waves-effect waves-light blue lighten-1"><?php echo $room["room_code"] == null ? "Open Room": "View Members/Scores" ?></a>
            <div class="modal" id="writetests">
                <div class="modal-content">
                    <h4 class="modal-header">New Test Case</h4>
                        <form action="./index.php" method="post" id="addtest">
                            <input type="hidden" name="action" value="add_case">
                            <input type="hidden" name="question_id" id="question_id">
                            <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                            <div class="input-field">
                                <textarea class="materialize-textarea" name="test_input" id="test_input"></textarea>
                                <label for="test_case">Test Input</label>
                            </div>
                            <div class="input-field">
                                <p>
                                    <label>
                                        <input type="checkbox" name="pass">
                                        <span>Should Pass?</span>
                                    </label>
                                </p>
                            </div>
                        </form>
                </div>
                <div class="modal-footer">
                    <a class="modal-close waves-effect waves-light btn-flat green-text" onclick="$('#addtest').submit()">Submit</a>
                    <a class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
                </div>
            </div>
            <div class="modal" id="displaytest">
                <div class="modal-container">
                    <h4 class="modal-header">Test Cases</h4>
                    <ul class="collection" id="case-list">
                    </ul>
                    <a class="btn-floating btn-large waves-effect waves-light blue" id="add-case-btn"><i class="material-icons">add</i></a>
                </div>
                <div class="modal-footer">
                    <a class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
                </div>
            </div>
            <div class="modal" id="add-questionm">
                <div class="modal-content">
                    <h4 class="modal-header">Add Question</h4>
                    <form action="./index.php" method="post" id="add">
                        <input type="hidden" name="action" value="add_question">
                        <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                        <div class="input-field">
                            <input type="text" name="question_name" id="question_name">
                            <label for="question_name">Question Statement</label>
                        </div>
                        <div class="input-field">
                            <select name="machine_type" id="machine-type-select">
                                <?php foreach ($machine_types as $type) { ?>
                                    <option value="<?php echo $type['type_cde'] ?>"><?php echo $type["type_name"] ?></option>
                                <?php } ?>
                            </select>
                            <label for="machine-type-select">Machine Type</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="modal-close waves-effect waves-green btn-flat green-text" onclick="$('#add').submit()">Add</a>
                    <a class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
                </div>
            </div>

        </div>
    </div>
    </div>
</div>

<?php generateFooter() ?>

<script>
    function write_test(question_id) {
        $("#writetests #question_id").val(question_id);
        $("#writetests").modal("open");
    }

    function show_test(question_id) {
        $.ajax({
            url: "./index.php",
            method: "GET",
            data: {
                "action": "get_test_cases",
                "question_id": question_id
            },
            success: function (response) {
                let cases = response.cases;
                let content = "";
                cases.forEach(function (test_case) {
                    content += "<li class='collection-item'>" + test_case["input"] + "<span class='badge'>" + (test_case["pass"] == 1 ? "Pass": "Fail") + "</span></li>"
                });
                $("#case-list").html(content);
                $("#add-case-btn").attr("data-question-id", question_id);
                $("#displaytest").modal("open");
            }
        })
    }

    $("#add-case-btn").click(function () {
        let question_id = $(this).attr("data-question-id");
        $("#addtest #question_id").val(question_id);
        $("#writetests").modal("open");
    })
</script>

<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
    <div class="container">
        <h3><?php echo $room["name"] ?></h3>
        <h5 style="text-decoration: underline;">Questions</h5>
        <ul class="collection">
            <?php foreach ($questions as $question) { ?>
                <li class="collection-item"><?php echo $question["text"] ?><span class="badge"><a href="#displaytest" class="modal-trigger"><i class="material-icons black-text">pregnant_woman</i></a><a onclick=write_test(<?php echo $question["question_id"]?>)><i class="material-icons blue-text">assignment</i></a><a href="./index.php?action=delete_question&question_id=<?php echo $question["question_id"] ?>&room_id=<?php echo $room_id ?>"><i class="material-icons red-text">delete</i></a></span></li>
            <?php } ?>
        </ul>
        <div class="center-align">
            <a href="#add-questionm" class="btn btn-large btn-floating waves-effect waves-light blue lighten-1 modal-trigger"><i class="material-icons">add</i></a>
            <br>
            <br>
            <a href="./index.php?action=create&room_id=<?php echo $room_id ?>" class="btn btn-large waves-effect waves-light blue lighten-1"><?php echo $room["room_code"] == null ? "Open Room": "View Room" ?></a>
            <div class="modal" id="writetests">
                <div class="modal-content">
                    <h4 class="modal-header">Write a Test Case!</h4>
                        <form action="./index.php" method="post" id="addtest">
                            <input type="hidden" name="action" value="add_case">
                            <input type="hidden" name="question_id" id="question_id">
                            <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                            <div class="input-field">
                                <textarea class="materialize-textarea" name="test_case" id="test_case"></textarea>
                                <label for="test_case">Test Case</label>
                            </div>
                            <div class="input-field">
                                <input type="text" name="fail" id="fail">
                                <label for="fail">Pass or Fail (P/F)</label>
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
                    <ul class="collection">
                        <?php foreach ($questions as $question) {?>
                        <li class="collection-item"></li>
                        <?php } ?>
                    </ul>
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
<script>
    function write_test(question_id) {
        $("#writetests #question_id").val(question_id);
        $("#writetests").modal("open");
    }
</script>
<?php generateFooter() ?>


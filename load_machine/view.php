<?php generateHeader(); ?>

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

<?php generateFooter(); ?>

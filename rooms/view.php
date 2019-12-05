<?php generateHeader() ?>

    <div class="row" style="height: 100%;">
        <div class="col s9">
            <div class="container">
                <h3 class="center-align">My Rooms</h3>
                <div class="collection">
                    <?php foreach ($rooms as $room) { ?>
                        <div class="collection-item"><?php echo $room["name"] ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col s3 center-align valign-wrapper" style="height: 100%;">
            <div class="row">
                <a href="#create-modal" class="col s12 btn btn-large blue lighten-1 waves-effect waves-light modal-trigger" style="margin-bottom: 40px;">Create</a>
                <a href="#join-modal" class="col s12 btn btn-large blue lighten-1 waves-effect waves-light modal-trigger" style="margin-bottom: 40px;">Join</a>
                <a class="col s12 btn btn-large blue lighten-1 waves-effect waves-light">Editor</a>
            </div>
        </div>
    </div>

    <div class="modal" id="create-modal">
        <div class="modal-content">
            <form action="./index.php" method="post" id="create-form">
                <input type="hidden" name="action" value="create_room">
                <div class="input-field">
                    <input type="text" name="room_name" id="room_name">
                    <label for="room_name">Room Name</label>
                </div>
                <div class="input-field">
                    <textarea id="room_desc" class="materialize-textarea" name="room_desc"></textarea>
                    <label for="room_desc">Description</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-green btn-flat green-text" onclick="$('#create-form').submit()">Create</a>
            <a class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
        </div>
    </div>

    <div class="modal" id="join-modal">
        <div class="modal-content">
            <form action="./index.php" method="post" id="join-form">
                <input type="hidden" name="action" value="join_room">
                <div class="input-field">
                    <input type="text" name="room_code" id="room_code">
                    <label for="room_code">Room Code</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat green-text" onclick="$('#join-form').submit()">Join</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
        </div>
    </div>

<?php generateFooter() ?>
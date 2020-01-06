<?php generateHeader() ?>

    <div class="row" style="height: 100%;">
        <div class="col s9">
            <div class="container">
                <h3 class="center-align">My Rooms</h3>
                <div class="collection">
                    <?php foreach ($rooms as $room) { ?>
                        <div class="collection-item" style="cursor: pointer;" onclick="location.href='../room_view/index.php?room_id=<?php echo $room["room_id"] ?>'"><?php echo $room["name"] ?><a class="right" href="#" onclick="event.stopPropagation(); showDesc(<?php echo $room["room_id"] ?>, '<?php echo addslashes($room["name"]) ?>', '<?php echo addslashes($room["room_desc"]) ?>')"><i class="material-icons black-text">info</i></a></div>
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
            <h4 class="modal-header">Create Room</h4>
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
            <h4 class="modal-header">Join Room</h4>
            <form action="./index.php" method="post" id="join-form">
                <input type="hidden" name="action" value="join_room">
                <div class="input-field">
                    <input type="text" name="room_code" id="room_code">
                    <label for="room_code">Room Code</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-green btn-flat green-text" onclick="$('#join-form').submit()">Join</a>
            <a class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
        </div>
    </div>

    <div class="modal" id="info-modal">
        <div class="modal-content">
            <h4 class="modal-header">Edit Room Info</h4>
            <form action="./index.php" method="post" id="edit-form">
                <input type="hidden" name="action" value="edit_room">
                <input type="hidden" name="room_id" id="room-id">
                <div class="input-field">
                    <input type="text" name="room_name" id="room-name">
                    <label for="room-name">Room Name</label>
                </div>
                <div class="input-field">
                    <textarea class="materialize-textarea" name="room_desc" id="room-desc"></textarea>
                    <label for="room-desc">Room Description</label>
                </div>
            </form>

        </div>
        <div class="modal-footer">
            <a class="waves-effect waves-light btn-flat green-text" onclick="$('#edit-form').submit()">Update</a>
            <a class="modal-close waves-effect waves-green btn-flat red-text">Close</a>
        </div>
    </div>

<?php generateFooter() ?>

<script>
    function showDesc(id, name, desc) {
        $("#info-modal #room-id").val(id);
        $("#info-modal #room-name").val(name);
        $("#info-modal #room-desc").val(desc);

        $("#info-modal").modal("open");
        M.updateTextFields();
    }
</script>

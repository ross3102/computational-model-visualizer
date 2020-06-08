<?php generateHeader() ?>
    <div class="container">
        <div class="section">
            <h3 class="center-align">My Rooms</h3>
            <div class="collection">
                <?php foreach ($rooms as $room) { ?>
                <div class="collection-item" style="cursor: pointer;" onclick="location.href='../room_view/index.php?room_id=<?php echo $room["room_id"] ?>'"><span style="display: inline-block; width: calc(100% - 93px);"><?php echo $room["name"] ?></span>
                    <span class="badge" style="margin: 0">
                        <?php if ($room["room_code"] != null) { ?>
                            <i class="red-text material-icons" onclick="event.stopPropagation(); location.href='./index.php?action=close_room&room_id=<?php echo $room["room_id"] ?>'">close</i>
                        <?php }  ?>
                        <i class="material-icons black-text" onclick="event.stopPropagation(); showDesc(<?php echo $room["room_id"] ?>, '<?php echo addslashes($room["name"]) ?>', '<?php echo addslashes($room["room_desc"]) ?>')">info</i>
                        <i class="material-icons red-text" onclick="event.stopPropagation(); deleteRoom(<?php echo $room["room_id"] ?>)">delete</i>
                    </span>
                </div>
                <?php } ?>
            </div>
            <div class="center-align">
                <a href="#create-modal" class="btn btn-large blue lighten-1 waves-effect waves-light modal-trigger">Create</a>
            </div>
        </div>
        <div class="divider"></div>
        <div class="section">
            <h3 class="center-align">My Joined Rooms</h3>
            <div class="collection">
                <?php foreach ($joined_rooms as $room) { ?>
                    <div class="collection-item" style="cursor: pointer;" onclick="location.href='./index.php?action=join_room_again&room_code=<?php echo $room["room_code"] ?>'"><span style="display: inline-block; width: calc(100% - 63px)"><?php echo $room["name"] ?></span><span class="badge" style="margin: 0"><a class="material-icons red-text" href="./index.php?action=leave_room&room_id=<?php echo $room["room_id"] ?>">exit_to_app</a><a class="material-icons black-text" onclick="event.stopPropagation(); showJoinedDesc('<?php echo addslashes($room["name"]) ?>', '<?php echo addslashes($room["room_desc"]) ?>')">info</a></span></div>
                <?php } ?>
            </div>
            <div class="center-align">
                <a href="#join-modal" class="btn btn-large blue lighten-1 waves-effect waves-light modal-trigger">Join</a>
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

    <div class="modal" id="mini-info-modal">
        <div class="modal-content">
            <h4 class="modal-header">Room Info</h4>
            <p style="font-weight: bold" id="view-room-name"></p>
            <p id="view-room-desc"></p>
        </div>
        <div class="modal-footer">
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

    function showJoinedDesc(name, desc) {
        $("#mini-info-modal #view-room-name").text(name);
        $("#mini-info-modal #view-room-desc").text(desc);
        $("#mini-info-modal").modal("open");
    }

    function deleteRoom(room_id) {
        M.toast({html: "Delete Room? <a class='btn btn-flat red-text' href='./index.php?action=delete_room&room_id=" + room_id + "'>Delete</a>", displayLength: 10000})
    }
</script>

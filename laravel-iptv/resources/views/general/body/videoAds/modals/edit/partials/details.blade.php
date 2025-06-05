<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        Title:
    </label>
    <div class="col-sm-12">
        <input type="text" name="name" id="name" class="form-control w-100" autocomplete="off">
    </div> 

    <div class="col-md-12 mt-2">
        <label class="form-label">Video:</label>
    </div>
    
    <div class="col-md-12">
        <input type="file" id="video_uri" name="video_uri">
        <div id="videoValidation"></div>
    </div>

    <label class="form-label mt-2">
        Room(s):
    </label>
    <div class="col-md-12">
        <select multiple="multiple" size="15" name="room_id[]" id="duallistbox">
        </select>
        <div id="roomValidation"></div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



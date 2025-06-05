<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        From:
    </label>
    <div class="col-sm-12">
        <input type="text" name="from" id="from" class="form-control w-100" autocomplete="off">
    </div> 

    <label class="form-label mt-2">
        Subject:
    </label>
    <div class="col-sm-12">
        <input type="text" name="subject" id="subject" class="form-control w-100" autocomplete="off">
    </div> 

    <label class="form-label mt-2">
        Body:
    </label>
    <div class="col-sm-12">
        <textarea class="form-control" name="body" id="body" rows="8"></textarea>
        <div id="bodyValidation"></div>
    </div>

    <div class="col-md-12 mt-2">
        <label class="form-label">Type:</label>
        <select class="form-control" name="type_id" id="type_id" data-placeholder="Select Type.."></select>
        <div id="typeValidation"></div>
    </div>

    <div class="col-md-12 mt-2 room-div">
        <label class="form-label">To:</label>
        <select class="form-control" name="room_id" id="room_id" data-placeholder="Choose room.."></select>
        <div id="roomValidation"></div>
    </div>

    <div class="col-md-12 mt-2 group-div">
        <label class="form-label">To:</label>
        <select class="form-control" name="category_id" id="category_id" data-placeholder="Choose Group.."></select>
        <div id="categoryValidation"></div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



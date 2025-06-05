<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        Message:
    </label>
    <div class="col-sm-12">
        <textarea class="form-control" name="message" id="message" rows="8"></textarea>
        <div id="messageValidation"></div>
    </div>

    <label class="form-label mt-2">
        Duration:
    </label>
    <div class="col-sm-12">
        <input type="text" name="duration" id="duration" class="form-control w-100" placeholder="Duration per second" autocomplete="off">
    </div> 

    <div class="col-md-12 mt-2">
        <label class="form-label">Type:</label>
        <select class="form-control" name="type_id" id="type_id" data-placeholder="Select Type.."></select>
        <div id="typeValidation"></div>
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



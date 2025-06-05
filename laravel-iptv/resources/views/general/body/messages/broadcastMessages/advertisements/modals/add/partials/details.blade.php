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
        <label class="form-label">Image:</label>
    </div>
    <div class="col-md-12 mt-2 text-center">
        <p><a href="" id="img-thumbnail-href" target="__blank"><img src="" class="img-thumbnail" width="100" height="100" id="img-thumbnail"></a></p> 
    </div> 
    <div class="col-md-12">
        <input type="file" id="img_uri" onchange="onChangeImg(this)" name="img_uri">
        <div id="imageValidation"></div>
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



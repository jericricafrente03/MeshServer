<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        Name:
    </label>
    <div class="col-sm-12">
        <input type="text" name="name" id="name" class="form-control w-100" autocomplete="off">
    </div> 

    <div class="col-md-12 mt-2">
        <label class="form-label">Background Image:</label>
    </div>
    <div class="col-md-12 mt-2 text-center">
        <p><a href="" id="bg-thumbnail-href" target="__blank"><img src="" class="img-thumbnail" width="450" height="450" id="bg-thumbnail"></a></p> 
    </div> 
    <div class="col-md-12">
        <input type="file" id="bg" onchange="onChangeImg(this,'#edit_theme_modal #bg-thumbnail', '#edit_theme_modal #bg-thumbnail-href')" name="bg">
        <div id="bgValidation"></div>
    </div> 

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        Text Color:
    </label>
    <div class="col-sm-12">
        <div id="text-color-picker" class="input-group colorpicker-component">
            <input type="text" name="text_color" value="" id="text_color" class="form-control" autocomplete="off"/>
            <span class="input-group-text input-group-addon"><i id="itext_color"></i></span>
        </div>
        <div id="textColorValidation"></div>
    </div> 

    <label class="form-label mt-2">
        Active Text Color:
    </label>
    <div class="col-sm-12">
        <div id="active-text-color-picker" class="input-group colorpicker-component">
            <input type="text" name="active_text_color" value="" id="active_text_color" class="form-control" autocomplete="off"/>
            <span class="input-group-text input-group-addon"><i id="iactive_text_color"></i></span>
        </div>
    </div> 

    <div class="col-md-12 mt-2 d-flex align-items-center">
        <label class="form-label">Zone Background Image:</label>
        <input type="hidden" id="is_null" value="0">
        <button type="button" class="btn btn-sm btn-warning ms-auto" id="toggleIsNull" onclick="toggleNull('#edit_theme_zone_modal');"></i> Change to Null</button>
    </div>
    <div class="col-md-12 mt-2 text-center image-holder-for-is-null">
        <p><a href="" id="bg-thumbnail-href" target="__blank"><img src="" class="img-thumbnail" width="450" height="450" id="bg-thumbnail"></a></p> 
    </div> 
    <div class="col-md-12 image-holder-for-is-null">
        <input type="file" id="bg" onchange="onChangeImg(this,'#edit_theme_zone_modal #bg-thumbnail', '#edit_theme_zone_modal #bg-thumbnail-href')" name="bg">
        <div id="bgValidation"></div>
    </div> 

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



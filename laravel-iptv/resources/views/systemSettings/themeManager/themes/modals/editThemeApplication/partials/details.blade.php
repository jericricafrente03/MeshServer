<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        Order #:
    </label>
    <div class="col-sm-12">
        <input type="text" name="order_no" id="order_no" class="form-control w-100" autocomplete="off">
    </div> 

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

    <div class="col-md-12 mt-2">
        <label class="form-label">Icon:</label>
    </div>
    <div class="col-md-12 mt-2 text-center">
        <p><a href="" id="icon-thumbnail-href" target="__blank"><img src="" class="img-thumbnail" width="100" height="100" id="icon-thumbnail"></a></p> 
    </div> 
    <div class="col-md-12">
        <input type="file" id="icon" onchange="onChangeImg(this,'#edit_theme_application_modal #icon-thumbnail', '#edit_theme_application_modal #icon-thumbnail-href')" name="icon">
        <div id="iconValidation"></div>
    </div> 

    <div class="col-md-12 mt-2">
        <label class="form-label">Active Icon:</label>
    </div>
    <div class="col-md-12 mt-2 text-center">
        <p><a href="" id="active-icon-thumbnail-href" target="__blank"><img src="" class="img-thumbnail" width="100" height="100" id="active-icon-thumbnail"></a></p> 
    </div> 
    <div class="col-md-12">
        <input type="file" id="active_icon" onchange="onChangeImg(this, '#edit_theme_application_modal #active-icon-thumbnail', '#edit_theme_application_modal #active-icon-thumbnail-href')" name="active_icon">
        <div id="activeIconValidation"></div>
    </div> 

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



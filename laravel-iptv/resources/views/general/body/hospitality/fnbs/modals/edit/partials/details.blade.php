<div class="card-body m-2">
    <br>
    <br>

    <label class="form-label mt-2">
        Name:
    </label>
    <div class="col-sm-12">
        <input type="text" name="name" id="name" class="form-control w-100" placeholder="Name" required autocomplete="off">
    </div> 

    <label class="form-label mt-2">
        Description:
    </label>
    <div class="col-sm-12">
        <textarea class="form-control" name="description" id="description" rows="8"></textarea>
    </div>

    <div class="col-md-12 mt-2">
        <label class="form-label">Category:</label>
        <select class="form-control" name="category_id" id="category_id" data-placeholder="Choose Fnb Category.."></select>
        <div id="categoryIdValidation"></div>
    </div>

    <label class="form-label mt-2">
        Price:
    </label>
    <div class="col-sm-12">
        <input type="text" name="unit_price" id="unit_price" class="form-control w-100" placeholder="0.00" required autocomplete="off">
    </div> 

    <div class="col-md-12 mt-2">
        <label class="form-label">Image:</label>
    </div>
    <div class="col-md-12 mt-2 text-center">
        <p><a href="" id="img-thumbnail-href" target="__blank"><img src="" class="img-thumbnail disabled" width="100" height="100" id="img-thumbnail"></a></p> 
    </div> 
    <div class="col-md-12">
        <input type="file" id="img_uri" name="img_uri">
    </div>   

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



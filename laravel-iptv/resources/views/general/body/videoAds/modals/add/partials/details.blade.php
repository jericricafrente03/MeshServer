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
        <?php
            // for ($i=0; $i < 5; $i++) { 
            //     echo '<option value="'.$i.'">Test '.$i.'</option>';
            // }
            // echo '<option value="'.$i.'" selected>Test '.$i.'</option>';
        ?>
        </select>
        <div id="roomValidation"></div>
    </div>

    <label class="form-label mt-2">
        Order #:
    </label>
    <div class="col-sm-12">
        <input type="text" name="order_no" id="order_no" class="form-control w-100" placeholder="Order no" autocomplete="off">
    </div> 

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>



<div class="card-body m-2">
    <div class="info-line">
        <i class="fa fa-hdd"></i>
        <h3 id="name">Device Name</h3>
        <small>
            <span class="text-success" title="Active">
                <i class="fa fa-circle"></i>
            </span>
        </small>
    </div>
    <label class="form-label mt-2">
        API ID:
    </label>
    <div class="col-sm-12">
        <label class="col-form-label" id="api_key">
        </label>
    </div> 

    <label class="form-label mt-2">
        IP Address:
    </label>
    <div class="col-sm-12">
        <label class="col-form-label" id="ip4_address">
        </label>
    </div>
    
    <label class="form-label mt-2">
        MAC Address #:
    </label>
    <div class="col-sm-12">
        <label class="col-form-label" id="mac_address">
        </label>
    </div> 
    
    <label class="form-label mt-2">
        Group:
    </label>
    <div class="col-sm-12">
        <label class="col-form-label" id="category">
        </label>
    </div> 
    <!-- <div class="col-md-12">
        <label for="role_id" class="form-label">Role</label>
        <select class="form-control" name="role_id" id="role_id" title="Role Selection..."></select>
    </div> -->
</div>

<style>
    .col-form-label{
        text-indent: 10px;
    }
    
    .info-line {
        display: flex;
        align-items: center; /* Vertically center items */
        gap: 10px; /* Adjust spacing between elements as needed */
    }

    .info-line h3 {
        margin: 0; /* Remove extra margin from <h3> */
    }

    .info-line small {
        /* margin-left: auto; /* Optional: Push the small element to the far right */
    }
</style>


<div class="modal" id="add_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Create Hotel Info</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                <form id="add_form" onsubmit="return false;">
                    <div class="col-md-12">
                        <!-- <input type="hidden" id="isDashboard" value="0"> -->
                        
                        <div class="row g-4">

                            <!-- details info start -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @include('general/body/hospitality/hotelInfos/modals/add/partials/details')
                            </div>
                            <!-- details info end -->

                            <!-- attachments info start -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <!-- include('stores/escalation/tickets/modals/add/partials/attachments') -->
                            </div>
                            <!-- attachments info end -->
                           
                        </div>
                    </div>
                </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-secondary" onclick="validateForm(true)">Create and add another</button>
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="validateForm()"></i> Create</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function validateForm(reform = false)
    {
        let formData = new FormData();
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        // $('#add_modal input, #add_modal textarea, #add_modal select').each(function() {
            
        //     // if(!$(`#${this.id}`)[0].checkValidity()) {
        //     //     $(`#${this.id}`).addClass("is-invalid");
        //     //     flag = false;
        //     // }
        //     data[this.id] = this.value;

        // });

        $('#add_modal input, #add_modal textarea, #add_modal select').each(function() {
            if(!this.checkValidity()) {
                $(this).addClass("is-invalid");
                flag = false;
            } else {
                formData.append(this.id, this.value);  // Add each field to FormData
            }
        });

        if(flag) {
            let modal= "#add_modal";
            let uploadFile = $(modal+' #img_uri').get(0).files[0];
            if (uploadFile) {
                formData.append("img_uri", uploadFile);
            }
            formData.append("data", JSON.stringify(data));
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            inFooterScriptAjaxFileCreateUpdateSubmit(formData, reform, modal, "/hotel-infos/add", "POST");
        }
    }

  </script>

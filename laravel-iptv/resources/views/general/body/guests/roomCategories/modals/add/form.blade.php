<div class="modal" id="add_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Create Room Category</h5>
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
                                @include('general/body/guests/roomCategories/modals/add/partials/details')
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
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#add_modal input, #add_modal textarea, #add_modal select').each(function() {
            
            // if(!$(`#${this.id}`)[0].checkValidity()) {
            //     $(`#${this.id}`).addClass("is-invalid");
            //     flag = false;
            // }
            data[this.id] = this.value;

        });

        if(flag) {
            let modal= "#add_modal";

            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/room-categories/add", "POST");
        }
    }

  </script>

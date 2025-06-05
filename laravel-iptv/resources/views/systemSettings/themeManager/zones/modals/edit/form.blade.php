<div class="modal" id="edit_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Edit Zone</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                <form id="edit_form" onsubmit="return false;">
                    <div class="col-md-12">
                        <input type="hidden" id="id" value="0">
                        
                        <div class="row g-4">

                            <!-- details info start -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @include('systemSettings/themeManager/zones/modals/edit/partials/details')
                            </div>
                           
                        </div>
                    </div>
                </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="uValidateForm()"></i> Update</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function uValidateForm(reform = false)
    {
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#edit_modal input, #edit_modal textarea, #edit_modal select').each(function() {
            data[this.id] = this.value;
        });
        

        if(flag) {
            let modal= "#edit_modal";
            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/zones/edit", "PUT");
        }
    }

  </script>

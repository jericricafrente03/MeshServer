<div class="modal" id="settings_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Settings</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                    <form id="add_form" onsubmit="return false;">
                        <div class="col-md-12">            
                            <div class="row g-4">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    @include('deviceAdb/manager/modals/settings/partials/details')
                                </div>
                            </div>
                        </div>
                    </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="validateSettingsForm()"></i> Update</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function validateSettingsForm(reform = false)
    {
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#settings_modal input, #settings_modal textarea, #settings_modal select').each(function() {
            data[this.id] = this.value;
        });

        if(flag) {
            let modal= "#settings_modal";
            
            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/adb-manager/settings", "POST");
        }
    }   
  </script>

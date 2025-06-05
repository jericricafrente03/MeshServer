<div class="modal" id="group_uninstall_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Group Uninstall</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                    <form id="add_form" onsubmit="return false;">
                        <div class="col-md-12">            
                            <div class="row g-4">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    @include('deviceAdb/manager/modals/groupUninstall/partials/details')
                                </div>
                            </div>
                        </div>
                    </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-secondary" onclick="validateGroupUninstallForm(true)">Submit and do another</button>
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="validateGroupUninstallForm()"></i> Submit</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function validateGroupUninstallForm(reform = false)
    {
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#group_uninstall_modal input, #group_uninstall_modal textarea, #group_uninstall_modal select').each(function() {
            data[this.id] = this.value;
        });

        if(flag) {
            let modal= "#group_uninstall_modal";
            
            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/adb-manager/group-uninstall", "POST");
        }
    }   
  </script>

<div class="modal" id="group_install_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Group Install</h5>
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
                                @include('deviceAdb/manager/modals/groupInstall/partials/details')
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
            <button type="button" class="btn btn-sm btn-secondary" onclick="validateGroupInstallForm(true)">Submit and do another</button>
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="validateGroupInstallForm()"></i> Submit</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function validateGroupInstallForm(reform = false)
    {
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#group_install_modal input, #group_install_modal textarea, #group_install_modal select').each(function() {
            data[this.id] = this.value;
        });

        if(flag) {
            let modal= "#group_install_modal";
            
            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/adb-manager/group-install", "POST", validatorForSelect2);
        }
    }

    function validatorForSelect2(response, modal){
        sweetAlert2('warning', 'Check field inputs.');
        $.each(response, function (key, val) {
            switch (key) {
                case 'apk':
                    $(modal + " #" + 'apkListValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
                default:
                    $(modal + " #" + key).after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
            }
        });
    }

  </script>

<div class="modal" id="edit_theme_application_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Edit Theme Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                <form id="edit_form" onsubmit="return false;">
                    <div class="col-md-12">
                        <input type="hidden" id="id" value="0">
                        <input type="hidden" id="theme_id" value="0">
                        
                        <div class="row g-4">

                            <!-- details info start -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @include('systemSettings/themeManager/themes/modals/editThemeApplication/partials/details')
                            </div>
                           
                        </div>
                    </div>
                </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="uThemeApplicationValidateForm()"></i> Update</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function uThemeApplicationValidateForm(reform = false)
    {
        let formData = new FormData();
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#edit_theme_application_modal input, #edit_theme_application_modal textarea, #edit_theme_application_modal select').each(function() {
            if(!this.checkValidity()) {
                $(this).addClass("is-invalid");
                flag = false;
            } else {
                formData.append(this.id, this.value);  // Add each field to FormData
            }
        });
        

        if(flag) {
            let modal= "#edit_theme_application_modal";
            let uploadFile = $(modal+' #icon').get(0).files[0];
            if (uploadFile) {
                formData.append("icon", uploadFile);
            }
            let uploadActiveIcon = $(modal+' #active_icon').get(0).files[0];
            if (uploadActiveIcon) {
                formData.append("active_icon", uploadActiveIcon);
            }
            formData.append("data", JSON.stringify(data));
            formData.append('_method', 'PUT');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            inFooterScriptAjaxFileCreateUpdateWithSpecificDataTableSubmit(formData, reform, modal, "/themes/edit-theme-application", "POST", validatorForSelect2, reloadThemeApplicationDataTable);
        }

        function validatorForSelect2(response, modal){
        sweetAlert2('warning', 'Check field inputs.');
        $.each(response, function (key, val) {
            switch (key) {
                case 'icon':
                    $(modal + " #" + 'iconValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
                case 'active_icon':
                    $(modal + " #" + 'activeIconValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
                case 'text_color':
                    $(modal + " #" + 'textColorValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
                default:
                    $(modal + " #" + key).after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
            }
        });
    }

    }

  </script>

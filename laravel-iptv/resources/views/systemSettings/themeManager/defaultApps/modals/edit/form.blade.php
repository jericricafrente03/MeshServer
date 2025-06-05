<div class="modal" id="edit_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Edit Default App</h5>
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
                                @include('systemSettings/themeManager/defaultApps/modals/edit/partials/details')
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
        let formData = new FormData();
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#edit_modal input, #edit_modal textarea, #edit_modal select').each(function() {
            if(!this.checkValidity()) {
                $(this).addClass("is-invalid");
                flag = false;
            } else {
                formData.append(this.id, this.value);  // Add each field to FormData
            }
        });
        

        if(flag) {
            let modal= "#edit_modal";
            
            formData.append("data", JSON.stringify(data));
            formData.append('_method', 'PUT');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            inFooterScriptAjaxFileCreateUpdateSubmit(formData, reform, modal, "/default-apps/edit", "POST", validatorForSelect2);
        }

        function validatorForSelect2(response, modal){
        sweetAlert2('warning', 'Check field inputs.');
        $.each(response, function (key, val) {
            switch (key) {
                case 'color':
                    $(modal + " #" + 'analyticColorValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
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

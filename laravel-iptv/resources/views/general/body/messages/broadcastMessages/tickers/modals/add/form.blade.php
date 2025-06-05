<div class="modal" id="add_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Create Ticker Message</h5>
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
                                @include('general/body/messages/broadcastMessages/tickers/modals/add/partials/details')
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
            
            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/tickers/add", "POST", validatorForSelect2);
        }
    }

    function validatorForSelect2(response, modal){
        sweetAlert2('warning', 'Check field inputs.');
        $.each(response, function (key, val) {
            switch (key) {
                case 'type_id':
                    $(modal + " #" + 'typeValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
                case 'category_id':
                    $(modal + " #" + 'categoryValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                    $(modal + " #" +key).addClass("is-invalid");
                    break;
                case 'message':
                    $(modal + " #" + 'messageValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
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

<div class="modal" id="edit_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Edit Video Ads</h5>
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
                                @include('general/body/videoAds/modals/edit/partials/details')
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
            let uploadFile = $(modal+' #video_uri').get(0).files[0];
            if (uploadFile) {
                formData.append("video_uri", uploadFile);
            }
            let roomIds = $(modal + ' #duallistbox').val(); // Get selected values as an array
            if (roomIds) {
                roomIds.forEach(id => {
                    formData.append('room_id[]', id); // Append each ID as an individual entry in the array
                });
            }

            formData.append('_method', 'PUT');

            inFooterScriptAjaxFileCreateUpdateSubmit(formData, reform, modal, "/video-ads/edit", "POST", validatorForSelect2);
        }

        function validatorForSelect2(response, modal){
            sweetAlert2('warning', 'Check field inputs.');
            $.each(response, function (key, val) {
                switch (key) {
                    case 'video_uri':
                        $(modal + " #" + 'videoValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                        $(modal + " #" +key).addClass("is-invalid");
                        break;
                    case 'room_id':
                        $(modal + " #" + 'roomValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
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
